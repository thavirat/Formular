@extends('admin.layouts.default')

@section('title', $currentMenu->title)

@push('css')
@endpush

@section('body')
    <div class="page-content container container-plus">
        <div class="page-header mb-2 pb-2 flex-column flex-sm-row align-items-start align-items-sm-center py-25 px-1">
            <h1 class="page-title text-primary-d2 text-140">{{ $currentMenu->title }} </h1>
            <div class="page-tools mt-3 mt-sm-0 mb-sm-n1">
                @if ($my_menu_permission[$currentMenu->url]['p'] == 'T')
                    <a href="{{ url('admin/Product/ExportPrint') }}" target="_blank"
                        class="btn btn-light-warning btn-h-warning btn-a-warning border-0 radius-3 py-2 text-600 text-90">
                        <span class="d-none d-sm-inline mr-1">
                            Print
                        </span>
                        <i class="fas fa-print text-110 w-2 h-2"></i>
                    </a>
                @endif
                @if ($my_menu_permission[$currentMenu->url]['ep'] == 'T')
                    <a href="{{ url('admin/Product/ExportPDF') }}" target="_blank"
                        class="btn btn-light-danger btn-h-danger btn-a-danger border-0 radius-3 py-2 text-600 text-90">
                        <span class="d-none d-sm-inline mr-1">
                            PDF
                        </span>
                        <i class="fas fa-file-pdf text-110 w-2 h-2"></i>
                    </a>
                @endif
                @if ($my_menu_permission[$currentMenu->url]['ee'] == 'T')
                    <a href="{{ url('admin/Product/ExportExcel') }}" target="_blank"
                        class="btn btn-light-primary btn-h-primary btn-a-primary border-0 radius-3 py-2 text-600 text-90">
                        <span class="d-none d-sm-inline mr-1">
                            Excel
                        </span>
                        <i class="fas fa-file-excel text-110 w-2 h-2"></i>
                    </a>
                @endif
                <button type="button"
                    class="btn btn-light-info btn-h-info btn-a-info border-0 radius-3 py-2 text-600 text-90"
                    data-toggle="modal" data-target="#ModalImportProduct">
                    <span class="d-none d-sm-inline mr-1">Import Products</span>
                    <i class="fa fa-file-import text-110 w-2 h-2"></i>
                </button>

                @if ($my_menu_permission[$currentMenu->url]['c'] == 'T')
                    <button type="button"
                        class="btn btn-light-green btn-h-green btn-a-green border-0 radius-3 py-2 text-600 text-90 btn-add">
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

                        <table id="tableProduct" class="table table-border-x brc-secondary-l4 border-0 mb-0 w-100">
                            <thead class="text-dark-tp3 bgc-grey-l4 text-90 border-b-1 brc-transparent">
                                <tr>
                                    <th class="text-center" width="5%">ลำดับ</th>
                                    <th>Category</th>
                                    <th>Product ID</th>
                                    <th>Name TH</th>
                                    <th>Name ENG</th>
                                    <th>Drawing</th>
                                    <th>Width</th>
                                    <th>Height</th>
                                    <th>Length</th>
                                    <th>Weight</th>
                                    <th>Sub Category</th>
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


    <div class="modal fade" id="ModalImportProduct" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content border-0 shadow radius-1">
                <form id="FormImportProduct" enctype="multipart/form-data">
                    <div class="modal-header bgc-info-d1">
                        <h5 class="modal-title text-white">
                            <i class="fa fa-boxes mr-2"></i>Import รายการสินค้าใหม่
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body p-4 bgc-grey-l5">
                        <div class="form-group">
                            <label class="text-80 text-grey-m1 uppercase font-bolder">เลือกไฟล์ Excel
                                สำหรับรายการสินค้า</label>
                            <input type="file" name="file" class="form-control" accept=".xlsx, .xls" required>
                        </div>
                    </div>
                    <div class="modal-footer bgc-white">
                        <button type="button" class="btn btn-outline-grey btn-bold px-4"
                            data-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-info btn-bold px-4">เริ่มนำเข้า</button>
                    </div>
                </form>
            </div>
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

                                    <label for="add_category_id">Category</label>
                                    <select name="category_id" id="add_category_id" class="form-control  autofocus">
                                        <option value="">เลือกกรุณาเลือก</option>
                                        @foreach ($ProductCategories as $ProductCategory)
                                            <option value="{{ $ProductCategory->id }}">{{ $ProductCategory->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="add_code">Product ID</label>
                                    <input type="text" name="code" id="add_code" class="form-control ">
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="add_name_th">Name TH</label>
                                    <input type="text" name="name_th" id="add_name_th" class="form-control ">
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="add_name_en">Name ENG</label>
                                    <input type="text" name="name_en" id="add_name_en" class="form-control ">
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="add_drawing">Drawing</label>
                                    <input type="text" name="drawing" id="add_drawing" class="form-control ">
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="add_width">Width</label>
                                    <input type="text" name="width" id="add_width" class="form-control ">
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="add_height">Height</label>
                                    <input type="text" name="height" id="add_height" class="form-control ">
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="add_length">Length</label>
                                    <input type="text" name="length" id="add_length" class="form-control ">
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="add_weight">Weight</label>
                                    <input type="text" name="weight" id="add_weight" class="form-control ">
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">

                                    <label for="add_sub_category_id">Sub Category</label>
                                    <select name="sub_category_id" id="add_sub_category_id" class="form-control  ">
                                        <option value="">เลือกกรุณาเลือก</option>
                                        @foreach ($SubCategories as $SubCategory)
                                            <option value="{{ $SubCategory->id }}">{{ $SubCategory->name_th }}</option>
                                        @endforeach
                                    </select>
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
                    <input type="hidden" id="Product_edit_id">
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

                                    <label for="edit_category_id">Category</label>
                                    <select name="category_id" id="edit_category_id" class="form-control  ">
                                        <option value="">เลือกกรุณาเลือก</option>
                                        @foreach ($ProductCategories as $ProductCategory)
                                            <option value="{{ $ProductCategory->id }}">{{ $ProductCategory->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_code">Product ID</label>
                                    <input type="text" name="code" id="edit_code" class="form-control ">
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_name_th">Name TH</label>
                                    <input type="text" name="name_th" id="edit_name_th" class="form-control ">
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_name_en">Name ENG</label>
                                    <input type="text" name="name_en" id="edit_name_en" class="form-control ">
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_drawing">Drawing</label>
                                    <input type="text" name="drawing" id="edit_drawing" class="form-control ">
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_width">Width</label>
                                    <input type="text" name="width" id="edit_width" class="form-control ">
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_height">Height</label>
                                    <input type="text" name="height" id="edit_height" class="form-control ">
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_length">Length</label>
                                    <input type="text" name="length" id="edit_length" class="form-control ">
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_weight">Weight</label>
                                    <input type="text" name="weight" id="edit_weight" class="form-control ">
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">

                                    <label for="edit_sub_category_id">Sub Category</label>
                                    <select name="sub_category_id" id="edit_sub_category_id" class="form-control  ">
                                        <option value="">เลือกกรุณาเลือก</option>
                                        @foreach ($SubCategories as $SubCategory)
                                            <option value="{{ $SubCategory->id }}">{{ $SubCategory->name_th }}</option>
                                        @endforeach
                                    </select>
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
        var tableProduct = $('#tableProduct').dataTable({

            "ajax": {
                "url": url_gb + "/admin/Product/Lists",
                "type": "POST",
                "data": function(d) {
                    // d.status = "A";
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
                    "data": "category_id",
                    "name": 'category_id'
                },
                {
                    "data": "code",
                    "name": 'code'
                },
                {
                    "data": "name_th",
                    "name": 'name_th'
                },
                {
                    "data": "name_en",
                    "name": 'name_en'
                },
                {
                    "data": "drawing",
                    "name": 'drawing'
                },
                {
                    "data": "width",
                    "name": 'width'
                },
                {
                    "data": "height",
                    "name": 'height'
                },
                {
                    "data": "length",
                    "name": 'length'
                },
                {
                    "data": "weight",
                    "name": 'weight'
                },
                {
                    "data": "sub_category_id",
                    "name": 'sub_category_id'
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
                url: url_gb + "/admin/Product",
                dataType: "json",
                data: form.serialize()
            }).done(function(res) {
                resetButton(form.find('button[type=submit]'));
                if (res.status == 1) {
                    Swal.fire(res.title, res.content, 'success');
                    resetFormCustom(form);
                    tableProduct.api().ajax.reload();
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
            var id = $("#Product_edit_id").val();
            var form = $(this);
            loadingButton(form.find('button[type=submit]'));
            $.ajax({
                method: "PUT",
                url: url_gb + "/admin/Product/" + id,
                dataType: 'json',
                data: form.serialize()
            }).done(function(res) {
                resetButton(form.find('button[type=submit]'));
                if (res.status == 1) {
                    Swal.fire(res.title, res.content, 'success');
                    resetFormCustom(form);
                    tableProduct.api().ajax.reload();
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
                        url: url_gb + "/admin/Product/" + id,
                        dataType: 'json',
                    }).done(function(res) {
                        if (res.status == 1) {
                            Swal.fire(res.title, res.content, 'success');
                            tableProduct.api().ajax.reload();
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
            $("#Product_edit_id").val(id);
            var btn = $(this);
            loadingButton(btn);
            $.ajax({
                method: "GET",
                url: url_gb + "/admin/Product/" + id,
                dataType: 'json',
            }).done(function(res) {
                resetButton(btn);
                $("#edit_category_id").val(res.content.category_id).trigger('change.select2');
                $("#edit_code").val(res.content.code);
                $("#edit_name_th").val(res.content.name_th);
                $("#edit_name_en").val(res.content.name_en);
                $("#edit_drawing").val(res.content.drawing);
                $("#edit_width").val(res.content.width);
                $("#edit_height").val(res.content.height);
                $("#edit_length").val(res.content.length);
                $("#edit_weight").val(res.content.weight);
                $("#edit_sub_category_id").val(res.content.sub_category_id).trigger('change.select2');

                $('#ModalEdit').modal('show');
            }).fail(function(res) {
                ajaxFail(res, "");
            });
        });


        $("#add_category_id").select2({
            placeholder: 'กรุณาเลือก',
            allowClear: true
        })
        $("#edit_category_id").select2({
            placeholder: 'กรุณาเลือก',
            allowClear: true
        })
        $("#add_sub_category_id").select2({
            placeholder: 'กรุณาเลือก',
            allowClear: true
        })
        $("#edit_sub_category_id").select2({
            placeholder: 'กรุณาเลือก',
            allowClear: true
        })


        $('#FormImportProduct').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                var btn = form.find('button[type=submit]');
                var formData = new FormData(this);

                loadingButton(btn);

                $.ajax({
                    url: url_gb + "/admin/Product/Import",
                    method: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                }).done(function(res) {
                    resetButton(btn);
                    if (res.status == 1) {
                        Swal.fire(res.title, res.content, 'success');
                        $('#ModalImportProduct').modal('hide');
                        if(typeof tableProduct !== 'undefined'){
                            tableProduct.api().ajax.reload();
                        }
                    } else {
                        Swal.fire(res.title, res.content, 'error');
                    }
                }).fail(function(res) {
                    // --- เปลี่ยนมาใช้ ajaxFail ตรงนี้ครับ ---
                    ajaxFail(res, form);
                });
            });
    </script>
@endpush
