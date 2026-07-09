<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // หัวชุดราคา (global) + ช่วงเวลา
        if (!Schema::hasTable('price_settings')) {
            Schema::create('price_settings', function (Blueprint $table) {
                $table->id();
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->enum('active', ['T', 'F'])->default('T');
                $table->timestamps();
            });
        }

        // เรตต่อสกุลเงิน  (ราคา = COST ÷ rate)
        if (!Schema::hasTable('price_setting_rates')) {
            Schema::create('price_setting_rates', function (Blueprint $table) {
                $table->id();
                $table->foreignId('price_setting_id')->constrained('price_settings')->onDelete('cascade');
                $table->unsignedBigInteger('currency_id');
                $table->decimal('rate', 12, 4)->nullable()->default(0);  // เช่น USD=30
                $table->timestamps();
            });
        }

        // ตัวคูณต่อระดับลูกค้า  (× multiplier)
        if (!Schema::hasTable('price_setting_levels')) {
            Schema::create('price_setting_levels', function (Blueprint $table) {
                $table->id();
                $table->foreignId('price_setting_id')->constrained('price_settings')->onDelete('cascade');
                $table->unsignedBigInteger('level_id');
                $table->decimal('multiplier', 10, 4)->nullable()->default(1); // เช่น BIG=1.1, SMALL=1.2
                $table->timestamps();
            });
        }

        // factor ต่อระดับจำนวน  (× 100 ÷ factor)  อิงราคา BIG เสมอ
        if (!Schema::hasTable('price_setting_qtys')) {
            Schema::create('price_setting_qtys', function (Blueprint $table) {
                $table->id();
                $table->foreignId('price_setting_id')->constrained('price_settings')->onDelete('cascade');
                $table->integer('min_qty')->default(1);          // ขั้นจำนวน เช่น 100, 500 (บังคับมี 1)
                $table->decimal('factor', 10, 4)->nullable()->default(100); // เช่น 100PCS=103, 500PCS=105
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('price_setting_qtys');
        Schema::dropIfExists('price_setting_levels');
        Schema::dropIfExists('price_setting_rates');
        Schema::dropIfExists('price_settings');
    }
};
