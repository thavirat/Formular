<#?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class {{$model_name}} extends Model
{
    protected $fillable = [
@foreach ($fields as $field)
@if($field->Field != 'id' && $field->Field != 'created_at' && $field->Field != 'updated_at')
        '{{ $field->Field }}',
@endif
@endforeach
    ];

    //
}
