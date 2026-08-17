<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('packing_form_services')) {
            Schema::create('packing_form_services', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('packing_form_id')->index();
                $table->integer('seq')->default(0);
                $table->string('name', 191)->nullable();
                $table->decimal('amount', 12, 4)->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('packing_form_services');
    }
};
