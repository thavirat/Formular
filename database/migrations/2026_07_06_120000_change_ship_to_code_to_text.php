<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ให้เก็บ shipping mark หลายบรรทัดได้
        DB::statement('ALTER TABLE ' . DB::getTablePrefix() . 'proforma_invoices MODIFY ship_to_code TEXT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE ' . DB::getTablePrefix() . 'proforma_invoices MODIFY ship_to_code VARCHAR(191) NULL');
    }
};
