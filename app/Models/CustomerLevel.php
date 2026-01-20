<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerLevel extends Model
{
    public function CustomerLevelDiscout(){
        return $this->hasOne('\App\Models\CustomerLevelDiscout' , 'level_id' , 'id');
    }
}
