<?php

namespace App\Services;

use App\Models\PackingForm;
use App\Models\PackingFormDetail;
use App\Models\ProformaInvoice;
use App\Models\ProformaInvoiceProduct;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * นำเข้า Packing list จาก Excel (รูปแบบ SANDEN)
 *
 * หัวเอกสาร → tb_packing_forms:
 * - D2=To, D3=customer_name, D4=country, A7=doc_date
 * - M8=pkg, O8=weight_nw, P8=weight_gw, Q8=weight_nt, R8=weight_gt, S8=cubic_meter, T8=qty
 *
 * รายการ → tb_packing_form_details เริ่มแถว 12:
 * - A=ลำดับ/กล่อง, B,C=from/to, D=เลขที่ PI, E=Part no
 * - F–S = cus part, desc, formular, W, L, H, qty, CBM, NW(O), GW, NT, GT, UOM
 * - V = from_co
 */
class PackingListImportService
{
    public const FIRST_DATA_ROW = 12;

    public const COL_PKG_MARKER = 0;

    public const COL_FROM = 1;

    public const COL_TO = 2;

    public const COL_PI_DOC = 3;

    public const COL_PART = 4;

    public const COL_CUS_PART = 5;

    public const COL_DESC = 6;

    public const COL_FORMULAR = 7;

    public const COL_WIDTH = 8;

    public const COL_LENGTH = 9;

    public const COL_HEIGHT = 10;

    public const COL_QTY = 11;

    public const COL_CBM = 12;

    /** คอลัมน์ O (index 14) */
    public const COL_NW = 14;

    public const COL_GW = 15;

    public const COL_NT = 16;

    public const COL_GT = 17;

    public const COL_UOM = 18;

    /** คอลัมน์ V (index 21) */
    public const COL_FROM_CO = 21;

