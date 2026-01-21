<?php
namespace App\Helpers;

use Auth;
use Cache;
use App\Models\CrudMenu;
use App\Models\Permission;

class UserPermission {
    public static function getMyPermissions() {
        if (!Auth::guard('admin')->check()) return [];

        $user_id = Auth::guard('admin')->id();

        // ใช้ Cache แยกตาม user_id เพื่อความเร็ว
        return Cache::remember("user_perms_{$user_id}", 3600, function() use ($user_id) {
            $my_menu_permission = [];

            // 1. ดึงสิทธิ์รายเมนู (CRUD)
            $menu_crud = CrudMenu::join('menus', 'menus.id', 'crud_menus.menu_id')
                ->where('user_id', $user_id)
                ->get();

            foreach($menu_crud as $menu){
                $my_menu_permission[$menu->url] = [
                    'c' => $menu->created,
                    'r' => $menu->readed,
                    'u' => $menu->updated,
                    'd' => $menu->deleted,
                    'p' => $menu->printed,
                    'ep' => $menu->export_pdf,
                    'ee' => $menu->export_excel,
                ];
            }

            // 2. ดึงสิทธิ์แบบ Key-Value (Global Permissions)
            $permissions = Permission::select('permissions.*', 'admin_permissions.permission')
                ->leftJoin('admin_permissions', function($join) use ($user_id) {
                    $join->on('permissions.id', 'admin_permissions.permission_id')
                         ->where('admin_permissions.admin_id', $user_id);
                })
                ->get();

            foreach($permissions as $permission){
                // เก็บค่า T/F หรือค่าสิทธิ์ลงใน key นั้นๆ
                $my_menu_permission[$permission->key_permission] = $permission->permission;
            }

            return $my_menu_permission;
        });
    }
}
