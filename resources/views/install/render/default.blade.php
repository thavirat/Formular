@@extends('admin.layouts.default')

@@section('title', $currentMenu->title)

@@push('css')

@@endpush

@@section('body')
<div class="page-content container container-plus">
    <div class="page-header mb-2 pb-2 flex-column flex-sm-row align-items-start align-items-sm-center py-25 px-1">
        <h1 class="page-title text-primary-d2 text-140">@{{ $currentMenu->title }} </h1>
        <div class="page-tools mt-3 mt-sm-0 mb-sm-n1">
             @@if ( $my_menu_permission[$currentMenu->url]['p'] == 'T' )
             @php
                $exportPrintUrl = "{{ url(\"admin/".$model_name."/ExportPrint\") }}";
             @endphp
    <a href="{!! html_entity_decode($exportPrintUrl) !!}" target="_blank" class="btn btn-light-warning btn-h-warning btn-a-warning border-0 radius-3 py-2 text-600 text-90">
                    <span class="d-none d-sm-inline mr-1">
                        Print
                    </span>
                    <i class="fas fa-print text-110 w-2 h-2"></i>
                </a>
            @@endif
            @@if ( $my_menu_permission[$currentMenu->url]['ep'] == 'T' )
                @php
                    $exportPdfUrl = "{{ url(\"admin/".$model_name."/ExportPDF\") }}";
                @endphp
<a href="{!! html_entity_decode($exportPdfUrl) !!}" target="_blank" class="btn btn-light-danger btn-h-danger btn-a-danger border-0 radius-3 py-2 text-600 text-90">
                    <span class="d-none d-sm-inline mr-1">
                        PDF
                    </span>
                    <i class="fas fa-file-pdf text-110 w-2 h-2"></i>
                </a>
            @@endif
            @@if ( $my_menu_permission[$currentMenu->url]['ee'] == 'T' )
                @php
                    $exportExcelUrl = "{{ url(\"admin/".$model_name."/ExportExcel\") }}";
                @endphp
<a href="{!! html_entity_decode($exportExcelUrl) !!}" target="_blank" class="btn btn-light-primary btn-h-primary btn-a-primary border-0 radius-3 py-2 text-600 text-90">
                    <span class="d-none d-sm-inline mr-1">
                        Excel
                    </span>
                    <i class="fas fa-file-excel text-110 w-2 h-2"></i>
                </a>
            @@endif
            @@if ( $my_menu_permission[$currentMenu->url]['c'] == 'T' )
                <button type="button" class="btn btn-light-green btn-h-green btn-a-green border-0 radius-3 py-2 text-600 text-90 btn-add">
                    <span class="d-none d-sm-inline mr-1">
                        เพิ่มข้อมูล
                    </span>
                    <i class="fa fa-plus text-110 w-2 h-2"></i>
                </button>
            @@endif
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

                    <table id="table{{ $model_name }}" class="table table-border-x brc-secondary-l4 border-0 mb-0 w-100">
                        <thead class="text-dark-tp3 bgc-grey-l4 text-90 border-b-1 brc-transparent">
                            <tr>
                                <th class="text-center" width="5%">ลำดับ</th>
@foreach ($field_in_table as $name=>$val)
@if (isset($name_in_table[$name]))
                                <th>{{ $name_in_table[$name] }}</th>
