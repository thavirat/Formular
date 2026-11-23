<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FicImportLine extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'inv_date'    => 'date',
        'qty'         => 'decimal:2',
        'price'       => 'decimal:2',
        'total_price' => 'decimal:2',
        'candidates'  => 'array',
        'applied'     => 'boolean',
    ];

    public function import(): BelongsTo
    {
        return $this->belongsTo(FicImport::class, 'fic_import_id');
    }
}
