<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'code',
        'contact_name',
        'company_name',
        'address',
        'tax_id',
        'phone',
        'mobile',
        'fax',
        'remark',
        'level_id',
        'short_name',
        'credit_day',
        'bill_name',
        'active',
    ];
}
