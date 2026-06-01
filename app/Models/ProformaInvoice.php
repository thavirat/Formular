<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProformaInvoice extends Model
{
    public function Products()
    {
        return $this->hasMany('\App\Models\ProformaInvoiceProduct', 'pi_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo('\App\Models\Customer', 'customer_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo('\App\Models\AdminUser', 'created_by', 'id');
    }

    public function Comments()
    {
        return $this->hasMany(Comment::class, 'pi_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function incoterm()
    {
        return $this->belongsTo(Incoterm::class, 'incoterm_id');
    }

    public function creditPayment()
    {
        return $this->belongsTo(CreditPayment::class, 'credit_payment_id');
    }
}
