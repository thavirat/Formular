<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentMethod extends Model
{
    protected $fillable = ['name', 'seq', 'active'];

    public function scopeActive($q)
    {
        return $q->where('active', 'T');
    }
}
