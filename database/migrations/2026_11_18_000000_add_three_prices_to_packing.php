<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * รองรับ 3 ราคาในใบ Invoice:
 *  - unit_price        : ราคาปกติ (มีอยู่แล้ว)
 *  - under_value_price : ราคา Under Value (คีย์เอง หรือคำนวณจาก % รวม)
 *  - freight_price     : ราคา/หน่วย ที่รวม freight แล้ว
 * และเก็บ % Under Value รวมไว้ที่หัวเอกสาร (ไว้ re-calc ตอนแก้)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packing_form_details', function (Blueprint $table) {
            if (!Schema::hasColumn('packing_form_details', 'under_value_price')) {
                $table->decimal('under_value_price', 12, 2)->nullable()->after('unit_price');
            }
            if (!Schema::hasColumn('packing_form_details', 'freight_price')) {
                $table->decimal('freight_price', 12, 2)->nullable()->after('under_value_price');
            }
        });

        Schema::table('packing_forms', function (Blueprint $table) {
            if (!Schema::hasColumn('packing_forms', 'under_value_percent')) {
                $table->decimal('under_value_percent', 6, 2)->nullable()->after('qty');
            }
        });
    }

    public function down(): void
    {
        Schema::table('packing_form_details', function (Blueprint $table) {
            foreach (['under_value_price', 'freight_price'] as $col) {
                if (Schema::hasColumn('packing_form_details', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('packing_forms', function (Blueprint $table) {
            if (Schema::hasColumn('packing_forms', 'under_value_percent')) {
                $table->dropColumn('under_value_percent');
            }
        });
    }
};
