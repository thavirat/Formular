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
    protected $signature = 'masters:import {what=all : factories|content|dimensions|brands|units|cost|coilcost|all}';
    protected $description = 'Import factory / content / dimensions / brand / unit / cost / coilcost master data via CLI (no web timeout)';

    public function handle()
    {
        DB::disableQueryLog();
        @ini_set('memory_limit', '2048M');
        @set_time_limit(0);

        $what = strtolower((string) $this->argument('what'));
        $valid = ['factories', 'content', 'dimensions', 'brands', 'units', 'cost', 'coilcost', 'all'];
        if (!in_array($what, $valid, true)) {
            $this->error("ค่าไม่ถูกต้อง: {$what} (ใช้ได้: " . implode(', ', $valid) . ')');
            return 1;
        }

        try {
            if ($what === 'all' || $what === 'factories') {
                $this->importFactories();
            }
            if ($what === 'all' || $what === 'brands') {
                $this->importBrands();
            }
            if ($what === 'all' || $what === 'units') {
                $this->importUnits();
            }
            if ($what === 'all' || $what === 'dimensions') {
                $this->importDimensions();
            }
            if ($what === 'all' || $what === 'content') {
                $this->importContent();
            }
            if ($what === 'all' || $what === 'cost') {
                $this->importCost();
            }
            if ($what === 'coilcost') { // ไม่รวมใน all (เป็นชุด COIL เฉพาะกลุ่ม)
                $this->importCoilCost();
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

    /** โหลด code ของสินค้าทั้งหมดมาเป็น set ไว้เช็คการ match (เลี่ยงนับ affected=0 ของแถวที่ค่าไม่เปลี่ยนว่าเป็น not found) */
    private function productCodeSet(): array
    {
        $set = [];
        foreach (DB::table('products')->pluck('code') as $c) {
            if ($c !== null) {
                $set[trim((string) $c)] = true;
            }
        }
        return $set;
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

        $codes = $this->productCodeSet();
        $prodRows = $this->rows($prodPath);
        $matched = $notFound = $noType = 0;
        foreach ($prodRows as $i => $r) {
            if ($i === 0) continue;
            $code = isset($r[0]) ? trim((string) $r[0]) : '';
            if ($code === '' || strtoupper($code) === 'NULL') continue;
            $typeId = $r[5] ?? null;
            if ($typeId === null || $typeId === '' || strtoupper((string) $typeId) === 'NULL') { $noType++; continue; }
            $facId = $factoryIdByType[(string) $typeId] ?? null;
            if ($facId === null) continue;
            if (!isset($codes[$code])) { $notFound++; continue; }
            DB::table('products')->where('code', $code)->update(['factory_id' => $facId]);
            $matched++;
        }
        DB::commit();
        $this->info("  factories: {$facCount} | จับคู่สินค้าได้: {$matched} | ไม่พบ code: {$notFound} | ไม่มี GoodTypeID: {$noType}");
    }

    /** ----- Brand : Brand_Master + Prod_Master.GoodBrandID(col7) -> brand_products / products.brand_id ----- */
    private function importBrands(): void
    {
        $brandPath = $this->findFile(['Brand_Master.xlsx']);
        $prodPath  = $this->findFile(['Prod_Master.xlsx']);
        if (!$brandPath || !$prodPath) {
            $this->warn('ข้าม brands: ไม่พบ Brand_Master.xlsx หรือ Prod_Master.xlsx');
            return;
        }
        $this->info('นำเข้า Brand...');

        // Brand_Master: 0=GoodBrandID, 1=GoodBrandCode, 2=GoodBrandName, 3=GoodBrandNameEng
        $brandRows = $this->rows($brandPath);
        DB::beginTransaction();
        $brandIdByGid = [];
        $cnt = 0;
        foreach ($brandRows as $i => $r) {
            if ($i === 0) continue;
            $gid = $r[0] ?? null;
            if ($gid === null || $gid === '') continue;
            $name = trim((string) ($r[2] ?? '')) ?: trim((string) ($r[1] ?? '')); // ชื่อ ไม่งั้นใช้ code
            if ($name === '' || strtoupper($name) === 'NULL') continue;

            $existing = DB::table('brand_products')->where('name', $name)->first();
            if ($existing) {
                $bid = $existing->id;
            } else {
                $bid = DB::table('brand_products')->insertGetId(['name' => $name, 'created_at' => now(), 'updated_at' => now()]);
            }
            $brandIdByGid[(string) $gid] = $bid;
            $cnt++;
        }

        $codes = $this->productCodeSet();
        $prodRows = $this->rows($prodPath);
        $codesByBrand = []; $notFound = $noBrand = 0;
        foreach ($prodRows as $i => $r) {
            if ($i === 0) continue;
            $code = isset($r[0]) ? trim((string) $r[0]) : '';
            if ($code === '' || strtoupper($code) === 'NULL') continue;
            $gid = $r[7] ?? null; // GoodBrandID
            if ($gid === null || $gid === '' || strtoupper((string) $gid) === 'NULL') { $noBrand++; continue; }
            $bid = $brandIdByGid[(string) $gid] ?? null;
            if ($bid === null) continue;
            if (!isset($codes[$code])) { $notFound++; continue; }
            $codesByBrand[$bid][] = $code;
        }
        // อัปเดตแบบ batch (whereIn) ต่อ brand -> ลดจำนวน query มหาศาล
        $matched = $this->bulkUpdate('brand_id', $codesByBrand);
        DB::commit();
        $this->info("  brands: {$cnt} | จับคู่สินค้าได้: {$matched} | ไม่พบ code: {$notFound} | ไม่มี GoodBrandID: {$noBrand}");
    }

    /** ----- Unit : Unit_Master + Prod_Master.MainGoodUnitID(col16) -> unit_products / products.unit_id ----- */
    private function importUnits(): void
    {
        $unitPath = $this->findFile(['Unit_Master.xlsx']);
        $prodPath = $this->findFile(['Prod_Master.xlsx']);
        if (!$unitPath || !$prodPath) {
            $this->warn('ข้าม units: ไม่พบ Unit_Master.xlsx หรือ Prod_Master.xlsx');
            return;
        }
        $this->info('นำเข้า Unit...');

        // Unit_Master: 0=GoodUnitID, 1=GoodUnitCode, 2=GoodUnitName
        $unitRows = $this->rows($unitPath);
        DB::beginTransaction();
        $unitIdByGid = [];
        $cnt = 0;
        foreach ($unitRows as $i => $r) {
            if ($i === 0) continue;
            $gid = $r[0] ?? null;
            if ($gid === null || $gid === '') continue;
            $name = trim((string) ($r[2] ?? '')) ?: trim((string) ($r[1] ?? ''));
            if ($name === '' || strtoupper($name) === 'NULL') continue;

            $existing = DB::table('unit_products')->where('name', $name)->first();
            if ($existing) {
                $uid = $existing->id;
            } else {
                $uid = DB::table('unit_products')->insertGetId(['name' => $name, 'created_at' => now(), 'updated_at' => now()]);
            }
            $unitIdByGid[(string) $gid] = $uid;
            $cnt++;
        }

        $codes = $this->productCodeSet();
        $prodRows = $this->rows($prodPath);
        $codesByUnit = []; $notFound = $noUnit = 0;
        foreach ($prodRows as $i => $r) {
            if ($i === 0) continue;
            $code = isset($r[0]) ? trim((string) $r[0]) : '';
            if ($code === '' || strtoupper($code) === 'NULL') continue;
            $gid = $r[16] ?? null; // MainGoodUnitID
            if ($gid === null || $gid === '' || strtoupper((string) $gid) === 'NULL') { $noUnit++; continue; }
            $uid = $unitIdByGid[(string) $gid] ?? null;
            if ($uid === null) continue;
            if (!isset($codes[$code])) { $notFound++; continue; }
            $codesByUnit[$uid][] = $code;
        }
        $matched = $this->bulkUpdate('unit_id', $codesByUnit);
        DB::commit();
        $this->info("  units: {$cnt} | จับคู่สินค้าได้: {$matched} | ไม่พบ code: {$notFound} | ไม่มี MainGoodUnitID: {$noUnit}");
    }

    /** อัปเดต products.<column> แบบ batch ตามค่า id (whereIn เป็นก้อนละ 1000) คืนจำนวน code ที่จับคู่ */
    private function bulkUpdate(string $column, array $codesById): int
    {
        $total = 0;
        foreach ($codesById as $id => $codeList) {
            foreach (array_chunk($codeList, 1000) as $chunk) {
                DB::table('products')->whereIn('code', $chunk)->update([$column => $id]);
            }
            $total += count($codeList);
        }
        return $total;
    }

    /** อัปเดต products.<column> ด้วยค่าต่อ code (ค่าต่างกันทุกแถว) แบบ batch ด้วย CASE WHEN เป็นก้อนละ 500 */
    private function bulkUpdateValues(string $column, array $valueByCode): int
    {
        $prefix = DB::getTablePrefix();
        $total = 0;
        foreach (array_chunk($valueByCode, 500, true) as $chunk) {
            $cases = '';
            $bindings = [];
            foreach ($chunk as $code => $val) {
                $cases .= 'WHEN ? THEN ? ';
                $bindings[] = (string) $code;
                $bindings[] = $val;
            }
            $codes = array_keys($chunk);
            $in = implode(',', array_fill(0, count($codes), '?'));
            foreach ($codes as $c) {
                $bindings[] = (string) $c;
            }
            DB::update("UPDATE {$prefix}products SET {$column} = CASE code {$cases} ELSE {$column} END WHERE code IN ({$in})", $bindings);
            $total += count($chunk);
        }
        return $total;
    }

    /** ----- ราคาทุน : product_cost.xlsx (ชีทแรก: DWG, Part No., cost) -> products.cost ----- */
    private function importCost(): void
    {
        $path = $this->findFile(['product_cost.xlsx', 'product_cost.xls']);
        if (!$path) {
            $this->warn('ข้าม cost: ไม่พบ product_cost.xlsx (วางที่ database/imports/masters/ หรือ storage/app/imports/)');
            return;
        }
        $this->info('นำเข้า ราคาทุน (cost)...');

        // อ่านชีทแรกโดยตรง (เลี่ยงปัญหา active sheet ในไฟล์ที่มีหลายชีท)
        $reader = IOFactory::createReaderForFile($path);
        $reader->setReadDataOnly(true);
        $rows = $reader->load($path)->getSheet(0)->toArray(null, true, false, false);

        $codesSet = $this->productCodeSet();
        $costByCode = []; $notFound = 0;
        foreach ($rows as $i => $r) {
            if ($i === 0) continue; // header: DWG, Part No., cost
            $code = isset($r[1]) ? trim((string) $r[1]) : ''; // Part No. (คอลัมน์ B)
            if ($code === '' || strtoupper($code) === 'NULL') continue;
            $raw = $r[2] ?? null; // cost (คอลัมน์ C)
            if (is_string($raw)) {
                $raw = str_replace(',', '', trim($raw));
            }
            if (!is_numeric($raw)) continue;
            if (!isset($codesSet[$code])) { $notFound++; continue; }
            $costByCode[$code] = (float) $raw; // แถวหลังทับแถวก่อน (กรณี code ซ้ำ ใช้ค่าล่าสุด)
        }

        DB::beginTransaction();
        $matched = $this->bulkUpdateValues('cost', $costByCode);
        DB::commit();
        $this->info("  cost: อัปเดต {$matched} รายการ | ไม่พบ code ในระบบ {$notFound}");
    }

    /** ----- ราคาทุน COIL : coil_price.xlsx (หลายชีท, detect คอลัมน์ COST จากหัว) -> products.cost ----- */
    private function importCoilCost(): void
    {
        $path = $this->findFile(['coil_price.xlsx', 'coil_price.xls']);
        if (!$path) {
            $this->warn('ข้าม coilcost: ไม่พบ coil_price.xlsx');
            return;
        }
        $this->info('นำเข้า ราคาทุน COIL (ทุกชีท)...');

        $reader = IOFactory::createReaderForFile($path);
        $reader->setReadDataOnly(true);
        $book = $reader->load($path);

        $codesSet = $this->productCodeSet();
        $costByCode = []; $notFound = 0;

        foreach ($book->getWorksheetIterator() as $sheet) {
            $rows = $sheet->toArray(null, true, false, false);
            if (empty($rows)) continue;
            $header = $rows[0];

            // หาคอลัมน์ต้นทุน: หัวคอลัมน์ = "COST" หรือขึ้นต้น "ปรับ" / "ต้นทุน"
            $costIdx = null;
            foreach ($header as $ci => $h) {
                $h = trim((string) $h);
                if (strtoupper($h) === 'COST' || mb_strpos($h, 'ปรับ') === 0 || mb_strpos($h, 'ต้นทุน') === 0) {
                    $costIdx = $ci;
                    break;
                }
            }
            if ($costIdx === null) continue; // ชีทนี้ไม่มีคอลัมน์ต้นทุน -> ข้าม

            foreach ($rows as $i => $r) {
                if ($i === 0) continue;
                $code = isset($r[1]) ? trim((string) $r[1]) : ''; // Part No คอลัมน์ B ทุกชีท
                if ($code === '' || strtoupper($code) === 'NULL') continue;
                $raw = $r[$costIdx] ?? null;
                if (is_string($raw)) $raw = str_replace(',', '', trim($raw));
                if (!is_numeric($raw) || (float) $raw <= 0) continue; // ข้าม #REF!, "-", ว่าง, 0
                if (!isset($codesSet[$code])) { $notFound++; continue; }
                $costByCode[$code] = (float) $raw; // ชีท/แถวหลังทับก่อน
            }
        }

        DB::beginTransaction();
        $matched = $this->bulkUpdateValues('cost', $costByCode);
        DB::commit();
        $this->info("  coil cost: อัปเดต {$matched} รายการ | ไม่พบ code ในระบบ {$notFound}");
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

        $codes = $this->productCodeSet();
        DB::beginTransaction();
        $matched = $skipped = $notFound = 0;
        foreach ($rows as $i => $r) {
            if ($i === 0) continue;
            $code = isset($r[9]) ? trim((string) $r[9]) : '';
            if ($code === '' || strtoupper($code) === 'NULL') continue;
            $w = is_numeric($r[14] ?? null) ? (float) $r[14] : null;
            $l = is_numeric($r[15] ?? null) ? (float) $r[15] : null;
            $h = is_numeric($r[16] ?? null) ? (float) $r[16] : null;
            $wt = is_numeric($r[17] ?? null) ? (float) $r[17] : 0;
            if ($w === null || $l === null || $h === null || $w <= 0 || $l <= 0 || $h <= 0) { $skipped++; continue; }
            if (!isset($codes[$code])) { $notFound++; continue; }
            $cube = round($w * $l * $h / 1000000000, 6);
            DB::table('products')->where('code', $code)->update([
                'width' => $w, 'length' => $l, 'height' => $h, 'weight' => $wt, 'cube' => $cube,
            ]);
            $matched++;
        }
        DB::commit();
        $this->info("  จับคู่+อัปเดต: {$matched} | ข้าม(ไม่มีมิติ): {$skipped} | ไม่พบ code: {$notFound}");
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

        $codes = $this->productCodeSet();
        DB::beginTransaction();
        $matched = $notFound = 0;
        foreach ($byCode as $code => $content) {
            if (!isset($codes[$code])) { $notFound++; continue; }
            DB::table('products')->where('code', $code)->update(['content' => $content]);
            $matched++;
        }
        DB::commit();
        $this->info("  จับคู่+อัปเดต content: {$matched} | ไม่พบ code: {$notFound} | code มีค่า T: " . count($byCode));
    }
}
