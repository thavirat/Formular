<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * เพิ่มช่อง marks (Marks & No.) แบบข้อความอิสระสำหรับใบ Invoice/Packing List
 * เช่น "DHANYA EX-477  2,147 CARTONS\nEX-478  804 CARTONS"
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packing_forms', function (Blueprint $table) {
            if (!Schema::hasColumn('packing_forms', 'marks')) {
                $table->text('marks')->nullable()->after('issued_by');
            }
        });
    }

    public function down(): void
    {
        Schema::table('packing_forms', function (Blueprint $table) {
            if (Schema::hasColumn('packing_forms', 'marks')) {
                $table->dropColumn('marks');
            }
        });
    }
};
