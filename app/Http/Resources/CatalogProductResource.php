<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Whitelist ของข้อมูลสินค้าที่ "ยอมให้" แสดงบนหน้าบ้าน catalog
 *
 * สำคัญ: tb_products มีคอลัมน์ภายใน เช่น `cost` (ทุน) และราคาขายภายใน
 * ห้าม return model ดิบ (->toArray()) ออกหน้าบ้าน เพราะจะติด field พวกนี้ไปด้วย
 * เพิ่ม field ใหม่ที่นี่เท่านั้น และอย่าเผลอใส่ cost / ข้อมูล margin
 */
class CatalogProductResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'        => $this->id,
            'code'      => $this->code,
            'part_no'   => $this->part_no,
            'name_th'   => $this->name_th,
            'name_en'   => $this->name_en,
            'drawing'   => $this->drawing,
            'unit'      => $this->unit,
            'dimension' => [
                'width'  => $this->width,
                'height' => $this->height,
                'length' => $this->length,
                'weight' => $this->weight,
            ],
            'category'  => $this->whenLoaded('category', fn () => [
                'id'      => $this->category->id,
                'name_th' => $this->category->name_th,
                'name_en' => $this->category->name_en,
            ]),
            // ไม่เปิดเผย: cost, price ภายใน, factory_id, ข้อมูล CRM อื่น ๆ
        ];
    }
}
