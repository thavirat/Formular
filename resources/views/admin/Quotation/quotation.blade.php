@extends('admin.layouts.default')

@section('title', $currentMenu->title)

@section('css')
<style>
    /* ปรับแต่งหัวตารางให้ดูโมเดิร์น */
    #tableQuotation thead th {
        background-color: #f8fafc;
        color: #475569;
        font-weight: 600;
        vertical-align: middle;
        border-bottom: 2px solid #e2e8f0;
        text-transform: none;
    }

    /* ปรับแต่งเนื้อหาตาราง */
    #tableQuotation tbody td {
        vertical-align: middle;
        padding: 12px 8px;
        color: #1e293b;
    }

    /* ตกแต่งเลขที่เอกสาร */
    .doc-no {
        font-family: 'Monaco', 'Consolas', monospace;
        font-weight: bold;
        color: #0284c7;
        background: #f0f9ff;
        padding: 4px 8px;
        border-radius: 4px;
    }

    /* ตกแต่งยอดเงินรวม */
    .total-amount {
        font-weight: bold;
        color: #0f172a;
    }

    /* แถวสลับสีและเอฟเฟกต์ Hover */
    #tableQuotation tbody tr:hover {
        background-color: #f1f5f9 !important;
        transition: all 0.2s ease;
    }

    /* จัดการปุ่ม Action ให้ดูสะอาดตา */
    .btn-action {
        border-radius: 6px;
        margin: 0 2px;
        width: 32px;
        height: 32px;
        padding: 0;
        line-height: 32px;
        display: inline-block;
    }
</style>
@endsection

@section('body')
<div class="page-content container container-plus">
    <div class="page-header mb-2 pb-2 flex-column flex-sm-row align-items-start align-items-sm-center py-25 px-1">
        <h1 class="page-title text-primary-d2 text-140">{{ $currentMenu->title }} </h1>
        <div class="page-tools mt-3 mt-sm-0 mb-sm-n1">
            @if( $my_menu_permission[$currentMenu->url]['c'] == 'T' )
                <a href="{{url('admin/'.$lang.'/Quotation/create')}}" class="btn btn-light-green btn-h-green btn-a-green border-0 radius-3 py-2 text-600 text-90 btn-add">
                    <span class="d-none d-sm-inline mr-1">
                        เพิ่มข้อมูล
                    </span>
                    <i class="fa fa-plus text-110 w-2 h-2"></i>
                </a>
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

                    <table id="tableQuotation" class="table table-border-x brc-secondary-l4 border-0 mb-0 w-100">
                        <thead class="text-dark-tp3 bgc-grey-l4 text-90 border-b-1 brc-transparent">
                            <tr>
                                <th class="text-center" width="5%" rowspan="2">{{__('No')}}</th>
                                <th rowspan="2">{{__('Document No')}}</th>
                                <th rowspan="2">{{__('Document Date')}}</th>
                                <th rowspan="2">{{__('Company Name')}}</th>
                                <th rowspan="2">{{__('Total')}}</th>
                                <th rowspan="2">{{__('Created By')}}</th>
                                <th class="text-center" colspan="3">{{__('Action')}}</th>
                            </tr>
                            <tr>
                                <th>{{__('View')}}</th>
                                <th>{{__('Edit')}}</th>
                                <th>{{__('Delete')}}</th>
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


@endsection

