$('body').on('change' , '.upload_file' , function(){
    var ele = $(this);
    var fd = new FormData();
    var files = $(this)[0].files[0]; 
    fd.append('file', files); 

    $.ajax({ 
        url: url_gb+'/admin/upload_file', 
        type: 'post', 
        data: fd, 
        contentType: false, 
        processData: false
    }).done(function( res ) {
            var path_split = res.path.split('/')
            var file_name = path_split[1];
            ele.next().append('<li class="list-group-item"><a href="'+res.link_preview+'" target="_blank">'+file_name+'</a> <button type="button" class="btn btn-xs btn-danger pull-right btn-romove-file" data-file="'+file_name+'"><i class="fa fa-trash"></i></button> <input type="hidden" name="{{$name}}[]" value="'+file_name+'"></li>');
            ele.val('');
    }).fail(function(res){
        resetButton(form.find('button[type=submit]'));
        var res = $.parseJSON(res.responseText);
        var str = "กรุณาถ่ายรูปหน้าจอนี้ให้กับเจ้าหน้าที่\n\r"+res.message+"\n\r"+res.exception+"\n\r"+res.file+" Line : "+res.line;
        swal("โอ๊ะโอ! ขอโทษด้วยมีบางอย่างผิดพลาด",str,'error');
    });
});

$('body').on('click', '.btn-romove-file' , function () {
    var ele = $(this);
    ele.closest('li').remove();
});