    /**
     * @return array{
     *   status:int,
     *   title?:string,
     *   content?:string,
     *   duplicate?:bool,
     *   existing_id?:int,
     *   packing_form_id?:int,
     *   warnings?:array<int, string>
     * }
     */
    public function import(UploadedFile $file, ?int $replacePackingFormId = null, bool $forceNew = false): array
    {
        $realPath = $file->getRealPath();
        if (!$realPath || !is_readable($realPath)) {
            return ['status' => 0, 'title' => 'ผิดพลาด', 'content' => 'อ่านไฟล์ไม่ได้ (ลองซิงค์ไฟล์ OneDrive หรืออัปโหลดจากเครื่องโดยตรง)'];
        }

        $sha256 = hash_file('sha256', $realPath);
        $originalName = $file->getClientOriginalName();

        $existing = null;
        if (!$forceNew) {
            $existing = PackingForm::query()->where('import_file_sha256', $sha256)->first();
        }
        if ($existing && (int) $replacePackingFormId !== (int) $existing->id) {
            return [
                'status' => 0,
                'duplicate' => true,
                'existing_id' => $existing->id,
                'title' => 'ไฟล์ซ้ำ',
                'content' => 'ไฟล์นี้เคยนำเข้าแล้ว (เลขที่ '.$existing->doc_no.'). กดนำเข้าซ้ำพร้อมระบุแทนที่ หรือลบชุดเดิมก่อน',
            ];
        }

        if ($replacePackingFormId) {
            $old = PackingForm::find($replacePackingFormId);
            if ($old) {
                PackingFormDetail::where('packing_form_id', $old->id)->delete();
                $old->delete();
            }
        }

        try {
            $spreadsheet = IOFactory::createReaderForFile($realPath)->load($realPath);
        } catch (\Throwable $e) {
            return ['status' => 0, 'title' => 'อ่าน Excel ไม่ได้', 'content' => $e->getMessage()];
        }

        $sheet = $spreadsheet->getActiveSheet();
        $highestRow = (int) $sheet->getHighestDataRow();
        $warnings = [];
        $seenLineKeys = [];

        $rowsPayload = [];
        for ($r = self::FIRST_DATA_ROW; $r <= $highestRow; $r++) {
            $row = [];
            for ($c = 1; $c <= 22; $c++) {
                $row[] = $sheet->getCellByColumnAndRow($c, $r)->getFormattedValue();
            }
            $piDoc = $this->str($row[self::COL_PI_DOC] ?? null);
            $part = $this->str($row[self::COL_PART] ?? null);
            if ($piDoc === '' && $part === '') {
                continue;
            }
            if ($this->looksLikeHeaderRow($piDoc, $part)) {
                continue;
            }

            $dupKey = $piDoc."\t".$part."\t".$r;
            if (isset($seenLineKeys[$dupKey])) {
                $warnings[] = "แถว {$r}: ข้ามบรรทัดซ้ำ (PI + Part เหมือนแถวก่อนหน้า)";
                continue;
            }
            $seenLineKeys[$dupKey] = true;

            $rowsPayload[] = [
                'excel_row' => $r,
                'row' => $row,
                'pi_doc' => $piDoc,
                'part' => $part,
            ];
        }

        if ($rowsPayload === []) {
            return ['status' => 0, 'title' => 'ไม่มีข้อมูล', 'content' => 'ไม่พบแถวข้อมูล (คอลัมน์ D/E ว่างทั้งแผ่น)'];
        }

        return DB::transaction(function () use ($rowsPayload, $sha256, $originalName, $warnings, $forceNew, $sheet) {
            $yearMonth = date('ym');
            $last = PackingForm::where('doc_no', 'like', 'PL'.$yearMonth.'%')
                ->orderByDesc('run_no')
                ->first();
            $runNo = $last ? ((int) $last->run_no) + 1 : 1;
            $docNo = 'PL'.$yearMonth.str_pad((string) $runNo, 4, '0', STR_PAD_LEFT);

            $form = new PackingForm();
            $form->import_file_sha256 = $forceNew ? null : $sha256;
            $form->source_filename = $originalName;
            $form->doc_no = $docNo;
            $form->run_no = $runNo;
            $this->applyHeaderFromSheet($form, $sheet);
            $form->save();

            $firstCustomerId = null;

            foreach ($rowsPayload as $payload) {
                $row = $payload['row'];
                $piProductId = $this->resolvePiProductId($payload['pi_doc'], $payload['part']);
                if ($piProductId === null) {
                    $warnings[] = "แถว {$payload['excel_row']}: ไม่พบ PI/Part (D={$payload['pi_doc']}, E={$payload['part']})";
                } else {
                    $line = ProformaInvoiceProduct::query()->find($piProductId);
                    $pi = $line ? ProformaInvoice::find($line->pi_id) : null;
                    if ($firstCustomerId === null && $pi) {
                        $firstCustomerId = $pi->customer_id;
                        $form->customer_id = $pi->customer_id;
                    }
                }

                $detail = new PackingFormDetail();
                $detail->packing_form_id = $form->id;
                $detail->excel_row = $payload['excel_row'];
                $detail->from = $this->intOrNull($row[self::COL_FROM] ?? null);
                $detail->to = $this->intOrNull($row[self::COL_TO] ?? null);
                $detail->pi_product_id = $piProductId;
                $detail->part_no = $payload['part'] ?: null;
                $detail->cus_part_no = $this->str($row[self::COL_CUS_PART] ?? null) ?: null;
                $detail->description = $this->str($row[self::COL_DESC] ?? null) ?: null;
                $detail->formular_number = $this->str($row[self::COL_FORMULAR] ?? null) ?: null;
                $detail->width = $this->decimalOrNull($row[self::COL_WIDTH] ?? null);
                $detail->lenght = $this->decimalOrNull($row[self::COL_LENGTH] ?? null);
                $detail->height = $this->decimalOrNull($row[self::COL_HEIGHT] ?? null);
                $detail->qty = (int) ($this->decimalOrNull($row[self::COL_QTY] ?? null) ?? 0);
                $detail->cubic_meter = $this->decimalOrNull($row[self::COL_CBM] ?? null);
                $detail->weight_nw = $this->decimalOrNull($row[self::COL_NW] ?? null);
                $detail->weight_gw = $this->decimalOrNull($row[self::COL_GW] ?? null);
                $detail->weight_nt = $this->decimalOrNull($row[self::COL_NT] ?? null);
                $detail->weight_gt = $this->decimalOrNull($row[self::COL_GT] ?? null);
                $detail->uom = $this->str($row[self::COL_UOM] ?? null) ?: null;
                $detail->from_co = $this->str($row[self::COL_FROM_CO] ?? null) ?: null;
                $detail->save();
            }

            $form->save();

            return [
                'status' => 1,
                'title' => 'นำเข้าสำเร็จ',
                'content' => 'สร้าง Packing List '.$form->doc_no.' จำนวน '.count($rowsPayload).' แถว',
                'packing_form_id' => $form->id,
                'warnings' => $warnings,
            ];
        });
    }