@push('scripts')
<script type="text/javascript">

    var tableQuotation = $('#tableQuotation').dataTable({
        "ajax": {
            "url": url_gb+"/admin/Quotation/Lists",
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
            {
                "data": "DT_RowIndex",
                "searchable": false,
                "orderable": false,
                "class": "text-center text-secondary"
            },
            {
                "data": "doc_no",
                "render": function(data) {
                    return '<span class="doc-no">' + data + '</span>';
                }
            },
            {
                "data": "doc_date",
                "render": function(data) {
                    return '<span class="text-80 text-grey-d1"><i class="far fa-calendar-alt mr-1"></i>' + data + '</span>';
                }
            },
            {
                "data": "company_name",
                "render": function(data) {
                    return '<div class="text-bold text-dark-m2">' + data + '</div>';
                }
            },
            {
                "data": "total",
                "class": "text-right",
                "render": function(data) {
                    // ใส่คอมม่าและทศนิยม 2 ตำแหน่ง
                    let val = parseFloat(data).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                    return '<span class="total-amount">' + val + '</span>';
                }
            },
            {
                "data": "created_by_name",
                "name": 'admin_users.name',
                "render": function(data) {
                    return '<span class="text-85"><i class="far fa-user mr-1 text-grey-l1"></i>' + data + '</span>';
                }
            },
            { "data": "btn-view", "searchable": false, "sortable": false, "class": "text-center" },
            { "data": "btn-edit", "searchable": false, "sortable": false, "class": "text-center" },
            { "data": "btn-delete", "searchable": false, "sortable": false, "class": "text-center" },
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
            url: url_gb+"/admin/Quotation",
            dataType : "json",
            data: form.serialize()
        }).done(function( res ) {
            resetButton(form.find('button[type=submit]'));
            if(res.status == 1){
                Swal.fire(res.title, res.content,'success');
                resetFormCustom(form);
                                                                                                                                                                                                                                                tableQuotation.api().ajax.reload();
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
        var id = $("#Quotation_edit_id").val();
        var form = $(this);
        loadingButton(form.find('button[type=submit]'));
        $.ajax({
            method: "PUT",
            url: url_gb+"/admin/Quotation/"+id,
            dataType : 'json',
            data: form.serialize()
            }).done(function( res ) {
                resetButton(form.find('button[type=submit]'));
                if(res.status == 1){
                    Swal.fire(res.title, res.content, 'success');
                    resetFormCustom(form);
                    tableQuotation.api().ajax.reload();
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
                    url: url_gb+"/admin/Quotation/"+id,
                    dataType : 'json',
                }).done(function( res ) {
                    if(res.status == 1){
                        Swal.fire(res.title, res.content,'success');
                        tableQuotation.api().ajax.reload();
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
        $("#Quotation_edit_id").val(id);
        var btn = $(this);
        loadingButton(btn);
        $.ajax({
            method: "GET",
            url: url_gb+"/admin/Quotation/"+id,
            dataType: 'json',
        }).done(function( res ) {
            resetButton(btn);

            $("#edit_customer_id").val(res.content.customer_id).trigger('change.select2');

            $("#edit_contact_name").val(res.content.contact_name);
            $("#edit_company_name").val(res.content.company_name);
            $("#edit_tax_id").val(res.content.tax_id);
            $("#edit_address").val(res.content.address);
            $("#edit_phone").val(res.content.phone);
            $("#edit_mobile").val(res.content.mobile);
            $("#edit_fax_no").val(res.content.fax_no);
            $("#edit_incoterm_id").val(res.content.incoterm_id).trigger('change.select2');

            $("#edit_currency_id").val(res.content.currency_id).trigger('change.select2');

            $("#edit_credit_payment_id").val(res.content.credit_payment_id).trigger('change.select2');

            $('#ModalEdit').modal('show');
        }).fail(function(res){
            ajaxFail(res , "");
        });
    });













 $("#add_customer_id").select2({
     placeholder: 'กรุณาเลือก',
     allowClear: true
 })
 $("#edit_customer_id").select2({
     placeholder: 'กรุณาเลือก',
     allowClear: true
 })
 $("#add_incoterm_id").select2({
     placeholder: 'กรุณาเลือก',
     allowClear: true
 })
 $("#edit_incoterm_id").select2({
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
 $("#add_credit_payment_id").select2({
     placeholder: 'กรุณาเลือก',
     allowClear: true
 })
 $("#edit_credit_payment_id").select2({
     placeholder: 'กรุณาเลือก',
     allowClear: true
 })

</script>
@endpush
