<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $fillable = [
        'name_th',
        'name_en',
        'code'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'sub_category_id');
    }

    /** ชื่อหมวดตามภาษาที่เลือก (en = default) */
    public function catalogName()
    {
        $th = Product::cleanName($this->name_th);
        $en = Product::cleanName($this->name_en);

        return app()->getLocale() === 'th' ? ($th ?: $en) : ($en ?: $th);
    }
}
