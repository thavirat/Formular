<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * เพิ่มช่อง shipping_mark ประจำลูกค้า (จดจำ Shipping Marks ตามลูกค้า)
 * - แก้ค่าได้ที่หน้าลูกค้า
 * - หน้า PI มีปุ่มโหลดค่านี้มาเติมช่อง ship_remark
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'shipping_mark')) {
                $table->text('shipping_mark')->nullable()->after('remark');
            }
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            if (Schema::hasColumn('customers', 'shipping_mark')) {
                $table->dropColumn('shipping_mark');
            }
        });
    }
};
