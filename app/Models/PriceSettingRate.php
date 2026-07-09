<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceSettingRate extends Model
{
    protected $fillable = ['price_setting_id', 'currency_id', 'rate'];

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
}
