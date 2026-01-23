<?php
namespace App\Imports;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\SubCategory;
use App\Models\ProductGroup;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts; // เพิ่มตัวนี้
use Maatwebsite\Excel\Concerns\WithChunkReading; // เพิ่มตัวนี้

class ProductImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    public function model(array $row)
    {
        if (empty($row['prodid'])) return null;

        // ใช้ firstOrCreate แทนเพื่อความเร็ว (ถ้ามีอยู่แล้วจะไม่ Update เพื่อลด Query)
        $category = ProductCategory::firstOrCreate(
            ['code' => trim($row['cateid'])],
            ['name_th' => $row['catenamethai'], 'name_en' => $row['catenameeng']]
        );

        $sub_category = SubCategory::firstOrCreate(
            ['code' => trim($row['subcateid'])],
            [
                'category_id' => $category->id,
                'name_th' => $row['subcatenamethai'],
                'name_en' => $row['subcatenameeng']
            ]
        );

        $group = ProductGroup::firstOrCreate(
            ['code' => trim($row['prodgroupid'])],
            ['name_en' => $row['prodgroupnameeng'], 'name_th' => $row['prodgroupnamethai']]
        );

        return new Product([
            'code'            => trim($row['prodid']),
            'category_id'     => $category->id,
            'sub_category_id' => $sub_category->id,
            'group_id'        => $group->id,
            'name_th'         => $row['prodnamethai'],
            'name_en'         => $row['prodnameeng1'],
            'active'          => 'T',
            // ... ฟิลด์อื่นๆ ...
        ]);
    }

    // กำหนดให้บันทึกเข้า DB ทีละ 100 แถว
    public function batchSize(): int
    {
        return 100;
    }

    // กำหนดให้อ่านไฟล์จาก Memory ทีละ 100 แถว (แก้ปัญหา Time Out)
    public function chunkSize(): int
    {
        return 100;
    }
}
