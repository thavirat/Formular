<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'code',
        'contact_name',
        'company_name',
        'address',
        'tax_id',
        'phone',
        'mobile',
        'fax',
        'remark',
        'shipping_mark',
        'level_id',
        'short_name',
        'credit_day',
        'bill_name',
        'active',
    ];

    /** ชุด Shipping Mark ของลูกค้า (หลายชุด, จัดการในหน้าแก้ไขลูกค้า) */
    public function shippingMarks()
    {
        return $this->hasMany(CustomerShippingMark::class)->orderByDesc('is_default')->orderBy('id');
    }

    /** ชุดหมายเหตุ (Remark) ของลูกค้า (หลายชุด, เปิด/ปิดได้, จัดการในหน้าแก้ไขลูกค้า) */
    public function remarks()
    {
        return $this->hasMany(CustomerRemark::class)->orderBy('seq')->orderBy('id');
    }
}
