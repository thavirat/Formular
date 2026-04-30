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
                 <a href="{{ url("admin/BankAccount/ExportPrint") }}" target="_blank" class="btn btn-light-warning btn-h-warning btn-a-warning border-0 radius-3 py-2 text-600 text-90">
                    <span class="d-none d-sm-inline mr-1">
                        Print
                    </span>
                    <i class="fas fa-print text-110 w-2 h-2"></i>
                </a>
            @endif
            @if( $my_menu_permission[$currentMenu->url]['ep'] == 'T' )
                <a href="{{ url("admin/BankAccount/ExportPDF") }}" target="_blank" class="btn btn-light-danger btn-h-danger btn-a-danger border-0 radius-3 py-2 text-600 text-90">
                    <span class="d-none d-sm-inline mr-1">
                        PDF
                    </span>
                    <i class="fas fa-file-pdf text-110 w-2 h-2"></i>
                </a>
            @endif
            @if( $my_menu_permission[$currentMenu->url]['ee'] == 'T' )
                <a href="{{ url("admin/BankAccount/ExportExcel") }}" target="_blank" class="btn btn-light-primary btn-h-primary btn-a-primary border-0 radius-3 py-2 text-600 text-90">
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

                    <table id="tableBankAccount" class="table table-border-x brc-secondary-l4 border-0 mb-0 w-100">
                        <thead class="text-dark-tp3 bgc-grey-l4 text-90 border-b-1 brc-transparent">
                            <tr>
                                <th class="text-center" width="5%">ลำดับ</th>
                                <th>Bank</th>
                                <th>Account No</th>
                                <th>Account Name</th>
                                <th>Account Type</th>
                                <th>Branch</th>
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

                                <label for="add_bank_id">Bank</label>
                                <select name="bank_id" id="add_bank_id" class="form-control  autofocus"  >
                                    <option value="">เลือกกรุณาเลือก</option>
                                    @foreach($Banks as $Bank)
                                    <option value="{{ $Bank->id }}">{{ $Bank->name_en }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="add_account_no">Account No</label>
                                    <input type="text" name="account_no" id="add_account_no" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="add_account_name">Account Name</label>
                                    <input type="text" name="account_name" id="add_account_name" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="add_account_type">Account Type</label>
                                    <input type="text" name="account_type" id="add_account_type" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="add_account_branch">Branch</label>
                                    <input type="text" name="account_branch" id="add_account_branch" class="form-control " >
                                </div>
                            </div>

                        </div>
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
                    <input type="hidden" id="BankAccount_edit_id">
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

                                <label for="edit_bank_id">Bank</label>
                                <select name="bank_id" id="edit_bank_id" class="form-control  "  >
                                    <option value="">เลือกกรุณาเลือก</option>
                                    @foreach($Banks as $Bank)
                                    <option value="{{ $Bank->id }}">{{ $Bank->name_en }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_account_no">Account No</label>
                                    <input type="text" name="account_no" id="edit_account_no" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_account_name">Account Name</label>
                                    <input type="text" name="account_name" id="edit_account_name" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_account_type">Account Type</label>
                                    <input type="text" name="account_type" id="edit_account_type" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_account_branch">Branch</label>
                                    <input type="text" name="account_branch" id="edit_account_branch" class="form-control " >
                                </div>
                            </div>

                        </div>
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

    var tableBankAccount = $('#tableBankAccount').dataTable({

        "ajax": {
            "url": url_gb+"/admin/BankAccount/Lists",
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
            {"data": "bank_name", "name": 'bank_name'},
            {"data": "account_no", "name": 'account_no'},
            {"data": "account_name", "name": 'account_name'},
            {"data": "account_type", "name": 'account_type'},
            {"data": "account_branch", "name": 'account_branch'},
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
            url: url_gb+"/admin/BankAccount",
            dataType : "json",
            data: form.serialize()
        }).done(function( res ) {
            resetButton(form.find('button[type=submit]'));
            if(res.status == 1){
                Swal.fire(res.title, res.content,'success');
                resetFormCustom(form);
                                                                                                tableBankAccount.api().ajax.reload();
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
        var id = $("#BankAccount_edit_id").val();
        var form = $(this);
        loadingButton(form.find('button[type=submit]'));
        $.ajax({
            method: "PUT",
            url: url_gb+"/admin/BankAccount/"+id,
            dataType : 'json',
            data: form.serialize()
            }).done(function( res ) {
                resetButton(form.find('button[type=submit]'));
                if(res.status == 1){
                    Swal.fire(res.title, res.content, 'success');
                    resetFormCustom(form);
                    tableBankAccount.api().ajax.reload();
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
                    url: url_gb+"/admin/BankAccount/"+id,
                    dataType : 'json',
                }).done(function( res ) {
                    if(res.status == 1){
                        Swal.fire(res.title, res.content,'success');
                        tableBankAccount.api().ajax.reload();
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
        $("#BankAccount_edit_id").val(id);
        var btn = $(this);
        loadingButton(btn);
        $.ajax({
            method: "GET",
            url: url_gb+"/admin/BankAccount/"+id,
            dataType: 'json',
        }).done(function( res ) {
            resetButton(btn);
                                    $("#edit_bank_id").val(res.content.bank_id).trigger('change.select2');
                                                $("#edit_account_no").val(res.content.account_no);                                                $("#edit_account_name").val(res.content.account_name);                                                $("#edit_account_type").val(res.content.account_type);                                                $("#edit_account_branch").val(res.content.account_branch);                        
            $('#ModalEdit').modal('show');
        }).fail(function(res){
            ajaxFail(res , "");
        });
    });


 $("#add_bank_id").select2({
     placeholder: 'กรุณาเลือก',
     allowClear: true
 })
 $("#edit_bank_id").select2({
     placeholder: 'กรุณาเลือก',
     allowClear: true
 })

</script>
@endpush
