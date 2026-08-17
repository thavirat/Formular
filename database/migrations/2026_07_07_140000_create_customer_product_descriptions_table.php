<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('customer_product_descriptions')) {
            Schema::create('customer_product_descriptions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('customer_id')->index();
                $table->unsignedBigInteger('product_id')->index();
                $table->text('description')->nullable();
                $table->timestamps();
                $table->unique(['customer_id', 'product_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_product_descriptions');
    }
};
