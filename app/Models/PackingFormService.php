<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackingFormService extends Model
{
    protected $fillable = [
        'packing_form_id',
        'seq',
        'name',
        'amount',
    ];

    protected $casts = [
        'amount' => 'decimal:4',
    ];

    public function packingForm(): BelongsTo
    {
        return $this->belongsTo(PackingForm::class, 'packing_form_id');
    }
}
