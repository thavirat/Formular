<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('packing_form_details')) {
            return;
        }

        Schema::table('packing_form_details', function (Blueprint $table) {
            if (!Schema::hasColumn('packing_form_details', 'excel_row')) {
                $table->unsignedInteger('excel_row')->nullable()->after('packing_form_id');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('packing_form_details')) {
            return;
        }

        Schema::table('packing_form_details', function (Blueprint $table) {
            $table->dropColumn('excel_row');
        });
    }
};
