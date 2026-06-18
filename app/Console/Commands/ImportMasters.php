<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * รัน import ข้อมูล master ผ่าน CLI (เลี่ยง timeout ของ Cloudflare/web 524)
 *   php artisan masters:import            # รันทั้งหมด
 *   php artisan masters:import factories  # เฉพาะ Fac No.
 *   php artisan masters:import content    # เฉพาะ CONTENT (จำนวนต่อลัง)
 *   php artisan masters:import dimensions # เฉพาะ กว้าง/ยาว/สูง/cube
 * ไฟล์อ่านจาก database/imports/masters/ ก่อน แล้ว fallback ไป storage/app/imports/
 */
class ImportMasters extends Command
{
    protected $signature = 'masters:import {what=all : factories|content|dimensions|all}';
    protected $description = 'Import factory / content / dimensions master data via CLI (no web timeout)';

    public function handle()
    {
        DB::disableQueryLog();
        @ini_set('memory_limit', '2048M');
        @set_time_limit(0);

        $what = strtolower((string) $this->argument('what'));
        $valid = ['factories', 'content', 'dimensions', 'all'];
        if (!in_array($what, $valid, true)) {
            $this->error("ค่าไม่ถูกต้อง: {$what} (ใช้ได้: " . implode(', ', $valid) . ')');
            return 1;
        }

        try {
            if ($what === 'all' || $what === 'factories') {
                $this->importFactories();
            }
            if ($what === 'all' || $what === 'dimensions') {
                $this->importDimensions();
            }
            if ($what === 'all' || $what === 'content') {
                $this->importContent();
            }
            $this->info('=== เสร็จสมบูรณ์ ===');
            return 0;
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error('ผิดพลาด: ' . $e->getMessage());
            $this->line('  ' . $e->getFile() . ':' . $e->getLine());
            return 1;
        }
    }

    /** หาไฟล์จากในโปรเจ็คก่อน แล้ว fallback ไป storage */
    private function findFile(array $names): ?string
    {
        foreach ($names as $n) {
            foreach ([database_path('imports/masters/' . $n), storage_path('app/imports/' . $n)] as $p) {
                if (is_file($p)) {
                    return $p;
                }
            }
        }
        return null;
    }

    private function rows(string $path): array
    {
        $reader = IOFactory::createReaderForFile($path);
        $reader->setReadDataOnly(true);
        return $reader->load($path)->getActiveSheet()->toArray(null, true, false, false);
    }

    private function columnExists(string $table, string $column): bool
    {
        $r = DB::select(
            'SELECT 1 AS x FROM information_schema.columns
             WHERE table_schema = ? AND table_name = ? AND column_name = ? LIMIT 1',
            [DB::getDatabaseName(), $table, $column]
        );
        return !empty($r);
    }

    /** ----- Fac No. : Type_Master + Prod_Master ----- */
    private function importFactories(): void
    {
        $typePath = $this->findFile(['Type_Master.xlsx']);
        $prodPath = $this->findFile(['Prod_Master.xlsx']);
        if (!$typePath || !$prodPath) {
            $this->warn('ข้าม factories: ไม่พบ Type_Master.xlsx หรือ Prod_Master.xlsx');
            return;
        }
        $this->info('นำเข้า Fac No. (factories)...');

        $typeRows = $this->rows($typePath);
        DB::beginTransaction();
        $factoryIdByType = [];
        $facCount = 0;
        foreach ($typeRows as $i => $r) {
            if ($i === 0) continue;
            $typeId = $r[0] ?? null;
            $facCode = isset($r[1]) ? trim((string) $r[1]) : '';
            if ($typeId === null || $typeId === '' || $facCode === '') continue;
            $nameEng = isset($r[3]) && trim((string) $r[3]) !== '' && strtoupper((string) $r[3]) !== 'NULL' ? trim((string) $r[3]) : null;
            $nameTh  = isset($r[2]) && trim((string) $r[2]) !== '' && strtoupper((string) $r[2]) !== 'NULL' ? trim((string) $r[2]) : null;
            $facName = $nameEng ?: $nameTh;

            $existing = DB::table('factories')->where('code', $facCode)->first();
            if ($existing) {
                DB::table('factories')->where('id', $existing->id)->update(['name' => $facName, 'updated_at' => now()]);
                $facId = $existing->id;
            } else {
                $facId = DB::table('factories')->insertGetId(['code' => $facCode, 'name' => $facName, 'created_at' => now(), 'updated_at' => now()]);
            }
            $factoryIdByType[(string) $typeId] = $facId;
            $facCount++;
        }

        $prodRows = $this->rows($prodPath);
        $updated = $notFound = $noType = 0;
        foreach ($prodRows as $i => $r) {
            if ($i === 0) continue;
            $code = isset($r[0]) ? trim((string) $r[0]) : '';
            if ($code === '' || strtoupper($code) === 'NULL') continue;
            $typeId = $r[5] ?? null;
            if ($typeId === null || $typeId === '' || strtoupper((string) $typeId) === 'NULL') { $noType++; continue; }
            $facId = $factoryIdByType[(string) $typeId] ?? null;
            if ($facId === null) continue;
            $aff = DB::table('products')->where('code', $code)->update(['factory_id' => $facId]);
            $aff > 0 ? $updated++ : $notFound++;
        }
        DB::commit();
        $this->info("  factories: {$facCount} | factory_id ที่ตั้ง: {$updated} | ไม่พบ code: {$notFound} | ไม่มี GoodTypeID: {$noType}");
    }

