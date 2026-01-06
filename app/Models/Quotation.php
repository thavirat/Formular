<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    public function products()
    {
        return $this->hasMany(QuotationProduct::class, 'quotation_id');
    }
}
