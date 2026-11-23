<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * เก็บ snapshot ตัวเลขเดิม (ก่อน FIC) ไว้ 1 ชุดต่อ detail — เขียนครั้งแรกครั้งเดียว
 * fic_snapshot = {qty, unit_price, weight_nw, weight_gw, weight_nt, weight_gt, cubic_meter}
 * fic_invoice_no = เลข FIC (inv_num) ที่ apply ล่าสุด
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packing_form_details', function (Blueprint $table) {
            if (!Schema::hasColumn('packing_form_details', 'fic_snapshot')) {
                $table->json('fic_snapshot')->nullable()->after('mark_no');
            }
            if (!Schema::hasColumn('packing_form_details', 'fic_invoice_no')) {
                $table->string('fic_invoice_no')->nullable()->after('fic_snapshot');
            }
        });
    }

    public function down(): void
    {
        Schema::table('packing_form_details', function (Blueprint $table) {
            if (Schema::hasColumn('packing_form_details', 'fic_invoice_no')) {
                $table->dropColumn('fic_invoice_no');
            }
            if (Schema::hasColumn('packing_form_details', 'fic_snapshot')) {
                $table->dropColumn('fic_snapshot');
            }
        });
    }
};
