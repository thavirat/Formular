<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PackingFormListExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
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
        return [
            'เลขที่เอกสาร',
            'วันที่เอกสาร',
            'Invoice No.',
            'Declaration No.',
            'ลูกค้า',
            'ประเทศ',
            'Port of Loading',
            'Port of Discharge',
            'Vessel Name',
            'ETD',
            'Terms of Payment',
            'จำนวนรายการ',
            'PKG (Cartons)',
            'Qty',
            'CBM',
            'N.W.',
            'G.W.',
            'N.T.',
            'G.T.',
            'ไฟล์ต้นทาง',
        ];
    }

    public function map($r): array
    {
        return [
            $r->doc_no,
            $r->doc_date ? $r->doc_date->format('Y-m-d') : '',
            $r->invoice_no,
            $r->declaration_no,
            $r->customer_name,
            $r->country,
            $r->shipped_from,
            $r->port_of_discharge,
            $r->per_vessel,
            $r->sailing_date ? $r->sailing_date->format('Y-m-d') : '',
            $r->term_of_payment,
            (int) ($r->details_count ?? 0),
            $r->pkg,
            $r->qty,
            $r->cubic_meter,
            $r->weight_nw,
            $r->weight_gw,
            $r->weight_nt,
            $r->weight_gt,
            $r->source_filename,
        ];
    }
}
