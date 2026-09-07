<?php

namespace App\Services;

use App\Models\AdminUser;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProformaInvoice;
use App\Models\ProformaInvoiceProduct;
use App\Models\ProformaInvoiceRemark;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * นำเข้า PI จากไฟล์ CSV "ใบสั่งจอง" (export จากระบบบัญชี)
 * อ่านตามชื่อ header (ไม่ยึดตำแหน่ง cell) — 1 แถว = 1 รายการสินค้า, ฟิลด์ระดับเอกสารซ้ำทุกแถว
 *
 * คอลัมน์ที่ใช้:
 *   ระดับเอกสาร: custcode, custnameEng, address1, address2, DocuNo, DocuDate, ShipDate,
 *               Custpono, Refno, currcode, empnameeng, ContactName, remark1..3
 *   ระดับรายการ: goodcode(Part No), goodnameeng1(Description), goodqty2(Qty),
 *               goodprice2(Unit Price), goodamnt(Amount), goodunitnameeng(Unit), listno(ลำดับ)
 */
class ProformaInvoiceImportService
{
    /**
     * @param array $opts currency_id, incoterm_id, credit_payment_id, doc_date, doc_no(optional), generated_doc_no
     */
    public function import(UploadedFile $file, array $opts): array
    {
        [$header, $rows] = $this->readCsv($file->getRealPath());
        if (empty($header) || empty($rows)) {
            return ['status' => 0, 'message' => 'ไฟล์ CSV ว่างหรืออ่านไม่ได้'];
        }

        $idx = [];
        foreach ($header as $i => $h) {
            $idx[trim((string) $h)] = $i;
        }
        $get = function (array $row, string $col) use ($idx) {
            return isset($idx[$col], $row[$idx[$col]]) ? trim((string) $row[$idx[$col]]) : '';
        };

        $warnings = [];
        $first = $rows[0];

        // ---------- ข้อมูลระดับเอกสาร (จากแถวแรก) ----------
        $custCode    = $get($first, 'custcode');
        $custNameEng = $get($first, 'custnameEng');
        $address     = trim($get($first, 'address1').' '.$get($first, 'address2'));
        $docNoRaw    = $get($first, 'DocuNo');
        $docDateCsv  = $this->parseDate($get($first, 'DocuDate'));
        $shipDate    = $this->parseDate($get($first, 'ShipDate'));
        $custPo      = $get($first, 'Custpono') ?: $get($first, 'Refno');
        $currCode    = $get($first, 'currcode');
        $saleName    = $this->cleanSaleName($get($first, 'empnameeng'));
        $contact     = $get($first, 'ContactName');

        $remarks = [];
        foreach (['remark1', 'remark2', 'remark3'] as $rk) {
            $rv = $get($first, $rk);
            if ($rv !== '') {
                $remarks[] = $rv;
            }
        }

        // ---------- resolve ลูกค้า / ผู้ขาย / สกุลเงิน ----------
        $customer = $this->resolveCustomer($custCode, $custNameEng);
        if (!$customer) {
            $warnings[] = 'ไม่พบลูกค้า "'.($custNameEng ?: $custCode).'" ในระบบ — ใช้ชื่อจากไฟล์แทน (ที่อยู่/เลขภาษีจะว่าง)';
        }

        $saleBy = $this->resolveSale($saleName);
        if ($saleName !== '' && !$saleBy) {
            $warnings[] = 'ไม่พบผู้ขายชื่อ "'.$saleName.'" — ใช้ผู้ที่นำเข้าแทน';
        }

        // สกุลเงิน: ที่เลือกในฟอร์ม > จากไฟล์ (currcode ตรง symbol/name)
        $currencyId = $opts['currency_id'] ?: null;
        if (!$currencyId && $currCode !== '') {
            $cur = Currency::whereRaw('UPPER(symbol) = ?', [mb_strtoupper($currCode)])
                ->orWhereRaw('UPPER(name) = ?', [mb_strtoupper($currCode)])
                ->first();
            $currencyId = $cur?->id;
        }
        if (!$currencyId) {
            $warnings[] = 'ไม่พบสกุลเงิน "'.$currCode.'" — โปรดตรวจสอบ';
        }

        // ---------- เลขที่เอกสาร: กรอกเอง > จากไฟล์ (clean) > ระบบ gen ----------
        $docNo = trim((string) ($opts['doc_no'] ?? ''));
        if ($docNo === '') {
            $clean = preg_replace('/[^A-Za-z0-9]/', '', $docNoRaw); // "PI-26080333." -> "PI26080333"
            $docNo = $clean !== '' ? $clean : ($opts['generated_doc_no'] ?? ('PI'.date('ym').'001'));
        }
        if (ProformaInvoice::where('doc_no', $docNo)->exists()) {
            return ['status' => 0, 'message' => 'เลขที่เอกสาร "'.$docNo.'" มีอยู่แล้วในระบบ กรุณาระบุเลขอื่น'];
        }

        // ---------- รายการสินค้า (เฉพาะแถวที่เป็นเอกสารเดียวกันกับแถวแรก) ----------
        $items = [];
        $otherDocs = 0;
        foreach ($rows as $row) {
            $code = $get($row, 'goodcode');
            if ($code === '') {
                continue;
            }
            // ถ้า CSV มีหลายเอกสาร ให้เอาเฉพาะเลขเดียวกับแถวแรก
            if ($docNoRaw !== '' && $get($row, 'DocuNo') !== '' && $get($row, 'DocuNo') !== $docNoRaw) {
                $otherDocs++;
                continue;
            }
            $qty    = (float) str_replace(',', '', $get($row, 'goodqty2'));
            $price  = (float) str_replace(',', '', $get($row, 'goodprice2'));
            $amount = (float) str_replace(',', '', $get($row, 'goodamnt'));
            $items[] = [
                'seq'     => $get($row, 'listno'),
                'code'    => $code,
                'name_en' => $get($row, 'goodnameeng1') ?: $get($row, 'goodname1') ?: $get($row, 'goodname'),
                'qty'     => $qty,
                'price'   => $price,
                'amount'  => $amount > 0 ? $amount : $qty * $price,
                'unit'    => $get($row, 'goodunitnameeng') ?: $get($row, 'goodunitname'),
            ];
        }

        if (empty($items)) {
            return ['status' => 0, 'message' => 'ไม่พบรายการสินค้าในไฟล์ (คอลัมน์ goodcode)'];
        }
        if ($otherDocs > 0) {
            $warnings[] = 'ไฟล์มีหลายเอกสาร — นำเข้าเฉพาะ '.$docNoRaw.' ('.$otherDocs.' แถวของเอกสารอื่นถูกข้าม)';
        }

        $subtotal = array_sum(array_column($items, 'amount'));

        return DB::transaction(function () use (
            $opts, $customer, $custNameEng, $address, $currencyId, $custPo, $shipDate,
            $docDateCsv, $saleBy, $contact, $docNo, $items, $remarks, $subtotal, $warnings
        ) {
            $pi = new ProformaInvoice();
            $pi->quotation_id      = null;
            $pi->status_id         = 1;
            $pi->customer_id       = $customer?->id;
            $pi->incoterm_id       = $opts['incoterm_id'] ?: null;
            $pi->currency_id       = $currencyId;
            $pi->credit_payment_id = $opts['credit_payment_id'] ?: null;
            $pi->doc_no            = $docNo;
            $pi->doc_date          = $opts['doc_date'] ?: ($docDateCsv ?: now()->format('Y-m-d'));
            $pi->run_no            = preg_match('/(\d+)$/', $docNo, $m) ? (int) $m[1] : 0;
            $pi->contact_name      = $customer?->contact_name ?: ($contact ?: null);
            $pi->company_name      = $customer?->company_name ?: $custNameEng;
            $pi->tax_id            = $customer?->tax_id;
            $pi->address           = $customer?->address ?: ($address ?: null);
            $pi->ship_date         = $shipDate;
            $pi->ship_to_code      = null;
            $pi->ship_remark       = null;
            $pi->cno               = null;
            $pi->customer_po       = $custPo ?: null;
            $pi->subtotal          = $subtotal;
            $pi->total             = $subtotal;
            $pi->created_by        = optional(Auth::guard('admin')->user())->id;
            $pi->sale_by           = $saleBy ?: $pi->created_by;
            $pi->save();

            $seq = 1;
            foreach ($items as $it) {
                $prod = Product::where('code', trim($it['code']))->first();
                $item = new ProformaInvoiceProduct();
                $item->pi_id          = $pi->id;
                $item->product_id     = $prod?->id;
                $item->part_no        = $it['code'];
                $item->seq            = is_numeric($it['seq']) ? (int) $it['seq'] : $seq;
                $item->drawing        = $prod?->drawing ?: null;
                $item->cus_code       = null;
                // DESCRIPTION: master ของสินค้า (ถ้าเจอ) มิฉะนั้นใช้ชื่อจากไฟล์
                $item->detail_eng     = $prod?->name_en ?: ($it['name_en'] ?: null);
                $item->detail_thai    = $prod?->name_th ?: null;
                $item->qty            = $it['qty'];
                $item->price_per_item = $it['price'];   // ✅ ดึงราคาจากไฟล์ CSV
                $item->total_price    = $it['amount'];
                $item->save();
                $seq++;
            }

            foreach ($remarks as $i => $rm) {
                ProformaInvoiceRemark::create(['pi_id' => $pi->id, 'seq' => $i + 1, 'remark' => $rm]);
            }

            return [
                'status'   => 1,
                'pi_id'    => $pi->id,
                'doc_no'   => $docNo,
                'items'    => count($items),
                'warnings' => $warnings,
            ];
        });
    }

