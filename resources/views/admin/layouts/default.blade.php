<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <base href="../" />

    <title>Dashboard - Ace Admin</title>

    <!-- include common vendor stylesheets & fontawesome -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/admin/node_modules/bootstrap/dist/css/bootstrap.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('assets/admin/node_modules/@fortawesome/fontawesome-free/css/fontawesome.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/admin/node_modules/@fortawesome/fontawesome-free/css/regular.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/admin/node_modules/@fortawesome/fontawesome-free/css/brands.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/admin/node_modules/@fortawesome/fontawesome-free/css/solid.css')}}">

    <!-- include vendor stylesheets used in "DataTables" page. see "/views//pages/partials/table-datatables/@vendor-stylesheets.hbs" -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/admin/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/admin/node_modules/datatables.net-buttons-bs4/css/buttons.bootstrap4.css') }}">


    <!-- include fonts -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/admin/dist/css/ace-font.css')}}">

    {{-- Orak Upload --}}
    <link rel="stylesheet" type="text/css" href="{{asset('assets/global/plugin/orakuploader/orakuploader.css')}}">

    {{-- select 2 --}}
    <link rel="stylesheet" type="text/css" href="{{asset('assets/admin/node_modules/select2/dist/css/select2.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{asset('assets/admin/scss/plugins/select2.scss') }}" >

    {{-- Datepicker --}}
    <link rel="stylesheet" type="text/css" href="{{asset('assets/global/plugin/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}" >

    <!-- ace.css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/admin/dist/css/ace.css')}}">

    <!-- Font -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/admin/assets/fonts/Sarabun/sarabun.css')}}">

    <!-- favicon -->
    <link rel="icon" type="image/png" href="{{asset('assets/admin/assets/favicon.png')}}" />

    <!-- "Dashboard" page styles, specific to this page for demo only -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/admin//views/pages/dashboard/@page-style.css')}}">

    <!-- custom.css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/admin/css/custom.css')}}">

    @yield('css')
</head>

