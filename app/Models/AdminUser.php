<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AdminUser extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function CrudMenus(){
        return $this->hasMany('\App\Models\CrudMenu' , 'user_id');
    }

    public function Department(){
        return $this->hasOne('\App\Models\AdminDepartment' , 'id' , 'department_id');
    }

    public function Position(){
        return $this->hasOne('\App\Models\AdminPosition' , 'id' , 'position_id');
    }
}
