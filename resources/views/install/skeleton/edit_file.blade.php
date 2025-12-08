var {{$name}} = JSON.parse(res.content.{{$name}});
$('#{{$id}}_list_file').html('');
for(i in {{$name}}){
    var rec = {{$name}}[i];
    $('#{{$id}}_list_file').append('<li class="list-group-item"><a href="'+asset_gb+'uploads/files/{{$model_name}}/'+rec+'" target="_blank">'+rec+'</a> <button type="button" class="btn btn-xs btn-danger pull-right btn-romove-file" data-file="'+rec+'"><i class="fa fa-trash"></i></button> <input type="hidden" name="{{$name}}[]" value="'+rec+'"></li>');
}
