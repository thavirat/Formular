<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('price_setting_qtys', function (Blueprint $t) {
            if (!Schema::hasColumn('price_setting_qtys', 'giveaway')) {
                $t->decimal('giveaway', 10, 4)->default(0)->after('min_qty'); // แถม เช่น 3, 5 (0 = ไม่แถม)
            }
        });

        if (Schema::hasColumn('price_setting_qtys', 'factor')) {
            // ค่าเดิม factor (103/105) -> แถม (3/5) = factor - 100
            DB::statement('UPDATE ' . DB::getTablePrefix() . 'price_setting_qtys SET giveaway = GREATEST(factor - 100, 0)');
            Schema::table('price_setting_qtys', function (Blueprint $t) {
                $t->dropColumn('factor');
            });
        }
    }

    public function down(): void
    {
        Schema::table('price_setting_qtys', function (Blueprint $t) {
            if (!Schema::hasColumn('price_setting_qtys', 'factor')) {
                $t->decimal('factor', 10, 4)->default(100)->after('min_qty');
            }
        });
        DB::statement('UPDATE ' . DB::getTablePrefix() . 'price_setting_qtys SET factor = giveaway + 100');
        Schema::table('price_setting_qtys', function (Blueprint $t) {
            if (Schema::hasColumn('price_setting_qtys', 'giveaway')) {
                $t->dropColumn('giveaway');
            }
        });
    }
};
