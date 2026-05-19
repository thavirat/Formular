<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('packing_forms')) {
            return;
        }

        Schema::table('packing_forms', function (Blueprint $table) {
            if (!Schema::hasColumn('packing_forms', 'import_file_sha256')) {
                $table->string('import_file_sha256', 64)->nullable()->after('id');
            }
            if (!Schema::hasColumn('packing_forms', 'source_filename')) {
                $table->string('source_filename', 255)->nullable();
            }
        });

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            $t = Schema::getConnection()->getTablePrefix().'packing_forms';
            DB::statement("ALTER TABLE `{$t}` MODIFY run_no INT UNSIGNED NULL");
            DB::statement("ALTER TABLE `{$t}` MODIFY pkg INT UNSIGNED NULL");
            DB::statement("ALTER TABLE `{$t}` MODIFY qty INT UNSIGNED NULL");
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('packing_forms')) {
            return;
        }

        Schema::table('packing_forms', function (Blueprint $table) {
            $table->dropColumn(['import_file_sha256', 'source_filename']);
        });
    }
};
