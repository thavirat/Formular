<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1) เพิ่มระดับลูกค้า BIG / SMALL ให้ตรงกับไฟล์ COIL
        foreach (['BIG', 'SMALL'] as $name) {
            if (!DB::table('customer_levels')->where('name', $name)->exists()) {
                DB::table('customer_levels')->insert(['name' => $name, 'created_at' => now(), 'updated_at' => now()]);
            }
        }
        $bigId   = DB::table('customer_levels')->where('name', 'BIG')->value('id');
        $smallId = DB::table('customer_levels')->where('name', 'SMALL')->value('id');

        // 2) สร้างชุดราคาตัวอย่าง (ค่าคงที่จากไฟล์ COIL 2024-2026) ถ้ายังไม่มีชุดใดเลย
        if (DB::table('price_settings')->count() === 0) {
            $psId = DB::table('price_settings')->insertGetId([
                'start_date' => '2024-01-01', 'end_date' => '2026-12-31', 'active' => 'T',
                'created_at' => now(), 'updated_at' => now(),
            ]);

            $usd = DB::table('currencies')->where('symbol', 'USD')->value('id');
            $eur = DB::table('currencies')->where('symbol', 'EUR')->value('id');
            $rates = [];
            if ($usd) $rates[] = ['price_setting_id' => $psId, 'currency_id' => $usd, 'rate' => 30,    'created_at' => now(), 'updated_at' => now()];
            if ($eur) $rates[] = ['price_setting_id' => $psId, 'currency_id' => $eur, 'rate' => 30.25, 'created_at' => now(), 'updated_at' => now()];
            if ($rates) DB::table('price_setting_rates')->insert($rates);

            DB::table('price_setting_levels')->insert([
                ['price_setting_id' => $psId, 'level_id' => $bigId,   'multiplier' => 1.1, 'is_qty_base' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['price_setting_id' => $psId, 'level_id' => $smallId, 'multiplier' => 1.2, 'is_qty_base' => 0, 'created_at' => now(), 'updated_at' => now()],
            ]);

            DB::table('price_setting_qtys')->insert([
                ['price_setting_id' => $psId, 'min_qty' => 1,   'factor' => 100, 'created_at' => now(), 'updated_at' => now()],
                ['price_setting_id' => $psId, 'min_qty' => 100, 'factor' => 103, 'created_at' => now(), 'updated_at' => now()],
                ['price_setting_id' => $psId, 'min_qty' => 500, 'factor' => 105, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }

    public function down(): void
    {
        // ไม่ลบระดับลูกค้า/ชุดราคา (เป็นข้อมูลที่อาจถูกใช้งานต่อ)
    }
};
