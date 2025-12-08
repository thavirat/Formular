                        if(res.content.{{$name}}){
                            $("#{{$id}}").val(convertDateToThai(res.content.{{$name}}));
                        }