<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerRemark extends Model
{
    protected $fillable = [
        'customer_id',
        'content',
        'active',
        'seq',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
