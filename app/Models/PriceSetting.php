<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceSetting extends Model
{
    protected $fillable = ['start_date', 'end_date', 'active'];

    public function rates()
    {
        return $this->hasMany(PriceSettingRate::class, 'price_setting_id');
    }

    public function levels()
    {
        return $this->hasMany(PriceSettingLevel::class, 'price_setting_id');
    }

    public function qtys()
    {
        return $this->hasMany(PriceSettingQty::class, 'price_setting_id')->orderBy('min_qty');
    }

    public function scopeActive($q)
    {
        return $q->where('active', 'T');
    }
}
