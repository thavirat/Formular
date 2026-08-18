<?php

namespace App\Services;

use App\Models\AdminUser;
use App\Models\Customer;
use App\Models\ProformaInvoice;
use App\Models\ProformaInvoiceProduct;
use App\Models\ProformaInvoiceRemark;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * นำเข้า PI จากไฟล์ fic2fi (.xls/.xlsx)
 * โครงสร้าง: A2=Cust_Code, B2=Ship To Code, J2=Ship To Cust Name, J3.. = Shipping Marks
 * แถวสินค้าเริ่มแถว 12: A=Cust PO, B=Sale, C=Ship Date, E=ITM, F=Part No, G=Qty, H=Unit Price,
 *   I=Customer Part, J=Description, K=UNIT, L=DWG, M=Fac No ; หมายเหตุอยู่คอลัมน์ J ใต้รายการ
 */
class ProformaInvoiceImportService
{
    public const FIRST_ITEM_ROW = 12;

    /**
     * @param array $opts currency_id, incoterm_id, credit_payment_id, doc_date, doc_no(optional), generated_doc_no
     */
    public function import(UploadedFile $file, array $opts): array
    {
        $sheet = IOFactory::load($file->getRealPath())->getActiveSheet();
        $warnings = [];

        $custCode   = $this->str($sheet, 'A2');
        $shipToCode = $this->str($sheet, 'B2');
        $shipToName = $this->str($sheet, 'J2');

        // Shipping marks + C/NO. (คอลัมน์ J แถว 3 ลงมา ก่อนถึงหัวตารางแถว 11)
        $markLines = [];
        $cno = null;
        for ($r = 3; $r <= 10; $r++) {
            $v = $this->str($sheet, 'J'.$r);
            if ($v === '') {
                continue;
            }
            if (preg_match('/^C\/?NO\.?\s*(.+)$/i', $v, $m)) {
                $cno = trim($m[1]);
            } else {
                $markLines[] = $v;
            }
        }
        $shipRemark = implode("\n", $markLines) ?: null;

        // ข้อมูลระดับเอกสาร (ซ้ำทุกแถว ใช้จากแถวแรก)
        $custPo   = $this->str($sheet, 'A'.self::FIRST_ITEM_ROW);
        $saleName = $this->str($sheet, 'B'.self::FIRST_ITEM_ROW);
        $shipDate = $this->cellDate($sheet, 'C'.self::FIRST_ITEM_ROW);

        // อ่านรายการสินค้า
        $items = [];
        $lastItemRow = self::FIRST_ITEM_ROW - 1;
        for ($r = self::FIRST_ITEM_ROW; $r <= $sheet->getHighestRow(); $r++) {
            $partNo = $this->str($sheet, 'F'.$r);
            if ($partNo === '') {
                break; // หมดรายการสินค้า
            }
            $items[] = [
                'itm'        => $this->str($sheet, 'E'.$r),
                'part_no'    => $partNo,
                'qty'        => (float) str_replace(',', '', $this->str($sheet, 'G'.$r)),
                'unit_price' => (float) str_replace(',', '', $this->str($sheet, 'H'.$r)),
                'cus_code'   => $this->str($sheet, 'I'.$r),
                'desc'       => $this->str($sheet, 'J'.$r),
                'drawing'    => $this->str($sheet, 'L'.$r),
            ];
            $lastItemRow = $r;
        }

        if (empty($items)) {
            return ['status' => 0, 'message' => 'ไม่พบรายการสินค้าในไฟล์ (เริ่มอ่านแถว '.self::FIRST_ITEM_ROW.' คอลัมน์ Part No)'];
        }

        // หมายเหตุ: คอลัมน์ J ใต้รายการสินค้า (ตัดเลขนำหน้า เช่น "1. ")
        $remarks = [];
        for ($r = $lastItemRow + 1; $r <= $sheet->getHighestRow(); $r++) {
            $v = $this->str($sheet, 'J'.$r);
            if ($v === '') {
                continue;
            }
            $remarks[] = preg_replace('/^\s*\d+\.\s*/', '', $v);
        }

        // resolve ลูกค้า จาก Cust_Code
        $customer = $custCode !== '' ? Customer::where('code', $custCode)->first() : null;
        if (!$customer) {
            $warnings[] = 'ไม่พบลูกค้ารหัส "'.$custCode.'" ในระบบ — ใช้ชื่อจากไฟล์แทน (บริษัท/ที่อยู่/เลขภาษีจะว่าง)';
        }

        // resolve ผู้ขาย จากชื่อ
        $saleBy = $this->resolveSale($saleName);
        if ($saleName !== '' && !$saleBy) {
            $warnings[] = 'ไม่พบผู้ขายชื่อ "'.$saleName.'" — เว้นว่างไว้';
        }

        // เลขที่เอกสาร: ใช้ที่กรอก > จากไฟล์ (FA-xxxxx -> PIxxxxx) > ที่ระบบ gen
        $docNo = trim((string) ($opts['doc_no'] ?? ''));
        if ($docNo === '') {
            if (preg_match('/(\d{6,})/', $custPo, $m)) {
                $docNo = 'PI'.$m[1];
            } else {
                $docNo = $opts['generated_doc_no'] ?? ('PI'.date('ym').'001');
            }
        }
        if (ProformaInvoice::where('doc_no', $docNo)->exists()) {
            return ['status' => 0, 'message' => 'เลขที่เอกสาร "'.$docNo.'" มีอยู่แล้วในระบบ กรุณาระบุเลขอื่น'];
        }

        $subtotal = 0.0;
        foreach ($items as $it) {
            $subtotal += $it['qty'] * $it['unit_price'];
        }

        return DB::transaction(function () use (
            $opts, $customer, $shipToName, $custCode, $shipToCode, $shipRemark, $cno,
            $custPo, $shipDate, $saleBy, $docNo, $items, $remarks, $subtotal, $warnings
        ) {
            $pi = new ProformaInvoice();
            $pi->quotation_id      = null;
            $pi->status_id         = 1;
            $pi->customer_id       = $customer?->id;
            $pi->incoterm_id       = $opts['incoterm_id'] ?: null;
            $pi->currency_id       = $opts['currency_id'] ?: null;
            $pi->credit_payment_id = $opts['credit_payment_id'] ?: null;
            $pi->doc_no            = $docNo;
            $pi->doc_date          = $opts['doc_date'] ?: now()->format('Y-m-d');
            $pi->run_no            = preg_match('/(\d+)$/', $docNo, $m) ? (int) $m[1] : 0;
            $pi->contact_name      = $customer?->contact_name;
            $pi->company_name      = $customer?->company_name ?: $shipToName;
            $pi->tax_id            = $customer?->tax_id;
            $pi->address           = $customer?->address;
            $pi->ship_date         = $shipDate;
            $pi->ship_to_code      = $shipToCode ?: null;
            $pi->ship_remark       = $shipRemark;
            $pi->cno               = $cno;
            $pi->customer_po       = $custPo ?: null;
            $pi->subtotal          = $subtotal;
            $pi->total             = $subtotal;
            $pi->created_by        = optional(Auth::guard('admin')->user())->id;
            $pi->sale_by           = $saleBy ?: $pi->created_by;
            $pi->save();

            $seq = 1;
            foreach ($items as $it) {
                $prod = \App\Models\Product::where('code', $it['part_no'])->first();
                $item = new ProformaInvoiceProduct();
                $item->pi_id          = $pi->id;
                $item->product_id     = $prod?->id;
                $item->part_no        = $it['part_no'];
                $item->seq            = is_numeric($it['itm']) ? (int) $it['itm'] : $seq;
                $item->drawing        = $it['drawing'] ?: null;
                $item->cus_code       = $it['cus_code'] ?: null;
                $item->detail_eng     = $it['desc'] ?: null;
                $item->detail_thai    = $it['desc'] ?: null;
                $item->qty            = $it['qty'];
                $item->price_per_item = $it['unit_price'];
                $item->total_price    = $it['qty'] * $it['unit_price'];
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

    private function str(Worksheet $sheet, string $coord): string
    {
        return trim((string) $sheet->getCell($coord)->getFormattedValue());
    }

    private function cellDate(Worksheet $sheet, string $coord): ?string
    {
        $cell = $sheet->getCell($coord);
        $val = $cell->getValue();
        if ($val === null || $val === '') {
            return null;
        }
        if (is_numeric($val)) {
            try {
                return ExcelDate::excelToDateTimeObject((float) $val)->format('Y-m-d');
            } catch (\Throwable $e) {
                return null;
            }
        }
        $ts = strtotime((string) $val);

        return $ts ? date('Y-m-d', $ts) : null;
    }
}
