    $('#edit_{{$name}}').closest('#orak_{{$id}}').html('<div id="{{$id}}" orakuploader="on"></div>');
            if(res.content.{{$name}}){
                $('#edit_{{$name}}').orakuploader({
                    orakuploader_field_name         : '{{$name}}',
                    orakuploader_maximum_uploads    : 0,
                    orakuploader_attach_images      : jQuery.parseJSON(res.content.{{$name}}),
                    orakuploader_finished           : function(){
                        
                    }
                });
            }else{
                $('#edit_{{$name}}').orakuploader({
                    orakuploader_field_name         : '{{$name}}',
                    orakuploader_finished           : function(){
                        
                    }
                });
            }