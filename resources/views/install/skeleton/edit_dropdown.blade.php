@if ($select_type == 'select2')
 $("#{{$id}}").val(res.content.{{$name}}).trigger('change.select2');
@else
 $("#{{$id}}").val(res.content.{{$name}});
@endif