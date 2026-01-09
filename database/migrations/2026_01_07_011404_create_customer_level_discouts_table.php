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
        Schema::create('customer_level_discouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('level_id')->nullable()->constrained('customer_levels')->onDelete('set null');
            $table->decimal('discount', 5, 2)->nullable()->default(0)->comment('Discount percentage');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_level_discouts');
    }
};
