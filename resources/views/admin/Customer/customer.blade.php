@extends('admin.layouts.default')

@section('title', $currentMenu->title)

@push('css')

@endpush

@section('body')
<div class="page-content container-fluid container-plus">
    <div class="page-header mb-2 pb-2 flex-column flex-sm-row align-items-start align-items-sm-center py-25 px-1">
        <h1 class="page-title text-primary-d2 text-140">{{ $currentMenu->title }} </h1>
        <div class="page-tools mt-3 mt-sm-0 mb-sm-n1">
             @if( $my_menu_permission[$currentMenu->url]['p'] == 'T' )
                 <a href="{{ url("admin/Customer/ExportPrint") }}" target="_blank" class="btn btn-light-warning btn-h-warning btn-a-warning border-0 radius-3 py-2 text-600 text-90">
                    <span class="d-none d-sm-inline mr-1">
                        Print
                    </span>
                    <i class="fas fa-print text-110 w-2 h-2"></i>
                </a>
            @endif
            @if( $my_menu_permission[$currentMenu->url]['ep'] == 'T' )
                <a href="{{ url("admin/Customer/ExportPDF") }}" target="_blank" class="btn btn-light-danger btn-h-danger btn-a-danger border-0 radius-3 py-2 text-600 text-90">
                    <span class="d-none d-sm-inline mr-1">
                        PDF
                    </span>
                    <i class="fas fa-file-pdf text-110 w-2 h-2"></i>
                </a>
            @endif
            @if( $my_menu_permission[$currentMenu->url]['ee'] == 'T' )
                <a href="{{ url("admin/Customer/ExportExcel") }}" target="_blank" class="btn btn-light-primary btn-h-primary btn-a-primary border-0 radius-3 py-2 text-600 text-90">
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

                    <div class="table-responsive">
                        <table id="tableCustomer" class="table table-striped table-bordered dt-responsive w-100">
                            <thead class="text-dark-tp3 bgc-grey-l4 text-90 border-b-1 brc-transparent">
                                <tr>
                                    <th class="text-center" width="5%">{{__('No')}}</th>
                                    <th>{{__('Contact Name')}}</th>
                                    <th>{{__('Company Name')}}</th>
                                    <th>{{__('Tax ID')}}</th>
                                    <th>{{__('Remark')}}</th>
                                    <th class="text-center">#</th>
                                </tr>
                            </thead>

                            <tbody class="mt-1">
                            </tbody>
                        </table>
                    </div>


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
                                    <label for="add_contact_name">Contact Name</label>
                                    <input type="text" name="contact_name" id="add_contact_name" class="form-control autofocus" >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="add_company_name">Company Name</label>
                                    <input type="text" name="company_name" id="add_company_name" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="add_level_id">{{__('Customer Level')}}</label>
                                    <select name="level_id" id="add_level_id" class="form-control">
                                        <option value="">{{__('Select Customer Level')}}</option>
                                        @foreach($CustomerLevels as $level)
                                        <option value="{{$level->id}}">{{$level->name}}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>


                            <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="add_address">Address</label>
                                <textarea name="address" id="add_address" rows="5" class="form-control " ></textarea>
                            </div>
                        </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="add_country">Country (ประเทศ)</label>
                                    <input type="text" name="country" id="add_country" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="add_region">Region (ภูมิภาค)</label>
                                    <select name="region" id="add_region" class="form-control">
                                        <option value="">— ไม่ระบุ —</option>
                                        <option value="ASIA">ASIA</option>
                                        <option value="MIDDLEAST">MIDDLE EAST</option>
                                        <option value="EUROPE">EUROPE</option>
                                        <option value="CHAMP">CHAMP</option>
                                        <option value="OTHER">OTHER</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="add_tax_id">Tax ID</label>
                                    <input type="text" name="tax_id" id="add_tax_id" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="add_phone">Phone</label>
                                    <input type="text" name="phone" id="add_phone" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="add_mobile">Mobile</label>
                                    <input type="text" name="mobile" id="add_mobile" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="add_fax">Fax</label>
                                    <input type="text" name="fax" id="add_fax" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="add_remark">Remark</label>
                                    <input type="text" name="remark" id="add_remark" class="form-control " >
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label class="d-block">Shipping Mark ประจำลูกค้า <small class="text-muted">(มีได้หลายชุด · เลือกชุดเริ่มต้นได้ · ใช้เติมช่อง Shipping Marks ในใบ PI)</small></label>
                                    <table class="table table-bordered table-sm mb-1" id="add_sm_table">
                                        <thead class="bgc-grey-l3">
                                            <tr>
                                                <th width="70" class="text-center">เริ่มต้น</th>
                                                <th width="180">ชื่อชุด</th>
                                                <th>Shipping Mark (หลายบรรทัดได้)</th>
                                                <th width="40"></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr><td colspan="4">
                                                <button type="button" class="btn btn-outline-primary btn-sm sm-add-row" data-prefix="add"><i class="fa fa-plus"></i> เพิ่มชุด Shipping Mark</button>
                                            </td></tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label class="d-block">หมายเหตุประจำลูกค้า (Remark) <small class="text-muted">(มีได้หลายชุด · เปิด/ปิดได้ · ปุ่มโหลดในใบ PI จะดึงเฉพาะที่เปิดอยู่มาทับ)</small></label>
                                    <table class="table table-bordered table-sm mb-1" id="add_cr_table">
                                        <thead class="bgc-grey-l3">
                                            <tr>
                                                <th width="70" class="text-center">เปิดใช้</th>
                                                <th>หมายเหตุ (หลายบรรทัดได้)</th>
                                                <th width="40"></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr><td colspan="3">
                                                <button type="button" class="btn btn-outline-primary btn-sm cr-add-row" data-prefix="add"><i class="fa fa-plus"></i> เพิ่มหมายเหตุ</button>
                                            </td></tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

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
    <div class="modal fade modal-lg" id="ModalEdit" role="dialog" aria-labelledby="ModalEditLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="FormEdit" data-parsley-validate="true">
                    <input type="hidden" id="Customer_edit_id">
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
                                    <label for="edit_contact_name">Contact Name</label>
                                    <input type="text" name="contact_name" id="edit_contact_name" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_company_name">Company Name</label>
                                    <input type="text" name="company_name" id="edit_company_name" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_level_id">{{__('Customer Level')}}</label>
                                    <select name="level_id" id="edit_level_id" class="form-control">
                                        <option value="">{{__('Select Customer Level')}}</option>
                                        @foreach($CustomerLevels as $level)
                                        <option value="{{$level->id}}">{{$level->name}}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="edit_address">Address</label>
                                <textarea name="address" id="edit_address" rows="5" class="form-control " ></textarea>
                            </div>
                        </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_country">Country (ประเทศ)</label>
                                    <input type="text" name="country" id="edit_country" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="edit_region">Region (ภูมิภาค)</label>
                                    <select name="region" id="edit_region" class="form-control">
                                        <option value="">— ไม่ระบุ —</option>
                                        <option value="ASIA">ASIA</option>
                                        <option value="MIDDLEAST">MIDDLE EAST</option>
                                        <option value="EUROPE">EUROPE</option>
                                        <option value="CHAMP">CHAMP</option>
                                        <option value="OTHER">OTHER</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_tax_id">Tax ID</label>
                                    <input type="text" name="tax_id" id="edit_tax_id" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_phone">Phone</label>
                                    <input type="text" name="phone" id="edit_phone" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_mobile">Mobile</label>
                                    <input type="text" name="mobile" id="edit_mobile" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_fax">Fax</label>
                                    <input type="text" name="fax" id="edit_fax" class="form-control " >
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_remark">Remark</label>
                                    <input type="text" name="remark" id="edit_remark" class="form-control " >
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label class="d-block">Shipping Mark ประจำลูกค้า <small class="text-muted">(มีได้หลายชุด · เลือกชุดเริ่มต้นได้ · ใช้เติมช่อง Shipping Marks ในใบ PI)</small></label>
                                    <table class="table table-bordered table-sm mb-1" id="edit_sm_table">
                                        <thead class="bgc-grey-l3">
                                            <tr>
                                                <th width="70" class="text-center">เริ่มต้น</th>
                                                <th width="180">ชื่อชุด</th>
                                                <th>Shipping Mark (หลายบรรทัดได้)</th>
                                                <th width="40"></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr><td colspan="4">
                                                <button type="button" class="btn btn-outline-primary btn-sm sm-add-row" data-prefix="edit"><i class="fa fa-plus"></i> เพิ่มชุด Shipping Mark</button>
                                            </td></tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label class="d-block">หมายเหตุประจำลูกค้า (Remark) <small class="text-muted">(มีได้หลายชุด · เปิด/ปิดได้ · ปุ่มโหลดในใบ PI จะดึงเฉพาะที่เปิดอยู่มาทับ)</small></label>
                                    <table class="table table-bordered table-sm mb-1" id="edit_cr_table">
                                        <thead class="bgc-grey-l3">
                                            <tr>
                                                <th width="70" class="text-center">เปิดใช้</th>
                                                <th>หมายเหตุ (หลายบรรทัดได้)</th>
                                                <th width="40"></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr><td colspan="3">
                                                <button type="button" class="btn btn-outline-primary btn-sm cr-add-row" data-prefix="edit"><i class="fa fa-plus"></i> เพิ่มหมายเหตุ</button>
                                            </td></tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

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
@endsection

