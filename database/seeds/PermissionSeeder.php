<?php

use Illuminate\Database\Seeder;
use \App\Models\Permission;
class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Permission = new Permission;
        $Permission->key_permission = 'admin_permission';
        $Permission->detail = 'จัดการสิทธิพนักงาน';
        $Permission->save();

        $Permission = new Permission;
        $Permission->key_permission = 'general_permission';
        $Permission->detail = 'สิทธิอื่นๆ';
        $Permission->save();
    }
}
