<?php

use Illuminate\Database\Seeder;
use \App\Models\AdminUser;
use \App\Models\Permission;
use \App\Models\AdminPermission;

class AdminPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admins = AdminUser::get();
        $permissions = Permission::get();
        foreach($admins as $admin){
            foreach($permissions as $permission){
                $admin_permission = new AdminPermission;
                $admin_permission->admin_id = $admin->id;
                $admin_permission->permission_id = $permission->id;
                $admin_permission->permission = 'T';
                $admin_permission->save();
            }
        }
    }
}
