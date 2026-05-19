<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PackingForm extends Model
{
    protected $fillable = [
        'import_file_sha256',
        'source_filename',
        'to',
        'customer_id',
        'customer_name',
        'country',
        'place_of_issue',
        'customer_address',
        'customer_phone',
        'doc_date',
        'sailing_date',
        'shipped_from',
        'per_vessel',
        'lc_no',
        'issued_by',
        'doc_no',
        'invoice_no',
        'run_no',
        'pkg',
        'qty',
        'cubic_meter',
        'weight_nw',
        'weight_gw',
        'weight_nt',
        'weight_gt',
    ];

    protected $casts = [
        'doc_date' => 'date',
        'sailing_date' => 'date',
        'cubic_meter' => 'decimal:2',
        'weight_nw' => 'decimal:2',
        'weight_gw' => 'decimal:2',
        'weight_nt' => 'decimal:2',
        'weight_gt' => 'decimal:2',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(PackingFormDetail::class, 'packing_form_id')->orderBy('excel_row')->orderBy('id');
    }
}
