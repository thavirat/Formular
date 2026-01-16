@extends('admin.layouts.default')

@section('css')

@endsection
@section('body')
    <div class="page-content container-fluid container-plus">

        <div class="page-header mb-2 pb-2 flex-column flex-sm-row align-items-start align-items-sm-center py-25 px-1">
            <h1 class="page-title text-primary-d2 text-140"> จัดการสิทธิ์ </h1>
            <div class="page-tools mt-3 mt-sm-0 mb-sm-n1">
            </div>
        </div>
        <form id="FormPermission" >
            <div class="row mt-3 justify-content-center ">

                <div class="col-md-3">
                    <div class="card dcard">
                        <div class="card-body px-1 px-md-3">

                            <div class="d-flex justify-content-between flex-column flex-sm-row mb-3 px-2 px-sm-0">
                                <h3 class="text-125 pl-1 mb-3 mb-sm-0 text-secondary-d4">
                                    รายละเอียดพนักงาน
                                </h3>
                            </div>

                            <table class="table" >
                                <tr>
                                    <td>ชื่อ:</td>
                                    <td>{{ $AdminUser->firstname }} {{ $AdminUser->lastname }}</td>
                                </tr>
                                <tr>
                                    <td>ชื่อสำหรับล็อกอิน:</td>
                                    <td>{{ $AdminUser->username }}</td>
                                </tr>
                                <tr>
                                    <td>เบอร์โทร:</td>
                                    <td>{{ $AdminUser->mobile }}</td>
                                </tr>
                                <tr>
                                    <td>อีเมล์:</td>
                                    <td>{{ $AdminUser->email }}</td>
                                </tr>
                                <tr>
                                    <td>หมายเหตุ:</td>
                                    <td>{{ $AdminUser->remark }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card dcard">
                        <div class="card-body px-1 px-md-3">

                            <div class="d-flex justify-content-between flex-column flex-sm-row mb-3 px-2 px-sm-0">
                                <h3 class="text-125 pl-1 mb-3 mb-sm-0 text-secondary-d4">
                                    สิทธิ์เมนู
                                </h3>
                            </div>
                            @php
                            $permissions_types = ['readed','created','updated','deleted','printed','export_pdf','export_excel'];
                            @endphp
                            <div class="table-responsive">
                                <table id="TableList" class="d-style w-100 table text-dark-m1 text-95 border-y-1 brc-black-tp11 collapsed dtr-table">
                                <thead class="">
                                    <tr>
                                        <th class="text-center"  rowspan="2" colspan="2" >เมนู</th>
                                        <th class="text-center" >เห็นเมนู</th>
                                        <th class="text-center" >เพิ่ม</th>
                                        <th class="text-center" >แก้ไข</th>
                                        <th class="text-center" >ลบ</th>
                                        <th class="text-center" >Print</th>
                                        <th class="text-center" >PDF</th>
                                        <th class="text-center" >EXCEL</th>
                                    </tr>
                                    <tr>
                                        @foreach ($permissions_types as $key => $permissions_type)
                                            <th class="text-center" > <input type="checkbox" class="select_all bgc-blue" data-type="{{ $permissions_type }}" id="select_all_{{ $permissions_type }}" > </th>
                                        @endforeach
                                    </tr>
                                </thead>

                                <tbody class="mt-1">
                                    @php
                                        function GenMenu($Menus, $row_index_str = '', $counter = 0)
                                        {
                                            $str = '';
                                            $rowIndex = 1;
                                            foreach ($Menus as $key => $Menu) {
                                                $tab_str = '';
                                                for ($i=1; $i <= $counter; $i++) {
                                                    $tab_str .= "&emsp;";
                                                }
                                                $str .= "<tr>";
                                                    $str .= "
                                                    <td>{$tab_str}{$row_index_str}{$rowIndex}. {$Menu->title_th} {$Menu->title_en}</td>
                                                    <td class='text-center'> <input type='checkbox' class='select_all_menu bgc-blue' data-id='{$Menu->id}' id='select_all_menu_{$Menu->id}' name='menu[{$Menu->id}][readed]' value='T' > </td>
                                                    <td class='text-center'> <input type='checkbox' class='bgc-blue crud' data-id='{$Menu->id}' id='readed_{$Menu->id}' name='menu[{$Menu->id}][readed]' value='T' > </td>
                                                    <td class='text-center'> <input type='checkbox' class='bgc-blue crud' data-id='{$Menu->id}' id='created_{$Menu->id}' name='menu[{$Menu->id}][created]' value='T' > </td>
                                                    <td class='text-center'> <input type='checkbox' class='bgc-blue crud' data-id='{$Menu->id}' id='updated_{$Menu->id}' name='menu[{$Menu->id}][updated]' value='T' > </td>
                                                    <td class='text-center'> <input type='checkbox' class='bgc-blue crud' data-id='{$Menu->id}' id='deleted_{$Menu->id}' name='menu[{$Menu->id}][deleted]' value='T' > </td>
                                                    <td class='text-center'> <input type='checkbox' class='bgc-blue crud' data-id='{$Menu->id}' id='printed_{$Menu->id}' name='menu[{$Menu->id}][printed]' value='T' > </td>
                                                    <td class='text-center'> <input type='checkbox' class='bgc-blue crud' data-id='{$Menu->id}' id='export_pdf_{$Menu->id}' name='menu[{$Menu->id}][export_pdf]' value='T' > </td>
                                                    <td class='text-center'> <input type='checkbox' class='bgc-blue crud' data-id='{$Menu->id}' id='export_excel_{$Menu->id}' name='menu[{$Menu->id}][export_excel]' value='T' > </td>
                                                    ";
                                                    $str .= "</tr>";
                                                    $str .= GenMenu($Menu->SubMenu, "{$row_index_str}{$rowIndex}.", $counter+1);
                                                    $rowIndex++;
                                                }
                                                return $str;
                                            }
                                        @endphp

                                        {!! GenMenu($Menus->where('main_menu_id', null)) !!}
                                    </tbody>
                                </table>
                            </div><!-- /.card-body -->
                            </div>
                        </div><!-- /.card -->
                    </div><!-- /.col -->

                    <div class="col-md-3">
                        <div class="card dcard">
                            <div class="card-body px-1 px-md-3">

                                <div class="d-flex justify-content-between flex-column flex-sm-row mb-3 px-2 px-sm-0">
                                    <h3 class="text-125 pl-1 mb-3 mb-sm-0 text-secondary-d4">
                                        สิทธิ์อื่นๆ
                                    </h3>
                                </div>

                                <table class="table" >
                                    <tr>
                                        <td>สิทธิ:</td>
                                        <td></td>
                                    </tr>
                                    @foreach ($Permissions as $Permission)
                                        <tr>
                                            <td>{{ $Permission->detail }}</td>
                                            <td>
                                                <input type="checkbox" class="bgc-blue" id="permission{{ $Permission->id }}" {{$Permission->permission == 'T' ? 'checked':''}} name="admin_permission[{{ $Permission->id }}]" value="T" >
                                                <label for="permission{{ $Permission->id }}">มีสิทธิ</label>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mt-3">
                        <div class="form-group">
                            <div class="text-center">
                                <a class="btn btn-danger" href="{{ url('admin/AdminUser') }}"> <i class="fa fa-window-close"></i> ย้อนกลับ</a>
                                <button type="submit" class="btn btn-primary" > <i class="fa fa-save"></i> บันทึก</button>
                            </div>
                        </div>
                    </div>

                </div>
        </form>

    </div>
@endsection
@section('js')
<script type="text/javascript">
    var menus = {!! json_encode($Menus) !!}
    var crud_menus = {!! json_encode($CrudMenus) !!};
    let permissions_types = {!! json_encode($permissions_types) !!};

    $.each(crud_menus, function(index, crud_menu) {
        $.each(permissions_types, function(index_permission, permissions_type) {
            $('[id="'+permissions_type+'_'+crud_menu.menu_id+'"][value="'+crud_menu[permissions_type]+'"]').prop('checked', true);
        });
    });

    checkboxCheckAll()

    $('body').on('click', '.select_all', function(event) {
        var type = $(this).data('type');
        if ( $(this).prop('checked') ) {
            $('[id^='+ type +'_]').prop('checked', true);
        }else{
            $('[id^='+ type +'_]').prop('checked', false);
        }
    });

    $('body').on('click', '.select_all_menu', function(event) {
        var id = $(this).data('id');
        if ( $(this).prop('checked') ) {
            $('.crud[data-id='+ id +']').prop('checked', true);
        }else{
            $('.crud[data-id='+ id +']').prop('checked', false);
        }
    });

    $('body').on('click', '[type="checkbox"]', function(event) {
        checkboxCheckAll();
    });

    function checkboxCheckAll()
    {
        $.each(permissions_types, function(index, permission) {
            if ( $( "input[id^='"+permission+"_']" ).length == $( "input[id^='"+permission+"_']:checked" ).length ) {
                $('#select_all_'+permission).prop('checked', true);
            }else{
                $('#select_all_'+permission).prop('checked', false);
            }
        });

        $.each(menus, function(index, menu) {
            let checked_all = true;
            $.each(permissions_types, function(index, permissions_type) {
                if ( $('#'+permissions_type+'_'+menu.id).prop('checked') == false ) {
                    checked_all = false;
                    return false;
                }
            });
            $('#select_all_menu_'+menu.id).prop('checked', checked_all);

        });

    }

    $('#FormPermission').validate({
        errorElement: 'div',
        errorClass: 'invalid-feedback',
        focusInvalid: false,
        rules: {

        },
        messages: {

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
                url : url_gb+"/admin/AdminUser/{{ $AdminUser->id }}/permission",
                dataType : 'json',
                data : $(form).serialize()
            }).done(function(rec){
                resetButton(btn)
                if(rec.status==1){
                    Swal.fire(rec.title,rec.content,"success").then(() => {
                        window.location.href = url_gb + '/admin/AdminUser';
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
