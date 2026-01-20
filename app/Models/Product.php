<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function CustomerLevelDiscout(){
        return $this->hasOne('\App\Models\CustomerLevelDiscout' , 'product_id' , 'id');
    }

    public function CustomerLevelDiscouts() {
        return $this->hasMany('\App\Models\CustomerLevelDiscout' , 'product_id' , 'id');
    }
}
