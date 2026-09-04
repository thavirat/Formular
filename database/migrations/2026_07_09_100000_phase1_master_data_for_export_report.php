<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * เฟส 1 (รายงานผู้บริหาร Export Order): เตรียม master data
 * - customers: เพิ่ม country + region (สำหรับกรุ๊ปภูมิภาค ASIA/Middleast/Europe)
 * - currencies: เพิ่ม THB, CNY
 * - incoterms: เพิ่ม C&F
 * - shipment_methods: เพิ่ม LCL, FCL, TRUCK
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('customers')) {
            Schema::table('customers', function (Blueprint $table) {
                if (!Schema::hasColumn('customers', 'country')) {
                    $table->string('country', 191)->nullable()->after('address');
                }
                if (!Schema::hasColumn('customers', 'region')) {
                    $table->string('region', 100)->nullable()->after('country');
                }
            });
        }

        // สกุลเงิน
        foreach ([
            ['name' => 'บาท (THB)', 'symbol' => 'THB'],
            ['name' => 'หยวน (CNY)', 'symbol' => 'CNY'],
        ] as $cur) {
            if (!DB::table('currencies')->where('symbol', $cur['symbol'])->exists()) {
                DB::table('currencies')->insert($cur + ['created_at' => now(), 'updated_at' => now()]);
            }
        }

        // incoterm
        if (!DB::table('incoterms')->where('code', 'C&F')->exists()) {
            DB::table('incoterms')->insert(['code' => 'C&F', 'created_at' => now(), 'updated_at' => now()]);
        }

        // shipment methods (ชนิดขนส่ง)
        $seq = (int) DB::table('shipment_methods')->max('seq');
        foreach (['LCL', 'FCL', 'TRUCK'] as $name) {
            if (!DB::table('shipment_methods')->where('name', $name)->exists()) {
                DB::table('shipment_methods')->insert([
                    'name' => $name, 'seq' => ++$seq, 'active' => 'T',
                    'created_at' => now(), 'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('customers')) {
            Schema::table('customers', function (Blueprint $table) {
                foreach (['country', 'region'] as $col) {
                    if (Schema::hasColumn('customers', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
        DB::table('currencies')->whereIn('symbol', ['THB', 'CNY'])->delete();
        DB::table('incoterms')->where('code', 'C&F')->delete();
        DB::table('shipment_methods')->whereIn('name', ['LCL', 'FCL', 'TRUCK'])->delete();
    }
};
