@extends('admin.layouts.default')

@section('title', $currentMenu->title)

@section('css')
    <style>
        #tableQuotation thead th {
            background-color: #f1f4f9 !important;
            color: #5a6a85 !important;
            text-transform: none !important;
            font-size: 0.9rem;
            padding: 15px 10px !important;
        }

        .comment-list-wrapper {
            max-height: 120px;
            overflow-y: auto;
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .comment-item {
            font-size: 0.85rem;
            color: #666;
            padding-bottom: 4px;
            list-style: none;
        }

        .table .form-control {
            font-size: 0.85rem;
            border-radius: 4px;
        }

        .btn-group-action .btn {
            margin: 0 1px;
            padding: 4px 8px;
        }

        .text-doc-no {
            font-family: 'Monaco', monospace;
            letter-spacing: 0.5px;
        }

        #tableQuotation tbody td {
            vertical-align: top !important;
            /* ชิดขอบบน */
            padding-top: 15px !important;
            /* เพิ่มระยะห่างด้านบนให้ดูไม่ติดเส้นเกินไป */
            padding-bottom: 15px !important;
        }

        /* ส่วนของเลขที่เอกสารให้ดูเด่นและสะอาด */
        .doc-info-wrapper {
            display: flex;
            flex-direction: column;
        }

        /* ส่วนของรายการคอมเมนต์ให้มีระยะห่างที่พอดี */
        .comment-list-wrapper {
            margin-top: 0;
            /* มั่นใจว่าชิดบน */
        }
    </style>
@endsection

