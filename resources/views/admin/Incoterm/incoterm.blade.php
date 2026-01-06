@extends('admin.layouts.default')

@section('title', $currentMenu->title)

@push('css')
	
@endpush

@section('body')
<div class="page-content container container-plus">
    <div class="page-header mb-2 pb-2 flex-column flex-sm-row align-items-start align-items-sm-center py-25 px-1">
        <h1 class="page-title text-primary-d2 text-140">{{ $currentMenu->title }} </h1>
        <div class="page-tools mt-3 mt-sm-0 mb-sm-n1">
             @if( $my_menu_permission[$currentMenu->url]['p'] == 'T' )
                 <a href="{{ url("admin/Incoterm/ExportPrint") }}" target="_blank" class="btn btn-light-warning btn-h-warning btn-a-warning border-0 radius-3 py-2 text-600 text-90">
                    <span class="d-none d-sm-inline mr-1">
                        Print
                    </span>
                    <i class="fas fa-print text-110 w-2 h-2"></i>
                </a>
            @endif
            @if( $my_menu_permission[$currentMenu->url]['ep'] == 'T' )
                <a href="{{ url("admin/Incoterm/ExportPDF") }}" target="_blank" class="btn btn-light-danger btn-h-danger btn-a-danger border-0 radius-3 py-2 text-600 text-90">
                    <span class="d-none d-sm-inline mr-1">
                        PDF
                    </span>
                    <i class="fas fa-file-pdf text-110 w-2 h-2"></i>
                </a>
            @endif
            @if( $my_menu_permission[$currentMenu->url]['ee'] == 'T' )
                <a href="{{ url("admin/Incoterm/ExportExcel") }}" target="_blank" class="btn btn-light-primary btn-h-primary btn-a-primary border-0 radius-3 py-2 text-600 text-90">
                    <span class="d-none d-sm-inline mr-1">
                        Excel
                    </span>
                    <i class="fas fa-file-excel text-110 w-2 h-2"></i>
                </a>
            @endif
            @if( $my_menu_permission[$currentMenu->url]['c'] == 'T' )
                <button type="button" class="btn btn-light-green btn-h-green btn-a-green border-0 radius-3 py-2 text-600 text-90 btn-add">
                    <span class="d-none d-sm-inline mr-1">
                        เพิ่มข้อมูล
                    </span>
                    <i class="fa fa-plus text-110 w-2 h-2"></i>
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

                    <table id="tableIncoterm" class="table table-border-x brc-secondary-l4 border-0 mb-0 w-100">
                        <thead class="text-dark-tp3 bgc-grey-l4 text-90 border-b-1 brc-transparent">
                            <tr>
                                <th class="text-center" width="5%">ลำดับ</th>
                                <th>รหัส</th>
                                <th>รายละเอียด</th>
                                <th class="text-center">#</th>
                            </tr>
                        </thead>

                        <tbody class="mt-1">
                        </tbody>
                    </table>


                </div><!-- /.card-body -->
            </div><!-- /.card -->
        </div><!-- /.col -->
    </div>

