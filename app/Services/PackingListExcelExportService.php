<?php

namespace App\Services;

use App\Models\ProformaInvoice;
use App\Models\ProformaInvoiceProduct;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * สร้างไฟล์ Excel รูปแบบ SANDEN สำหรับนำเข้าเมนู Packing List
 * (เซลล์เดียวกับ PackingListImportService)
 */
class PackingListExcelExportService
{
    public function downloadForProformaInvoice(ProformaInvoice $pi): StreamedResponse
    {
        $spreadsheet = $this->buildForProformaInvoice($pi);
        $safeDoc = preg_replace('/[^\w\-]+/', '_', (string) $pi->doc_no) ?: 'PI';
        $filename = 'PACKING_LIST_'.$safeDoc.'_'.date('YmdHis').'.xlsx';

        return $this->streamDownload($spreadsheet, $filename);
    }

    public function downloadBlankTemplate(): StreamedResponse
    {
        $spreadsheet = $this->buildBlankTemplate();
        $filename = 'PACKING_LIST_TEMPLATE_'.date('YmdHis').'.xlsx';

        return $this->streamDownload($spreadsheet, $filename);
    }

    private function buildForProformaInvoice(ProformaInvoice $pi): Spreadsheet
    {
        $spreadsheet = $this->buildBlankTemplate();
        $sheet = $spreadsheet->getActiveSheet();

        $customerName = trim((string) ($pi->company_name ?? ''));
        if ($customerName === '' && $pi->relationLoaded('customer') && $pi->customer) {
            $customerName = trim((string) ($pi->customer->company_name ?? ''));
        }

        $sheet->setCellValue('D2', $customerName);
        $sheet->setCellValue('D3', $customerName);
        if ($pi->doc_date) {
            $sheet->setCellValue('A7', $pi->doc_date);
        }

        $products = $this->loadProducts($pi);
        $row = PackingListImportService::FIRST_DATA_ROW;
        $totalQty = 0;
        $lineNo = 1;

        foreach ($products as $product) {
            $partNo = trim((string) ($product->part_no ?? $product->code ?? ''));
            if ($partNo === '') {
                continue;
            }

            $this->writeDetailRow($sheet, $row, $pi->doc_no, $product, $lineNo);
            $totalQty += (float) ($product->qty ?? 0);
            $lineNo++;
            $row++;
        }

        if ($totalQty > 0) {
            $sheet->setCellValue('T8', $totalQty);
        }

        return $spreadsheet;
    }

    private function buildBlankTemplate(): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('PACKING LIST');

        $sheet->setCellValue('C2', 'To');
        $sheet->setCellValue('C3', 'Customer');
        $sheet->setCellValue('C4', 'Country');
        $sheet->setCellValue('A6', 'Date');
        $sheet->setCellValue('M7', 'PKG');
        $sheet->setCellValue('O7', 'NW');
        $sheet->setCellValue('P7', 'GW');
        $sheet->setCellValue('Q7', 'NT');
        $sheet->setCellValue('R7', 'GT');
        $sheet->setCellValue('S7', 'CBM');
        $sheet->setCellValue('T7', 'QTY');

        $headers = [
            1 => 'Pkg',
            2 => 'From',
            3 => 'To',
            4 => 'PI Doc',
            5 => 'Part no',
            6 => 'Cus part',
            7 => 'Description',
            8 => 'Formular',
            9 => 'W',
            10 => 'L',
            11 => 'H',
            12 => 'Qty',
            13 => 'CBM',
            15 => 'GW',
            16 => 'NT',
            17 => 'GT',
            18 => 'UOM',
            22 => 'From CO',
        ];
        $headerRow = PackingListImportService::FIRST_DATA_ROW - 1;
        foreach ($headers as $col => $label) {
            $sheet->setCellValueByColumnAndRow($col, $headerRow, $label);
        }

        return $spreadsheet;
    }

    private function writeDetailRow(
        \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet,
        int $row,
        ?string $piDocNo,
        object $product,
        int $lineNo
    ): void {
        $partNo = trim((string) ($product->part_no ?? $product->code ?? ''));

        $sheet->setCellValueByColumnAndRow(PackingListImportService::COL_PKG_MARKER + 1, $row, $lineNo);
        $sheet->setCellValueByColumnAndRow(PackingListImportService::COL_PI_DOC + 1, $row, $piDocNo ?? '');
        $sheet->setCellValueByColumnAndRow(PackingListImportService::COL_PART + 1, $row, $partNo);
        $sheet->setCellValueByColumnAndRow(PackingListImportService::COL_CUS_PART + 1, $row, $product->cus_code ?? '');
        $desc = trim((string) ($product->detail_eng ?? $product->detail_thai ?? $product->name_en ?? ''));
        $sheet->setCellValueByColumnAndRow(PackingListImportService::COL_DESC + 1, $row, $desc);
        $sheet->setCellValueByColumnAndRow(PackingListImportService::COL_FORMULAR + 1, $row, $product->drawing ?? '');
        if ($product->qty !== null && $product->qty !== '') {
            $sheet->setCellValueByColumnAndRow(PackingListImportService::COL_QTY + 1, $row, (float) $product->qty);
        }
    }

    private function loadProducts(ProformaInvoice $pi)
    {
        return ProformaInvoiceProduct::query()
            ->leftJoin('products', 'proforma_invoice_products.product_id', '=', 'products.id')
            ->where('proforma_invoice_products.pi_id', $pi->id)
            ->select(
                'proforma_invoice_products.*',
                'products.code',
                'products.name_en'
            )
            ->orderBy('proforma_invoice_products.seq')
            ->orderBy('proforma_invoice_products.id')
            ->get();
    }

    private function streamDownload(Spreadsheet $spreadsheet, string $filename): StreamedResponse
    {
        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
