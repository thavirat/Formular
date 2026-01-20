@extends('admin.layouts.default')
@section('css')
@endsection
@section('body')
    <div class="page-content container container-plus">
        <div class="page-header mb-2 pb-2 flex-column flex-sm-row align-items-start align-items-sm-center py-25 px-1">
            <h1 class="page-title text-primary-d2 text-140"> {{ $currentMenu->title }} </h1>
            <div class="page-tools mt-3 mt-sm-0 mb-sm-n1">
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
                                    <th>เมนูหลัก</th>
                                    <th>ชื่อเมนู</th>
                                    <th>ไอค่อน</th>
                                    <th>ลิงค์</th>
                                    <th>ลำดับ</th>
                                    <th>แสดง</th>
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
    <div class="modal modal-lg fade" id="ModalAdd" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="" id="FormAdd" >
                    <div class="modal-header">
                        <h5 class="modal-title text-primary-d3" id="exampleModalLabel">
                            เพิ่มข้อมูล{{ $currentMenu->title }}
                        </h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="add_main_menu_id" class=""> เมนูหลัก </label>
                                    <br>
                                    <select class="form-control w-100" id="add_main_menu_id"  tabindex="0" name="main_menu_id"  >
                                        <option value="">เมนูหลัก</option>
                                        @foreach ($Menus as $Menu)
                                            <option value="{{ $Menu->id }}">{{ $Menu->title_th }} {{ $Menu->title_en }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="add_title" class=""> ชื่อเมนูไทย </label>
                                    <input type="text" class="form-control" name="title_th" id="add_title" value="">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="add_title_en" class=""> ชื่อเมนูอังกฤษ </label>
                                    <input type="text" class="form-control" name="title_en" id="add_title_en" value="">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="add_icon">ไอคอน</label>
                                    <input type="text" class="form-control" name="icon" id="add_icon" value="">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="add_url">URL</label>
                                    <input type="text" class="form-control" name="url" id="add_url" value="">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="add_sort_id">ลำดับ</label>
                                    <input type="text" class="form-control number-only" name="sort_id" id="add_sort_id" value="">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="add_show">สถานะการแสดง</label>
                                    <select class="form-control" name="show" id="add_show" >
                                        <option value="">เลือกแสดงผล</option>
                                        <option value="T">เปิดแสดงผล</option>
                                        <option value="F">ปิดแสดงผล</option>
                                    </select>
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
    <div class="modal modal-lg fade" id="ModalEdit" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="" id="FormEdit" >
                    <input type="hidden" id="edit_id" value="">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary-d3" id="exampleModalLabel">
                            แก้ไขข้อมูล{{ $currentMenu->title }}
                        </h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_main_menu_id" class=""> เมนูหลัก </label>
                                    <br>
                                    <select class="form-control w-100" id="edit_main_menu_id" name="main_menu_id" >
                                        <option value="">เมนูหลัก</option>
                                        @foreach ($Menus as $Menu)
                                            <option value="{{ $Menu->id }}">{{ $Menu->title_th }} | {{ $Menu->title_en }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_title_th" class=""> ชื่อเมนูไทย </label>
                                    <input type="text" class="form-control" name="title_th" id="edit_title_th" value="">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_title_en" class=""> ชื่อเมนูอังกฤษ </label>
                                    <input type="text" class="form-control" name="title_en" id="edit_title_en" value="">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_icon">ไอคอน</label>
                                    <input type="text" class="form-control" name="icon" id="edit_icon" value="">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_url">URL</label>
                                    <input type="text" class="form-control" name="url" id="edit_url" value="">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_sort_id">ลำดับ</label>
                                    <input type="text" class="form-control number-only" name="sort_id" id="edit_sort_id" value="">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_show">สถานะการแสดง</label>
                                    <select class="form-control" id="edit_show" name="show">
                                        <option value="">เลือกแสดงผล</option>
                                        <option value="T">เปิดแสดงผล</option>
                                        <option value="F">ปิดแสดงผล</option>
                                    </select>
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

        		"pageLength": 50,
        		"ajax": {
        			"method" : "GET",
        			"url": url_gb+"/admin/Menu/Lists",
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
        			{"data" : "main_menu_title", "name": 'main_menus.title_th'},
        			{"data" : "title_th"},
        			{"data" : "url"},
        			{"data" : "icon"},
        			{"data" : "sort_id"},
        			{"data" : "show" , "searchable" : false, "className": "text-center"},
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

        $('body').on('click', '.btn-add', function(event) {
            $('#ModalAdd').modal('show');
        });


        $('body').on('click', '.btn-edit', function(event) {
            var btn = $(this);
            loadingButton(btn);
            var id = $(this).data('id');
            $('#edit_id').val(id);
            $.ajax({
                method : "GET",
                url : url_gb+"/admin/Menu/"+id,
                dataType : 'json'
            }).done(function(rec){

                $('#edit_main_menu_id').val(rec.main_menu_id).trigger('change');
                $('#edit_title').val(rec.title);
                $('#edit_icon').val(rec.icon);
                $('#edit_url').val(rec.url);
                $('#edit_sort_id').val(rec.sort_id);
                $('#edit_show').val(rec.show);
                $('#edit_title_th').val(rec.title_th);
                $('#edit_title_en').val(rec.title_en);

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

                title: {
                    required: true,
                },

                url: {
                    required: true,
                },

            },
            messages: {

                title: {
                    required: 'กรุณาระบบ',
                },

                url: {
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
                loadingButton(btn);
                $.ajax({
                    method : "POST",
                    url : url_gb+"/admin/Menu",
                    dataType : 'json',
                    data : $(form).serialize()
                }).done(function(rec){
                    resetButton(btn)
                    if(rec.status==1){
                        TableList.api().ajax.reload();
                        resetFormCustom(form);
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

            title: {
                required: true,
            },

            url: {
                required: true,
            },

        },
        messages: {

            title: {
                required: 'กรุณาระบบ',
            },

            url: {
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
            var id = $('#edit_id').val();
            loadingButton(btn);
            $.ajax({
                method : "PUT",
                url : url_gb+"/admin/Menu/"+id,
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
                    url: url_gb+"/admin/Menu/"+id,
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
                    resetButton(btn);
                    var res = $.parseJSON(res.responseText);
                    var str = "กรุณาถ่ายรูปหน้าจอนี้ให้กับเจ้าหน้าที่\n\r"+res.message+"\n\r"+res.exception+"\n\r"+res.file+" Line : "+res.line;
                    Swal.fire("โอ๊ะโอ! ขอโทษด้วยมีบางอย่างผิดพลาด",str,'error');
                });
            } else {
                resetButton(btn);
            }
        });
    });

    $('#add_main_menu_id').select2()
    $('#edit_main_menu_id').select2()

</script>
@endsection
