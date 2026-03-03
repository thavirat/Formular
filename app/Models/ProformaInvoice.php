<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProformaInvoice extends Model
{
    public function Products(){
        return $this->hasMany('\App\Models\ProformaInvoiceProduct' , 'pi_id' , 'id');
    }

    public function customer(){
        return $this->belongsTo('\App\Models\Customer' , 'customer_id' , 'id');
    }
}
