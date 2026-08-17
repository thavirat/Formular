<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('packing_forms') && !Schema::hasColumn('packing_forms', 'declaration_no')) {
            Schema::table('packing_forms', function (Blueprint $table) {
                $table->string('declaration_no', 100)->nullable()->after('invoice_no');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('packing_forms') && Schema::hasColumn('packing_forms', 'declaration_no')) {
            Schema::table('packing_forms', function (Blueprint $table) {
                $table->dropColumn('declaration_no');
            });
        }
    }
};
