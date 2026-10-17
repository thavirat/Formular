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
        'content',
        'active',
        'price',
        'sub_category_id',
        'group_id',
        'factory_id',
    ];
    public function CustomerLevelDiscout(){
        return $this->hasOne('\App\Models\CustomerLevelDiscout' , 'product_id' , 'id');
    }

    public function CustomerLevelDiscouts() {
        return $this->hasMany('\App\Models\CustomerLevelDiscout' , 'product_id' , 'id');
    }

    public function Factory(){
        return $this->belongsTo('\App\Models\Factory' , 'factory_id' , 'id');
    }

    public function unitProduct()
    {
        return $this->belongsTo(UnitProduct::class, 'unit_id');
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    public function brand()
    {
        return $this->belongsTo(BrandProduct::class, 'brand_id');
    }

    // Catalog: รถที่รองรับ (fitment) + เลข OE  — ดู CATALOG_PLAN.md
    public function fitments()
    {
        return $this->hasMany(ProductFitment::class, 'product_id');
    }

    public function oeNumbers()
    {
        return $this->hasMany(ProductOeNumber::class, 'product_id');
    }
}
