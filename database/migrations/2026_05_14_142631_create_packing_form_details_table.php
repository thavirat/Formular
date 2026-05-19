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
        Schema::create('packing_form_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('packing_form_id')->nullable()->constrained('packing_forms');
            $table->integer('from')->unsigned()->nullable();
            $table->integer('to')->unsigned()->nullable();
            $table->foreignId('pi_product_id')->nullable()->constrained('proforma_invoice_products');
            $table->string('part_no')->nullable();
            $table->string('cus_part_no')->nullable();
            $table->text('description')->nullable();
            $table->string('formular_number')->nullable();
            $table->decimal('width', 5, 2)->nullable();
            $table->decimal('lenght', 5, 2)->nullable();
            $table->decimal('height', 5, 2)->nullable();
            $table->integer('qty')->unsigned();
            $table->decimal('cubic_meter', 6, 2)->nullable();
            $table->decimal('weight_nw', 6, 2)->nullable();
            $table->decimal('weight_gw', 6, 2)->nullable();
            $table->decimal('weight_nt', 6, 2)->nullable();
            $table->decimal('weight_gt', 6, 2)->nullable();
            $table->string('uom')->nullable();
            $table->string('from_co')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packing_form_details');
    }
};
