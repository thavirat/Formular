<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('packing_forms') && !Schema::hasColumn('packing_forms', 'term_of_payment')) {
            Schema::table('packing_forms', function (Blueprint $table) {
                $table->string('term_of_payment', 255)->nullable()->after('lc_no');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('packing_forms') && Schema::hasColumn('packing_forms', 'term_of_payment')) {
            Schema::table('packing_forms', function (Blueprint $table) {
                $table->dropColumn('term_of_payment');
            });
        }
    }
};
