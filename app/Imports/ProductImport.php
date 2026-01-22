<?php
namespace App\Imports;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\SubCategory;
use App\Models\ProductGroup;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // 1. ข้ามแถวที่ไม่มีรหัสสินค้า (ProdID)
        if (empty($row['prodid'])) {
            return null;
        }

        // 2. จัดการ Category: เช็คจาก cateid (เช่น AirPart)
        // ถ้าไม่มีให้สร้างใหม่พร้อมเก็บชื่อและโค้ด
        $category = null;
        if (!empty($row['cateid'])) {
            $category = ProductCategory::updateOrCreate(
                ['code' => trim($row['cateid'])], // ใช้ CateID เป็น Key ในการเช็ค
                [
                    'name_th' => $row['catenamethai'] ?? $row['cateid'],
                    'name_en' => $row['catenameeng'] ?? $row['cateid']
                ]
            );
        }

        // 3. จัดการ SubCategory: เช็คจาก subcateid (เช่น SIROCCO)
        $sub_category = null;
        if (!empty($row['subcateid'])) {
            $sub_category = SubCategory::updateOrCreate(
                ['code' => trim($row['subcateid'])], // ใช้ SubCateID เป็น Key
                [
                    'category_id' => $category->id ?? null,
                    'name_th' => $row['subcatenamethai'] ?? $row['subcateid'],
                    'name_en' => $row['subcatenameeng'] ?? $row['subcateid']
                ]
            );
        }

        // 4. จัดการ Group: เช็คจาก prodgroupid (เช่น FAN001)
        $group = null;
        if (!empty($row['prodgroupid'])) {
            $group = ProductGroup::updateOrCreate(
                ['code' => trim($row['prodgroupid'])], // ใช้ ProdGroupID เป็น Key
                [
                    'name_en' => $row['prodgroupnameeng'] ?? $row['prodgroupid'],
                    'name_th' => $row['prodgroupnamethai'] ?? $row['prodgroupnameeng']
                ]
            );
        }

        // 5. บันทึกข้อมูลสินค้าหลัก
        return Product::updateOrCreate(
            ['code' => trim($row['prodid'])],
            [
                'category_id'     => $category->id ?? null,
                'sub_category_id' => $sub_category->id ?? null,
                'group_id'        => $group->id ?? null,
                'name_th'         => $row['prodnamethai'] ?? null,
                'name_en'         => $row['prodnameeng1'] ?? null,
                'width'           => (float)($row['prodwide'] ?? 0),
                'length'          => (float)($row['prodlong'] ?? 0),
                'height'          => (float)($row['prodhigh'] ?? 0),
                'weight'          => (float)($row['prodweight'] ?? 0),
                'active'          => 'T',
            ]
        );
    }
}
