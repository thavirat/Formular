<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceSettingLevel extends Model
{
    protected $fillable = ['price_setting_id', 'level_id', 'multiplier', 'is_qty_base'];

    public function level()
    {
        return $this->belongsTo(CustomerLevel::class, 'level_id');
    }
}
