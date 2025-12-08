@extends('admin.layouts.default')
@section('css')
@endsection
@section('body')
    <div class="page-content container container-plus">
        <div class="page-header mb-2 pb-2 flex-column flex-sm-row align-items-start align-items-sm-center py-25 px-1">
            <h1 class="page-title text-primary-d2 text-140"> {{ $currentMenu->title }} </h1>
            <div class="page-tools mt-3 mt-sm-0 mb-sm-n1">
                @if ( $my_menu_permission[$currentMenu->url]['c'] == 'T' )
                    <button type="button" class="btn btn-light-green btn-h-green btn-a-green border-0 radius-3 py-2 text-600 text-90 btn-add">
                        <i class="fa fa-plus mr-1"></i>
                        เพิ่มข้อมูล
                    </button>
                @endif
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <div class="card dcard">
                    <div class="card-body p-0">

                        <div class="d-flex justify-content-between flex-column flex-sm-row px-2 px-sm-0">
                            <div class="pos-rel ml-sm-auto mr-sm-2 order-last order-sm-0">
                            </div>
                        </div>

                        <table id="TableList" class="table table-border-x brc-secondary-l4 border-0 mb-0 w-100">
                            <thead class="">
                                <tr>
                                    <th></th>
                                    <th>Key Permission</th>
                                    <th>รานละเอียด</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody class="mt-1">
                            </tbody>
                        </table>


                    </div><!-- /.card-body -->
                </div><!-- /.card -->
            </div><!-- /.col -->
        </div>

        <!-- Modal Add -->
        <div class="modal modal-lg fade" id="ModalAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form class="" id="FormAdd" >
                        <div class="modal-header">
                            <h5 class="modal-title text-primary-d3" id="exampleModalLabel">
                                เพิ่มข้อมูล {{ $currentMenu->title }}
                            </h5>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="add_key_permission">Key Permission</label>
                                        <input type="text" class="form-control" name="key_permission" id="add_key_permission" value="">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="add_detail">รายละเอียด</label>
                                        <input type="text" class="form-control" name="detail" id="add_detail" value="">
                                    </div>
                                </div>

                            </div>
                            <br />
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary px-4" data-dismiss="modal"> <i class="fa fa-window-close"></i> ปิด </button>
                            <button type="submit" class="btn btn-primary"> <i class="fa fa-save"></i> บันทึก </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>



        <!-- Modal Edit -->
        <div class="modal modal-lg fade" id="ModalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form class="" id="FormEdit" >
                        <input type="hidden" id="edit_id" value="">
                        <div class="modal-header">
                            <h5 class="modal-title text-primary-d3" id="exampleModalLabel">
                                แก้ไขข้อมูล {{ $currentMenu->title }}
                            </h5>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="edit_key_permission">Key Permission</label>
                                        <input type="text" class="form-control" name="key_permission" id="edit_key_permission" value="">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="edit_detail">รายละเอียด</label>
                                        <input type="text" class="form-control" name="detail" id="edit_detail" value="">
                                    </div>
                                </div>

                            </div>
                            <br />
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary px-4" data-dismiss="modal"> <i class="fa fa-window-close"></i> ปิด </button>
                            <button type="submit" class="btn btn-primary"> <i class="fa fa-save"></i> บันทึก </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
@section('js')
<script type="text/javascript">

var TableList = null;

$(function(){

    TableList = $('#TableList').dataTable({
        scrollY : height-380,
        scrollX : true,
        "pageLength": 50,
        "ajax": {
            "url": url_gb+"/admin/Permission/Lists",
            "data": function ( d ) {
                // d.custom = $('#myInput').val();
                // etc
            }
        },
        "drawCallback": function( settings ) {
            $('[data-toggle="tooltip"]').tooltip();
        },
        "responsive": true,
        "columns": [
            {"data" : "collapse", "searchable": false, "sortable": false},
            {"data" : "key_permission"},
            {"data" : "detail"},
            {
                "data" : "action" ,
                "name" : "action",
                "searchable": false,
                "sortable": false,
                "class" : "text-md-center"
            },
        ]
    });

});

$('body').on('click', '.btn-add', function(event) {
    $('#ModalAdd').modal('show');
});

