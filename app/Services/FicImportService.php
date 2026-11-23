<?php

namespace App\Services;

use App\Models\CreditNote;
use App\Models\CreditNoteItem;
use App\Models\FicImport;
use App\Models\FicImportLine;
use App\Models\PackingFormDetail;
use App\Models\ProformaInvoice;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * นำเข้าไฟล์ FIC/SHORT (เอกสารบัญชี ที่เป็นจำนวน/ราคาจริงที่ส่ง-วางบิล)
 * เชื่อมกับ PI ผ่านคอลัมน์ "Po No" (FA-xxxxxxxx) = doc_no ของ PI (เทียบด้วยเลขล้วน)
 * แล้วจับ packing detail ด้วย part_no (คอลัมน์ item)
 *
 * โฟลว์: createFromFile() -> พรีวิว (ผู้ใช้เลือก packing ให้แถวกำกวม) -> apply()
 *
 * ตอน apply:
 *  - qty บวก  : ทับ qty + unit_price ของ packing detail + scale น้ำหนัก(รวม)/CBM ตามสัดส่วน qty
 *               (น้ำหนักต่อกล่อง nw/gw คงเดิม) + เก็บ snapshot ตัวเลขเดิมครั้งแรกครั้งเดียว
 *  - qty ลบ   : ออก Credit Note อัตโนมัติ (รวมตาม inv_num) — ไม่แตะ packing
 *  - ไม่เจอ packing ของ part นั้น : ข้าม
 */
class FicImportService
{
    /** header ที่คาดหวังในไฟล์ FIC */
    private const COL = [
        'inv_num'      => 'inv_num',
        'inv_date'     => 'inv_date',
        'cust_num'     => 'cust_num',
        'item'         => 'item',
        'desc'         => 'desc',
        'qty'          => 'qty_invoiced',
        'price'        => 'price',
        'total_price'  => 'Total Price',
        'po_no'        => 'Po No',
        'poline'       => 'Poline',
        'product_code' => 'product_code',
    ];

    /* ========================= PARSE + MATCH ========================= */

