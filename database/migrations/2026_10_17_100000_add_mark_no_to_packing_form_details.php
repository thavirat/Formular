<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Mark & No. แก้ไขเองต่อแถว (เว้นว่าง = ใช้เลข FA อัตโนมัติ) — ใช้จัดกลุ่มในเอกสาร
     */
    public function up(): void
    {
        if (Schema::hasTable('packing_form_details') && !Schema::hasColumn('packing_form_details', 'mark_no')) {
            Schema::table('packing_form_details', function (Blueprint $table) {
                $table->string('mark_no')->nullable()->after('from_co');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('packing_form_details') && Schema::hasColumn('packing_form_details', 'mark_no')) {
            Schema::table('packing_form_details', function (Blueprint $table) {
                $table->dropColumn('mark_no');
            });
        }
    }
};
