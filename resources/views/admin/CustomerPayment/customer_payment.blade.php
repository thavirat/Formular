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
                 <a href="{{ url("admin/CustomerPayment/ExportPrint") }}" target="_blank" class="btn btn-light-warning btn-h-warning btn-a-warning border-0 radius-3 py-2 text-600 text-90">
                    <span class="d-none d-sm-inline mr-1">
                        Print
                    </span>
                    <i class="fas fa-print text-110 w-2 h-2"></i>
                </a>
            @endif
            @if( $my_menu_permission[$currentMenu->url]['ep'] == 'T' )
                <a href="{{ url("admin/CustomerPayment/ExportPDF") }}" target="_blank" class="btn btn-light-danger btn-h-danger btn-a-danger border-0 radius-3 py-2 text-600 text-90">
                    <span class="d-none d-sm-inline mr-1">
                        PDF
                    </span>
                    <i class="fas fa-file-pdf text-110 w-2 h-2"></i>
                </a>
            @endif
            @if( $my_menu_permission[$currentMenu->url]['ee'] == 'T' )
                <a href="{{ url("admin/CustomerPayment/ExportExcel") }}" target="_blank" class="btn btn-light-primary btn-h-primary btn-a-primary border-0 radius-3 py-2 text-600 text-90">
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

                    <table id="tableCustomerPayment" class="table table-border-x brc-secondary-l4 border-0 mb-0 w-100">
                        <thead class="text-dark-tp3 bgc-grey-l4 text-90 border-b-1 brc-transparent">
                            <tr>
                                <th class="text-center" width="5%">ลำดับ</th>
                                <th>Bank</th>
                                <th>Payment Method</th>
                                <th>Currency</th>
                                <th>Ref No</th>
                                <th>Payment Date</th>
                                <th>Remark</th>
                                <th>Amount</th>
                                <th>Amount Bath</th>
                                <th>Exchange Rate</th>
                                <th>Photo</th>
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

                                <label for="add_bank_account_id">Bank</label>
                                <select name="bank_account_id" id="add_bank_account_id" class="form-control  autofocus"  >
                                    <option value="">เลือกกรุณาเลือก</option>
                                    @foreach($BankAccounts as $BankAccount)
                                    <option value="{{ $BankAccount->id }}">{{ $BankAccount->account_no }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                            <div class="col-md-6 col-sm-12">
                            <div class="form-group">

                                <label for="add_payment_method_id">Payment Method</label>
                                <select name="payment_method_id" id="add_payment_method_id" class="form-control  "  >
                                    <option value="">เลือกกรุณาเลือก</option>
                                    @foreach($PaymentMethods as $PaymentMethod)
                                    <option value="{{ $PaymentMethod->id }}">{{ $PaymentMethod->name_en }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                            <div class="col-md-6 col-sm-12">
                            <div class="form-group">

                                <label for="add_currency_id">Currency</label>
                                <select name="currency_id" id="add_currency_id" class="form-control  "  >
                                    <option value="">เลือกกรุณาเลือก</option>
                                    @foreach($Currencies as $Currency)
                                    <option value="{{ $Currency->id }}">{{ $Currency->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="add_reference_no">Ref No</label>
                                    <input type="text" name="reference_no" id="add_reference_no" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="add_payment_date">Payment Date</label>
                                <div class="input-group">
                                    <input type="text" name="payment_date" class="form-control init-date" id="add_payment_date" readonly>
                                    <div class="input-group-addon input-group-append remove_date_time">
                                        <div class="input-group-text">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                    <div class="input-group-addon input-group-append">
                                        <div class="input-group-text">
                                            <i class="far fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @include('admin.CustomerPayment.partials.payment_time_fields', ['hourId' => 'add_payment_time_hour', 'minuteId' => 'add_payment_time_minute'])
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="add_payment_note">Remark</label>
                                    <input type="text" name="payment_note" id="add_payment_note" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="add_amount">Amount</label>
                                    <input type="text" name="amount" id="add_amount" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="add_amount_bath">Amount Bath</label>
                                    <input type="text" name="amount_bath" id="add_amount_bath" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="add_exchange_rate">Exchange Rate</label>
                                    <input type="text" name="exchange_rate" id="add_exchange_rate" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label for="add_photo">Photo</label>
                                    <div id="orak_add_photo">
                                        <div id="add_photo" orakuploader="on"></div>
                                    </div>
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
                    <input type="hidden" id="CustomerPayment_edit_id">
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

                                <label for="edit_bank_account_id">Bank</label>
                                <select name="bank_account_id" id="edit_bank_account_id" class="form-control  "  >
                                    <option value="">เลือกกรุณาเลือก</option>
                                    @foreach($BankAccounts as $BankAccount)
                                    <option value="{{ $BankAccount->id }}">{{ $BankAccount->account_no }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                            <div class="col-md-6 col-sm-12">
                            <div class="form-group">

                                <label for="edit_payment_method_id">Payment Method</label>
                                <select name="payment_method_id" id="edit_payment_method_id" class="form-control  "  >
                                    <option value="">เลือกกรุณาเลือก</option>
                                    @foreach($PaymentMethods as $PaymentMethod)
                                    <option value="{{ $PaymentMethod->id }}">{{ $PaymentMethod->name_en }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                            <div class="col-md-6 col-sm-12">
                            <div class="form-group">

                                <label for="edit_currency_id">Currency</label>
                                <select name="currency_id" id="edit_currency_id" class="form-control  "  >
                                    <option value="">เลือกกรุณาเลือก</option>
                                    @foreach($Currencies as $Currency)
                                    <option value="{{ $Currency->id }}">{{ $Currency->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_reference_no">Ref No</label>
                                    <input type="text" name="reference_no" id="edit_reference_no" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="edit_payment_date">Payment Date</label>
                                <div class="input-group">
                                    <input type="text" name="payment_date" class="form-control init-date" id="edit_payment_date" readonly>
                                    <div class="input-group-addon input-group-append remove_date_time">
                                        <div class="input-group-text">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                    <div class="input-group-addon input-group-append">
                                        <div class="input-group-text">
                                            <i class="far fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @include('admin.CustomerPayment.partials.payment_time_fields', ['hourId' => 'edit_payment_time_hour', 'minuteId' => 'edit_payment_time_minute'])
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_payment_note">Remark</label>
                                    <input type="text" name="payment_note" id="edit_payment_note" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_amount">Amount</label>
                                    <input type="text" name="amount" id="edit_amount" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_amount_bath">Amount Bath</label>
                                    <input type="text" name="amount_bath" id="edit_amount_bath" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_exchange_rate">Exchange Rate</label>
                                    <input type="text" name="exchange_rate" id="edit_exchange_rate" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_photo">Photo</label>
                                    <div id="orak_edit_photo">
                                        <div id="edit_photo" orakuploader="on"></div>
                                    </div>
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

    var tableCustomerPayment = $('#tableCustomerPayment').dataTable({

        "ajax": {
            "url": url_gb+"/admin/CustomerPayment/Lists",
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
            {"data": "pi_id", "name": 'pi_id'},
            {"data": "bank_account_id", "name": 'bank_account_id'},
            {"data": "payment_method_id", "name": 'payment_method_id'},
            {"data": "currency_id", "name": 'currency_id'},
            {"data": "reference_no", "name": 'reference_no'},
            {"data": "payment_date", "name": 'payment_date'},
            {"data": "payment_note", "name": 'payment_note'},
            {"data": "amount", "name": 'amount'},
            {"data": "amount_bath", "name": 'amount_bath'},
            {"data": "exchange_rate", "name": 'exchange_rate'},
            {"data": "photo", "name": 'photo'},
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
        $('#add_payment_time_hour').val(0);
        $('#add_payment_time_minute').val(0);
        $('#ModalAdd').modal('show');
    });

    $('body').on('submit', '#FormAdd', function(e){
        e.preventDefault();
        var form = $(this);
        loadingButton(form.find('button[type=submit]'));
        $.ajax({
            method: "POST",
            url: url_gb+"/admin/CustomerPayment",
            dataType : "json",
            data: form.serialize()
        }).done(function( res ) {
            resetButton(form.find('button[type=submit]'));
            if(res.status == 1){
                Swal.fire(res.title, res.content,'success');
                resetFormCustom(form);
                                                                                                                                                                                                tableCustomerPayment.api().ajax.reload();
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
        var id = $("#CustomerPayment_edit_id").val();
        var form = $(this);
        loadingButton(form.find('button[type=submit]'));
        $.ajax({
            method: "PUT",
            url: url_gb+"/admin/CustomerPayment/"+id,
            dataType : 'json',
            data: form.serialize()
            }).done(function( res ) {
                resetButton(form.find('button[type=submit]'));
                if(res.status == 1){
                    Swal.fire(res.title, res.content, 'success');
                    resetFormCustom(form);
                    tableCustomerPayment.api().ajax.reload();
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
                    url: url_gb+"/admin/CustomerPayment/"+id,
                    dataType : 'json',
                }).done(function( res ) {
                    if(res.status == 1){
                        Swal.fire(res.title, res.content,'success');
                        tableCustomerPayment.api().ajax.reload();
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
        $("#CustomerPayment_edit_id").val(id);
        var btn = $(this);
        loadingButton(btn);
        $.ajax({
            method: "GET",
            url: url_gb+"/admin/CustomerPayment/"+id,
            dataType: 'json',
        }).done(function( res ) {
            resetButton(btn);
                                    $("#edit_bank_account_id").val(res.content.bank_account_id).trigger('change.select2');
                                                $("#edit_payment_method_id").val(res.content.payment_method_id).trigger('change.select2');
                                                $("#edit_currency_id").val(res.content.currency_id).trigger('change.select2');
                                                $("#edit_reference_no").val(res.content.reference_no);
                        if (res.content.payment_date) {
                            var pdm = String(res.content.payment_date).replace('T', ' ');
                            if (pdm.indexOf(' ') !== -1) {
                                var parts = pdm.split(' ');
                                $("#edit_payment_date").val(convertDateToThai(parts[0]));
                                var tm = parts[1].split(':');
                                $("#edit_payment_time_hour").val(parseInt(tm[0], 10) || 0);
                                $("#edit_payment_time_minute").val(parseInt(tm[1], 10) || 0);
                            } else {
                                $("#edit_payment_date").val(convertDateToThai(res.content.payment_date));
                                $("#edit_payment_time_hour").val(0);
                                $("#edit_payment_time_minute").val(0);
                            }
                        }
                                                $("#edit_payment_note").val(res.content.payment_note);                                                $("#edit_amount").val(res.content.amount);                                                $("#edit_amount_bath").val(res.content.amount_bath);                                                $("#edit_exchange_rate").val(res.content.exchange_rate);                                                $('#edit_photo').closest('#orak_edit_photo').html('<div id="edit_photo" orakuploader="on"></div>');
            if(res.content.photo){
                $('#edit_photo').orakuploader({
                    orakuploader_field_name         : 'photo',
                    orakuploader_maximum_uploads    : 0,
                    orakuploader_attach_images      : jQuery.parseJSON(res.content.photo),
                    orakuploader_finished           : function(){
                        
                    }
                });
            }else{
                $('#edit_photo').orakuploader({
                    orakuploader_field_name         : 'photo',
                    orakuploader_finished           : function(){
                        
                    }
                });
            }                        
            $('#ModalEdit').modal('show');
        }).fail(function(res){
            ajaxFail(res , "");
        });
    });

$('#add_photo').orakuploader({
    orakuploader_field_name         : 'photo',
    orakuploader_finished           : function(){
        
    }
});
 $("#add_bank_account_id").select2({
     placeholder: 'กรุณาเลือก',
     allowClear: true
 })
 $("#edit_bank_account_id").select2({
     placeholder: 'กรุณาเลือก',
     allowClear: true
 })
 $("#add_payment_method_id").select2({
     placeholder: 'กรุณาเลือก',
     allowClear: true
 })
 $("#edit_payment_method_id").select2({
     placeholder: 'กรุณาเลือก',
     allowClear: true
 })
 $("#add_currency_id").select2({
     placeholder: 'กรุณาเลือก',
     allowClear: true
 })
 $("#edit_currency_id").select2({
     placeholder: 'กรุณาเลือก',
     allowClear: true
 })

</script>
@endpush
