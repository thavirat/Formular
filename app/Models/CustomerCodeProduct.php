<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerCodeProduct extends Model
{
    protected $fillable = [
        'customer_id',
        'product_id',
        'code',
    ];
}