</div>


    <!-- Modal Add -->
    <div class="modal fade modal-lg" id="ModalAdd" role="dialog" aria-labelledby="ModalAddLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="FormAdd" data-parsley-validate="true">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary-d3" id="ModalAddLabel">
                            เพิ่มข้อมูล{{ $currentMenu->title }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="add_code">รหัส</label>
                                    <input type="text" name="code" id="add_code" class="form-control autofocus" >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="add_description">รายละเอียด</label>
                                <textarea name="description" id="add_description" rows="5" class="form-control " ></textarea>
                            </div>
                        </div>                        </div>
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
    <div class="modal fade modal-lg" id="ModalEdit" role="dialog" aria-labelledby="ModalEditLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="FormEdit" data-parsley-validate="true">
                    <input type="hidden" id="Incoterm_edit_id">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary-d3" id="ModalEditLabel">
                            แก้ไขข้อมูล{{ $currentMenu->title }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_code">รหัส</label>
                                    <input type="text" name="code" id="edit_code" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="edit_description">รายละเอียด</label>
                                <textarea name="description" id="edit_description" rows="5" class="form-control " ></textarea>
                            </div>
                        </div>                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary px-4" data-dismiss="modal"> <i class="fa fa-window-close"></i> ปิด </button>
                        <button type="submit" class="btn btn-primary"> <i class="fa fa-save"></i> บันทึก </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script type="text/javascript">

    var tableIncoterm = $('#tableIncoterm').dataTable({
        scrollY : height-500,
        scrollX : true,
        "ajax": {
            "url": url_gb+"/admin/Incoterm/Lists",
            "type": "POST",
            "data": function ( d ) {
                // d.status = "A";
                // d.custom = $('#myInput').val();
                // etc
            }
        },
        "drawCallback": function( settings ) {
            $('[data-toggle="tooltip"]').tooltip();
        },
        "responsive": false,
        "columns": [
            {"data": "DT_RowIndex", 'searchable': false, 'orderable': false, "class": "text-center"},
            {"data": "code", "name": 'code'},
            {"data": "description", "name": 'description'},
            {
                "data": "action" , 
                "name": "action", 
                "searchable": false, 
                "sortable": false,
                "class": "text-center"
            },
        ]
    });

    $('body').on('click','.btn-add',function(data){
        $('#ModalAdd').modal('show');
    });

    $('body').on('submit', '#FormAdd', function(e){
        e.preventDefault();
        var form = $(this);
        loadingButton(form.find('button[type=submit]'));
        $.ajax({
            method: "POST",
            url: url_gb+"/admin/Incoterm",
            dataType : "json",
            data: form.serialize()
        }).done(function( res ) {
            resetButton(form.find('button[type=submit]'));
            if(res.status == 1){
                Swal.fire(res.title, res.content,'success');
                resetFormCustom(form);
                tableIncoterm.api().ajax.reload();
                $('#ModalAdd').modal('hide');
            }else{
                Swal.fire(res.title, res.content,'error');
            }
        }).fail(function(res){
            ajaxFail(res , form);
        });
    });

    $('body').on('submit', '#FormEdit', function(e){
        e.preventDefault();
        var id = $("#Incoterm_edit_id").val();
        var form = $(this);
        loadingButton(form.find('button[type=submit]'));
        $.ajax({
            method: "PUT",
            url: url_gb+"/admin/Incoterm/"+id,
            dataType : 'json',
            data: form.serialize()
            }).done(function( res ) {
                resetButton(form.find('button[type=submit]'));
                if(res.status == 1){
                    Swal.fire(res.title, res.content, 'success');
                    resetFormCustom(form);
                    tableIncoterm.api().ajax.reload();
                    $('#ModalEdit').modal('hide');
                }else{
                    Swal.fire(res.title, res.content, 'error');
                }
            }).fail(function(res){
                ajaxFail(res , form);
            });
    });
    
    $('body').on('click', '.btn-delete', function(){
        var id = $(this).data('id');
        Swal.fire({
            title: 'คุณต้องการลบข้อมูลหรือไม่ ?',
            text: "ข้อมูลที่ถูกลบไปแล้วจะไม่สามารถนำกลับมาได้ไม่ว่ากรณีใดๆทั้งสิ้นการลบข้อมูลจะทำให้ข้อมูลอื่นๆที่ถูกนำไปใช้ ลบหายไปด้วย!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'ยกเลิก',
            confirmButtonText: 'ลบ'
        }).then((say) => {
            if (say.isConfirmed) {
                $.ajax({
                    method: "DELETE",
                    url: url_gb+"/admin/Incoterm/"+id,
                    dataType : 'json',
                }).done(function( res ) {
                    if(res.status == 1){
                        Swal.fire(res.title, res.content,'success');
                        tableIncoterm.api().ajax.reload();
                    }else{
                        Swal.fire(res.title, res.content,'warning');
                    }
                }).fail(function(res){
                    ajaxFail(res , "");
                });
            } else {
                // Do something you want
            }
        });
    });

    $('body').on('click','.btn-edit',function(data){
        var id = $(this).data('id');
        $("#Incoterm_edit_id").val(id);
        var btn = $(this);
        loadingButton(btn);
        $.ajax({
            method: "GET",
            url: url_gb+"/admin/Incoterm/"+id,
            dataType: 'json',
        }).done(function( res ) {
            resetButton(btn);
                         
            $("#edit_code").val(res.content.code);                                     
            $("#edit_description").val(res.content.description);                        
            $('#ModalEdit').modal('show');
        }).fail(function(res){
            ajaxFail(res , "");
        });
    });

 
 


</script>
@endpush
