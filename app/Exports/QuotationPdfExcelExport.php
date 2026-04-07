<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class QuotationPdfExcelExport implements WithMultipleSheets
{
    protected $quotation;

    public function __construct($quotation)
    {
        $this->quotation = $quotation;
    }

    public function sheets(): array
    {
        return [
            new QuotationPdfStyleSheet($this->quotation),
            new QuotationImportDataSheet($this->quotation),
        ];
    }
}

class QuotationPdfStyleSheet implements FromView, ShouldAutoSize
{
    protected $quotation;

    public function __construct($quotation)
    {
        $this->quotation = $quotation;
    }

    public function view(): View
    {
        return view('admin.Quotation.quotation_pdf_excel', [
            'Quotation' => $this->quotation
        ]);
    }
}

class QuotationImportDataSheet implements FromView, ShouldAutoSize
{
    protected $quotation;

    public function __construct($quotation)
    {
        $this->quotation = $quotation;
    }

    public function view(): View
    {
        return view('admin.Quotation.quotation_pdf_excel_import_data', [
            'Quotation' => $this->quotation
        ]);
    }
}

