<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * เพิ่มช่องราคาต่อหน่วยในรายการ packing (แก้ได้จากหน้า Invoice)
 * ถ้าว่าง ระบบจะ fallback ไปใช้ราคาจาก PI ที่ผูกไว้
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packing_form_details', function (Blueprint $table) {
            if (!Schema::hasColumn('packing_form_details', 'unit_price')) {
                $table->decimal('unit_price', 12, 2)->nullable()->after('qty');
            }
        });
    }

    public function down(): void
    {
        Schema::table('packing_form_details', function (Blueprint $table) {
            if (Schema::hasColumn('packing_form_details', 'unit_price')) {
                $table->dropColumn('unit_price');
            }
        });
    }
};
