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
        Schema::create('customer_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pi_id')->nullable()->constrained('proforma_invoices')->onDelete('set null');
            $table->foreignId('bank_account_id')->nullable()->constrained('bank_accounts')->onDelete('set null');
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->onDelete('set null');
            $table->foreignId('currency_id')->nullable()->constrained('currencies')->onDelete('set null');
            $table->string('reference_no')->nullable();
            $table->dateTime('payment_date')->nullable();
            $table->string('payment_note')->nullable();
            $table->decimal('amount', 12, 4)->nullable()->default(0);
            $table->decimal('amount_bath', 12, 4)->nullable()->default(0);
            $table->decimal('exchange_rate', 12, 4)->nullable()->default(0);
            $table->foreignId('created_by')->nullable()->constrained('admin_users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_payments');
    }
};
