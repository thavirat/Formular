<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use \App\Models\AdminUser;
class seed_user_admin extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
                $admin_user = new AdminUser;
                $admin_user->firstname = 'เวิร์คบายไทย';
                $admin_user->lastname = 'อินเตอร์เน็ต';
                $admin_user->nickname = 'ผู้ดูแลสูงสุด';
                $admin_user->username = 'admin';
                $admin_user->password = bcrypt('123456789');
                $admin_user->active = 'T';
                $admin_user->photo = '[]';
                $admin_user->save();

                $admin_user = new AdminUser;
                $admin_user->firstname = 'แอดมินระบบ';
                $admin_user->lastname = '';
                $admin_user->nickname = 'แอดมิน';
                $admin_user->username = 'adminc';
                $admin_user->password = bcrypt('123456789');
                $admin_user->active = 'T';
                $admin_user->photo = '[]';
                $admin_user->save();
    }
}
