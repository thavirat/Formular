<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerShippingMark extends Model
{
    protected $fillable = [
        'customer_id',
        'name',
        'content',
        'is_default',
        'active',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'active'     => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
