<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proforma_invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('proforma_invoices', 'sale_by')) {
                // ผู้ขาย = ผู้สร้างใบเสนอราคา (admin_users.id) — แยกจาก created_by ที่เป็นผู้สร้าง PI
                $table->unsignedBigInteger('sale_by')->nullable()->after('created_by');
            }
        });
    }

    public function down(): void
    {
        Schema::table('proforma_invoices', function (Blueprint $table) {
            if (Schema::hasColumn('proforma_invoices', 'sale_by')) {
                $table->dropColumn('sale_by');
            }
        });
    }
};
