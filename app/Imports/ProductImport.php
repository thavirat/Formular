<?php
namespace App\Imports;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\SubCategory;
use App\Models\ProductGroup;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents; // เพิ่มตัวนี้
use Maatwebsite\Excel\Events\BeforeImport; // เพิ่มตัวนี้
use Maatwebsite\Excel\Events\AfterChunk;   // เพิ่มตัวนี้

class ProductImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading, WithEvents
{
    private $rows = 0; // ตัวนับจำนวนแถวที่ทำไปแล้ว

    public function model(array $row)
    {
        $this->rows++; // นับเพิ่มทุกครั้งที่เข้า Model

        if (empty($row['prodid'])) return null;

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
            'drawing'         => $row['proddrawing'],
            'active'          => 'T',
        ]);
    }

    public function batchSize(): int { return 100; }
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
