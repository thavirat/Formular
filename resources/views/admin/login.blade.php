<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1">
    <base href="{{ url('') }}" />

    <title>Login - Package2022</title>

    <!-- include common vendor stylesheets & fontawesome -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/node_modules/bootstrap/dist/css/bootstrap.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/node_modules/@fortawesome/fontawesome-free/css/fontawesome.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/node_modules/@fortawesome/fontawesome-free/css/regular.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/node_modules/@fortawesome/fontawesome-free/css/brands.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/node_modules/@fortawesome/fontawesome-free/css/solid.css') }}">



    <!-- include fonts -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/dist/css/ace-font.css') }}">

    <!-- Font -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/admin/assets/fonts/Sarabun/sarabun.css')}}">

    <!-- ace.css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/dist/css/ace') }}.css">


    <!-- favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/admin/assets/favicon.png') }}" />

    <link rel="stylesheet" type="text/css" href="{{asset('assets/admin/assets/fonts/Sarabun/sarabun.css')}}">

    <!-- "Login" page styles, specific to this page for demo only -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/views/pages/page-login/@page-style.css') }}">

    <!-- custom.css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/admin/css/custom.css')}}">

</head>

<body>
    <div class="body-container">

        <div class="main-container container bgc-transparent">

            <div class="main-content minh-100 justify-content-center">
                <div class="p-2 p-md-4">


                    <div class="row justify-content-center" id="row-1">
                        <div class="bgc-white shadow radius-1 overflow-hidden col-12 col-lg-6 col-xl-5">

                            <div class="row" id="row-2">




                                <div id="id-col-main" class="col-12 py-lg-5 bgc-white px-0">
                                    <!-- you can also use these tab links -->
                                    <ul class="d-none mt-n4 mb-4 nav nav-tabs nav-tabs-simple justify-content-end bgc-black-tp11" role="tablist">
                                        <li class="nav-item mx-2">
                                            <a class="nav-link active px-2" data-toggle="tab" href="#id-tab-login" role="tab" aria-controls="id-tab-login" aria-selected="true">
                                                Login
                                            </a>
                                        </li>
                                        <li class="nav-item mx-2">
                                            <a class="nav-link px-2" data-toggle="tab" href="#id-tab-signup" role="tab" aria-controls="id-tab-signup" aria-selected="false">
                                                Signup
                                            </a>
                                        </li>
                                    </ul>


                                    <div class="tab-content tab-sliding border-0 p-0" data-swipe="right">

                                        <div class="tab-pane active show mh-100 px-3 px-lg-0 pb-3" id="id-tab-login">
                                            <!-- show this in desktop -->


                                            <!-- show this in mobile device -->
                                            <div class="text-secondary-m1 my-4 text-center">
                                                <a href="javascript:;">
                                                    @php
                                                        $logo = null;
                                                        if ( isset($settings['bg_login']) ) {
                                                            $logo = json_decode($settings['bg_login']);
                                                            $logo = ( sizeof( $logo ) > 0 ? $logo[0] : null );
                                                        }
                                                    @endphp
                                                    <img src="{{ asset('uploads/SettingSystem/'.$logo) }}" alt="">
                                                </a>
                                                <h1 class="text-170">
                                                    <span class="text-blue-d1">
                                                        {{ $settings['program_name'] }}
                                                    </span>
                                                </h1>
                                                Welcome back
                                            </div>


                                            <form id="FormLogin" autocomplete="off" class="form-row mt-4">
                                                @csrf
                                                <div class="form-group col-sm-10 offset-sm-1 col-md-8 offset-md-2">
                                                    <div class="d-flex align-items-center input-floating-label text-blue brc-blue-m2">
                                                        <input name="username" type="text" class="form-control form-control-lg pr-4 shadow-none" id="id-login-username">
                                                        <i class="fa fa-user text-grey-m2 ml-n4"></i>
                                                        <label class="floating-label text-grey-l1 ml-n3" for="id-login-username">
                                                            Username
                                                        </label>
                                                    </div>
                                                </div>


                                                <div class="form-group col-sm-10 offset-sm-1 col-md-8 offset-md-2 mt-2 mt-md-1">
                                                    <div class="d-flex align-items-center input-floating-label text-blue brc-blue-m2">
                                                        <input name="password" type="password" class="form-control form-control-lg pr-4 shadow-none" id="id-login-password">
                                                        <i class="fa fa-key text-grey-m2 ml-n4"></i>
                                                        <label class="floating-label text-grey-l1 ml-n3" for="id-login-password">
                                                            Password
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="form-group col-sm-10 offset-sm-1 col-md-8 offset-md-2">
                                                    <label class="d-inline-block mt-3 mb-0 text-dark-l1">
                                                        <input type="checkbox" class="mr-1" name="remember_me" id="id-remember-me">
                                                        Remember me
                                                    </label>

                                                    <button type="submit" class="btn btn-primary btn-block px-4 btn-bold mt-2 mb-4">
                                                        Sign In
                                                    </button>
                                                </div>
                                            </form>

                                        </div>


                                    </div><!-- .tab-content -->
                                </div>

                            </div><!-- /.row -->

                        </div><!-- /.col -->
                    </div>

                    <div class="d-lg-none my-3 text-white-tp1 text-center">
                        <i class="fa fa-leaf text-success-l3 mr-1 text-110"></i> Ace Company &copy; 2021
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- include common vendor scripts used in demo pages -->
    <script src="{{ asset('assets/admin/node_modules/jquery/dist/jquery.js') }}"></script>
    <script src="{{ asset('assets/admin/node_modules/popper.js/dist/umd/popper.js') }}"></script>
    <script src="{{ asset('assets/admin/node_modules/bootstrap/dist/js/bootstrap.js') }}"></script>
    <script src="{{asset('assets/admin/node_modules/sweetalert2/dist/sweetalert2.all.js') }}"></script>

    <!-- include vendor scripts used in "Login" page. see "/views//pages/partials/page-login/@vendor-scripts.hbs" -->


    <!-- include ace.js -->
    <script src="{{ asset('assets/admin/dist/js/ace.js') }}"></script>


    <!-- "Login" page script to enable its demo functionality -->
    <script src="{{ asset('assets/admin/views/pages/page-login/@page-script.js') }}"></script>

    {{-- <script src="{{ asset('assets/admin/js/function.js') }}" ></script> --}}

    <script type="text/javascript">

    $('body').on('submit' , '#FormLogin' , function(e){

        e.preventDefault();
        var form = $(this);
        loadingButton(form.find('button[type=submit]'));

        $.ajax({
            method: "POST",
            url: "{{url('admin/CheckLogin')}}",
            dataType: "json",
            data: form.serialize()
        }).done(function( res ) {
            resetButton(form.find('button[type=submit]'));
            if(res.status==0){
                Swal.fire(res.title, res.content, 'error');
                // swal(res.title,res.content,'error');
            }else{
                var url_redirect = "{{$url_redirect}}";
                if(url_redirect){
                    window.location = url_redirect;
                }else{
                    window.location = "{{url('/admin')}}";
                }
            }
        }).fail(function(res){
            var res = $.parseJSON(res.responseText);
            var str = "กรุณาถ่ายรูปหน้าจอนี้ให้กับเจ้าหน้าที่\n\r"+res.message+"\n\r"+res.exception+"\n\r"+res.file+" Line : "+res.line;
            Swal.fire("โอ๊ะโอ! ขอโทษด้วยมีบางอย่างผิดพลาด",str,'error');
            resetButton(form.find('button[type=submit]'));
        });
    });

    function loadingButton(btn){
        var org_text = btn.data('loading');
        var current_text = btn.html();
        if(org_text===undefined){
            org_text = '<i class="fa fa-spinner fa-spin"></i>';
        }
        btn.attr('disabled','disabled');
        btn.html(org_text);
        btn.data('loading' , current_text);
    }

    function resetButton(btn){
        var org_text = btn.data('loading');
        var current_text = btn.html();
        if(!org_text){
            org_text = '<i class="fa fa-refresh fa-spin"></i>';
        }
        btn.removeAttr('disabled','disabled');
        btn.html(org_text);
        btn.data('loading' , current_text);
    }



    </script>
</body>

</html>
