<?php
namespace App\Imports;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\SubCategory;
use App\Models\ProductGroup;
use Maatwebsite\Excel\Concerns\OnEachRow; // เปลี่ยนจาก ToModel เป็น OnEachRow
use Maatwebsite\Excel\Row; // เพิ่มตัวนี้
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Events\AfterChunk;

// ใช้ OnEachRow แทน ToModel เพื่อจัดการการบันทึกเองทีละแถว
class ProductImport implements OnEachRow, WithHeadingRow, WithChunkReading, WithEvents
{
    private $rows = 0;

    public function onRow(Row $row)
    {
        $this->rows++;
        $data = $row->toArray(); // แปลงข้อมูลแถวเป็น array

        if (empty($data['prodid'])) return null;

        // 1. จัดการ Category
        $category = ProductCategory::firstOrCreate(
            ['code' => trim($data['cateid'])],
            ['name_th' => $data['catenamethai'] ?? '', 'name_en' => $data['catenameeng'] ?? '']
        );

        // 2. จัดการ SubCategory
        $sub_category = SubCategory::firstOrCreate(
            ['code' => trim($data['subcateid'])],
            [
                'category_id' => $category->id,
                'name_th' => $data['subcatenamethai'] ?? '',
                'name_en' => $data['subcatenameeng'] ?? ''
            ]
        );

        // 3. จัดการ Product Group
        $group = ProductGroup::firstOrCreate(
            ['code' => trim($data['prodgroupid'])]
        );

        // 4. ใช้ updateOrCreate โดยตรง ไม่ต้อง return ให้ Library ไปบันทึกซ้ำ
        Product::updateOrCreate(
            [
                'code' => trim($data['prodid'])
            ],
            [
                'category_id'     => $category->id,
                'sub_category_id' => $sub_category->id,
                'group_id'        => $group->id,
                'name_th'         => $data['prodnamethai'] ?? '',
                'name_en'         => $data['prodnameeng1'] ?? '',
                'drawing'         => $data['proddrawing'] ?? '',
                'active'          => 'T',
            ]
        );
    }

    public function chunkSize(): int { return 100; }

    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function (BeforeImport $event) {
                dump("--- Starting Import Process ---");
            },
            AfterChunk::class => function (AfterChunk $event) {
                dump("Imported: {$this->rows} rows complete.");
            },
        ];
    }
}
