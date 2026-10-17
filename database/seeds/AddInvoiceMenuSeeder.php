<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

/**
 * เพิ่มเมนู "Invoice" ในแถบข้าง (เป็นเมนูข้าง ๆ PackingForm)
 * และให้สิทธิ์ (crud_menus: อ่าน+แก้ไข) กับผู้ใช้ทุกคนที่มีสิทธิ์ PackingForm อยู่แล้ว
 *
 * รัน:  php artisan db:seed --class=AddInvoiceMenuSeeder
 *       php artisan cache:clear   (ให้เมนูขึ้นทันที)
 *
 * หมายเหตุ: หน้า Invoice ใช้สิทธิ์ร่วมกับ PackingForm อยู่แล้ว เมนูนี้แค่ทำลิงก์ให้กดง่าย
 */
class AddInvoiceMenuSeeder extends Seeder
{
    public function run()
    {
        if (DB::table('menus')->where('url', 'Invoice')->exists()) {
            $this->command->info('มีเมนู Invoice อยู่แล้ว — ข้าม');

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
        $granted = 0;

        $grant = function ($userId) use ($menuId, $now, &$granted) {
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
            $granted++;
        };

        if ($pack) {
            foreach (DB::table('crud_menus')->where('menu_id', $pack->id)->get() as $c) {
                $grant($c->user_id);
            }
        }

        if ($granted === 0) {
            foreach (DB::table('admin_users')->pluck('id') as $uid) {
                $grant($uid);
            }
        }

        $this->command->info("เพิ่มเมนู Invoice แล้ว (ให้สิทธิ์ {$granted} ผู้ใช้) — อย่าลืม php artisan cache:clear");
    }
}