    /**
     * อ่านไฟล์ + จับคู่ + สร้าง FicImport (สถานะ pending) พร้อม lines
     * @return array{status:int, message?:string, import?:FicImport}
     */
    public function createFromFile(UploadedFile $file): array
    {
        try {
            $rows = $this->readSheet($file->getRealPath());
        } catch (\Throwable $e) {
            return ['status' => 0, 'message' => 'อ่านไฟล์ไม่ได้: '.$e->getMessage()];
        }
        if (count($rows) < 2) {
            return ['status' => 0, 'message' => 'ไฟล์ว่างหรือไม่มีข้อมูล'];
        }

        // header map (ตามชื่อคอลัมน์ ไม่ยึดตำแหน่ง)
        $header = array_map(fn ($h) => trim((string) $h), $rows[0]);
        $idx = [];
        foreach ($header as $i => $h) {
            if ($h !== '') {
                $idx[$h] = $i;
            }
        }
        foreach (['inv_num', 'item', 'qty', 'po_no'] as $need) {
            if (!isset($idx[self::COL[$need]])) {
                return ['status' => 0, 'message' => 'ไม่พบคอลัมน์ "'.self::COL[$need].'" ในไฟล์ FIC'];
            }
        }
        $get = function (array $row, string $key) use ($idx) {
            $col = self::COL[$key];
            return isset($idx[$col], $row[$idx[$col]]) ? $row[$idx[$col]] : null;
        };

        $data = array_slice($rows, 1);

        // index การจับคู่ล่วงหน้า
        $piByDigits = $this->loadPiIndex();           // digits => [ {id, doc_no, customer_id} ]
        $detailIndex = $this->loadPackingDetailIndex(); // "piId|PARTNO" => [ {detail_id, packing_form_id, packing_doc_no, qty, from, to} ]

        DB::beginTransaction();
        try {
            $import = FicImport::create([
                'source_filename' => $file->getClientOriginalName(),
                'file_sha256'     => hash_file('sha256', $file->getRealPath()),
                'status'          => 'pending',
                'imported_by'     => optional(Auth::guard('admin')->user())->id,
            ]);

            $counts = [
                'total' => 0, 'relevant' => 0, 'update' => 0, 'ambiguous' => 0,
                'credit' => 0, 'skip_no_packing' => 0, 'skip_no_pi' => 0,
            ];

            foreach ($data as $n => $row) {
                $invNum = trim((string) $get($row, 'inv_num'));
                $item   = trim((string) $get($row, 'item'));
                $qtyRaw = $get($row, 'qty');
                $poNo   = trim((string) $get($row, 'po_no'));

                // ข้ามแถวว่าง
                if ($invNum === '' && $item === '' && ($qtyRaw === null || $qtyRaw === '')) {
                    continue;
                }
                $counts['total']++;

                $qty   = $this->num($qtyRaw);
                $digits = $this->digits($poNo);

                // 1) หา PI จาก Po No
                $pis = $piByDigits[$digits] ?? [];
                if ($digits === '' || empty($pis)) {
                    $counts['skip_no_pi']++;
                    continue; // ไม่เก็บ line (ไฟล์มีของบริษัททั้งหมด แถวนอกระบบเยอะ)
                }

                // 2) หา packing detail ด้วย part_no ภายใน PI ที่แมตช์
                $partKey = strtoupper($item);
                $cands = [];
                foreach ($pis as $pi) {
                    $key = $pi->id.'|'.$partKey;
                    foreach ($detailIndex[$key] ?? [] as $d) {
                        $cands[] = $d;
                    }
                }
                $firstPi = $pis[0];

                $base = [
                    'fic_import_id' => $import->id,
                    'row_no'        => $n + 2, // +1 header +1 เป็น 1-based
                    'inv_num'       => $invNum ?: null,
                    'inv_date'      => $this->date($get($row, 'inv_date')),
                    'cust_num'      => trim((string) $get($row, 'cust_num')) ?: null,
                    'item'          => $item ?: null,
                    'fic_desc'      => trim((string) $get($row, 'desc')) ?: null,
                    'qty'           => $qty,
                    'price'         => $this->num($get($row, 'price')),
                    'total_price'   => $this->num($get($row, 'total_price')),
                    'po_no'         => $poNo ?: null,
                    'po_digits'     => $digits ?: null,
                    'poline'        => trim((string) $get($row, 'poline')) ?: null,
                    'product_code'  => trim((string) $get($row, 'product_code')) ?: null,
                    'matched_pi_id' => $firstPi->id,
                    'matched_pi_doc_no' => $firstPi->doc_no,
                    'candidates'    => array_map(fn ($d) => [
                        'detail_id'      => $d->detail_id,
                        'packing_doc_no' => $d->packing_doc_no,
                        'qty'            => (float) $d->qty,
                        'from'           => $d->from,
                        'to'             => $d->to,
                    ], $cands),
                ];

                if ($qty < 0) {
                    // คืนของ → credit note (ยึด detail แรกไว้อ้าง pi_product)
                    $base['action'] = 'credit_note';
                    $base['matched_detail_id'] = $cands[0]->detail_id ?? null;
                    $base['note'] = empty($cands) ? 'คืนของ (ไม่พบ packing — เครดิตตาม part_no)' : 'คืนของ';
                    $counts['credit']++;
                    $counts['relevant']++;
                } elseif (empty($cands)) {
                    $base['action'] = 'skipped_no_packing';
                    $base['note'] = 'เจอ PI แต่ไม่มี packing ของ part นี้';
                    $counts['skip_no_packing']++;
                    $counts['relevant']++;
                } elseif (count($cands) === 1) {
                    $base['action'] = 'update';
                    $base['matched_detail_id'] = $cands[0]->detail_id;
                    $counts['update']++;
                    $counts['relevant']++;
                } else {
                    // กำกวม → default = packing ใบล่าสุด (packing_form_id มากสุด)
                    $latest = $cands[0];
                    foreach ($cands as $d) {
                        if ($d->packing_form_id > $latest->packing_form_id) {
                            $latest = $d;
                        }
                    }
                    $base['action'] = 'ambiguous';
                    $base['matched_detail_id'] = $latest->detail_id;
                    $base['note'] = 'เจอ packing '.count($cands).' แถว — เลือกใบที่จะใช้';
                    $counts['ambiguous']++;
                    $counts['relevant']++;
                }

                FicImportLine::create($base);
            }

            $import->update([
                'total_rows'              => $counts['total'],
                'relevant_rows'           => $counts['relevant'],
                'update_rows'             => $counts['update'],
                'ambiguous_rows'          => $counts['ambiguous'],
                'credit_rows'             => $counts['credit'],
                'skipped_no_packing_rows' => $counts['skip_no_packing'],
                'skipped_no_pi_rows'      => $counts['skip_no_pi'],
            ]);

            DB::commit();

            return ['status' => 1, 'import' => $import->fresh()];
        } catch (\Throwable $e) {
            DB::rollBack();

            return ['status' => 0, 'message' => 'ประมวลผลไฟล์ไม่สำเร็จ: '.$e->getMessage()];
        }
    }

    /* ========================= APPLY ========================= */

