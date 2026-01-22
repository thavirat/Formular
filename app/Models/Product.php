<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'brand_id',
        'design_id',
        'unit_id',
        'code',
        'part_no',
        'name_th',
        'name_en',
        'name_cn',
        'drawing',
        'unit',
        'width',
        'height',
        'length',
        'weight',
        'cube',
        'active',
        'price',
        'sub_category_id',
        'group_id',
    ];
    public function CustomerLevelDiscout(){
        return $this->hasOne('\App\Models\CustomerLevelDiscout' , 'product_id' , 'id');
    }

    public function CustomerLevelDiscouts() {
        return $this->hasMany('\App\Models\CustomerLevelDiscout' , 'product_id' , 'id');
    }
}