@endif
@endforeach
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
    <div class="modal fade {{$modal_size}}" id="ModalAdd" role="dialog" aria-labelledby="ModalAddLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="FormAdd" data-parsley-validate="true">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary-d3" id="ModalAddLabel">
                            เพิ่มข้อมูล@{{ $currentMenu->title }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
<?php $i = 0; ?>
@foreach ($field_in_form as $k => $input)
@if ($input == 'on')
                            @include('install.skeleton.input_'.$input_in_form[$k],['name'=>$k , 'id'=>'add_'.$k ,'label'=>$name_in_form[$k] , 'radio'=>(isset($radio[$k]) ? $radio[$k]:''), 'checkbox'=>(isset($checkbox[$k]) ? $checkbox[$k]:'') , 'index_show'=>$i])
@php $i++; @endphp
@endif
@endforeach
                        </div>
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
    <div class="modal fade {{$modal_size}}" id="ModalEdit" role="dialog" aria-labelledby="ModalEditLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="FormEdit" data-parsley-validate="true">
                    <input type="hidden" id="{{$model_name}}_edit_id">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary-d3" id="ModalEditLabel">
                            แก้ไขข้อมูล@{{ $currentMenu->title }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">

@foreach ($field_in_form as $k => $input)
@if ($input == 'on')
                            @include('install.skeleton.input_'.$input_in_form[$k],['name'=>$k , 'id'=>'edit_'.$k ,'label'=>$name_in_form[$k] , 'radio'=>(isset($radio[$k]) ? $radio[$k]:''), 'checkbox'=>(isset($checkbox[$k]) ? $checkbox[$k]:'') , 'index_show'=>$i])
@php $i++; @endphp
@endif
@endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary px-4" data-dismiss="modal"> <i class="fa fa-window-close"></i> ปิด </button>
                        <button type="submit" class="btn btn-primary"> <i class="fa fa-save"></i> บันทึก </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@@endsection

@@push('scripts')
<script type="text/javascript">

    var table{{ $model_name }} = $('#table{{ $model_name }}').dataTable({

        "ajax": {
            "url": url_gb+"/admin/{{$model_name}}/Lists",
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
@foreach($field_in_table as $k=>$val)
            {"data": "{{$k}}", "name": '{{$k}}'},
@endforeach
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
            url: url_gb+"/admin/{{$model_name}}",
            dataType : "json",
            data: form.serialize()
        }).done(function( res ) {
            resetButton(form.find('button[type=submit]'));
            if(res.status == 1){
                Swal.fire(res.title, res.content,'success');
                resetFormCustom(form);
                @if(isset($select_type))
                @foreach($select_type as $k => $type)
                @if($type == 'select2')
                @endif
                @endforeach
                @endif
table{{ $model_name }}.api().ajax.reload();
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
        var id = $("#{{$model_name}}_edit_id").val();
        var form = $(this);
        loadingButton(form.find('button[type=submit]'));
        $.ajax({
            method: "PUT",
            url: url_gb+"/admin/{{$model_name}}/"+id,
            dataType : 'json',
            data: form.serialize()
            }).done(function( res ) {
                resetButton(form.find('button[type=submit]'));
                if(res.status == 1){
                    Swal.fire(res.title, res.content, 'success');
                    resetFormCustom(form);
                    table{{$model_name}}.api().ajax.reload();
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
                    url: url_gb+"/admin/{{$model_name}}/"+id,
                    dataType : 'json',
                }).done(function( res ) {
                    if(res.status == 1){
                        Swal.fire(res.title, res.content,'success');
                        table{{$model_name}}.api().ajax.reload();
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
        $("#{{$model_name}}_edit_id").val(id);
        var btn = $(this);
        loadingButton(btn);
        $.ajax({
            method: "GET",
            url: url_gb+"/admin/{{$model_name}}/"+id,
            dataType: 'json',
        }).done(function( res ) {
            resetButton(btn);
            @foreach($field_in_form as $k=>$input)
            @if($input == "on")
            @include('install.skeleton.edit_'.$input_in_form[$k],['name'=>$k , 'id'=>'edit_'.$k ,'label'=>$name_in_form[$k] , 'radio'=>(isset($radio[$k]) ? $radio[$k]:''), 'checkbox'=>(isset($checkbox[$k]) ? $checkbox[$k]:'') ,'select_type'=>(isset($select_type[$k]) ? $select_type[$k] : '')])
            @endif
            @endforeach

            $('#ModalEdit').modal('show');
        }).fail(function(res){
            ajaxFail(res , "");
        });
    });

@foreach($field_in_form as $k => $input)
@if($input == "on")
@include('install.skeleton.init_js_'.$input_in_form[$k],['name'=>$k , 'id'=>'add_'.$k ,'label'=>$name_in_form[$k] , 'radio'=>(isset($radio[$k]) ? $radio[$k]:''), 'checkbox'=>(isset($checkbox[$k]) ? $checkbox[$k]:'')])
@endif
@endforeach

@if(isset($select_type))
@foreach($select_type as $k => $type)
@if($type == 'select2')
 $("#add_{{ $k }}").select2({
     placeholder: 'กรุณาเลือก',
     allowClear: true
 })
 $("#edit_{{ $k }}").select2({
     placeholder: 'กรุณาเลือก',
     allowClear: true
 })
@endif
@endforeach
@endif

</script>
@@endpush
