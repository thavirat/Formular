<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackingFormDetail extends Model
{
    protected $fillable = [
        'packing_form_id',
        'excel_row',
        'from',
        'to',
        'pi_product_id',
        'part_no',
        'cus_part_no',
        'description',
        'formular_number',
        'width',
        'lenght',
        'height',
        'qty',
        'cubic_meter',
        'weight_nw',
        'weight_gw',
        'weight_nt',
        'weight_gt',
        'uom',
        'from_co',
    ];

    protected $casts = [
        'width' => 'decimal:2',
        'lenght' => 'decimal:2',
        'height' => 'decimal:2',
        'cubic_meter' => 'decimal:2',
        'weight_nw' => 'decimal:2',
        'weight_gw' => 'decimal:2',
        'weight_nt' => 'decimal:2',
        'weight_gt' => 'decimal:2',
    ];

    public function packingForm(): BelongsTo
    {
        return $this->belongsTo(PackingForm::class, 'packing_form_id');
    }

    public function piProduct(): BelongsTo
    {
        return $this->belongsTo(ProformaInvoiceProduct::class, 'pi_product_id');
    }
}
