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
                 <a href="{{ url("admin/Product/ExportPrint") }}" target="_blank" class="btn btn-light-warning btn-h-warning btn-a-warning border-0 radius-3 py-2 text-600 text-90">
                    <span class="d-none d-sm-inline mr-1">
                        Print
                    </span>
                    <i class="fas fa-print text-110 w-2 h-2"></i>
                </a>
            @endif
            @if( $my_menu_permission[$currentMenu->url]['ep'] == 'T' )
                <a href="{{ url("admin/Product/ExportPDF") }}" target="_blank" class="btn btn-light-danger btn-h-danger btn-a-danger border-0 radius-3 py-2 text-600 text-90">
                    <span class="d-none d-sm-inline mr-1">
                        PDF
                    </span>
                    <i class="fas fa-file-pdf text-110 w-2 h-2"></i>
                </a>
            @endif
            @if( $my_menu_permission[$currentMenu->url]['ee'] == 'T' )
                <a href="{{ url("admin/Product/ExportExcel") }}" target="_blank" class="btn btn-light-primary btn-h-primary btn-a-primary border-0 radius-3 py-2 text-600 text-90">
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

                    <table id="tableProduct" class="table table-border-x brc-secondary-l4 border-0 mb-0 w-100">
                        <thead class="text-dark-tp3 bgc-grey-l4 text-90 border-b-1 brc-transparent">
                            <tr>
                                <th class="text-center" width="5%">ลำดับ</th>
                                <th>หมวด</th>
                                <th>ยี่ห้อ</th>
                                <th>Design</th>
                                <th>หน่วย</th>
                                <th>รหัสสินค้า</th>
                                <th>รหัสอะไหล่</th>
                                <th>ชื่อไทย</th>
                                <th>Drawing</th>
                                <th>กว้าง</th>
                                <th>ยาว</th>
                                <th>สูง</th>
                                <th>น้ำหนัก</th>
                                <th>คิว</th>
                                <th>เปิดใช้งาน</th>
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

                                <label for="add_category_id">หมวด</label>
                                <select name="category_id" id="add_category_id" class="form-control  autofocus"  >
                                    <option value="">เลือกกรุณาเลือก</option>
                                    @foreach($ProductCategories as $ProductCategory)
                                    <option value="{{ $ProductCategory->id }}">{{ $ProductCategory->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                            <div class="col-md-6 col-sm-12">
                            <div class="form-group">

                                <label for="add_brand_id">ยี่ห้อ</label>
                                <select name="brand_id" id="add_brand_id" class="form-control  "  >
                                    <option value="">เลือกกรุณาเลือก</option>
                                    @foreach($BrandProducts as $BrandProduct)
                                    <option value="{{ $BrandProduct->id }}">{{ $BrandProduct->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                            <div class="col-md-6 col-sm-12">
                            <div class="form-group">

                                <label for="add_design_id">Design</label>
                                <select name="design_id" id="add_design_id" class="form-control  "  >
                                    <option value="">เลือกกรุณาเลือก</option>
                                    @foreach($DesignProducts as $DesignProduct)
                                    <option value="{{ $DesignProduct->id }}">{{ $DesignProduct->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                            <div class="col-md-6 col-sm-12">
                            <div class="form-group">

                                <label for="add_unit_id">หน่วย</label>
                                <select name="unit_id" id="add_unit_id" class="form-control  "  >
                                    <option value="">เลือกกรุณาเลือก</option>
                                    @foreach($UnitProducts as $UnitProduct)
                                    <option value="{{ $UnitProduct->id }}">{{ $UnitProduct->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="add_code">รหัสสินค้า</label>
                                    <input type="text" name="code" id="add_code" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="add_part_no">รหัสอะไหล่</label>
                                    <input type="text" name="part_no" id="add_part_no" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="add_name_th">ชื่อไทย</label>
                                    <input type="text" name="name_th" id="add_name_th" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="add_name_en">ชื่ออังกฤษ</label>
                                    <input type="text" name="name_en" id="add_name_en" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="add_name_cn">ชื่อจีน</label>
                                    <input type="text" name="name_cn" id="add_name_cn" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="add_drawing">Drawing</label>
                                    <input type="text" name="drawing" id="add_drawing" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="add_width">กว้าง</label>
                                    <input type="text" name="width" id="add_width" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="add_height">ยาว</label>
                                    <input type="text" name="height" id="add_height" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="add_length">สูง</label>
                                    <input type="text" name="length" id="add_length" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="add_weight">น้ำหนัก</label>
                                    <input type="text" name="weight" id="add_weight" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="add_cube">คิว</label>
                                    <input type="text" name="cube" id="add_cube" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="add_price">ราคา</label>
                                    <input type="text" name="price" id="add_price" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="active" value="T" id="add_active" >
                                <label class="custom-control-label" for="add_active" value="T">เปิดใช้งาน</label>
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

                                <label for="edit_category_id">หมวด</label>
                                <select name="category_id" id="edit_category_id" class="form-control  "  >
                                    <option value="">เลือกกรุณาเลือก</option>
                                    @foreach($ProductCategories as $ProductCategory)
                                    <option value="{{ $ProductCategory->id }}">{{ $ProductCategory->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                            <div class="col-md-6 col-sm-12">
                            <div class="form-group">

                                <label for="edit_brand_id">ยี่ห้อ</label>
                                <select name="brand_id" id="edit_brand_id" class="form-control  "  >
                                    <option value="">เลือกกรุณาเลือก</option>
                                    @foreach($BrandProducts as $BrandProduct)
                                    <option value="{{ $BrandProduct->id }}">{{ $BrandProduct->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                            <div class="col-md-6 col-sm-12">
                            <div class="form-group">

                                <label for="edit_design_id">Design</label>
                                <select name="design_id" id="edit_design_id" class="form-control  "  >
                                    <option value="">เลือกกรุณาเลือก</option>
                                    @foreach($DesignProducts as $DesignProduct)
                                    <option value="{{ $DesignProduct->id }}">{{ $DesignProduct->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                            <div class="col-md-6 col-sm-12">
                            <div class="form-group">

                                <label for="edit_unit_id">หน่วย</label>
                                <select name="unit_id" id="edit_unit_id" class="form-control  "  >
                                    <option value="">เลือกกรุณาเลือก</option>
                                    @foreach($UnitProducts as $UnitProduct)
                                    <option value="{{ $UnitProduct->id }}">{{ $UnitProduct->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_code">รหัสสินค้า</label>
                                    <input type="text" name="code" id="edit_code" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_part_no">รหัสอะไหล่</label>
                                    <input type="text" name="part_no" id="edit_part_no" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_name_th">ชื่อไทย</label>
                                    <input type="text" name="name_th" id="edit_name_th" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_name_en">ชื่ออังกฤษ</label>
                                    <input type="text" name="name_en" id="edit_name_en" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_name_cn">ชื่อจีน</label>
                                    <input type="text" name="name_cn" id="edit_name_cn" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_drawing">Drawing</label>
                                    <input type="text" name="drawing" id="edit_drawing" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_width">กว้าง</label>
                                    <input type="text" name="width" id="edit_width" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_height">ยาว</label>
                                    <input type="text" name="height" id="edit_height" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_length">สูง</label>
                                    <input type="text" name="length" id="edit_length" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_weight">น้ำหนัก</label>
                                    <input type="text" name="weight" id="edit_weight" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_cube">คิว</label>
                                    <input type="text" name="cube" id="edit_cube" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_price">ราคา</label>
                                    <input type="text" name="price" id="edit_price" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="active" value="T" id="edit_active" >
                                <label class="custom-control-label" for="edit_active" value="T">เปิดใช้งาน</label>
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

    var tableProduct = $('#tableProduct').dataTable({
        scrollY : height-500,
        scrollX : true,
        "ajax": {
            "url": url_gb+"/admin/Product/Lists",
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
            {"data": "category_name", "name": 'product_categories.name'},
            {"data": "design_name", "name": 'design_products.name'},
            {"data": "brand_name", "name": 'brand_products.name'},
            {"data": "unit_name", "name": 'unit_products.name'},
            {"data": "code", "name": 'code'},
            {"data": "part_no", "name": 'part_no' , "visible" : false},
            {"data": "name_th", "name": 'name_th'},
            {"data": "drawing", "name": 'drawing' , "visible" : false},
            {"data": "width", "name": 'width'},
            {"data": "height", "name": 'height'},
            {"data": "length", "name": 'length'},
            {"data": "weight", "name": 'weight'},
            {"data": "cube", "name": 'cube'},
            {"data": "active", "name": 'active'},
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
            url: url_gb+"/admin/Product",
            dataType : "json",
            data: form.serialize()
        }).done(function( res ) {
            resetButton(form.find('button[type=submit]'));
            if(res.status == 1){
                Swal.fire(res.title, res.content,'success');
                resetFormCustom(form);
                                                                                                                                                                                                                                                tableProduct.api().ajax.reload();
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
        var id = $("#Product_edit_id").val();
        var form = $(this);
        loadingButton(form.find('button[type=submit]'));
        $.ajax({
            method: "PUT",
            url: url_gb+"/admin/Product/"+id,
            dataType : 'json',
            data: form.serialize()
            }).done(function( res ) {
                resetButton(form.find('button[type=submit]'));
                if(res.status == 1){
                    Swal.fire(res.title, res.content, 'success');
                    resetFormCustom(form);
                    tableProduct.api().ajax.reload();
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
                    url: url_gb+"/admin/Product/"+id,
                    dataType : 'json',
                }).done(function( res ) {
                    if(res.status == 1){
                        Swal.fire(res.title, res.content,'success');
                        tableProduct.api().ajax.reload();
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
        $("#Product_edit_id").val(id);
        var btn = $(this);
        loadingButton(btn);
        $.ajax({
            method: "GET",
            url: url_gb+"/admin/Product/"+id,
            dataType: 'json',
        }).done(function( res ) {
            resetButton(btn);

            $("#edit_category_id").val(res.content.category_id).trigger('change.select2');

            $("#edit_brand_id").val(res.content.brand_id).trigger('change.select2');

            $("#edit_design_id").val(res.content.design_id).trigger('change.select2');

            $("#edit_unit_id").val(res.content.unit_id).trigger('change.select2');

            $("#edit_code").val(res.content.code);
            $("#edit_part_no").val(res.content.part_no);
            $("#edit_name_th").val(res.content.name_th);
            $("#edit_name_en").val(res.content.name_en);
            $("#edit_name_cn").val(res.content.name_cn);
            $("#edit_drawing").val(res.content.drawing);
            $("#edit_width").val(res.content.width);
            $("#edit_height").val(res.content.height);
            $("#edit_length").val(res.content.length);
            $("#edit_weight").val(res.content.weight);
            $("#edit_price").val(res.content.price);
            $("#edit_cube").val(res.content.cube);
            if(res.content.active=='T'){
                            $("#edit_active").prop('checked' , 'checked');
                        }else{
                            $("#edit_active").prop('checked' , false);
                        }

            $('#ModalEdit').modal('show');
        }).fail(function(res){
            ajaxFail(res , "");
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
 $("#add_brand_id").select2({
     placeholder: 'กรุณาเลือก',
     allowClear: true
 })
 $("#edit_brand_id").select2({
     placeholder: 'กรุณาเลือก',
     allowClear: true
 })
 $("#add_design_id").select2({
     placeholder: 'กรุณาเลือก',
     allowClear: true
 })
 $("#edit_design_id").select2({
     placeholder: 'กรุณาเลือก',
     allowClear: true
 })
 $("#add_unit_id").select2({
     placeholder: 'กรุณาเลือก',
     allowClear: true
 })
 $("#edit_unit_id").select2({
     placeholder: 'กรุณาเลือก',
     allowClear: true
 })

</script>
@endpush
