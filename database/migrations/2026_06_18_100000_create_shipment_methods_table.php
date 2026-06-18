<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('shipment_methods')) {
            Schema::create('shipment_methods', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();              // เช่น SEAFREIGHT, AIRFREIGHT
                $table->integer('seq')->nullable()->default(0);  // ลำดับการแสดง
                $table->enum('active', ['T', 'F'])->nullable()->default('T');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('shipment_methods');
    }
};
