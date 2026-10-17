<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleMake extends Model
{
    protected $fillable = ['name'];

    public function models()
    {
        return $this->hasMany(VehicleModel::class, 'vehicle_make_id');
    }

    public function fitments()
    {
        return $this->hasMany(ProductFitment::class, 'vehicle_make_id');
    }
}
