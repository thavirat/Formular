<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('price_setting_levels', function (Blueprint $table) {
            if (!Schema::hasColumn('price_setting_levels', 'is_qty_base')) {
                // ระดับที่ใช้เป็นฐานคำนวณราคาตามจำนวน (เจ้าใหญ่/BIG) -> qty tier ใช้ตัวคูณของระดับนี้
                $table->boolean('is_qty_base')->default(false)->after('multiplier');
            }
        });
    }

    public function down(): void
    {
        Schema::table('price_setting_levels', function (Blueprint $table) {
            if (Schema::hasColumn('price_setting_levels', 'is_qty_base')) {
                $table->dropColumn('is_qty_base');
            }
        });
    }
};
