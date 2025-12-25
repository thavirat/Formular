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
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('symbol')->nullable();
            $table->decimal('buying_sight', 12, 7)->nullable()->default(0);
            $table->decimal('buying_transfer', 12, 7)->nullable()->default(0);
            $table->decimal('selling', 12, 7)->nullable()->default(0);
            $table->decimal('mid_rate', 12, 7)->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};


