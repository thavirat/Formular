<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PackingDocumentExport implements FromView
{
    protected $packingForm;
    protected $variant;
    protected $descSource;
    protected $custDescMap;

    public function __construct($packingForm, string $variant, string $descSource, array $custDescMap)
    {
        $this->packingForm = $packingForm;
        $this->variant = $variant;
        $this->descSource = $descSource;
        $this->custDescMap = $custDescMap;
    }

    public function view(): View
    {
        return view('admin.PackingForm._excel_pl_document', [
            'packingForm' => $this->packingForm,
            'variant' => $this->variant,
            'descSource' => $this->descSource,
            'custDescMap' => $this->custDescMap,
        ]);
    }
}
