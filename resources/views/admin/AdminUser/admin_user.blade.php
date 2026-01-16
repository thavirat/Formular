@extends('admin.layouts.default')
@section('css')
@endsection
@section('body')
    {{-- dev --}}
    <div class="page-content container container-plus">
        <div class="page-header mb-2 pb-2 flex-column flex-sm-row align-items-start align-items-sm-center py-25 px-1">
            <h1 class="page-title text-primary-d2 text-140"> {{ $currentMenu->title }} </h1>
            <div class="page-tools mt-3 mt-sm-0 mb-sm-n1">
                @if ( $my_menu_permission[$currentMenu->url]['ep'] == 'T' )
                    <a href="{{ url('admin/AdminUser/ExportPDF') }}" class="btn btn-light-danger btn-h-danger btn-a-danger border-0 radius-3 py-2 text-600 text-90">
                        <span class="d-none d-sm-inline mr-1">
                            PDF
                        </span>
                        <i class="fas fa-file-pdf text-110 w-2 h-2"></i>
                    </a>
                @endif
                @if ( $my_menu_permission[$currentMenu->url]['ee'] == 'T' )
                    <a href="{{ url('admin/AdminUser/ExportExcel') }}" class="btn btn-light-primary btn-h-primary btn-a-primary border-0 radius-3 py-2 text-600 text-90">
                        <span class="d-none d-sm-inline mr-1">
                            Excel
                        </span>
                        <i class="fas fa-file-excel text-110 w-2 h-2"></i>
                    </a>
                @endif
                @if ( $my_menu_permission[$currentMenu->url]['c'] == 'T' )
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

                        <table id="TableList" class="table table-border-x brc-secondary-l4 border-0 mb-0 w-100">
                            <thead class="text-dark-tp3 bgc-grey-l4 text-90 border-b-1 brc-transparent">
                                <tr>
                                    <th>ลำดับ</th>
                                    <th>คำนำหน้าชื่อ</th>
                                    <th>ชื่อ</th>
                                    <th>นามสกุล</th>
                                    <th>ชื่อสำหรับล็อกอิน</th>
                                    <th>เบอร์โทร</th>
                                    <th>สถานะ</th>
                                    <th></th>
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
    <div class="modal fade modal-lg" id="ModalAdd"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="" id="FormAdd" >
                    <div class="modal-header">
                        <h5 class="modal-title text-primary-d3" id="exampleModalLabel">
                            เพิ่มข้อมูล {{ $currentMenu->title }}
                        </h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="add_prefix_id">คำนำหน้าชื่อ</label>
                                    <select class="form-control" name="prefix_id" id="add_prefix_id" >
                                        <option value="">เลือกคำนำหน้าชื่อ</option>
                                        @foreach ($Prefixs as $Prefix)
                                            <option value="{{ $Prefix->id }}">{{ $Prefix->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="add_department_id">{{__('Department')}}</label>
                                    <select class="form-control" name="department_id" id="add_department_id" >
                                        <option value="">{{__('Select Department')}}</option>
                                        @foreach ($AdminDepartments as $AdminDepartment)
                                            <option value="{{ $AdminDepartment->id }}">{{ $AdminDepartment->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="add_firstname">ชื่อ</label>
                                    <input type="text" class="form-control" id="add_firstname" name="firstname" value="">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="add_lastname">นามสกุล</label>
                                    <input type="text" class="form-control" id="add_lastname" name="lastname" value="">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="add_nickname">ชื่อเล่น</label>
                                    <input type="text" class="form-control" id="add_nickname" name="nickname" value="">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="add_email">อีเมล์</label>
                                    <input type="email" class="form-control" id="add_email" name="email" value="">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="add_mobile">เบอร์โทร</label>
                                    <input type="text" class="form-control " id="add_mobile" name="mobile" value="">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="add_birthday">วันเกิด</label>

                                    <div class="input-group date">
                                        <input type="text" name="birthday" value="" class="form-control" readonly id="add_birthday">
                                        <div class="input-group-addon input-group-append">
                                            <div class="input-group-text">
                                                <i class="far fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="add_username">ชื่อสำหรับล็อกอิน</label>
                                    <input type="text" class="form-control " id="add_username" name="username" value="">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="add_password">รหัสผ่าน</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="password" id="add_password" autocomplete="new-password" >
                                        <div class="input-group-append group-password">
                                            <button class="btn btn-outline-secondary btn-show-password" type="button">
                                                <i class="far fa-eye mr-1 icon-show-password"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">&nbsp;</label><br>
                                    <input type="checkbox" id="add_active" class="bgc-blue" name="active" value="T" checked >
                                    <label for="add_active">เปิดใช้งาน</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="add_remark">หมายเหตุ</label>
                                    <textarea class="form-control" id="add_remark" rows="2" name="remark" ></textarea>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="add_photo">รูปประจำตัว</label>
                                    <div class="row">
                                        <div class="offset-md-1 col-md-10">
                                            <div id="orak_add_photo">
                                                <div id="add_photo" orakuploader="on"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <br />
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
    <div class="modal fade modal-lg" id="ModalEdit"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="" id="FormEdit" >
                    <input type="hidden" id="edit_id" value="">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary-d3" id="exampleModalLabel">
                            แก้ไขข้อมูล {{ $currentMenu->title }}
                        </h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_prefix_id">คำนำหน้าชื่อ</label>
                                    <select class="form-control" name="prefix_id" id="edit_prefix_id" >
                                        <option value="">เลือกคำนำหน้าชื่อ</option>
                                        @foreach ($Prefixs as $Prefix)
                                            <option value="{{ $Prefix->id }}">{{ $Prefix->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_department_id">{{__('Department')}}</label>
                                    <select class="form-control" name="department_id" id="edit_department_id" >
                                        <option value="">{{__('Select Department')}}</option>
                                        @foreach ($AdminDepartments as $AdminDepartment)
                                            <option value="{{ $AdminDepartment->id }}">{{ $AdminDepartment->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_firstname">ชื่อ</label>
                                    <input type="text" class="form-control" id="edit_firstname" name="firstname" value="">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_lastname">นามสกุล</label>
                                    <input type="text" class="form-control" id="edit_lastname" name="lastname" value="">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_nickname">ชื่อเล่น</label>
                                    <input type="text" class="form-control" id="edit_nickname" name="nickname" value="">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_email">อีเมล์</label>
                                    <input type="email" class="form-control" id="edit_email" name="email" value="">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_mobile">เบอร์โทร</label>
                                    <input type="text" class="form-control " id="edit_mobile" name="mobile" value="">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_birthday">วันเกิด</label>

                                    <div class="input-group date">
                                        <input type="text" name="birthday" value="" class="form-control" readonly id="edit_birthday">
                                        <div class="input-group-addon input-group-append">
                                            <div class="input-group-text">
                                                <i class="far fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_username">ชื่อสำหรับล็อกอิน</label>
                                    <input type="text" class="form-control " id="edit_username" name="username" value="">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_remark">หมายเหตุ</label>
                                    <textarea class="form-control" id="edit_remark" rows="1" name="remark" ></textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">&nbsp;</label><br>
                                    <input type="checkbox" id="edit_active" class="bgc-blue" name="active" value="T">
                                    <label for="edit_active">เปิดใช้งาน</label>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="add_photo">รูปประจำตัว</label>
                                    <div class="row">
                                        <div class="offset-md-1 col-md-10">
                                            <div id="orak_edit_photo">
                                                <div id="edit_photo"  orakuploader="on"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <br />
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
    <div class="modal fade modal-xs" id="ModalPassword"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="" id="FormPassword" >
                    <input type="hidden" id="edit_password_id" value="">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary-d3" id="exampleModalLabel">
                            เปลี่ยนรหัสผ่าน
                        </h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="password">รหัสผ่านใหม่</label>
                                    <input type="text" class="form-control" id="password" name="password" value="" placeholder="กรุณาระบุรหัสผ่านใหม่" >
                                </div>
                            </div>

                        </div>
                        <br />
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
@section('js')
<script type="text/javascript">
        var TableList = null;

        $(function(){

            TableList = $('#TableList').dataTable({
                scrollY : height-380,
                scrollX : true,
        		"pageLength": 50,
        		"ajax": {
        			"method" : "GET",
        			"url": url_gb+"/admin/AdminUser/Lists",
        			"data": function ( d ) {
        				d.start_date = $('#search_start_date').val();
        				d.end_date = $('#search_end_date').val();
        				d.payment_status = $('#search_payment_status').val();
        				d.zone_id = $('#search_zone_id').find('option:selected').val();
        				// d.custom = $('#myInput').val();
        				// etc
        			}
        		},
        		"drawCallback": function( settings ) {
        			$('[data-toggle="tooltip"]').tooltip();

        		},

        		"columns": [
                    {"data" : "DT_RowIndex","searchable" : false , "orderable" : false},
        			{"data" : "prefix_name", "name": 'prefixes.name'},
        			{"data" : "firstname"},
        			{"data" : "lastname"},
        			{"data" : "username"},
        			{"data" : "mobile"},
        			{"data" : "active"},
        			{
        				"data" : "action" ,
        				"name" : "action",
        				"searchable": false,
        				"sortable": false,
        				"class" : "text-md-center"
        			},
        		],
        	});

        })

        $('body').on('click', '.btn-show-password', function(event) {
            let ele = $(this);
            let form = $(ele).closest('form');
            let password =  $(form).find('[name="password"]');

            if ( password.prop('type') == 'password' ) {
                $(password).prop('type', 'text');
                $(form).find('.icon-show-password').removeClass('fa-eye').addClass('fa-eye-slash');
            }else{
                $(password).prop('type', 'password');
                $(form).find('.icon-show-password').removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });

        $('body').on('click', '.btn-add', function(event) {
            $('#ModalAdd').modal('show');
        });

        $('body').on('click', '.btn-password', function(event) {
            let id = $(this).data('id');
            $('#edit_password_id').val(id);
            $('#ModalPassword').modal('show');
        });

        $('body').on('click', '.btn-edit', function(event) {
            var btn = $(this);
            loadingButton(btn)
            var id = $(this).data('id');
            $('#edit_id').val(id);
            $.ajax({
                method : "GET",
                url : url_gb+"/admin/AdminUser/"+id,
                dataType : 'json'
            }).done(function(rec){
                $('#edit_prefix_id').val(rec.prefix_id);
                $('#edit_firstname').val(rec.firstname);
                $('#edit_lastname').val(rec.lastname);
                $('#edit_nickname').val(rec.nickname);
                $('#edit_email').val(rec.email);
                $('#edit_mobile').val(rec.mobile);
                $('#edit_department_id').val(rec.department_id);
                $('#edit_active').prop('checked', false);
                $('[id="edit_active"][value="'+rec.active+'"]').prop('checked', true);
                $('#edit_username').val(rec.username);
                $('#edit_remark').val(rec.remark);

                var photo_orak = JSON.parse(rec.photo);
                var max_file = 1;
                if(photo_orak.length > 0){
                    max_file = photo_orak.length - 1;
                }

                $('#edit_photo').closest('#orak_edit_photo').html('<div id="edit_photo" orakuploader="on"></div>');
        		$('#edit_photo').orakuploader({
        			orakuploader_path                    :     url_gb + '/',
        			orakuploader_ckeditor                :     false,
        			orakuploader_use_dragndrop           :     true,
        			orakuploader_main_path               :     'uploads/temp/',
        			orakuploader_thumbnail_path          :     'uploads/temp/',
        			orakuploader_thumbnail_real_path     :     asset_gb + 'uploads/temp/',
        			orakuploader_add_image               :     asset_gb + 'images/add.png',
        			orakuploader_loader_image            :     asset_gb + 'images/loader.gif',
        			orakuploader_no_image                :     asset_gb + 'images/no-image.jpg',
        			orakuploader_add_label               :     'เพิ่มรูป',
        			orakuploader_use_rotation            :     false,
        			orakuploader_maximum_uploads         :     max_file,
        			orakuploader_attach_images           :     photo_orak,
        			orakuploader_hide_on_exceed          :     true,
        			orakuploader_field_name              :     'photo',
        			orakuploader_finished: function () {
        			}
        		});

                $('#edit_birthday').datepicker({
                    language:'th-th',
                    format:'dd/mm/yyyy',
                    autoclose: true,
                    todayHighlight: true
                }).datepicker("setDate", rec.birthday);

                $('#edit_remark').val(rec.remark);

                resetButton(btn)
                ShowModal('ModalEdit');
            }).fail(function(){
                Swal.fire("system.system_alert","system.system_error","error");
                resetButton(btn)
            });
        });



        $('#FormAdd').validate({
            errorElement: 'div',
            errorClass: 'invalid-feedback',
            focusInvalid: false,
            rules: {
                username: {
                    required: true,
                },
                password: {
                    required: true,
                },
            },
            messages: {
                username: {
                    required: 'กรุณาระบุ',
                },
                password: {
                    required: 'กรุณาระบุ',
                },
            },
            highlight: function (e) {
                validate_highlight(e);
            },
            success: function (e) {
                validate_success(e);
            },

            errorPlacement: function (error, element) {
                validate_errorplacement(error, element);
            },
            submitHandler: function (form) {

                var btn = $(form).find('[type="submit"]');
                loadingButton(btn)
                $.ajax({
                    method : "POST",
                    url : url_gb+"/admin/AdminUser",
                    dataType : 'json',
                    data : $(form).serialize()
                }).done(function(rec){
                    resetButton(btn)
                    if(rec.status==1){
                        TableList.api().ajax.reload();
                        resetFormCustom(form);
                        $('#add_active').prop('checked', true);
                        Swal.fire(rec.title,rec.content,"success");
                        $('#ModalAdd').modal('hide');
                    }else{
                        Swal.fire(rec.title,rec.content,"error");
                    }
                }).fail(function(){
                    Swal.fire("system.system_alert","system.system_error","error");
                    resetButton(btn)
                });
            },
            invalidHandler: function (form) {

            }
        });


    $('#FormEdit').validate({
        errorElement: 'div',
        errorClass: 'invalid-feedback',
        focusInvalid: false,
        rules: {
            username: {
                required: true,
            },
            password: {
                required: true,
            },
        },
        messages: {
            username: {
                required: 'กรุณาระบุ',
            },
            password: {
                required: 'กรุณาระบุ',
            },
        },
        highlight: function (e) {
            validate_highlight(e);
        },
        success: function (e) {
            validate_success(e);
        },

        errorPlacement: function (error, element) {
            validate_errorplacement(error, element);
        },
        submitHandler: function (form) {

            var btn = $(form).find('[type="submit"]');
            var id = $('#edit_id').val();
            loadingButton(btn)
            $.ajax({
                method : "PUT",
                url : url_gb+"/admin/AdminUser/"+id,
                dataType : 'json',
                data : $(form).serialize()
            }).done(function(rec){
                resetButton(btn)
                if(rec.status==1){
                    TableList.api().ajax.reload();
                    resetFormCustom(form);
                    Swal.fire(rec.title,rec.content,"success");
                    $('#ModalEdit').modal('hide');
                }else{
                    Swal.fire(rec.title,rec.content,"error");
                }
            }).fail(function(){
                Swal.fire("system.system_alert","system.system_error","error");
                resetButton(btn)
            });
        },
        invalidHandler: function (form) {

        }
    });


    $('#FormPassword').validate({
        errorElement: 'div',
        errorClass: 'invalid-feedback',
        focusInvalid: false,
        rules: {
            password: {
            required: true,
            },
        },
        messages: {
            password: {
                required: 'กรุณาระบบ',
            },

        },
        highlight: function (e) {
            validate_highlight(e);
        },
        success: function (e) {
            validate_success(e);
        },

        errorPlacement: function (error, element) {
            validate_errorplacement(error, element);
        },
        submitHandler: function (form) {

            var btn = $(form).find('[type="submit"]');
            var id = $('#edit_password_id').val();
            loadingButton(btn)
            $.ajax({
                method : "PATCH",
                url : url_gb+"/admin/AdminUser/"+id+"/password",
                dataType : 'json',
                data : $(form).serialize()
            }).done(function(rec){
                resetButton(btn)
                if(rec.status==1){
                    TableList.api().ajax.reload();
                    resetFormCustom(form);
                    Swal.fire(rec.title,rec.content,"success");
                    $('#ModalPassword').modal('hide');
                }else{
                    Swal.fire(rec.title,rec.content,"error");
                }
            }).fail(function(){
                Swal.fire("system.system_alert","system.system_error","error");
                resetButton(btn)
            });
        },
        invalidHandler: function (form) {

        }
    });

    $('body').on('click' , '.btn-delete' , function (){
        var id = $(this).data('id');
        var btn = $(this);
        loadingButton(btn);
        Swal.fire({
            title: "คุณต้องการลบข้อมูลหรือไม่ ?",
            text: "ข้อมูลที่ถูกลบไปแล้วจะไม่สามารถนำกลับมาได้ไม่ว่ากรณีใดๆทั้งสิ้นการลบข้อมูลจะทำให้ข้อมูลอื่นๆที่ถูกนำไปใช้ ลบหายไปด้วย",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: '#78c350',
            cancelButtonColor: '#f7531f',
            confirmButtonText: 'ยืนยันลบ',
            cancelButtonText: 'ยกเลิก ไม่ลบ'
        })
        .then((willDelete) => {
            if (willDelete.value) {
                loadingButton(btn);
                $.ajax({
                    method: "DELETE",
                    url: url_gb+"/admin/AdminUser/"+id,
                    dataType : 'json',
                    data: {id : id}
                }).done(function( res ) {
                    resetButton(btn);
                    if(res.status==1){
                        Swal.fire(res.title,res.content,'success');
                        TableList.api().ajax.reload();
                    }else{
                        Swal.fire(res.title,res.content,'error');
                    }
                }).fail(function(res){
                    var res = $.parseJSON(res.responseText);
                    var str = "กรุณาถ่ายรูปหน้าจอนี้ให้กับเจ้าหน้าที่\n\r"+res.message+"\n\r"+res.exception+"\n\r"+res.file+" Line : "+res.line;
                    Swal.fire("โอ๊ะโอ! ขอโทษด้วยมีบางอย่างผิดพลาด",str,'error');
                });
            } else {
                resetButton(btn);
            }
        });

    });


    $('.date').datepicker({
        language:'th-th',
        format:'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true
    })

    $('#add_photo').orakuploader({
        orakuploader_field_name         : 'photo',
        orakuploader_finished           : function(){
        }
    });

    $('#add_main_menu_id').select2()
    $('#edit_main_menu_id').select2()

</script>
@endsection