@push('scripts')
<script type="text/javascript">

    var tableCustomer = $('#tableCustomer').dataTable({

        "ajax": {
            "url": url_gb+"/admin/Customer/Lists",
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
            {"data": "contact_name", "name": 'contact_name'},
            {"data": "company_name", "name": 'company_name'},
            {"data": "tax_id", "name": 'tax_id'},
            {"data": "remark", "name": 'remark'},
            {"data": "action", "name": 'action'},

        ]
    });

    $('body').on('click','.btn-add',function(data){
        smRender('add', []);   // เริ่มด้วย 1 แถวว่าง
        crRender('add', []);   // หมายเหตุ: เริ่มจากตารางว่าง
        $('#ModalAdd').modal('show');
    });

    $('body').on('submit', '#FormAdd', function(e){
        e.preventDefault();
        var form = $(this);
        smReindex('add');      // จัดลำดับ default ให้ตรง DOM ก่อนส่ง
        loadingButton(form.find('button[type=submit]'));
        $.ajax({
            method: "POST",
            url: url_gb+"/admin/Customer",
            dataType : "json",
            data: form.serialize()
        }).done(function( res ) {
            resetButton(form.find('button[type=submit]'));
            if(res.status == 1){
                Swal.fire(res.title, res.content,'success');
                resetFormCustom(form);
                tableCustomer.api().ajax.reload();
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
        var id = $("#Customer_edit_id").val();
        var form = $(this);
        smReindex('edit');     // จัดลำดับ default ให้ตรง DOM ก่อนส่ง
        loadingButton(form.find('button[type=submit]'));
        $.ajax({
            method: "PUT",
            url: url_gb+"/admin/Customer/"+id,
            dataType : 'json',
            data: form.serialize()
            }).done(function( res ) {
                resetButton(form.find('button[type=submit]'));
                if(res.status == 1){
                    Swal.fire(res.title, res.content, 'success');
                    resetFormCustom(form);
                    tableCustomer.api().ajax.reload();
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
                    url: url_gb+"/admin/Customer/"+id,
                    dataType : 'json',
                }).done(function( res ) {
                    if(res.status == 1){
                        Swal.fire(res.title, res.content,'success');
                        tableCustomer.api().ajax.reload();
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
        $("#Customer_edit_id").val(id);
        var btn = $(this);
        loadingButton(btn);
        $.ajax({
            method: "GET",
            url: url_gb+"/admin/Customer/"+id,
            dataType: 'json',
        }).done(function( res ) {
            resetButton(btn);

            $("#edit_contact_name").val(res.content.contact_name);
            $("#edit_company_name").val(res.content.company_name);
            $("#edit_address").val(res.content.address);
            $("#edit_country").val(res.content.country);
            $("#edit_region").val(res.content.region);
            $("#edit_tax_id").val(res.content.tax_id);
            $("#edit_phone").val(res.content.phone);
            $("#edit_mobile").val(res.content.mobile);
            $("#edit_fax").val(res.content.fax);
            $("#edit_remark").val(res.content.remark);
            smRender('edit', res.content.shipping_marks || []);
            crRender('edit', res.content.remarks || []);
            $("#edit_level_id").val(res.content.level_id);
            $('#ModalEdit').modal('show');
        }).fail(function(res){
            ajaxFail(res , "");
        });
    });

    // ===== Shipping Mark หลายชุดต่อลูกค้า (ใช้ทั้ง modal add/edit) =====
    function smEsc(s){ return $('<div>').text(s == null ? '' : s).html(); }

    function smRowHtml(d){
        d = d || {};
        var checked = d.is_default ? 'checked' : '';
        return '<tr class="sm-row">'
            + '<td class="text-center align-middle"><input type="radio" name="sm_default" value="0" '+checked+'></td>'
            + '<td><input type="hidden" name="sm_id[]" value="'+(d.id != null ? d.id : '')+'">'
            + '<input type="text" class="form-control form-control-sm" name="sm_name[]" value="'+smEsc(d.name)+'" placeholder="เช่น ปกติ / ทางเรือ"></td>'
            + '<td><textarea class="form-control form-control-sm" name="sm_content[]" rows="2" placeholder="พิมพ์ได้หลายบรรทัด">'+smEsc(d.content)+'</textarea></td>'
            + '<td class="text-center align-middle"><button type="button" class="btn btn-outline-danger btn-sm sm-remove-row" tabindex="-1"><i class="fa fa-trash"></i></button></td>'
            + '</tr>';
    }

    function smReindex(prefix){
        var $rows = $('#'+prefix+'_sm_table tbody tr.sm-row');
        var anyChecked = $rows.find('input[name="sm_default"]:checked').length > 0;
        $rows.each(function(i){
            $(this).find('input[name="sm_default"]').val(i);
            if(!anyChecked && i === 0){ $(this).find('input[name="sm_default"]').prop('checked', true); }
        });
    }

    function smRender(prefix, marks){
        var $b = $('#'+prefix+'_sm_table tbody');
        $b.empty();
        if(marks && marks.length){
            marks.forEach(function(m){ $b.append(smRowHtml(m)); });
        } else {
            $b.append(smRowHtml({ is_default: true }));
        }
        smReindex(prefix);
    }

    $('body').on('click', '.sm-add-row', function(){
        var prefix = $(this).data('prefix');
        $('#'+prefix+'_sm_table tbody').append(smRowHtml({}));
        smReindex(prefix);
    });

    $('body').on('click', '.sm-remove-row', function(){
        var prefix = $(this).closest('table').attr('id').replace('_sm_table','');
        $(this).closest('tr').remove();
        smReindex(prefix);
    });

    // ===== หมายเหตุ (Remark) หลายชุดต่อลูกค้า + เปิด/ปิด (ใช้ทั้ง modal add/edit) =====
    function crEsc(s){ return $('<div>').text(s == null ? '' : s).html(); }

    function crRowHtml(d){
        d = d || {};
        var active = (d.active === undefined) ? true : !!d.active; // แถวใหม่เปิดไว้ก่อน
        return '<tr class="cr-row">'
            + '<td class="text-center align-middle">'
            +   '<input type="hidden" name="cr_active[]" value="'+(active ? '1' : '0')+'">'
            +   '<label class="mb-0"><input type="checkbox" class="cr-active-toggle" '+(active ? 'checked' : '')+'></label>'
            + '</td>'
            + '<td><input type="hidden" name="cr_id[]" value="'+(d.id != null ? d.id : '')+'">'
            +   '<textarea class="form-control form-control-sm" name="cr_content[]" rows="2" placeholder="พิมพ์ได้หลายบรรทัด">'+crEsc(d.content)+'</textarea></td>'
            + '<td class="text-center align-middle"><button type="button" class="btn btn-outline-danger btn-sm cr-remove-row" tabindex="-1"><i class="fa fa-trash"></i></button></td>'
            + '</tr>';
    }

    function crRender(prefix, remarks){
        var $b = $('#'+prefix+'_cr_table tbody');
        $b.empty();
        if(remarks && remarks.length){
            remarks.forEach(function(r){ $b.append(crRowHtml(r)); });
        }
        // เริ่มจากว่าง: ไม่ใส่แถวเริ่มต้นอัตโนมัติ (ผู้ใช้กด "เพิ่มหมายเหตุ" เอง)
    }

    // checkbox เป็นแค่ UI -> sync ค่าไปที่ hidden cr_active[] (serialize ให้ตรง index)
    $('body').on('change', '.cr-active-toggle', function(){
        $(this).closest('tr').find('input[name="cr_active[]"]').val(this.checked ? '1' : '0');
    });

    $('body').on('click', '.cr-add-row', function(){
        var prefix = $(this).data('prefix');
        $('#'+prefix+'_cr_table tbody').append(crRowHtml({}));
    });

    $('body').on('click', '.cr-remove-row', function(){
        $(this).closest('tr').remove();
    });








</script>
@endpush
