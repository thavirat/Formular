@extends('admin.Customer.customer_layout')

@section('content')
    <div class="col-12 col-md-8 mt-3 mt-md-0">
        <div class="card dcard h-100">
            <div class="card-header bg-transparent border-b-1 brc-grey-l3 d-flex justify-content-between align-items-center">
                <h5 class="card-title text-110 text-primary-d2">
                    <i class="fa fa-users text-grey-m2 mr-1"></i> {{ __('List Customer') }}
                </h5>
                <button class="btn btn-sm btn-success btn-bold px-3  btn-add">
                    <i class="fa fa-plus mr-1"></i> {{ __('Add Customer') }}
                </button>
            </div>
            <div class="card-body p-0 bgc-white">
                <table id="tableCustomerContact" class="table table-striped-primary table-borderless border-0 mb-0 w-100">
                    <thead class="bgc-grey-l4 text-grey-m1 text-85 uppercase font-bolder">
                        <tr>
                            <th class="text-center" width="5%">No</th>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th class="text-center">#</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Add -->
    <div class="modal fade modal-lg" id="ModalAdd" tabindex="-1" role="document" aria-labelledby="ModalAddLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow radius-1">
                <form id="FormAdd" data-parsley-validate="true">
                    <input type="hidden" name="customer_id" value="{{$customer->id}}">
                    <div class="modal-header bgc-primary-d1">
                        <h5 class="modal-title text-white" id="ModalAddLabel">
                            <i class="fa fa-user-plus mr-2 text-white-tp2"></i>เพิ่มรายชื่อผู้ติดต่อ
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body p-4 bgc-grey-l5">
                        <div class="row">


                            <div class="col-md-12 mb-3">
                                <label class="text-80 text-grey-m1 uppercase font-bolder">ชื่อ-นามสกุล <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bgc-white"><i
                                                class="fa fa-user text-primary-m2"></i></span>
                                    </div>
                                    <input type="text" name="name" id="add_name" class="form-control"
                                        placeholder="ระบุชื่อผู้ติดต่อ" required>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="text-80 text-grey-m1 uppercase font-bolder">เบอร์มือถือ</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bgc-white"><i
                                                class="fa fa-mobile-alt text-success-m2"></i></span>
                                    </div>
                                    <input type="text" name="mobile" id="add_mobile" class="form-control"
                                        placeholder="08x-xxx-xxxx">
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="text-80 text-grey-m1 uppercase font-bolder">อีเมล</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bgc-white"><i
                                                class="fa fa-envelope text-info-m2"></i></span>
                                    </div>
                                    <input type="email" name="email" id="add_email" class="form-control"
                                        placeholder="example@company.com">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bgc-white">
                        <button type="button" class="btn btn-outline-grey btn-bold px-4" data-dismiss="modal">
                            ยกเลิก
                        </button>
                        <button type="submit" class="btn btn-primary btn-bold px-4">
                            <i class="fa fa-save mr-1"></i> บันทึกข้อมูล
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ModalEdit" role="dialog" aria-labelledby="ModalEditLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow radius-1">
                <form id="FormEdit" data-parsley-validate="true">
                    <input type="hidden" id="CustomerContact_edit_id">
                    <input type="hidden" name="customer_id" value="{{$customer->id}}">


                    <div class="modal-header bgc-orange-d1">
                        <h5 class="modal-title text-white" id="ModalEditLabel">
                            <i class="fa fa-edit mr-2 text-white-tp2"></i>แก้ไขข้อมูลผู้ติดต่อ
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body p-4 bgc-grey-l5">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="text-80 text-grey-m1 uppercase font-bolder">ชื่อ-นามสกุล <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bgc-white"><i
                                                class="fa fa-user text-orange-m2"></i></span>
                                    </div>
                                    <input type="text" name="name" id="edit_name" class="form-control"
                                        placeholder="ระบุชื่อผู้ติดต่อ" required>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="text-80 text-grey-m1 uppercase font-bolder">เบอร์มือถือ</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bgc-white"><i
                                                class="fa fa-mobile-alt text-success-m2"></i></span>
                                    </div>
                                    <input type="text" name="mobile" id="edit_mobile" class="form-control"
                                        placeholder="08x-xxx-xxxx">
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="text-80 text-grey-m1 uppercase font-bolder">อีเมล</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bgc-white"><i
                                                class="fa fa-envelope text-info-m2"></i></span>
                                    </div>
                                    <input type="email" name="email" id="edit_email" class="form-control"
                                        placeholder="example@company.com">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bgc-white">
                        <button type="button" class="btn btn-outline-grey btn-bold px-4" data-dismiss="modal">
                            ยกเลิก
                        </button>
                        <button type="submit" class="btn btn-warning btn-bold px-4 text-white">
                            <i class="fa fa-save mr-1"></i> บันทึกการแก้ไข
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script type="text/javascript">
        var tableCustomerContact = $('#tableCustomerContact').dataTable({

            "ajax": {
                "url": url_gb + "/admin/CustomerContact/Lists",
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
                    "class": "text-center align-middle"
                },
                {
                    "data": "name",
                    "name": 'name',
                    "class": "align-middle"
                },
                {
                    "data": "contact_info",
                    "name": "mobile",
                    "class": "align-middle"
                }, // ใช้ Custom Column
                {
                    "data": "action",
                    "name": "action",
                    "searchable": false,
                    "sortable": false,
                    "class": "text-center align-middle"
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
                url: url_gb + "/admin/CustomerContact",
                dataType: "json",
                data: form.serialize()
            }).done(function(res) {
                resetButton(form.find('button[type=submit]'));
                if (res.status == 1) {
                    Swal.fire(res.title, res.content, 'success');
                    resetFormCustom(form);
                    tableCustomerContact.api().ajax.reload();
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
            var id = $("#CustomerContact_edit_id").val();
            var form = $(this);
            loadingButton(form.find('button[type=submit]'));
            $.ajax({
                method: "PUT",
                url: url_gb + "/admin/CustomerContact/" + id,
                dataType: 'json',
                data: form.serialize()
            }).done(function(res) {
                resetButton(form.find('button[type=submit]'));
                if (res.status == 1) {
                    Swal.fire(res.title, res.content, 'success');
                    resetFormCustom(form);
                    tableCustomerContact.api().ajax.reload();
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
                        url: url_gb + "/admin/CustomerContact/" + id,
                        dataType: 'json',
                    }).done(function(res) {
                        if (res.status == 1) {
                            Swal.fire(res.title, res.content, 'success');
                            tableCustomerContact.api().ajax.reload();
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
            $("#CustomerContact_edit_id").val(id);
            var btn = $(this);
            loadingButton(btn);
            $.ajax({
                method: "GET",
                url: url_gb + "/admin/CustomerContact/" + id,
                dataType: 'json',
            }).done(function(res) {
                resetButton(btn);
                $("#edit_customer_id").val(res.content.customer_id).trigger('change.select2');
                $("#edit_name").val(res.content.name);
                $("#edit_mobile").val(res.content.mobile);
                $("#edit_email").val(res.content.email);
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
    </script>
@endpush
