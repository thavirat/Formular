<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrudMenu extends Model
{
    protected $fillable = ['readed','created','updated','deleted','printed','export_pdf','export_excel'];
}
