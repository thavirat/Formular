<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductFitment extends Model
{
    protected $fillable = [
        'product_id',
        'vehicle_make_id',
        'vehicle_model_id',
        'year_start',
        'year_end',
        'note',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function make()
    {
        return $this->belongsTo(VehicleMake::class, 'vehicle_make_id');
    }

    public function model()
    {
        return $this->belongsTo(VehicleModel::class, 'vehicle_model_id');
    }
}
