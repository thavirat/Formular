<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerCode extends Model
{
    protected $fillable = [
        'customer_id',
        'product_id',
        'code',
    ];
}
