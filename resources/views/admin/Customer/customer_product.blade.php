@extends('admin.Customer.customer_layout')
@section('css')
    <style>
        /* Reset ค่าจาก Layout หลักเพื่อให้ Modal กลับมาขนาดปกติ */
        .modal-dialog {
            display: block !important;
            width: auto !important;
            /* ยกเลิก width 100% จากเลย์เอาต์หลัก */
        }

        /* จัดกึ่งกลางหน้าจอแนวตั้ง */
        .modal-dialog-centered {
            display: flex !important;
            align-items: center !important;
            min-height: calc(100% - 3.5rem) !important;
        }

        /* ปรับแต่ง Select2 ให้เข้ากับ Input Group */
        .input-group>.select2-container--default {
            flex: 1 1 auto;
            width: 1% !important;
        }

        .input-group>.select2-container--default .select2-selection--single {
            height: 100% !important;
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
            border: 1px solid #d5d5d5 !important;
            display: flex;
            align-items: center;
        }

        /* ปรับแต่งส่วน Header ของ Card ให้ดูสะอาด */
        .card-header {
            background-color: #f8f9fa !important;
        }
    </style>
@endsection
@section('content')
    <div class="col-12 col-md-8 mt-3 mt-md-0">
        <div class="card dcard h-100">
            <div class="card-header bg-transparent border-b-1 brc-grey-l3 d-flex justify-content-between align-items-center">
                <h5 class="card-title text-110 text-primary-d2">
                    <i class="fa fa-users text-grey-m2 mr-1"></i> {{ __('List Customer Code') }}
                </h5>
                <button class="btn btn-sm btn-success btn-bold px-3  btn-add">
                    <i class="fa fa-plus mr-1"></i> {{ __('Add Customer Code') }}
                </button>
            </div>
            <div class="card-body p-0 bgc-white">
                <table id="tableCustomerCodeProduct" class="table table-striped-primary table-borderless border-0 mb-0 w-100">
                    <thead class="text-dark-tp3 bgc-grey-l4 text-90 border-b-1 brc-transparent">
                        <tr>
                            <th class="text-center" width="5%">ลำดับ</th>
                            <th>สินค้า</th>
                            <th>รหัสสินค้าเฉพาะลูกค้า</th>
                            <th class="text-center" width="15%">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle"></tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- Modal Add -->
    <div class="modal fade modal-lg" id="ModalAdd" role="dialog" aria-labelledby="ModalAddLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="FormAdd" data-parsley-validate="true">
                    <input type="hidden" name="customer_id" value="{{$customer->id}}">
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

                                    <label for="add_product_id">สินค้า</label>
                                    <select name="product_id" id="add_product_id" class="form-control  ">
                                        <option value="">เลือกกรุณาเลือก</option>
                                        @foreach ($Products as $Product)
                                            <option value="{{ $Product->id }}">{{ $Product->name_en }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="add_code">รหัสสินค้า</label>
                                    <input type="text" name="code" id="add_code" class="form-control ">
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary px-4" data-dismiss="modal"> <i
                                class="fa fa-window-close"></i> ปิด </button>
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
                    <input type="hidden" id="CustomerCodeProduct_edit_id">
                    <input type="hidden" name="customer_id" value="{{$customer->id}}">
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

                                    <label for="edit_product_id">สินค้า</label>
                                    <select name="product_id" id="edit_product_id" class="form-control  ">
                                        <option value="">เลือกกรุณาเลือก</option>
                                        @foreach ($Products as $Product)
                                            <option value="{{ $Product->id }}">{{ $Product->name_en }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_code">รหัสสินค้า</label>
                                    <input type="text" name="code" id="edit_code" class="form-control ">
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary px-4" data-dismiss="modal"> <i
                                class="fa fa-window-close"></i> ปิด </button>
                        <button type="submit" class="btn btn-primary"> <i class="fa fa-save"></i> บันทึก </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript">
        var tableCustomerCodeProduct = $('#tableCustomerCodeProduct').dataTable({

            "ajax": {
                "url": url_gb + "/admin/CustomerCodeProduct/Lists",
                "type": "POST",
                "data": function(d) {
                    d.customer_id = {{$customer->id}};
                    // d.custom = $('#myInput').val();
                    // etc
                }
            },
            "drawCallback": function(settings) {
                $('[data-toggle="tooltip"]').tooltip();
            },
            "responsive": false,
            "columns": [{
                    "data": "DT_RowIndex",
                    'searchable': false,
                    'orderable': false,
                    "class": "text-center"
                },
                {
                    "data": "product_display",
                    "name": 'product_id'
                },
                {
                    "data": "code",
                    "name": 'code'
                },
                {
                    "data": "action",
                    "name": "action",
                    "searchable": false,
                    "sortable": false,
                    "class": "text-center"
                },
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
                url: url_gb + "/admin/CustomerCodeProduct",
                dataType: "json",
                data: form.serialize()
            }).done(function(res) {
                resetButton(form.find('button[type=submit]'));
                if (res.status == 1) {
                    Swal.fire(res.title, res.content, 'success');
                    resetFormCustom(form);
                    tableCustomerCodeProduct.api().ajax.reload();
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
            var id = $("#CustomerCodeProduct_edit_id").val();
            var form = $(this);
            loadingButton(form.find('button[type=submit]'));
            $.ajax({
                method: "PUT",
                url: url_gb + "/admin/CustomerCodeProduct/" + id,
                dataType: 'json',
                data: form.serialize()
            }).done(function(res) {
                resetButton(form.find('button[type=submit]'));
                if (res.status == 1) {
                    Swal.fire(res.title, res.content, 'success');
                    resetFormCustom(form);
                    tableCustomerCodeProduct.api().ajax.reload();
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
                        url: url_gb + "/admin/CustomerCodeProduct/" + id,
                        dataType: 'json',
                    }).done(function(res) {
                        if (res.status == 1) {
                            Swal.fire(res.title, res.content, 'success');
                            tableCustomerCodeProduct.api().ajax.reload();
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
            $("#CustomerCodeProduct_edit_id").val(id);
            var btn = $(this);
            loadingButton(btn);
            $.ajax({
                method: "GET",
                url: url_gb + "/admin/CustomerCodeProduct/" + id,
                dataType: 'json',
            }).done(function(res) {
                resetButton(btn);
                $("#edit_customer_id").val(res.content.customer_id).trigger('change.select2');
                $("#edit_product_id").val(res.content.product_id).trigger('change.select2');
                $("#edit_code").val(res.content.code);
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
        $("#add_product_id").select2({
            placeholder: 'กรุณาเลือก',
            allowClear: true
        })
        $("#edit_product_id").select2({
            placeholder: 'กรุณาเลือก',
            allowClear: true
        })
    </script>
@endpush
