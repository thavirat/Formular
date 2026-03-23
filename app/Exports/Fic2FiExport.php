<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class Fic2FiExport implements FromView, ShouldAutoSize
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
}