    /**
     * @param array $choices  [line_id => detail_id] สำหรับแถว ambiguous ที่ผู้ใช้เลือก
     * @return array{status:int, message?:string, summary?:array}
     */
    public function apply(FicImport $import, array $choices = []): array
    {
        if ($import->status === 'applied') {
            return ['status' => 0, 'message' => 'batch นี้ apply ไปแล้ว'];
        }

        DB::beginTransaction();
        try {
            $summary = ['updated' => 0, 'credit_notes' => 0, 'credit_items' => 0, 'skipped' => 0];

            $lines = $import->lines()->get();

            // 1) อัปเดต packing (update + ambiguous)
            foreach ($lines as $line) {
                if (!in_array($line->action, ['update', 'ambiguous'], true)) {
                    continue;
                }
                $detailId = (int) ($choices[$line->id] ?? $line->matched_detail_id);
                // ยอมรับเฉพาะ detail ที่อยู่ใน candidates ของแถวนี้
                $allowed = collect($line->candidates ?? [])->pluck('detail_id')->map(fn ($x) => (int) $x)->all();
                if ($detailId && !empty($allowed) && !in_array($detailId, $allowed, true)) {
                    $detailId = (int) $line->matched_detail_id;
                }
                if (!$detailId) {
                    continue;
                }

                $detail = PackingFormDetail::find($detailId);
                if (!$detail) {
                    continue;
                }

                // snapshot ครั้งแรกครั้งเดียว
                if ($detail->fic_snapshot === null) {
                    $detail->fic_snapshot = [
                        'qty'         => $detail->qty,
                        'unit_price'  => $detail->unit_price,
                        'weight_nw'   => $detail->weight_nw,
                        'weight_gw'   => $detail->weight_gw,
                        'weight_nt'   => $detail->weight_nt,
                        'weight_gt'   => $detail->weight_gt,
                        'cubic_meter' => $detail->cubic_meter,
                    ];
                }
                $snap = $detail->fic_snapshot;

                $origQty = (float) ($snap['qty'] ?? $detail->qty);
                $newQty  = abs((float) $line->qty);
                $ratio   = $origQty > 0 ? ($newQty / $origQty) : 1.0;

                $detail->qty        = (int) round($newQty);
                $detail->unit_price = (float) $line->price;
                // น้ำหนักรวม + CBM scale จาก "ค่าเดิม" (snapshot) — กัน compound ตอน re-import
                $detail->weight_nt   = isset($snap['weight_nt'])   && $snap['weight_nt']   !== null ? round((float) $snap['weight_nt'] * $ratio, 2) : $detail->weight_nt;
                $detail->weight_gt   = isset($snap['weight_gt'])   && $snap['weight_gt']   !== null ? round((float) $snap['weight_gt'] * $ratio, 2) : $detail->weight_gt;
                $detail->cubic_meter = isset($snap['cubic_meter']) && $snap['cubic_meter'] !== null ? round((float) $snap['cubic_meter'] * $ratio, 3) : $detail->cubic_meter;
                // weight_nw / weight_gw (ต่อกล่อง) คงเดิม
                $detail->fic_invoice_no = $line->inv_num;
                $detail->save();

                $line->update(['matched_detail_id' => $detailId, 'applied' => true]);
                $summary['updated']++;
            }

            // 2) Credit Note (รวมตาม inv_num)
            $creditLines = $lines->where('action', 'credit_note');
            foreach ($creditLines->groupBy('inv_num') as $invNum => $group) {
                $cn = $this->createCreditNote($invNum, $group);
                if ($cn) {
                    foreach ($group as $line) {
                        $line->update(['credit_note_id' => $cn->id, 'applied' => true]);
                    }
                    $summary['credit_notes']++;
                    $summary['credit_items'] += $group->count();
                }
            }

            $summary['skipped'] = $lines->where('action', 'skipped_no_packing')->count();

            $import->update(['status' => 'applied', 'applied_at' => now()]);

            DB::commit();

            return ['status' => 1, 'summary' => $summary];
        } catch (\Throwable $e) {
            DB::rollBack();

            return ['status' => 0, 'message' => 'apply ไม่สำเร็จ: '.$e->getMessage()];
        }
    }

    /* ========================= internal ========================= */

