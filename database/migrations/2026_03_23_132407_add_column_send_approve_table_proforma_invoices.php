<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('proforma_invoices', function (Blueprint $table) {
            $table->dateTime('send_approve_date')->nullable();
            $table->foreignId('send_approve_by')->nullable()->constrained('admin_users')->onDelete('set null');
            $table->dateTime('approve_date')->nullable();
            $table->foreignId('approve_by')->nullable()->constrained('admin_users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proforma_invoices', function (Blueprint $table) {
            $table->dropForeign(['send_approve_by']);
            $table->dropColumn('send_approve_by');
            $table->dropForeign(['approve_by']);
            $table->dropColumn('approve_by');
            $table->dropColumn('send_approve_date');
            $table->dropColumn('approve_date');
        });
    }
};
