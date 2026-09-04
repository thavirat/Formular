<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportOrderReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $rows;

    public function __construct($rows)
    {
        $this->rows = $rows;
    }

    public function collection()
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return ['Invoice No.', 'วันที่', 'ETD', 'ลูกค้า', 'ประเทศ', 'ภูมิภาค', 'CBM', 'G.W.(KGS)', 'ยอดเงิน', 'สกุลเงิน'];
    }

    public function map($r): array
    {
        return [
            $r->invoice_no ?: $r->doc_no,
            $r->doc_date ? Carbon::parse($r->doc_date)->format('d/m/Y') : '',
            $r->sailing_date ? Carbon::parse($r->sailing_date)->format('d/m/Y') : '',
            $r->customer_name,
            $r->country,
            $r->region ?: '-',
            (float) $r->cubic_meter,
            (float) $r->weight_gw,
            (float) $r->ship_amount,
            $r->ship_currency ?: '',
        ];
    }
}
