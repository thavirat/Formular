@extends('admin.layouts.default')

@section('title', $currentMenu->title)

@section('css')
<style>
    #{{ $listTableId }} thead th {
        background-color: #f1f4f9 !important;
        color: #5a6a85 !important;
        text-transform: none !important;
        font-size: 0.9rem;
        padding: 15px 10px !important;
    }
    #{{ $listTableId }} tbody td {
        vertical-align: top !important;
        padding-top: 15px !important;
        padding-bottom: 15px !important;
    }
    #{{ $listTableId }} .btn-group .btn {
        margin: 0 1px;
        padding: 4px 8px;
    }
    .comment-list-wrapper {
        max-height: 120px;
        overflow-y: auto;
        margin-bottom: 10px;
        border-bottom: 1px solid #eee;
    }
</style>
@endsection

@section('body')
<div class="page-content container container-plus">
    <div class="page-header mb-2 pb-2 flex-column flex-sm-row align-items-start align-items-sm-center py-25 px-1">
        <h1 class="page-title text-primary-d2 text-140">{{ $currentMenu->title }}</h1>
        <div class="page-tools mt-3 mt-sm-0 mb-sm-n1">
            <span class="badge badge-info px-3 py-2">แสดงเฉพาะ PI ที่อนุมัติแล้ว</span>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card dcard">
                <div class="card-body">
                    <div class="row">
                        <div class="col-2">
                            <div class="form-group">
                                <label for="filter_doc_date_start">{{ __('Filter Date Start') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="filter_doc_date_start" class="form-control init-date" id="filter_doc_date_start" value="" readonly>
                                    <div class="input-group-append btn-clear-date" style="cursor: pointer;" data-target="#filter_doc_date_start">
                                        <div class="input-group-text text-danger-m1"><i class="fa fa-times"></i></div>
                                    </div>
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="far fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label for="filter_doc_date_end">{{ __('Filter Date End') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="filter_doc_date_end" class="form-control init-date" id="filter_doc_date_end" value="{{ date('Y-m-t') }}" readonly>
                                    <div class="input-group-append btn-clear-date" style="cursor: pointer;" data-target="#filter_doc_date_end">
                                        <div class="input-group-text text-danger-m1"><i class="fa fa-times"></i></div>
                                    </div>
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="far fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label for="filter_admin">{{ __('Filter Admin') }}</label>
                                <select name="filter_admin" id="filter_admin" class="form-control">
                                    <option value="all">{{ __('All Admin') }}</option>
                                    @foreach ($admins as $admin)
                                        <option value="{{ $admin->id }}">{{ $admin->nickname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label for="filter_customer">{{ __('Customer') }}</label>
                                <select name="filter_customer" id="filter_customer" class="form-control">
                                    <option value="all">{{ __('All Customer') }}</option>
                                    @foreach ($Customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <div class="form-group">
                                <label for="filter_button" style="visibility: hidden;">_</label>
                                <button type="button" class="btn btn-primary" id="filter_button">
                                    <i class="fa fa-search"></i> {{ __('Filter') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card dcard">
                <div class="card-body p-0">
                    <table id="{{ $listTableId }}" class="table table-striped-primary table-borderless border-0 mb-0 w-100 table-hover">
                        <thead>
                            <tr class="bgc-primary-d1 text-white">
                                <th class="text-center" width="5%">#</th>
                                <th>เลขที่ / วันที่เอกสาร</th>
                                <th>ลูกค้า / ผู้จัดทำ</th>
                                <th class="text-right">ยอดรวม</th>
                                <th>สถานะ</th>
                                <th width="300px">บันทึกติดตามงาน</th>
                                <th class="text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="align-middle"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    var approvedPiTable = $('#{{ $listTableId }}').dataTable({
        "ajax": {
            "url": url_gb + "/admin/{{ $listsPath }}",
            "type": "POST",
            "data": function (d) {
                d.start_date = $('#filter_doc_date_start').val();
                d.end_date = $('#filter_doc_date_end').val();
                d.admin_id = $('#filter_admin').val();
                d.customer_id = $('#filter_customer').val();
            }
        },
        "drawCallback": function() {
            $('[data-toggle="tooltip"]').tooltip();
        },
        "responsive": false,
        "columns": [
            {"data": "DT_RowIndex", 'searchable': false, 'orderable': false, "class": "text-center"},
            {"data": "doc_info", "name": 'doc_no', "className": "align-middle"},
            {"data": "customer_info", "name": 'company_name', "className": "align-middle"},
            {"data": "total", "name": 'total', "className": "text-right align-middle text-600 text-success-d1"},
            {"data": "status_name", "name": 'proforma_invoice_statuses.name', "className": "text-right align-middle text-600 text-success-d1"},
            {"data": "comment_box", "searchable": false, "sortable": false, "className": "align-middle"},
            {"data": "action_btns", "searchable": false, "sortable": false, "className": "text-center align-middle"}
        ],
        "order": [[1, "desc"]]
    });

    $('body').on('click', '#filter_button', function() {
        approvedPiTable.api().ajax.reload();
    });

    $("#filter_admin").select2({ placeholder: 'กรุณาเลือกผู้จัดทำ', allowClear: true });
    $("#filter_customer").select2({ placeholder: 'กรุณาเลือกลูกค้า', allowClear: true });

    $('body').on('click', '.btn-save-comment', function() {
        var id = $(this).data('id');
        var customer_id = $(this).data('customer-id');
        var btn = $(this);
        var detail = $('.comment-' + id).val();
        var channel_id = $('.channel-' + id).val();
        if (detail.trim() == "") {
            Swal.fire('แจ้งเตือน', 'กรุณากรอกรายละเอียดคอมเมนต์', 'warning');
            return false;
        }
        loadingButton(btn);
        $.ajax({
            method: "POST",
            url: url_gb + "/admin/ProformaInvoice/SaveComment",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                pi_id: id,
                customer_id: customer_id,
                detail: detail,
                contact_channel_id: channel_id
            }
        }).done(function(res) {
            resetButton(btn);
            if (res.status == 1) {
                approvedPiTable.api().ajax.reload(null, false);
                Swal.fire({ icon: 'success', title: 'บันทึกสำเร็จ', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
            } else {
                Swal.fire('ผิดพลาด', res.content, 'error');
            }
        }).fail(function(res) {
            ajaxFail(res, "");
        });
    });

    $('body').on('click', '.btn-delete-pi', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'คุณต้องการลบข้อมูลหรือไม่ ?',
            text: "ข้อมูลที่ถูกลบไปแล้วจะไม่สามารถนำกลับมาได้",
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
                    url: url_gb + "/admin/ProformaInvoice/" + id,
                    dataType: "json",
                }).done(function(res) {
                    if (res.status == 1) {
                        Swal.fire(res.title, res.content, 'success');
                        approvedPiTable.api().ajax.reload();
                    } else {
                        Swal.fire(res.title, res.content, 'warning');
                    }
                }).fail(function(res) {
                    ajaxFail(res, "");
                });
            }
        });
    });
</script>
@endpush
