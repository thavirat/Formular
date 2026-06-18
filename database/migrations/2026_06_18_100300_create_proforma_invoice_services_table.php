<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('proforma_invoice_services')) {
            Schema::create('proforma_invoice_services', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pi_id')->nullable()->constrained('proforma_invoices')->onDelete('cascade');
                $table->integer('seq')->nullable()->default(0);   // ลำดับ
                $table->string('name')->nullable();               // ชื่อค่าบริการ
                $table->decimal('amount', 12, 4)->nullable()->default(0); // จำนวนเงิน
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('proforma_invoice_services');
    }
};
