<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('quotation_statuses', 'color')) {
            Schema::table('quotation_statuses', function (Blueprint $t) {
                $t->string('color', 20)->nullable()->after('name'); // โค้ดสี hex เช่น #28a745
            });
        }

        // สร้าง/อัปเดตสถานะมาตรฐาน 5 ตัว พร้อมสีดีฟอลต์ (ใส่สีเฉพาะตัวที่ยังว่าง ไม่ทับที่ผู้ใช้ตั้งเอง)
        $defaults = [
            1 => ['name' => 'รอเสนอ',      'color' => '#adb5bd'],
            2 => ['name' => 'รอ Customer', 'color' => '#ffc107'],
            3 => ['name' => 'อนุมัติ',      'color' => '#28a745'],
            4 => ['name' => 'ยกเลิก',       'color' => '#dc3545'],
            5 => ['name' => 'ออก PI แล้ว',  'color' => '#0d6efd'],
        ];
        foreach ($defaults as $id => $d) {
            if (DB::table('quotation_statuses')->where('id', $id)->exists()) {
                DB::table('quotation_statuses')->where('id', $id)->whereNull('color')
                    ->update(['color' => $d['color'], 'updated_at' => now()]);
            } else {
                DB::table('quotation_statuses')->insert([
                    'id' => $id, 'name' => $d['name'], 'color' => $d['color'],
                    'created_at' => now(), 'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('quotation_statuses', 'color')) {
            Schema::table('quotation_statuses', function (Blueprint $t) {
                $t->dropColumn('color');
            });
        }
    }
};
