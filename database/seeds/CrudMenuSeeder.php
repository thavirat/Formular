<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\CrudMenu;
class CrudMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Users = DB::table('admin_users')->get();
        $Menus = DB::table('menus')->get();
        foreach($Users as $User){
            foreach($Menus as $Menu){
                $crud_menu = new CrudMenu;
                $crud_menu->user_id = $User->id;
                $crud_menu->menu_id = $Menu->id;
                $crud_menu->created = 'T';
                $crud_menu->readed = 'T';
                $crud_menu->updated = 'T';
                $crud_menu->deleted = 'T';
                $crud_menu->printed = 'T';
                $crud_menu->export_excel = 'T';
                $crud_menu->export_pdf = 'T';
                $crud_menu->save();
            }
        }
        
    }
}
