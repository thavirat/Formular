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
        Schema::table('quotations', function (Blueprint $table) {
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
        Schema::table('quotations', function (Blueprint $table) {
            //
        });
    }
};
