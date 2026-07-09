<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceSettingQty extends Model
{
    protected $table = 'price_setting_qtys'; // กัน Laravel เดาเป็น price_setting_qties
    protected $fillable = ['price_setting_id', 'min_qty', 'giveaway'];
}
