<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Menu;
class seed_menu extends Seeder
{
  /**
  * Run the database seeds.
  *
  * @return void
  */
  public function run()
  {
    $menu = new Menu;
    $menu->main_menu_id = null;
    $menu->icon ='fa fa-desktop';
    $menu->img =null;
    $menu->title ='Install';
    $menu->url ='Install';
    $menu->label =null;
    $menu->highlight =null;
    $menu->show ='T';
    $menu->sort_id ='0';
    $menu->save();

    $menu = new Menu;
    $menu->main_menu_id = null;
    $menu->icon ='fa fa-th-large';
    $menu->img =null;
    $menu->title ='แดชบอร์ด';
    $menu->url ='Dashboard';
    $menu->label =null;
    $menu->highlight =null;
    $menu->show ='T';
    $menu->sort_id ='1';
    $menu->save();

    $menu = new Menu;
    $menu->main_menu_id = null;
    $menu->icon ='fa fa-bars';
    $menu->img =null;
    $menu->title ='จัดการเมนู';
    $menu->url ='Menu';
    $menu->label =null;
    $menu->highlight =null;
    $menu->show ='T';
    $menu->sort_id ='2';
    $menu->save();

    $menu = new Menu;
    $menu->main_menu_id =null;
    $menu->icon ='fa fa-user-circle';
    $menu->img =null;
    $menu->title ='ข้อมูลส่วนตัว';
    $menu->url ='Profile';
    $menu->label =null;
    $menu->highlight =null;
    $menu->show ='F';
    $menu->sort_id ='3';
    $menu->save();

    $menu = new Menu;
    $menu->main_menu_id =null;
    $menu->icon ='fa fa-users';
    $menu->img =null;
    $menu->title ='ผู้ใช้งานระบบ';
    $menu->url ='AdminUser';
    $menu->label =null;
    $menu->highlight =null;
    $menu->show ='T';
    $menu->sort_id ='4';
    $menu->save();

    $menu_setting = new Menu;
    $menu_setting->main_menu_id =null;
    $menu_setting->icon ='fa fa-cogs';
    $menu_setting->img =null;
    $menu_setting->title ='ตั้งค่า';
    $menu_setting->url ='Setting';
    $menu_setting->label =null;
    $menu_setting->highlight =null;
    $menu_setting->show ='T';
    $menu_setting->sort_id ='5';
    $menu_setting->save();

    $menu = new Menu;
    $menu->main_menu_id =$menu_setting->id;
    $menu->icon ='fa fa-cog';
    $menu->img =null;
    $menu->title ='ตั้งค่าระบบ';
    $menu->url ='SettingSystem';
    $menu->label =null;
    $menu->highlight =null;
    $menu->show ='T';
    $menu->sort_id ='6';
    $menu->save();

    $menu = new Menu;
    $menu->main_menu_id = $menu_setting->id;
    $menu->icon ='fa fa-lock';
    $menu->img =null;
    $menu->title ='จัดการสิทธิ';
    $menu->url ='Permission';
    $menu->label =null;
    $menu->highlight =null;
    $menu->show ='T';
    $menu->sort_id ='7';
    $menu->save();

    $menu = new Menu;
    $menu->main_menu_id =null;
    $menu->icon ='fa fa-sign-out-alt';
    $menu->img =null;
    $menu->title ='ออกจากระบบ';
    $menu->url ='Logout';
    $menu->label =null;
    $menu->highlight =null;
    $menu->show ='T';
    $menu->sort_id ='9999';
    $menu->save();

  }
}