    /** อ่าน CSV -> [header(array), rows(array of array)] ตัดแถวว่าง */
    private function readCsv(string $path): array
    {
        $fh = fopen($path, 'r');
        if ($fh === false) {
            return [[], []];
        }
        $header = fgetcsv($fh);
        if ($header === false) {
            fclose($fh);

            return [[], []];
        }
        // ตัด BOM ที่หัวคอลัมน์แรก (ถ้ามี)
        if (isset($header[0])) {
            $header[0] = preg_replace('/^\x{FEFF}/u', '', (string) $header[0]);
        }
        $rows = [];
        while (($row = fgetcsv($fh)) !== false) {
            if (trim(implode('', $row)) === '') {
                continue;
            }
            $rows[] = $row;
        }
        fclose($fh);

        return [$header, $rows];
    }

    /** ตัดคำนำหน้าชื่อ (Miss/Mr/Mrs/Ms) + ยุบช่องว่างซ้ำ เพื่อจับคู่ผู้ขายได้ */
    private function cleanSaleName(string $name): string
    {
        $name = preg_replace('/^\s*(miss|mrs|mr|ms)\.?\s*/i', '', trim($name));

        return trim(preg_replace('/\s+/', ' ', $name));
    }

    /** resolve ลูกค้า: code -> ชื่อ (exact แบบ normalize) -> prefix (เฉพาะเจอตัวเดียว) */
    private function resolveCustomer(string $custCode, string $custName): ?Customer
    {
        $custCode = trim($custCode);
        if ($custCode !== '') {
            $byCode = Customer::where('code', $custCode)->first();
            if ($byCode) {
                return $byCode;
            }
        }
        $name = trim($custName);
        if ($name === '') {
            return null;
        }
        $target = mb_strtoupper(trim(str_replace(['.', ','], '', $name)));
        $exact = Customer::whereRaw("UPPER(TRIM(REPLACE(REPLACE(company_name,'.',''),',',''))) = ?", [$target])->first();
        if ($exact) {
            return $exact;
        }
        $prefix = Customer::where('company_name', 'like', $name.'%')->limit(2)->get();
        if ($prefix->count() === 1) {
            return $prefix->first();
        }

        return null;
    }

    private function resolveSale(string $name): ?int
    {
        $name = trim($name);
        if ($name === '') {
            return null;
        }
        $u = AdminUser::whereRaw("LOWER(TRIM(CONCAT(COALESCE(firstname,''),' ',COALESCE(lastname,'')))) = ?", [mb_strtolower($name)])
            ->orWhereRaw('LOWER(nickname) = ?', [mb_strtolower($name)])
            ->first();

        return $u?->id;
    }

    /** แปลงวันที่ "9/3/2026 00:00:00" (M/D/Y) -> Y-m-d */
    private function parseDate(string $val): ?string
    {
        $val = trim($val);
        if ($val === '') {
            return null;
        }
        $ts = strtotime($val);

        return $ts ? date('Y-m-d', $ts) : null;
    }
}
