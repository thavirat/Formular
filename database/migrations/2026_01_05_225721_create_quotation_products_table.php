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
        Schema::create('quotation_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->nullable()->constrained('quotations')->onDelete('set null');
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null');
            $table->string('part_no')->nullable();
            $table->string('drawing')->nullable();
            $table->string('cus_code')->nullable();
            $table->longText('detail_thai')->nullable();
            $table->longText('detail_eng')->nullable();
            $table->decimal('qty', 10, 2)->nullable()->default(0);
            $table->decimal('price_per_item', 10, 2)->nullable()->default(0);
            $table->decimal('total_price', 10, 2)->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_products');
    }
};
