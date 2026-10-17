<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

/**
 * เพิ่มเมนู "Invoice" ในแถบข้าง (ข้าง ๆ PackingForm) + ให้สิทธิ์ (crud_menus: อ่าน+แก้)
 * กับผู้ใช้ทุกคนที่มีสิทธิ์ PackingForm อยู่แล้ว
 *
 * ทำเป็น migration (ไม่ใช่ seeder) เพราะ post-deploy รัน `migrate` อัตโนมัติ
 * → เมนูขึ้นเองตอน deploy ไม่ต้องรัน composer dump-autoload / db:seed บน server
 * คอลัมน์ insert อิง pattern เดียวกับ add_credit_note_menu (ที่รันผ่านจริงบน production)
 * idempotent: ถ้ามีเมนู Invoice แล้วจะข้าม
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!DB::table('menus')->where('url', 'Invoice')->exists()) {
            $pack = DB::table('menus')->where('url', 'PackingForm')->first();

            DB::table('menus')->insert([
                'main_menu_id' => $pack->main_menu_id ?? null,
                'icon' => 'fa fa-file-invoice-dollar',
                'img' => null,
                'title_th' => 'Invoice',
                'title_en' => 'Invoice',
                'url' => 'Invoice',
                'badge' => null,
                'label' => null,
                'highlight' => null,
                'show' => 'T',
                'sort_id' => $pack ? ((int) $pack->sort_id) : 93,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $menuId = DB::table('menus')->where('url', 'Invoice')->value('id');
        $pack = DB::table('menus')->where('url', 'PackingForm')->first();

        // ให้สิทธิ์ผู้ใช้ที่มีสิทธิ์ PackingForm (อ่าน+แก้) ไม่งั้น fallback ให้ทุก admin
        $userIds = $pack
            ? DB::table('crud_menus')->where('menu_id', $pack->id)->pluck('user_id')->all()
            : DB::table('admin_users')->pluck('id')->all();

        foreach (array_unique($userIds) as $userId) {
            $has = DB::table('crud_menus')->where('user_id', $userId)->where('menu_id', $menuId)->exists();
            if (!$has) {
                DB::table('crud_menus')->insert([
                    'user_id' => $userId,
                    'menu_id' => $menuId,
                    'created' => 'F', 'readed' => 'T', 'updated' => 'T', 'deleted' => 'F',
                    'printed' => 'T', 'export_excel' => 'F', 'export_pdf' => 'F',
                    'created_at' => now(), 'updated_at' => now(),
                ]);
            }
            Cache::forget("user_perms_{$userId}");
        }
    }

    public function down(): void
    {
        $menuId = DB::table('menus')->where('url', 'Invoice')->value('id');
        if ($menuId) {
            DB::table('crud_menus')->where('menu_id', $menuId)->delete();
            DB::table('menus')->where('id', $menuId)->delete();
        }
    }
};
