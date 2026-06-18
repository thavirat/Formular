<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProformaInvoiceService extends Model
{
    protected $fillable = ['pi_id', 'seq', 'name', 'amount'];
}
