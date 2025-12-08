$('#add_{{$name}}').orakuploader({
    orakuploader_field_name         : '{{$name}}',
    orakuploader_maximum_uploads    : 5,
    orakuploader_finished           : function(){
        $(".file").addClass("multi_file");
    }
});