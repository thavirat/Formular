<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductOeNumber extends Model
{
    protected $fillable = [
        'product_id',
        'oe_number',
        'maker',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
