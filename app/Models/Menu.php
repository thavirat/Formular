<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Auth;

class Menu extends Model
{
    public function scopeActive($q){
        $user = Auth::user();
        $cruds = $user->CrudMenus;
        $permission = [];
        foreach($cruds as $crud){
            if($crud->readed=='T'){
                $permission[] = $crud->menu_id;
            }
        }

        $q->where('show' , 'T');
        $q->whereIn('id' , $permission);
        $q->whereNull('main_menu_id');
        $q->orderBy('sort_id');
    }

    public function SubMenu(){
        return $this->hasMany('\App\Models\Menu' ,'main_menu_id' ,'id');
    }

    public function MainMenu(){
        return $this->hasOne('\App\Models\Menu', 'id', 'main_menu_id');
    }

}
