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
        Schema::table('proforma_invoice_products', function (Blueprint $table) {
            $table->decimal('produced_qty', 10, 2)->default(0)->after('qty');
        });
    }

    public function down(): void
    {
        Schema::table('proforma_invoice_products', function (Blueprint $table) {
            $table->dropColumn('produced_qty');
        });
    }
};
