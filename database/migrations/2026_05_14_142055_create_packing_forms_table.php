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
        Schema::create('packing_forms', function (Blueprint $table) {
            $table->id();
            $table->string('to')->nullable();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');
            $table->string('customer_name')->nullable();
            $table->string('country')->nullable();
            $table->date('doc_date')->nullable();
            $table->string('doc_no')->nullable();
            $table->integer('run_no')->unsigned();
            $table->integer('pkg')->unsigned();
            $table->integer('qty')->unsigned();
            $table->decimal('cubic_meter', 6, 2)->nullable();
            $table->decimal('weight_nw', 6, 2)->nullable();
            $table->decimal('weight_gw', 6, 2)->nullable();
            $table->decimal('weight_nt', 6, 2)->nullable();
            $table->decimal('weight_gt', 6, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packing_forms');
    }
};
