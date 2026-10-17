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
 * idempotent: ถ้ามีเมนู Invoice แล้วจะข้าม
 */
return new class extends Migration
{
    public function up(): void
    {
        if (DB::table('menus')->where('url', 'Invoice')->exists()) {
            return;
        }

        $pack = DB::table('menus')->where('url', 'PackingForm')->first();

        $menuId = DB::table('menus')->insertGetId([
            'main_menu_id' => $pack->main_menu_id ?? null,
            'icon' => 'fa fa-file-invoice-dollar',
            'title' => 'Invoice',
            'title_en' => 'Invoice',
            'title_th' => 'Invoice',
            'url' => 'Invoice',
            'show' => 'T',
            'sort_id' => $pack ? ((int) $pack->sort_id) : 99,
        ]);

        $now = now();

        $grant = function ($userId) use ($menuId, $now) {
            DB::table('crud_menus')->insert([
                'user_id' => $userId,
                'menu_id' => $menuId,
                'created' => 'F',
                'readed' => 'T',
                'updated' => 'T',
                'deleted' => 'F',
                'printed' => 'T',
                'export_excel' => 'F',
                'export_pdf' => 'F',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            Cache::forget("user_perms_{$userId}");
        };

        $granted = 0;
        if ($pack) {
            foreach (DB::table('crud_menus')->where('menu_id', $pack->id)->get() as $c) {
                $grant($c->user_id);
                $granted++;
            }
        }
        if ($granted === 0) {
            foreach (DB::table('admin_users')->pluck('id') as $uid) {
                $grant($uid);
            }
        }
    }

    public function down(): void
    {
        $menu = DB::table('menus')->where('url', 'Invoice')->first();
        if ($menu) {
            DB::table('crud_menus')->where('menu_id', $menu->id)->delete();
            DB::table('menus')->where('id', $menu->id)->delete();
        }
    }
};
