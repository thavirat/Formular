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
        Schema::table('quotation_products', function (Blueprint $table) {
            $table->decimal('discount_percents', 10, 2)->nullable()->default(0)->comment('Discount percentage');
            $table->decimal('discount_amount', 10, 2)->nullable()->default(0)->comment('Discount amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotation_products', function (Blueprint $table) {
            //
        });
    }
};
