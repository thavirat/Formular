<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('packing_forms') && !Schema::hasColumn('packing_forms', 'port_of_discharge')) {
            Schema::table('packing_forms', function (Blueprint $table) {
                $table->string('port_of_discharge', 255)->nullable()->after('shipped_from');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('packing_forms') && Schema::hasColumn('packing_forms', 'port_of_discharge')) {
            Schema::table('packing_forms', function (Blueprint $table) {
                $table->dropColumn('port_of_discharge');
            });
        }
    }
};
