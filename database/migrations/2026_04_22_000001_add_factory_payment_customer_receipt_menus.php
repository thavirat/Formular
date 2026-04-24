<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $existsFactory = DB::table('menus')->where('url', 'FactoryPayment')->exists();
        if (!$existsFactory) {
            DB::table('menus')->insert([
                'main_menu_id' => null,
                'icon' => 'fa fa-industry',
                'img' => null,
                'title_th' => 'ชำระเงินโรงงาน',
                'title_en' => 'Factory Payment',
                'url' => 'FactoryPayment',
                'badge' => null,
                'label' => null,
                'highlight' => null,
                'show' => 'T',
                'sort_id' => 88,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $existsCustomer = DB::table('menus')->where('url', 'CustomerReceipt')->exists();
        if (!$existsCustomer) {
            DB::table('menus')->insert([
                'main_menu_id' => null,
                'icon' => 'fa fa-hand-holding-usd',
                'img' => null,
                'title_th' => 'รับเงินลูกค้า',
                'title_en' => 'Customer Receipt',
                'url' => 'CustomerReceipt',
                'badge' => null,
                'label' => null,
                'highlight' => null,
                'show' => 'T',
                'sort_id' => 89,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $menuIds = DB::table('menus')->whereIn('url', ['FactoryPayment', 'CustomerReceipt'])->pluck('id');
        $userIds = DB::table('admin_users')->pluck('id');
        foreach ($menuIds as $menuId) {
            foreach ($userIds as $userId) {
                $has = DB::table('crud_menus')
                    ->where('user_id', $userId)
                    ->where('menu_id', $menuId)
                    ->exists();
                if (!$has) {
                    DB::table('crud_menus')->insert([
                        'user_id' => $userId,
                        'menu_id' => $menuId,
                        'created' => 'T',
                        'readed' => 'T',
                        'updated' => 'T',
                        'deleted' => 'T',
                        'printed' => 'T',
                        'export_excel' => 'T',
                        'export_pdf' => 'T',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        $menuIds = DB::table('menus')->whereIn('url', ['FactoryPayment', 'CustomerReceipt'])->pluck('id');
        DB::table('crud_menus')->whereIn('menu_id', $menuIds)->delete();
        DB::table('menus')->whereIn('url', ['FactoryPayment', 'CustomerReceipt'])->delete();
    }
};