    /** ----- dimensions : product_dimensions.xlsx ----- */
    private function importDimensions(): void
    {
        $path = $this->findFile(['product_dimensions.xlsx', 'product_dimensions.xls']);
        if (!$path) {
            $this->warn('ข้าม dimensions: ไม่พบ product_dimensions.xlsx');
            return;
        }
        $this->info('นำเข้า กว้าง/ยาว/สูง/cube (dimensions)...');

        // ขยายคอลัมน์ cube ให้เก็บค่าเล็ก ๆ ได้
        $scale = DB::select(
            'SELECT NUMERIC_SCALE AS s FROM information_schema.columns
             WHERE table_schema = ? AND table_name = ? AND column_name = ? LIMIT 1',
            [DB::getDatabaseName(), 'tb_products', 'cube']
        );
        if ((int) ($scale[0]->s ?? 0) < 6) {
            DB::statement('ALTER TABLE tb_products MODIFY cube decimal(12,6) NULL DEFAULT 0.000000');
        }

        $reader = IOFactory::createReaderForFile($path);
        $reader->setReadDataOnly(true);
        $ss = $reader->load($path);
        $sheet = $ss->getSheetByName('All in') ?: $ss->getActiveSheet();
        $rows = $sheet->toArray(null, true, false, false);

        DB::beginTransaction();
        $updated = $skipped = $notFound = 0;
        foreach ($rows as $i => $r) {
            if ($i === 0) continue;
            $code = isset($r[9]) ? trim((string) $r[9]) : '';
            if ($code === '' || strtoupper($code) === 'NULL') continue;
            $w = is_numeric($r[14] ?? null) ? (float) $r[14] : null;
            $l = is_numeric($r[15] ?? null) ? (float) $r[15] : null;
            $h = is_numeric($r[16] ?? null) ? (float) $r[16] : null;
            $wt = is_numeric($r[17] ?? null) ? (float) $r[17] : 0;
            if ($w === null || $l === null || $h === null || $w <= 0 || $l <= 0 || $h <= 0) { $skipped++; continue; }
            $cube = round($w * $l * $h / 1000000000, 6);
            $aff = DB::table('products')->where('code', $code)->update([
                'width' => $w, 'length' => $l, 'height' => $h, 'weight' => $wt, 'cube' => $cube,
            ]);
            $aff > 0 ? $updated++ : $notFound++;
        }
        DB::commit();
        $this->info("  อัปเดต: {$updated} | ข้าม(ไม่มีมิติ): {$skipped} | ไม่พบ code: {$notFound}");
    }

    /** ----- content : product_content.xls (คอลัมน์ T) ----- */
    private function importContent(): void
    {
        $path = $this->findFile(['product_content.xls', 'product_content.xlsx']);
        if (!$path) {
            $this->warn('ข้าม content: ไม่พบ product_content.xls');
            return;
        }
        $this->info('นำเข้า CONTENT (จำนวนต่อลัง)...');

        if (!$this->columnExists('tb_products', 'content')) {
            DB::statement('ALTER TABLE tb_products ADD COLUMN content decimal(10,2) NULL DEFAULT 0 AFTER cube');
        }

        $rows = $this->rows($path);
        $byCode = [];
        foreach ($rows as $i => $r) {
            if ($i < 2) continue; // row0 = เลขคอลัมน์, row1 = หัวตาราง
            $code = isset($r[0]) ? trim((string) $r[0]) : '';
            if ($code === '' || strtoupper($code) === 'NULL') continue;
            $raw = $r[19] ?? null;
            if (is_string($raw)) $raw = str_replace(',', '', trim($raw));
            if (!is_numeric($raw)) continue;
            $t = (float) $raw;
            if ($t <= 0) continue;
            if (!isset($byCode[$code]) || $t > $byCode[$code]) $byCode[$code] = $t;
        }

        DB::beginTransaction();
        $updated = $notFound = 0;
        foreach ($byCode as $code => $content) {
            $aff = DB::table('products')->where('code', $code)->update(['content' => $content]);
            $aff > 0 ? $updated++ : $notFound++;
        }
        DB::commit();
        $this->info("  อัปเดต content: {$updated} | ไม่พบ code: {$notFound} | code มีค่า T: " . count($byCode));
    }
}
