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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('product_categories')->onDelete('set null');
            $table->foreignId('brand_id')->nullable()->constrained('brand_products')->onDelete('set null');
            $table->foreignId('design_id')->nullable()->constrained('design_products')->onDelete('set null');
            $table->foreignId('unit_id')->nullable()->constrained('unit_products')->onDelete('set null');
            $table->string('code')->nullable();
            $table->string('part_no')->nullable();
            $table->string('name_th')->nullable();
            $table->string('name_en')->nullable();
            $table->string('name_cn')->nullable();
            $table->string('drawing')->nullable();
            $table->string('unit')->nullable();
            $table->decimal('width', 10, 2)->nullable()->default(0);
            $table->decimal('height', 10, 2)->nullable()->default(0);
            $table->decimal('length', 10, 2)->nullable()->default(0);
            $table->decimal('weight', 10, 2)->nullable()->default(0);
            $table->decimal('cube', 10, 2)->nullable()->default(0);
            $table->enum('active', ['T', 'F'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
