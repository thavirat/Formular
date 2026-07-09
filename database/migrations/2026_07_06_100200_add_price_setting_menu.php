<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!DB::table('menus')->where('url', 'PriceSetting')->exists()) {
            DB::table('menus')->insert([
                'main_menu_id' => 6, // กลุ่ม master data
                'icon' => 'fa fa-dollar-sign',
                'img' => null,
                'title_th' => 'ตั้งค่าราคาขาย',
                'title_en' => 'Price Setting',
                'url' => 'PriceSetting',
                'badge' => null, 'label' => null, 'highlight' => null,
                'show' => 'T', 'sort_id' => 92,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        $menuId = DB::table('menus')->where('url', 'PriceSetting')->value('id');
        foreach (DB::table('admin_users')->pluck('id') as $userId) {
            $has = DB::table('crud_menus')->where('user_id', $userId)->where('menu_id', $menuId)->exists();
            if (!$has) {
                DB::table('crud_menus')->insert([
                    'user_id' => $userId, 'menu_id' => $menuId,
                    'created' => 'T', 'readed' => 'T', 'updated' => 'T', 'deleted' => 'T',
                    'printed' => 'T', 'export_excel' => 'T', 'export_pdf' => 'T',
                    'created_at' => now(), 'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        $menuId = DB::table('menus')->where('url', 'PriceSetting')->value('id');
        if ($menuId) {
            DB::table('crud_menus')->where('menu_id', $menuId)->delete();
            DB::table('menus')->where('id', $menuId)->delete();
        }
    }
};
