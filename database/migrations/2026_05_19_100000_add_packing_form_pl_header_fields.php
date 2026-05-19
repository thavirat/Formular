<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('packing_forms')) {
            return;
        }

        Schema::table('packing_forms', function (Blueprint $table) {
            if (!Schema::hasColumn('packing_forms', 'invoice_no')) {
                $table->string('invoice_no', 100)->nullable()->after('doc_no');
            }
            if (!Schema::hasColumn('packing_forms', 'place_of_issue')) {
                $table->string('place_of_issue', 100)->nullable()->after('country');
            }
            if (!Schema::hasColumn('packing_forms', 'customer_address')) {
                $table->text('customer_address')->nullable()->after('customer_name');
            }
            if (!Schema::hasColumn('packing_forms', 'customer_phone')) {
                $table->string('customer_phone', 100)->nullable()->after('customer_address');
            }
            if (!Schema::hasColumn('packing_forms', 'sailing_date')) {
                $table->date('sailing_date')->nullable()->after('doc_date');
            }
            if (!Schema::hasColumn('packing_forms', 'shipped_from')) {
                $table->string('shipped_from', 255)->nullable()->after('sailing_date');
            }
            if (!Schema::hasColumn('packing_forms', 'per_vessel')) {
                $table->string('per_vessel', 255)->nullable()->after('shipped_from');
            }
            if (!Schema::hasColumn('packing_forms', 'lc_no')) {
                $table->string('lc_no', 100)->nullable()->after('per_vessel');
            }
            if (!Schema::hasColumn('packing_forms', 'issued_by')) {
                $table->string('issued_by', 255)->nullable()->after('lc_no');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('packing_forms')) {
            return;
        }

        Schema::table('packing_forms', function (Blueprint $table) {
            $table->dropColumn([
                'invoice_no',
                'place_of_issue',
                'customer_address',
                'customer_phone',
                'sailing_date',
                'shipped_from',
                'per_vessel',
                'lc_no',
                'issued_by',
            ]);
        });
    }
};
