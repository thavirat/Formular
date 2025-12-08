                        if(res.content.{{$name}}=='{{$checkbox[0]['value']}}'){
                            $("#{{$id}}").prop('checked' , 'checked');
                        }else{
                            $("#{{$id}}").prop('checked' , false);
                        }
                        