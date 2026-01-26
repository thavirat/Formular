<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProformaInvoice extends Model
{
    public function Products(){
        return $this->hasMany('\App\Models\ProformaInvoiceProduct' , 'pi_id' , 'id');
    }
}
