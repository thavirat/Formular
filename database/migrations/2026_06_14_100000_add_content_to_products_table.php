<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * คอลัมน์ content = CONTENT(จำนวนต่อลัง/carton) จากไฟล์ Packing List Master
     * ใช้เป็นตัวหารในการคำนวณคิว: (W * L * H * จำนวนสั่งซื้อ) / content
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'content')) {
                $table->decimal('content', 10, 2)->nullable()->default(0)->after('cube');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'content')) {
                $table->dropColumn('content');
            }
        });
    }
};
