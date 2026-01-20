<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerLevelDiscout extends Model
{
    protected $fillable = [
        'level_id',
        'product_id',
        'currency_id',
        'price'
    ];
}
