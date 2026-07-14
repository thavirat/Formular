<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ให้ shipping mark (ship_remark) เก็บหลายบรรทัดได้
        DB::statement('ALTER TABLE ' . DB::getTablePrefix() . 'proforma_invoices MODIFY ship_remark TEXT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE ' . DB::getTablePrefix() . 'proforma_invoices MODIFY ship_remark VARCHAR(191) NULL');
    }
};
