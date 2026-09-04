<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class Fic2FiExport implements FromView, ShouldAutoSize, WithEvents
{
    protected $pi;

    public function __construct($pi)
    {
        $this->pi = $pi;
    }

    public function view(): View
    {

        return view('admin.ProformaInvoice.fic_2_fi', [
            'ProformaInvoice' => $this->pi
        ]);
    }

    /**
     * ปิดเส้นตาราง (gridlines) ของชีต — ให้เอกสารสะอาด ไม่มีเส้นแถว
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->setShowGridlines(false);
            },
        ];
    }
}
