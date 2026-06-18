@extends('admin.layouts.default')

@section('title', $currentMenu->title)

@section('body')
<div class="page-content container-fluid container-plus">
    <div class="page-header mb-2 pb-2 flex-column flex-sm-row align-items-start align-items-sm-center py-25 px-1">
        <h1 class="page-title text-primary-d2 text-140">{{ $currentMenu->title }}</h1>
        <div class="page-tools mt-3 mt-sm-0 mb-sm-n1">
            @if( $my_menu_permission[$currentMenu->url]['c'] == 'T' )
                <button type="button" class="btn btn-light-green btn-h-green btn-a-green border-0 radius-3 py-2 text-600 text-90 btn-add">
                    <span class="d-none d-sm-inline mr-1">เพิ่มข้อมูล</span>
                    <i class="fa fa-plus text-110 w-2 h-2"></i>
                </button>
            @endif
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card dcard">
                <div class="card-body p-0">
                    <table id="tableShipmentMethod" class="table table-border-x brc-secondary-l4 border-0 mb-0 w-100">
                        <thead class="text-dark-tp3 bgc-grey-l4 text-90 border-b-1 brc-transparent">
                            <tr>
                                <th class="text-center" width="5%">ลำดับ</th>
                                <th>ชื่อวิธีการขนส่ง</th>
                                <th class="text-center" width="10%">ลำดับแสดง</th>
                                <th class="text-center" width="12%">สถานะ</th>
                                <th class="text-center" width="12%">#</th>
                            </tr>
                        </thead>
                        <tbody class="mt-1"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add -->
<div class="modal fade modal-lg" id="ModalAdd" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="FormAdd" data-parsley-validate="true">
                <div class="modal-header">
                    <h5 class="modal-title text-primary-d3">เพิ่มข้อมูล{{ $currentMenu->title }}</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="add_name">ชื่อวิธีการขนส่ง</label>
                                <input type="text" name="name" id="add_name" class="form-control autofocus" placeholder="เช่น SEAFREIGHT">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="add_seq">ลำดับแสดง</label>
                                <input type="number" name="seq" id="add_seq" class="form-control" value="0">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="add_active">สถานะ</label>
                                <select name="active" id="add_active" class="form-control">
                                    <option value="T">ใช้งาน</option>
                                    <option value="F">ปิด</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-4" data-dismiss="modal"><i class="fa fa-window-close"></i> ปิด</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade modal-lg" id="ModalEdit" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="FormEdit" data-parsley-validate="true">
                <input type="hidden" id="ShipmentMethod_edit_id">
                <div class="modal-header">
                    <h5 class="modal-title text-primary-d3">แก้ไขข้อมูล{{ $currentMenu->title }}</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="edit_name">ชื่อวิธีการขนส่ง</label>
                                <input type="text" name="name" id="edit_name" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="edit_seq">ลำดับแสดง</label>
                                <input type="number" name="seq" id="edit_seq" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="edit_active">สถานะ</label>
                                <select name="active" id="edit_active" class="form-control">
                                    <option value="T">ใช้งาน</option>
                                    <option value="F">ปิด</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-4" data-dismiss="modal"><i class="fa fa-window-close"></i> ปิด</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    var tableShipmentMethod = $('#tableShipmentMethod').dataTable({
        "ajax": { "url": url_gb+"/admin/ShipmentMethod/Lists", "type": "POST" },
        "drawCallback": function () { $('[data-toggle="tooltip"]').tooltip(); },
        "responsive": false,
        "columns": [
            {"data": "DT_RowIndex", 'searchable': false, 'orderable': false, "class": "text-center"},
            {"data": "name", "name": 'name'},
            {"data": "seq", "name": 'seq', "class": "text-center"},
            {"data": "active_label", "name": 'active', "searchable": false, "class": "text-center"},
            {"data": "action", "name": "action", "searchable": false, "sortable": false, "class": "text-center"},
        ]
    });

    $('body').on('click', '.btn-add', function () { $('#ModalAdd').modal('show'); });

    $('body').on('submit', '#FormAdd', function (e) {
        e.preventDefault();
        var form = $(this);
        loadingButton(form.find('button[type=submit]'));
        $.ajax({ method: "POST", url: url_gb+"/admin/ShipmentMethod", dataType: "json", data: form.serialize() })
        .done(function (res) {
            resetButton(form.find('button[type=submit]'));
            if (res.status == 1) {
                Swal.fire(res.title, res.content, 'success');
                resetFormCustom(form);
                tableShipmentMethod.api().ajax.reload();
                $('#ModalAdd').modal('hide');
            } else { Swal.fire(res.title, res.content, 'error'); }
        }).fail(function (res) { ajaxFail(res, form); });
    });

    $('body').on('submit', '#FormEdit', function (e) {
        e.preventDefault();
        var id = $("#ShipmentMethod_edit_id").val();
        var form = $(this);
        loadingButton(form.find('button[type=submit]'));
        $.ajax({ method: "PUT", url: url_gb+"/admin/ShipmentMethod/"+id, dataType: 'json', data: form.serialize() })
        .done(function (res) {
            resetButton(form.find('button[type=submit]'));
            if (res.status == 1) {
                Swal.fire(res.title, res.content, 'success');
                tableShipmentMethod.api().ajax.reload();
                $('#ModalEdit').modal('hide');
            } else { Swal.fire(res.title, res.content, 'error'); }
        }).fail(function (res) { ajaxFail(res, form); });
    });

    $('body').on('click', '.btn-delete', function () {
        var id = $(this).data('id');
        Swal.fire({
            title: 'คุณต้องการลบข้อมูลหรือไม่ ?',
            text: "ข้อมูลที่ถูกลบไปแล้วจะไม่สามารถนำกลับมาได้",
            icon: 'warning', showCancelButton: true,
            confirmButtonColor: '#3085d6', cancelButtonColor: '#d33',
            cancelButtonText: 'ยกเลิก', confirmButtonText: 'ลบ'
        }).then((say) => {
            if (say.isConfirmed) {
                $.ajax({ method: "DELETE", url: url_gb+"/admin/ShipmentMethod/"+id, dataType: 'json' })
                .done(function (res) {
                    if (res.status == 1) { Swal.fire(res.title, res.content, 'success'); tableShipmentMethod.api().ajax.reload(); }
                    else { Swal.fire(res.title, res.content, 'warning'); }
                }).fail(function (res) { ajaxFail(res, ""); });
            }
        });
    });

    $('body').on('click', '.btn-edit', function () {
        var id = $(this).data('id');
        $("#ShipmentMethod_edit_id").val(id);
        var btn = $(this);
        loadingButton(btn);
        $.ajax({ method: "GET", url: url_gb+"/admin/ShipmentMethod/"+id, dataType: 'json' })
        .done(function (res) {
            resetButton(btn);
            $("#edit_name").val(res.content.name);
            $("#edit_seq").val(res.content.seq);
            $("#edit_active").val(res.content.active);
            $('#ModalEdit').modal('show');
        }).fail(function (res) { ajaxFail(res, ""); });
    });
</script>
@endpush
