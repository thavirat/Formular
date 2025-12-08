<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use \App\Models\CrudMenu;
use \App\Models\Setting;
use \App\Models\AdminPermission;
use \App\Models\Permission;
use View;
use Session;
class SystemPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::guard('admin')->check()){
            $user_id = Auth::guard('admin')->user()->id;
            $menu_crud = CrudMenu::join('menus' , 'menus.id' , 'crud_menus.menu_id')->where('user_id' , $user_id)->get();
            $my_menu_permission = [];
            $permission_menus = [];
            foreach($menu_crud as $menu){
                $my_menu_permission[$menu->url]['c'] = $menu->created;
                $my_menu_permission[$menu->url]['r'] = $menu->readed;
                $my_menu_permission[$menu->url]['u'] = $menu->updated;
                $my_menu_permission[$menu->url]['d'] = $menu->deleted;
                $my_menu_permission[$menu->url]['p'] = $menu->printed;
                $my_menu_permission[$menu->url]['ep'] = $menu->export_pdf;
                $my_menu_permission[$menu->url]['ee'] = $menu->export_excel;
                if($menu->readed=='T'){
                    $permission_menus[] = $menu->menu_id;
                }
            }
            $permissions = Permission::select('permissions.*' , 'admin_permissions.permission')
                                     ->leftJoin('admin_permissions', function($join) use ($user_id) {
                                         $join->on('permissions.id','admin_permissions.permission_id')
                                              ->where('admin_permissions.admin_id', $user_id);
                                     })
                                    ->get();
            foreach($permissions as $permission){
                $my_menu_permission[$permission->key_permission] = $permission->permission;
            }
            View::share('my_menu_permission' , $my_menu_permission);
            Session::put('all_permissions', $my_menu_permission);
            Session::put('permission_menus', $permission_menus);

        }

        $settings = Setting::get();
        $info = [];
        foreach($settings as $setting){
            $info[$setting->meta] = $setting->meta_value;
        }
        View::share('settings' , $info);
        Session::put('settings', $info);
        return $next($request);
    }
}