<body>
    <div class="body-container">
        <nav class="navbar navbar-expand-lg navbar-fixed navbar-blue">
            <div class="navbar-inner">

                <div class="navbar-intro justify-content-xl-between">

                    <button type="button" class="btn btn-burger burger-arrowed static collapsed ml-2 d-flex d-xl-none" data-toggle-mobile="sidebar" data-target="#sidebar" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle sidebar">
                        <span class="bars"></span>
                    </button><!-- mobile sidebar toggler button -->

                    <a class="navbar-brand text-white" href="{{ url('admin') }}">
                        @php
                            $logo = null;
                            if ( isset($settings['logo']) ) {
                                $logo = json_decode($settings['logo']);
                                $logo = ( sizeof( $logo ) > 0 ? $logo[0] : null );
                            }
                        @endphp
                        <img src="{{ asset('uploads/SettingSystem/'.$logo) }}" style="width:52px;height:58px;" alt="">
                        <span>{{ $settings['program_name'] }}</span>
                    </a><!-- /.navbar-brand -->

                    <button type="button" class="btn btn-burger mr-2 d-none d-xl-flex" data-toggle="sidebar" data-target="#sidebar" aria-controls="sidebar" aria-expanded="true" aria-label="Toggle sidebar">
                        <span class="bars"></span>
                    </button><!-- sidebar toggler button -->

                </div><!-- /.navbar-intro -->

                @php
                $img = '';
                if ( Auth::user()->photo ) {
                    $photos = json_decode(Auth::user()->photo);
                    if ( sizeof($photos) > 0 ) {
                        $img = asset('uploads/AdminUser/'.$photos[0]);
                    }
                }
                @endphp

                <!-- mobile #navbarMenu toggler button -->
                <button class="navbar-toggler ml-1 mr-2 px-1" type="button" data-toggle="collapse" data-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navbar menu">
                    <span class="pos-rel">
                        @if ($img)
                            <img class="border-2 brc-white-tp1 radius-round" width="36" src="{{ $img }}" alt="Jason's Photo">
                        @endif
                        <span class="bgc-warning radius-round border-2 brc-white p-1 position-tr mr-n1px mt-n1px"></span>
                    </span>
                </button>

                <div class="navbar-menu collapse navbar-collapse navbar-backdrop" id="navbarMenu">

                    <div class="navbar-nav">
                        <ul class="nav">

                            {{-- dropdown profile --}}
                            <li class="nav-item dropdow\n order-first order-lg-last">
                                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                    @if ($img)
                                        <img id="id-navbar-user-image" class="d-none d-lg-inline-block radius-round border-2 brc-white-tp1 mr-2 w-6" src="{{ $img }}">
                                    @endif
                                    <span class="d-inline-block d-lg-none d-xl-inline-block">
                                        <span class="nav-user-name">
                                            {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}
                                        </span>
                                    </span>

                                    <i class="caret fa fa-angle-down d-none d-xl-block"></i>
                                    <i class="caret fa fa-angle-left d-block d-lg-none"></i>
                                </a>

                                <div class="dropdown-menu dropdown-caret dropdown-menu-right dropdown-animated brc-primary-m3 py-1">
                                    <div class="d-none d-lg-block d-xl-none">
                                        <div class="dropdown-header">
                                            <span class="nav-user-name">
                                            </span>
                                        </div>
                                        <div class="dropdown-divider"></div>
                                    </div>
                                    @if ( isset($my_menu_permission['Profile']['u']) && $my_menu_permission['Profile']['u'] == 'T' )
                                    <a class="mt-1 dropdown-item btn btn-outline-grey bgc-h-primary-l3 btn-h-light-primary btn-a-light-primary" href="{{ url('admin/Profile') }}">
                                        <i class="fa fa-user-circle text-primary-m1 text-105 mr-1"></i>
                                        ข้อมูลส่วนตัว
                                    </a>
                                    @endif

                                    @if ( isset($my_menu_permission['SettingSystem']['u']) && $my_menu_permission['SettingSystem']['u'] == 'T' )
                                        <a class="dropdown-item btn btn-outline-grey bgc-h-success-l3 btn-h-light-success btn-a-light-success" href="{{ url('admin/SettingSystem') }}">
                                            <i class="fa fa-cog text-success-m1 text-105 mr-1"></i>
                                            ตั้งค่าระบบ
                                        </a>
                                    @endif

                                    <div class="dropdown-divider brc-primary-l2"></div>

                                    <a class="dropdown-item btn btn-outline-grey bgc-h-secondary-l3 btn-h-light-secondary btn-a-light-secondary" href="{{ url('admin/Logout') }}">
                                        <i class="fa fa-power-off text-warning-d1 text-105 mr-1"></i>
                                        ออกจากระบบ
                                    </a>
                                </div>
                            </li><!-- /.nav-item:last -->

                        </ul><!-- /.navbar-nav menu -->
                    </div><!-- /.navbar-nav -->

                </div><!-- /#navbarMenu -->


            </div><!-- /.navbar-inner -->
        </nav>
        <div class="main-container bgc-white">

            <div id="sidebar" class="sidebar sidebar-fixed expandable sidebar-light">
                <div class="sidebar-inner">

                    <div class="ace-scroll flex-grow-1" data-ace-scroll="{}">
                        <ul class="nav has-active-border active-on-right">

                            @php
                                function recursiveMenu($Menus, $currentMenu = null)
                                {
                                    $htmlMenu = '';
                                    foreach ($Menus as $key => $Menu) {
                                        $SubMenu = ( sizeof($Menu->SubMenu) > 0 ? null : url('admin/'.$Menu->url ) );
                                        $dropdown_toggle = ( sizeof($Menu->SubMenu) > 0 ? 'dropdown-toggle collapsed' : '' );
                                        $htmlMenu .= '
                                                        <li class="nav-item nav-item-menu '. ( $currentMenu && $currentMenu->url == $Menu->url ? 'active' : '' ) .'" id="'. ( $currentMenu && $currentMenu->url == $Menu->url ? 'active_menu' : '' ) .'" >
                                                            <a href="'.$SubMenu.'" class="nav-link '.$dropdown_toggle.'">
                                                                '.( $Menu->icon ? '<i class=" nav-icon '.$Menu->icon.'"></i>' : '' ).'
                                                                <span class="nav-text fadeable">
                                                                    <span>'.$Menu->title.'</span>
                                                                </span>
                                                                '. ( !$SubMenu ? '<b class="caret fa fa-angle-left rt-n90"></b>' : '' ) .'
                                                            </a>
                                                            ';

                                        if (!$SubMenu) {
                                            $htmlMenu .= '
                                                            <div class="hideable submenu collapse">
                                                                <ul class="submenu-inner">
                                                        ';
                                            $htmlMenu .= recursiveMenu($Menu->SubMenu, $currentMenu);
                                            $htmlMenu .='
                                                                </ul>
                                                            </div>
                                                        ';
                                        }

                                        $htmlMenu .= '
                                                        <b class="sub-arrow"></b>
                                                        </li>
                                                    ';

                                    }
                                    return $htmlMenu;
                                }

                            @endphp

                            {!! recursiveMenu($SidebarMenus, $currentMenu) !!}

                        </ul>
                    </div>
                </div>
            </div>
            <div role="main" class="main-content">
                @yield('body')
            </div>

        </div>
    </div>

    <!-- include common vendor scripts used in demo pages -->
    <script src="{{asset('assets/admin/node_modules/jquery/dist/jquery.js')}}"></script>
    <script src="{{asset('assets/global/plugin/orakuploader/jquery-ui.min.js') }}"></script>
    <script src="{{asset('assets/admin/node_modules/popper.js/dist/umd/popper.js')}}"></script>
    <script src="{{asset('assets/admin/node_modules/bootstrap/dist/js/bootstrap.js')}}"></script>
    <script src="{{asset('assets/admin/node_modules/typeahead.js/dist/typeahead.bundle.js')}}" > </script>

    <!-- include vendor scripts used in "DataTables" page. see "/views//pages/partials/table-datatables/@vendor-scripts.hbs" -->
    <script src="{{asset('assets/admin/node_modules/datatables/media/js/jquery.dataTables.js')}}"></script>
    <script src="{{asset('assets/admin/node_modules/datatables.net-bs4/js/dataTables.bootstrap4.js')}}"></script>
    <script src="{{asset('assets/admin/node_modules/datatables.net-colreorder/js/dataTables.colReorder.js')}}"></script>
    <script src="{{asset('assets/admin/node_modules/datatables.net-select/js/dataTables.select.js')}}"></script>


    <script src="{{asset('assets/admin/node_modules/datatables.net-buttons/js/dataTables.buttons.js')}}"></script>
    <script src="{{asset('assets/admin/node_modules/datatables.net-buttons-bs4/js/buttons.bootstrap4.js')}}"></script>
    <script src="{{asset('assets/admin/node_modules/datatables.net-buttons/js/buttons.html5.js')}}"></script>
    <script src="{{asset('assets/admin/node_modules/datatables.net-buttons/js/buttons.print.js')}}"></script>
    <script src="{{asset('assets/admin/node_modules/datatables.net-buttons/js/buttons.colVis.js')}}"></script>


    <script src="{{asset('assets/admin/node_modules/datatables.net-responsive/js/dataTables.responsive.js') }}"></script>


    {{-- Sweet Alert --}}
    <script src="{{asset('assets/admin/node_modules/sweetalert2/dist/sweetalert2.all.js') }}"></script>

    <!-- include vendor scripts used in "Dashboard" page. see "/views//pages/partials/dashboard/@vendor-scripts.hbs" -->
    <script src="{{asset('assets/admin/node_modules/chart.js/dist/Chart.js')}}"></script>


    <script src="{{asset('assets/admin/node_modules/sortablejs/Sortable.js')}}"></script>

    <script type="text/javascript">

        var url_gb = '{{ url('') }}';
        var asset_gb = '{{ asset('') }}';
        var height = window.innerHeight;

        let menu_element = $('.active-on-right').find('#active_menu');
        let nested_count = 0;
        while ( $(menu_element).length != 0 && nested_count < 5 ) {
            $(menu_element).closest('.submenu').addClass('show');
            $(menu_element).closest('ul').closest('li').addClass('open')
            $(menu_element).closest('ul').closest('li').addClass('active');
            $(menu_element).closest('.hideable').addClass('show');
            menu_element = $(menu_element).closest('ul').closest('li');
            nested_count++;
        }


    </script>

    <!-- include ace.js -->
    <script src="{{asset('assets/admin/dist/js/ace.js')}}"></script>

    {{-- Orak Upload --}}
    <script src="{{asset('assets/global/plugin/orakuploader/orakuploader.js') }}"></script>

    {{-- select2 --}}
    <script src="{{asset('assets/admin/node_modules/select2/dist/js/select2.js') }}"></script>

    {{-- Datepicker --}}
    <script src="{{asset('assets/global/plugin/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"> </script>
    <script src="{{asset('assets/global/plugin/bootstrap-datepicker/dist/locales/bootstrap-datepicker.th.min.js') }}"> </script>
    <script src="{{asset('assets/global/plugin/bootstrap-datepicker-BE/bootstrap-datepicker-BE.js') }}"> </script>


    <script src="{{asset('assets/global/js/modal.js')}}"></script>
    <script src="{{asset('assets/admin/js/function.js')}}"></script>

    {{-- Jquery Validate --}}
    <script src="{{asset('assets/admin/node_modules/jquery-validation/dist/jquery.validate.js')}}"></script>
    <script src="{{asset('assets/global/js/validate.js')}}"></script>

    <script src="{{asset('assets/global/plugin/parsleyjs/dist/parsley.min.js')}}"></script>

    {{-- typeahead --}}

    @yield('js')
    @stack('scripts')
</body>

</html>
