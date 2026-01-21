<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        view()->composer('admin.layouts.default', function ($view) {
            $view->with('SidebarMenus', \App\Models\Menu::Active()->get());
        });

        view()->composer('*', function ($view) {
            $settings = \Cache::remember('system_settings', 86400, function() {
                return \App\Models\Setting::pluck('meta_value', 'meta')->toArray();
            });

            $view->with('settings', $settings);

            // ถ้า Login อยู่ ให้ส่งสิทธิ์ไปด้วย
            if (\Auth::guard('admin')->check()) {
                $view->with('my_menu_permission', \App\Helpers\UserPermission::getMyPermissions());
            }
        });


    }
}
