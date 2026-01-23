<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Imports\ProductImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class ImportProducts extends Command
{
    // ชื่อคำสั่งที่ใช้รัน: php artisan import:products {path_to_file}
    protected $signature = 'import:products {path}';
    protected $description = 'Import products from CSV/Excel file via CLI';

    public function handle()
    {
        $path = $this->argument('path');

        if (!file_exists($path)) {
            $this->error("ไม่พบไฟล์ที่เส้นทาง: {$path}");
            return;
        }

        $this->info("กำลังเริ่มนำเข้าข้อมูลจาก: {$path} ...");

        // ปิดการเก็บ Log ของ Database เพื่อประหยัด RAM
        DB::disableQueryLog();
        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        try {
            // เรียกใช้ ProductImport ที่เราเขียนไว้ก่อนหน้า
            // แนะนำว่าใน ProductImport ควรใส่ WithChunkReading และ WithBatchInserts ด้วย
            Excel::import(new ProductImport, $path);

            $this->info("--- นำเข้าข้อมูลสำเร็จเรียบร้อยแล้ว ---");
        } catch (\Exception $e) {
            $this->error("เกิดข้อผิดพลาด: " . $e->getMessage());
            $this->info("Line: " . $e->getLine() . " File: " . $e->getFile());
        }
    }
}
