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

        <form id="FormAdd">
            <div class="row mt-3">

                <div class="col-md-6">
                    <div class="card ccard radius-t-0 h-100">
                        <div class="position-tl w-102 border-t-3 brc-primary-tp3 ml-n1px mt-n1px"></div>
                        <div class="card-header">
                            <h4 class="page-title text-120" >โลโก้หน้าเข้าสู่ระบบ <small class="text-danger" >(ขนาดรูปที่แนะนำ 226 x 58 px)</small> </h4>
                        </div>

                        <div class="card-body px-1 px-md-3">

                            <div class="form-group">
                                <div id="orak_bg_login">
                                    <div id="bg_login"  orakuploader="on"></div>
                                </div>
                            </div>

                        </div><!-- /.card-body -->
                    </div><!-- /.card -->
                </div><!-- /.col -->

                <div class="col-md-6">
                    <div class="card ccard radius-t-0 h-100">
                        <div class="position-tl w-102 border-t-3 brc-primary-tp3 ml-n1px mt-n1px"></div>
                        <div class="card-header">
                            <h4 class="page-title text-120" >โลโก้ <small class="text-danger" >(ขนาดรูปที่แนะนำ 52 x 58 px)</small> </h4>
                        </div>
                        <div class="card-body px-1 px-md-3">

                            <div class="form-group">
                                <div id="orak_logo">
                                    <div id="logo"  orakuploader="on"></div>
                                </div>
                            </div>

                        </div><!-- /.card-body -->
                    </div><!-- /.card -->
                </div><!-- /.col -->

            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="card dcard">
                        <div class="position-tl w-102 border-t-3 brc-primary-tp3 ml-n1px mt-n1px"></div>

                        <div class="card-header">
                            <h4 class="page-title text-120" >รายละเอียดระบบ</h4>
                        </div>

                        <div class="card-body px-1 px-md-3">
                            <div class="form-group">
                                <label for="company_name">ชื่อบริษัท/หน่วยงาน</label>
                                <input class="form-control" type="text" name="company_name" value="{{ $settings['company_name'] }}">
                            </div>

                            <div class="form-group">
                                <label for="program_name">ชื่อโปรแกรม/ระบบ</label>
                                <input class="form-control" type="text" name="program_name" value="{{ $settings['program_name'] }}">
                            </div>
                        </div><!-- /.card-body -->
                    </div>
                </div>
            </div>

            <div class="text-center mt-3">
                <button class="btn btn-primary" type="submit"> <i class="fa fa-save"></i> บันทึก </button>
            </div>

        </form>

    </div>

@endsection
@section('js')
<script type="text/javascript">

    var max_file_bg_login = 1;
    var bg_login = {!! $settings['bg_login'] !!};
    if (bg_login.length > 0) {
        max_file_bg_login = bg_login.length - 1;
    }

    $('#bg_login').closest('#orak_bg_login').html('<div id="bg_login" orakuploader="on"></div>');
    $('#bg_login').orakuploader({
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
        orakuploader_maximum_uploads         :     max_file_bg_login,
        orakuploader_attach_images           :     bg_login,
        orakuploader_hide_on_exceed          :     true,
        orakuploader_field_name              :     'bg_login',
        orakuploader_finished: function () {
        }
    });



    var max_file_logo = 1;
    var logo = {!! $settings['logo'] !!};
    if (bg_login.length > 0) {
        max_file_logo = bg_login.length - 1;
    }

    $('#logo').closest('#orak_logo').html('<div id="logo" orakuploader="on"></div>');
    $('#logo').orakuploader({
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
        orakuploader_maximum_uploads         :     max_file_logo,
        orakuploader_attach_images           :     logo,
        orakuploader_hide_on_exceed          :     true,
        orakuploader_field_name              :     'logo',
        orakuploader_finished: function () {
        }
    });

    $('#FormAdd').validate({
        errorElement: 'div',
        errorClass: 'invalid-feedback',
        focusInvalid: false,
        rules: {
            company_name: {
                required: true,
            },
            program_name: {
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
                method : "PUT",
                url : url_gb+"/admin/SettingSystem/0",
                dataType : 'json',
                data : $(form).serialize()
            }).done(function(rec){
                resetButton(btn)
                if(rec.status==1){
                    Swal.fire(rec.title,rec.content,"success").then(() =>{
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