    private function createCreditNote(?string $invNum, $group): ?CreditNote
    {
        $first = $group->first();
        $pi = $first && $first->matched_pi_id ? ProformaInvoice::with('customer')->find($first->matched_pi_id) : null;
        $customer = $pi ? $pi->customer : null;

        $companyName = ($pi->company_name ?? null) ?: (optional($customer)->company_name) ?: ($first->cust_num ?? 'N/A');

        $year = $first->inv_date ? (int) $first->inv_date->format('Y') : (int) date('Y');
        [$docNo, $run] = $this->genCreditDocNo($year);

        $cn = new CreditNote();
        $cn->doc_no        = $docNo;
        $cn->run_no        = $run;
        $cn->doc_year      = $year;
        $cn->doc_type      = 'credit';
        $cn->doc_date      = $first->inv_date ?: now()->toDateString();
        $cn->customer_id   = $pi->customer_id ?? null;
        $cn->company_name  = $companyName;
        $cn->address       = ($pi->address ?? null) ?: optional($customer)->address;
        $cn->tax_id        = ($pi->tax_id ?? null) ?: optional($customer)->tax_id;
        $cn->contact_name  = ($pi->contact_name ?? null) ?: optional($customer)->contact_name;
        $cn->phone         = ($pi->phone ?? null) ?: optional($customer)->phone;
        $cn->currency_id   = $pi->currency_id ?? null;
        $cn->refer         = $invNum;
        $cn->reason        = 'คืนสินค้า/SHORT อัตโนมัติจาก FIC '.($invNum ?: '');
        $cn->created_by    = optional(Auth::guard('admin')->user())->id;
        $cn->save();

        $total = 0.0;
        $seq = 1;
        foreach ($group as $line) {
            $pipId = null;
            if ($line->matched_detail_id) {
                $pipId = PackingFormDetail::where('id', $line->matched_detail_id)->value('pi_product_id');
            }
            $qty   = abs((float) $line->qty);
            $price = (float) $line->price;
            $amount = $qty * $price;
            $total += $amount;
            CreditNoteItem::create([
                'credit_note_id' => $cn->id,
                'seq'            => $seq++,
                'pi_id'          => $line->matched_pi_id,
                'pi_product_id'  => $pipId,
                'invoice_no'     => $line->inv_num,
                'part_no'        => $line->item,
                'description'    => $line->fic_desc,
                'qty'            => $qty,
                'unit_price'     => $price,
                'amount'         => $amount,
            ]);
        }
        $cn->total = $total;
        $cn->save();

        return $cn;
    }

    /** สร้างเลข C/N-XXX/YYYY (รันแยกตามปี) — ตรรกะเดียวกับ CreditNoteController */
    private function genCreditDocNo(int $year): array
    {
        $last = CreditNote::where('doc_type', 'credit')->where('doc_year', $year)->max('run_no');
        $run = ((int) $last) + 1;
        $docNo = 'C/N-'.str_pad((string) $run, 3, '0', STR_PAD_LEFT).'/'.$year;

        return [$docNo, $run];
    }

    /** digits => list ของ PI */
    private function loadPiIndex(): array
    {
        $map = [];
        DB::table('proforma_invoices')
            ->select('id', 'doc_no', 'customer_id')
            ->whereNotNull('doc_no')
            ->orderBy('id')
            ->get()
            ->each(function ($pi) use (&$map) {
                $d = $this->digits($pi->doc_no);
                if ($d === '') {
                    return;
                }
                $map[$d][] = $pi;
            });

        return $map;
    }

    /** "piId|PARTNO" => list ของ packing detail */
    private function loadPackingDetailIndex(): array
    {
        $map = [];
        DB::table('packing_form_details as d')
            ->join('proforma_invoice_products as pip', 'd.pi_product_id', '=', 'pip.id')
            ->join('packing_forms as pf', 'd.packing_form_id', '=', 'pf.id')
            ->whereNotNull('d.part_no')
            ->select(
                'd.id as detail_id',
                'd.packing_form_id',
                'd.part_no',
                'd.qty',
                'd.from',
                'd.to',
                'pip.pi_id',
                'pf.doc_no as packing_doc_no'
            )
            ->orderBy('d.id')
            ->get()
            ->each(function ($d) use (&$map) {
                $key = $d->pi_id.'|'.strtoupper(trim((string) $d->part_no));
                $map[$key][] = $d;
            });

        return $map;
    }

    private function readSheet(string $path): array
    {
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($path);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($path);
        $sheet = $spreadsheet->getActiveSheet();

        // ยึดขอบเขต "ข้อมูลจริง" (ไฟล์ export มักมี used-range บวมถึงคอลัมน์ WVV → toArray ทั้งแผ่นกิน RAM หลาย GB)
        $maxCol = $sheet->getHighestDataColumn();
        $maxRow = $sheet->getHighestDataRow();

        // rangeToArray($range, $nullValue, $calculateFormulas, $formatData, $returnCellRef)
        return $sheet->rangeToArray('A1:'.$maxCol.$maxRow, null, false, false, false);
    }

    private function digits(?string $s): string
    {
        return preg_replace('/\D+/', '', (string) $s) ?? '';
    }

    private function num($v): float
    {
        if ($v === null || $v === '') {
            return 0.0;
        }
        $s = str_replace([',', ' '], '', trim((string) $v));

        return is_numeric($s) ? (float) $s : 0.0;
    }

    private function date($v): ?string
    {
        if ($v === null || $v === '') {
            return null;
        }
        // PhpSpreadsheet (data-only) อาจคืนเป็น serial หรือสตริงวันที่
        try {
            if (is_numeric($v)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $v)->format('Y-m-d');
            }
            $ts = strtotime((string) $v);

            return $ts ? date('Y-m-d', $ts) : null;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