@section('body')
    <div class="page-content container container-plus">
        <div class="page-header mb-2 pb-2 flex-column flex-sm-row align-items-start align-items-sm-center py-25 px-1">
            <h1 class="page-title text-primary-d2 text-140">{{ $currentMenu->title }} </h1>
            <div class="page-tools mt-3 mt-sm-0 mb-sm-n1">
                @if ($my_menu_permission[$currentMenu->url]['c'] == 'T')
                    <a href="{{ url('admin/' . $lang . '/Quotation/create') }}"
                        class="btn btn-light-green btn-h-green btn-a-green border-0 radius-3 py-2 text-600 text-90 btn-add">
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
                <div class="car dcard">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="filter_doc_date_start">{{ __('Filter Date Start') }} <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="filter_doc_date_start" class="form-control init-date"
                                            id="filter_doc_date_start" value="{{ date('Y-m-01') }}" readonly>
                                        <div class="input-group-append btn-clear-date" style="cursor: pointer;"
                                            data-target="#filter_doc_date_start">
                                            <div class="input-group-text text-danger-m1">
                                                <i class="fa fa-times"></i>
                                            </div>
                                        </div>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><i class="far fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="filter_doc_date_end">{{ __('Filter Date End') }} <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="filter_doc_date_end" class="form-control init-date"
                                            id="filter_doc_date_end" value="{{ date('Y-m-t') }}" readonly>

                                        <div class="input-group-append btn-clear-date" style="cursor: pointer;"
                                            data-target="#filter_doc_date_end">
                                            <div class="input-group-text text-danger-m1">
                                                <i class="fa fa-times"></i>
                                            </div>
                                        </div>

                                        <div class="input-group-append">
                                            <div class="input-group-text"><i class="far fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="filter_status">{{ __('Filter Status') }}</label>
                                    <select name="filter_status" id="filter_status" class="form-control">
                                        <option value="all">{{ __('All Status') }}</option>
                                        @foreach ($quotation_statuses as $status)
                                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-3">
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


                        <table id="tableQuotation"
                            class="table table-striped-primary table-borderless border-0 mb-0 w-100 table-hover">
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
                "url": url_gb + "/admin/Quotation/Lists",
                "type": "POST",
                "data": function(d) {
                    d.start_date = $('#filter_doc_date_start').val();
                    d.end_date = $('#filter_doc_date_end').val();
                    d.status_id = $('#filter_status').val();
                    d.admin_id = $('#filter_admin').val();

                }
            },
            "drawCallback": function(settings) {
                $('[data-toggle="tooltip"]').tooltip();
            },
            "columns": [{
                    "data": "DT_RowIndex",
                    "class": "text-center align-middle",
                    "searchable": false,
                    "orderable": false
                },
                {
                    "data": "doc_info",
                    "name": "doc_no",
                    "class": "align-middle"
                },
                {
                    "data": "customer_info",
                    "name": "company_name",
                    "class": "align-middle"
                },
                {
                    "data": "total",
                    "name": "total",
                    "class": "text-right align-middle text-600 text-success-d1",

                },
                {
                    "data": "status_name",
                    "name": "quotation_statuses.name",
                    "class": "text-right align-middle text-600 text-success-d1",

                },


                {
                    "data": "comment_box", // เปลี่ยนจาก comment_box เป็นชื่อที่ตรงกับ Controller
                    "searchable": false,
                    "sortable": false,
                    "class": "align-middle"
                },
                {
                    "data": "action_btns", // ใช้ปุ่มที่รวมกลุ่มแล้วจาก Controller
                    "searchable": false,
                    "sortable": false,
                    "class": "text-center align-middle"
                }
            ],
            "order": [
                [1, "desc"]
            ]
        });

        $('body').on('click', '.btn-add', function(data) {
            $('#ModalAdd').modal('show');
        });

        $('body').on('submit', '#FormAdd', function(e) {
            e.preventDefault();
            var form = $(this);
            loadingButton(form.find('button[type=submit]'));
            $.ajax({
                method: "POST",
                url: url_gb + "/admin/Quotation",
                dataType: "json",
                data: form.serialize()
            }).done(function(res) {
                resetButton(form.find('button[type=submit]'));
                if (res.status == 1) {
                    Swal.fire(res.title, res.content, 'success');
                    resetFormCustom(form);
                    tableQuotation.api().ajax.reload();
                    $('#ModalAdd').modal('hide');
                } else {
                    Swal.fire(res.title, res.content, 'error');
                }
            }).fail(function(res) {
                ajaxFail(res, form);
            });
        });

        $('body').on('submit', '#FormEdit', function(e) {
            e.preventDefault();
            var id = $("#Quotation_edit_id").val();
            var form = $(this);
            loadingButton(form.find('button[type=submit]'));
            $.ajax({
                method: "PUT",
                url: url_gb + "/admin/Quotation/" + id,
                dataType: 'json',
                data: form.serialize()
            }).done(function(res) {
                resetButton(form.find('button[type=submit]'));
                if (res.status == 1) {
                    Swal.fire(res.title, res.content, 'success');
                    resetFormCustom(form);
                    tableQuotation.api().ajax.reload();
                    $('#ModalEdit').modal('hide');
                } else {
                    Swal.fire(res.title, res.content, 'error');
                }
            }).fail(function(res) {
                ajaxFail(res, form);
            });
        });

        $('body').on('click', '.btn-delete', function() {
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
                        url: url_gb + "/admin/Quotation/" + id,
                        dataType: 'json',
                    }).done(function(res) {
                        if (res.status == 1) {
                            Swal.fire(res.title, res.content, 'success');
                            tableQuotation.api().ajax.reload();
                        } else {
                            Swal.fire(res.title, res.content, 'warning');
                        }
                    }).fail(function(res) {
                        ajaxFail(res, "");
                    });
                } else {
                    // Do something you want
                }
            });
        });

        $('body').on('click', '.btn-edit', function(data) {
            var id = $(this).data('id');
            $("#Quotation_edit_id").val(id);
            var btn = $(this);
            loadingButton(btn);
            $.ajax({
                method: "GET",
                url: url_gb + "/admin/Quotation/" + id,
                dataType: 'json',
            }).done(function(res) {
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
            }).fail(function(res) {
                ajaxFail(res, "");
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



        $('body').on('click', '.btn-save-comment', function() {
            var id = $(this).data('id'); // ID ของ Quotation
            var customer_id = $(this).data('customer-id');
            var btn = $(this);

            // ดึงค่าจาก textarea และ select โดยอ้างอิงจาก ID ที่เราทำไว้ใน Controller
            var detail = $('.comment-' + id).val();
            var channel_id = $('.channel-' + id).val();

            if (detail.trim() == "") {
                Swal.fire('แจ้งเตือน', 'กรุณากรอกรายละเอียดคอมเมนต์', 'warning');
                return false;
            }

            loadingButton(btn); // เปลี่ยนปุ่มเป็นสถานะกำลังโหลด

            $.ajax({
                method: "POST",
                url: url_gb + "/admin/Quotation/SaveComment", // สร้าง Route นี้รอไว้เลยครับ
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    quotation_id: id,
                    customer_id: customer_id,
                    detail: detail,
                    contact_channel_id: channel_id
                }
            }).done(function(res) {
                resetButton(btn);
                if (res.status == 1) {
                    tableQuotation.api().ajax.reload(null,
                        false); // false คือให้ค้างอยู่ที่หน้าเดิม (Pagination ไม่เด้งไปหน้า 1)
                    Swal.fire({
                        icon: 'success',
                        title: 'บันทึกสำเร็จ',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                } else {
                    Swal.fire('ผิดพลาด', res.content, 'error');
                }
            }).fail(function(res) {
                ajaxFail(res, "");
            });



        });


        $('body').on('click', '.btn-request-approval', function() {
            var id = $(this).data('id');
            var btn = $(this);

            Swal.fire({
                title: 'ยืนยันการส่งขออนุมัติ?',
                text: "เมื่อส่งแล้ว จะไม่สามารถแก้ไขข้อมูลได้ชั่วคราวจนกว่าจะได้รับการพิจารณา",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#8914cc', // สีม่วงให้เข้ากับปุ่ม
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'ยืนยันส่งข้อมูล',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    loadingButton(btn);
                    $.ajax({
                        method: "POST",
                        url: url_gb + "/admin/Quotation/RequestApproval",
                        data: {
                            id: id
                        }
                    }).done(function(res) {
                        resetButton(btn);
                        if (res.status == 1) {
                            Swal.fire('สำเร็จ', res.content, 'success');
                            tableQuotation.api().ajax.reload(null, false);
                        } else {
                            Swal.fire('ผิดพลาด', res.content, 'error');
                        }
                    }).fail(function(res) {
                        ajaxFail(res, "");
                    });
                }
            });
        });


        $('body').on('click', '.btn-approve', function() {
            var id = $(this).data('id');
            var btn = $(this);

            Swal.fire({
                title: 'ยืนยันการอนุมัติ?',
                text: "เมื่ออนุมัติแล้ว เอกสารนี้จะถือเป็นอันสิ้นสุด",
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#28a745', // สีเขียว Success
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'ยืนยันการอนุมัติ',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    loadingButton(btn);
                    $.ajax({
                        method: "POST",
                        url: url_gb + "/admin/Quotation/Approve",
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            id: id
                        }
                    }).done(function(res) {
                        resetButton(btn);
                        if (res.status == 1) {
                            Swal.fire('อนุมัติแล้ว', res.content, 'success');
                            tableQuotation.api().ajax.reload(null, false);
                        } else {
                            Swal.fire('ผิดพลาด', res.content, 'error');
                        }
                    }).fail(function(res) {
                        ajaxFail(res, "");
                    });
                }
            });
        });

        $('body').on('click', '#filter_button', function() {
            tableQuotation.api().ajax.reload();
        });
    </script>
@endpush
