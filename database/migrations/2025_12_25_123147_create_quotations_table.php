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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('status_id')->nullable()->constrained('quotation_statuses')->onDelete('set null');
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');
            $table->string('doc_no')->nullable();
            $table->date('doc_date')->nullable();
            $table->integer('run_no')->unsigned()->nullable();
            $table->string('contact_name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('tax_id')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('fax_no')->nullable();
            $table->decimal('subtotal', 12, 4)->nullable()->default(0);
            $table->decimal('total', 12, 4)->nullable()->default(0);
            $table->foreignId('created_by')->nullable()->constrained('admin_users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
