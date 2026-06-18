<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proforma_invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('proforma_invoices', 'cno')) {
                $table->string('cno')->nullable()->after('ship_remark');          // C/NO. เช่น 1-UP
            }
            if (!Schema::hasColumn('proforma_invoices', 'shipment_method_id')) {
                $table->unsignedBigInteger('shipment_method_id')->nullable()->after('cno');
            }
            if (!Schema::hasColumn('proforma_invoices', 'shipment_to')) {
                $table->string('shipment_to')->nullable()->after('shipment_method_id'); // ปลายทาง TO
            }
        });
    }

    public function down(): void
    {
        Schema::table('proforma_invoices', function (Blueprint $table) {
            foreach (['cno', 'shipment_method_id', 'shipment_to'] as $col) {
                if (Schema::hasColumn('proforma_invoices', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
