<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleModel extends Model
{
    protected $fillable = ['vehicle_make_id', 'name'];

    public function make()
    {
        return $this->belongsTo(VehicleMake::class, 'vehicle_make_id');
    }

    public function fitments()
    {
        return $this->hasMany(ProductFitment::class, 'vehicle_model_id');
    }
}
