<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!DB::table('menus')->where('url', 'Report')->exists()) {
            DB::table('menus')->insert([
                'main_menu_id' => null,
                'icon' => 'fa fa-chart-line',
                'img' => null,
                'title_th' => 'รายงาน',
                'title_en' => 'Reports',
                'url' => 'Report',
                'badge' => null, 'label' => null, 'highlight' => null,
                'show' => 'T', 'sort_id' => 90,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        $menuId = DB::table('menus')->where('url', 'Report')->value('id');
        foreach (DB::table('admin_users')->pluck('id') as $userId) {
            if (!DB::table('crud_menus')->where('user_id', $userId)->where('menu_id', $menuId)->exists()) {
                DB::table('crud_menus')->insert([
                    'user_id' => $userId, 'menu_id' => $menuId,
                    'created' => 'T', 'readed' => 'T', 'updated' => 'T', 'deleted' => 'T',
                    'printed' => 'T', 'export_excel' => 'T', 'export_pdf' => 'T',
                    'created_at' => now(), 'updated_at' => now(),
                ]);
            }
        }

        // ซ่อนเมนู AR เดี่ยว -> ย้ายไปอยู่ใต้ "รายงาน" (ยังเข้าถึง URL ได้)
        DB::table('menus')->where('url', 'AccountReceivable')->update(['show' => 'F', 'updated_at' => now()]);
    }

    public function down(): void
    {
        DB::table('menus')->where('url', 'AccountReceivable')->update(['show' => 'T']);
        $menuId = DB::table('menus')->where('url', 'Report')->value('id');
        if ($menuId) {
            DB::table('crud_menus')->where('menu_id', $menuId)->delete();
            DB::table('menus')->where('id', $menuId)->delete();
        }
    }
};