$('body').on('click', '.btn-edit', function(event) {
    var btn = $(this);
    loadingButton(btn);
    var id = $(this).data('id');
    $('#edit_id').val(id);
    $.ajax({
        method : "GET",
        url : url_gb+"/admin/Permission/"+id,
        dataType : 'json'
    }).done(function(rec){

        $('#edit_key_permission').val(rec.key_permission);
        $('#edit_detail').val(rec.detail);

        resetButton(btn)
        ShowModal('ModalEdit');
    }).fail(function(){
        Swal.fire("system.system_alert","system.system_error","error");
        resetButton(btn)
    });
});

$('body').on('click' , '.btn-delete' , function (){
    var id = $(this).data('id');
    Swal.fire({
        title: "คุณต้องการลบข้อมูลหรือไม่ ?",
        text: "ข้อมูลที่ถูกลบไปแล้วจะไม่สามารถนำกลับมาได้ไม่ว่ากรณีใดๆทั้งสิ้นการลบข้อมูลจะทำให้ข้อมูลอื่นๆที่ถูกนำไปใช้ ลบหายไปด้วย",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: '#78c350',
        cancelButtonColor: '#f7531f',
        confirmButtonText: 'ยืนยันลบ',
        cancelButtonText: 'ยกเลิก ไม่ลบ'
    })
    .then((willDelete) => {
        if (willDelete.value) {
            $.ajax({
                method: "DELETE",
                url: url_gb+"/admin/Permission/"+id,
                dataType : 'json',
                data: {id : id}
            }).done(function( res ) {
                if(res.status==1){
                    Swal.fire(res.title,res.content,'success');
                    TableList.api().ajax.reload();
                }else{
                    Swal.fire(res.title,res.content,'error');
                }
            }).fail(function(res){
                var res = $.parseJSON(res.responseText);
                var str = "กรุณาถ่ายรูปหน้าจอนี้ให้กับเจ้าหน้าที่\n\r"+res.message+"\n\r"+res.exception+"\n\r"+res.file+" Line : "+res.line;
                Swal.fire("โอ๊ะโอ! ขอโทษด้วยมีบางอย่างผิดพลาด",str,'error');
            });
        } else {
        }
    });

});

$('#FormAdd').validate({
    errorElement: 'div',
    errorClass: 'invalid-feedback',
    focusInvalid: false,
    rules: {

        key_permission: {
            required: true,
        },

    },
    messages: {

        key_permission: {
            required: 'กรุณาระบบ',
        },

    },
    highlight: function (e) {
        validate_highlight(e);
    },
    success: function (e) {
        validate_success(e);
    },

    errorPlacement: function (error, element) {
        validate_errorplacement(error, element);
    },
    submitHandler: function (form) {
        var btn = $(form).find('[type="submit"]');
        loadingButton(btn);
        $.ajax({
            method : "POST",
            url : url_gb+"/admin/Permission",
            dataType : 'json',
            data : $(form).serialize()
        }).done(function(rec){
            resetButton(btn)
            if(rec.status==1){
                TableList.api().ajax.reload();
                resetFormCustom(form);
                Swal.fire(rec.title,rec.content,"success");
                $('#ModalAdd').modal('hide');
            }else{
                Swal.fire(rec.title,rec.content,"error");
            }
        }).fail(function(){
            Swal.fire("system.system_alert","system.system_error","error");
            resetButton(btn)
        });
    },
    invalidHandler: function (form) {

    }
});


$('#FormEdit').validate({
    errorElement: 'div',
    errorClass: 'invalid-feedback',
    focusInvalid: false,
    rules: {

        key_permission: {
            required: true,
        },

    },
    messages: {

        key_permission: {
            required: 'กรุณาระบบ',
        },

    },
    highlight: function (e) {
        validate_highlight(e);
    },
    success: function (e) {
        validate_success(e);
    },

    errorPlacement: function (error, element) {
        validate_errorplacement(error, element);
    },
    submitHandler: function (form) {

        var btn = $(form).find('[type="submit"]');
        var id = $('#edit_id').val();
        loadingButton(btn)
        $.ajax({
            method : "PUT",
            url : url_gb+"/admin/Permission/"+id,
            dataType : 'json',
            data : $(form).serialize()
        }).done(function(rec){
            resetButton(btn)
            if(rec.status==1){
                TableList.api().ajax.reload();
                resetFormCustom(form);
                Swal.fire(rec.title,rec.content,"success");
                $('#ModalEdit').modal('hide');
            }else{
                Swal.fire(rec.title,rec.content,"error");
            }
        }).fail(function(){
            Swal.fire("system.system_alert","system.system_error","error");
            resetButton(btn)
        });
    },
    invalidHandler: function (form) {

    }
});



</script>
@endsection