    private function applyHeaderFromSheet(PackingForm $form, Worksheet $sheet): void
    {
        $form->to = $this->cellStr($sheet, 'D2') ?: null;
        $form->customer_name = $this->cellStr($sheet, 'D3') ?: null;
        $form->country = $this->cellStr($sheet, 'D4') ?: null;
        $form->doc_date = $this->cellDate($sheet, 'A7') ?? now()->toDateString();
        $form->pkg = $this->cellInt($sheet, 'M8');
        $form->weight_nw = $this->cellDecimal($sheet, 'O8');
        $form->weight_gw = $this->cellDecimal($sheet, 'P8');
        $form->weight_nt = $this->cellDecimal($sheet, 'Q8');
        $form->weight_gt = $this->cellDecimal($sheet, 'R8');
        $form->cubic_meter = $this->cellDecimal($sheet, 'S8');
        $form->qty = $this->cellInt($sheet, 'T8') ?? 0;
    }

    private function cellStr(Worksheet $sheet, string $coordinate): string
    {
        return $this->str($sheet->getCell($coordinate)->getFormattedValue());
    }

    private function cellInt(Worksheet $sheet, string $coordinate): ?int
    {
        return $this->intOrNull($sheet->getCell($coordinate)->getFormattedValue());
    }

    private function cellDecimal(Worksheet $sheet, string $coordinate): ?string
    {
        return $this->decimalOrNull($sheet->getCell($coordinate)->getFormattedValue());
    }

    private function cellDate(Worksheet $sheet, string $coordinate): ?string
    {
        $cell = $sheet->getCell($coordinate);
        $value = $cell->getValue();
        if ($value === null || $value === '') {
            return null;
        }
        if (is_numeric($value)) {
            try {
                return ExcelDate::excelToDateTimeObject((float) $value)->format('Y-m-d');
            } catch (\Throwable) {
                return null;
            }
        }

        $formatted = $this->str($cell->getFormattedValue());
        if ($formatted === '') {
            return null;
        }
        $ts = strtotime($formatted);

        return $ts !== false ? date('Y-m-d', $ts) : null;
    }

    private function looksLikeHeaderRow(string $d, string $e): bool
    {
        $ud = strtoupper($d);
        $ue = strtoupper($e);

        return str_contains($ud, 'DOC') && str_contains($ue, 'PART')
            || str_contains($ud, 'PI') && str_contains($ue, 'PART');
    }

    private function resolvePiProductId(string $docNo, string $partNo): ?int
    {
        if ($docNo === '' || $partNo === '') {
            return null;
        }

        $pi = ProformaInvoice::query()
            ->whereRaw('TRIM(doc_no) = ?', [$docNo])
            ->first();

        if (!$pi) {
            return null;
        }

        $id = DB::table('proforma_invoice_products')
            ->leftJoin('products', 'proforma_invoice_products.product_id', '=', 'products.id')
            ->where('proforma_invoice_products.pi_id', $pi->id)
            ->where(function ($q) use ($partNo) {
                $q->whereRaw('TRIM(proforma_invoice_products.part_no) = ?', [$partNo])
                    ->orWhereRaw('TRIM(products.code) = ?', [$partNo]);
            })
            ->value('proforma_invoice_products.id');

        return $id ? (int) $id : null;
    }

    private function str(mixed $v): string
    {
        if ($v === null) {
            return '';
        }

        return trim((string) $v);
    }

    private function intOrNull(mixed $v): ?int
    {
        $s = $this->str($v);
        if ($s === '') {
            return null;
        }
        if (!is_numeric($s)) {
            return null;
        }

        return (int) $s;
    }

    private function decimalOrNull(mixed $v): ?string
    {
        $s = $this->str($v);
        if ($s === '') {
            return null;
        }
        $s = str_replace([',', ' '], '', $s);
        if ($s === '' || !is_numeric($s)) {
            return null;
        }

        return $s;
    }
}
