@extends('admin.layouts.default')
@section('css')
@endsection
@section('body')
    <div class="page-content container container-plus">
        <div class="page-header mb-2 pb-2 flex-column flex-sm-row align-items-start align-items-sm-center py-25 px-1">
            <h1 class="page-title text-primary-d2 text-140"> {{ $currentMenu->title }} </h1>
            <div class="page-tools mt-3 mt-sm-0 mb-sm-n1">

            </div>
        </div>

        <div class="row mt-3 mx-0">
            <div class="col-12">
                <div class="card dcard">
                    <div class="card-body px-1 px-md-3">
                        <form id="FormAdd" >
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">รูปประจำตัว</label>
                                        <div class="row">
                                            <div class="offset-md-1 col-md-10">
                                                <div id="orak_photo">
                                                    <div id="photo"  orakuploader="on"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="edit_prefix_id">คำนำหน้าชื่อ</label>
                                        <select class="form-control" name="prefix_id" id="prefix_id" >
                                            <option value="">เลือกคำนำหน้าชื่อ</option>
                                            @foreach ($Prefixs as $Prefix)
                                                <option {{ $AdminUser->prefix_id == $Prefix->id ? 'selected' : '' }} value="{{ $Prefix->id }}">{{ $Prefix->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="firstname">ชื่อ</label>
                                        <input type="text" class="form-control" id="firstname" name="firstname" value="{{ $AdminUser->firstname }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="lastname">นามสกุล</label>
                                        <input type="text" class="form-control" id="lastname" name="lastname" value="{{ $AdminUser->lastname }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nickname">ชื่อเล่น</label>
                                        <input type="text" class="form-control" id="nickname" name="nickname" value="{{ $AdminUser->nickname }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">อีเมล์</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ $AdminUser->email }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mobile">เบอร์โทร</label>
                                        <input type="text" class="form-control " id="mobile" name="mobile" value="{{ $AdminUser->mobile }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="birthday">วันเกิด</label>

                                        <div class="input-group date">
                                            @php
                                                $birthday = null;
                                                if ($AdminUser->birthday) {
                                                    // $birthday = date('d/m/Y',strtotime($AdminUser->birthday.'+543 years'));
                                                    $birthday = $AdminUser->birthday;
                                                }
                                            @endphp
                                            <input type="text" name="birthday" value="{{ $birthday }}" class="form-control" readonly id="birthday">
                                            <div class="input-group-addon input-group-append">
                                                <div class="input-group-text">
                                                    <i class="far fa-clock"></i>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="username">ชื่อสำหรับล็อกอิน</label>
                                        <input type="text" class="form-control " id="username" name="username" value="{{ $AdminUser->username }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">รหัสผ่าน</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="password" id="password" autocomplete="new-password" >
                                            <div class="input-group-append group-password">
                                                <button class="btn btn-outline-secondary btn-show-password" type="button">
                                                    <i class="far fa-eye mr-1 icon-show-password"></i>
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary"> <i class="fa fa-save"></i> บันทึก </button>
                                </div>

                            </div>
                        </form>

                    </div>

                </div><!-- /.card-body -->
            </div><!-- /.card -->
        </div><!-- /.col -->
    </div>
@endsection
@section('js')
    <script type="text/javascript">

    var AdminUser = {!! json_encode($AdminUser) !!};
    var photo_orak = [];
    var max_file = 1;
    if(AdminUser.photo){
        photo_orak.push(AdminUser.photo);
        max_file = photo_orak.length - 1;
    }
    $('#photo').closest('#orak_photo').html('<div id="photo" orakuploader="on"></div>');
    $('#photo').orakuploader({
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

    $('#birthday').datepicker({
        language:'th-th',
        format:'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true
    });

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

    $('#FormAdd').validate({
        errorElement: 'div',
        errorClass: 'invalid-feedback',
        focusInvalid: false,
        rules: {
            username: {
                required: true,
            },
            email: {
                required: true,
            },
        },
        messages: {
            username: {
                required: 'กรุณาระบุ',
            },
            email: {
                required: 'กรุณาระบุ',
                email: 'กรุณาระบุอีเมล์ให้ถูกต้อง',
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
                method : "PUT",
                url : url_gb+"/admin/Profile/{{ $AdminUser->id }}",
                dataType : 'json',
                data : $(form).serialize()
            }).done(function(rec){
                resetButton(btn)
                if(rec.status==1){
                    Swal.fire(rec.title,rec.content,"success").then(() => {
                        location.reload();
                    });
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

</script>
@endsection
