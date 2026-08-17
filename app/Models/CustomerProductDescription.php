<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerProductDescription extends Model
{
    protected $fillable = [
        'customer_id',
        'product_id',
        'description',
    ];
}
