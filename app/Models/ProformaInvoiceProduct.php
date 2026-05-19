<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProformaInvoiceProduct extends Model
{
    public function pi(): BelongsTo
    {
        return $this->belongsTo(ProformaInvoice::class, 'pi_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
