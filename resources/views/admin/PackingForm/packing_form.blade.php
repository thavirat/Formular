@extends('admin.layouts.default')

@section('title', $currentMenu->title)

@section('body')
<div class="page-content container-fluid container-plus">
    <div class="page-header mb-2 pb-2 flex-column flex-sm-row align-items-start align-items-sm-center py-25 px-1">
        <h1 class="page-title text-primary-d2 text-140">{{ $currentMenu->title }}</h1>
        <div class="page-tools mt-3 mt-sm-0 mb-sm-n1">
            @if( $my_menu_permission[$currentMenu->url]['c'] == 'T' )
                <a href="{{ url('admin/'.$route_locale.'/PackingForm/create') }}" class="btn btn-light-green btn-h-green btn-a-green border-0 radius-3 py-2 text-600 text-90 mr-1">
                    <span class="d-none d-sm-inline mr-1">เพิ่มข้อมูล</span>
                    <i class="fa fa-plus text-110 w-2 h-2"></i>
                </a>
                <button type="button" class="btn btn-light-primary btn-h-primary btn-a-primary border-0 radius-3 py-2 text-600 text-90" id="btn-open-import">
                    <span class="d-none d-sm-inline mr-1">นำเข้า Excel</span>
                    <i class="fa fa-file-upload text-110 w-2 h-2"></i>
                </button>
            @endif
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card dcard">
                <div class="card-body p-0">
                    <table id="tablePackingForm" class="table table-striped-primary table-borderless border-0 mb-0 w-100 table-hover">
                        <thead>
                            <tr class="bgc-primary-d1 text-white">
                                <th class="text-center" width="5%">#</th>
                                <th>เลขที่ / วันที่</th>
                                <th>ลูกค้า</th>
                                <th>ไฟล์ต้นทาง</th>
                                <th class="text-center">แถว</th>
                                <th class="text-center" width="120">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalImport" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="FormImport" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title text-primary-d3">นำเข้า Packing List (Excel)</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <p class="text-90 text-muted mb-2">หัวเอกสารอ่านจาก D2–D4, A7, M8, O8–T8 (รวม CBM ที่ S8) — รายการสินค้าเริ่มแถว 12 คอลัมน์ D = เลขที่ PI, E = Part no</p>
                    <div class="form-group">
                        <label for="import_file">ไฟล์ .xlsx / .xls</label>
                        <input type="file" name="file" id="import_file" class="form-control" accept=".xlsx,.xls" required>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="force_new" name="force_new" value="1">
                        <label class="custom-control-label" for="force_new">อนุญาตนำเข้าไฟล์ซ้ำเป็นชุดใหม่ (ไม่ใช้ตรวจซ้ำจาก hash)</label>
                    </div>
                    <input type="hidden" name="replace_id" id="replace_id" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> นำเข้า</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    var tablePackingForm = $('#tablePackingForm').DataTable({
        ajax: {
            url: url_gb + "/admin/{{ $route_locale }}/PackingForm/Lists",
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: function(d) {
                d._token = $('meta[name="csrf-token"]').attr('content');
            }
        },
        columns: [
            { data: "DT_RowIndex", searchable: false, orderable: false, className: "text-center" },
            { data: "doc_info", name: "doc_no", className: "align-middle" },
            { data: "customer_info", name: "customer_name", className: "align-middle" },
            { data: "file_info", name: "source_filename", className: "align-middle text-90" },
            { data: "lines", name: "details_count", className: "text-center align-middle" },
            { data: "action_btns", searchable: false, orderable: false, className: "text-center align-middle" }
        ],
        order: [[1, "desc"]]
    });

    $('#btn-open-import').on('click', function() {
        $('#replace_id').val('');
        $('#force_new').prop('checked', false);
        $('#ModalImport').modal('show');
    });

    function submitImport(extra) {
        var form = $('#FormImport')[0];
        var fd = new FormData(form);
        if (extra && extra.replace_id) fd.set('replace_id', extra.replace_id);
        if (extra && extra.force_new) fd.set('force_new', '1');
        var btn = $('#FormImport button[type=submit]');
        loadingButton(btn);
        $.ajax({
            url: url_gb + "/admin/{{ $route_locale }}/PackingForm/Import",
            type: "POST",
            data: fd,
            processData: false,
            contentType: false,
            dataType: "json"
        }).done(function(res) {
            resetButton(btn);
            if (res.status == 1) {
                $('#ModalImport').modal('hide');
                $('#FormImport')[0].reset();
                tablePackingForm.ajax.reload(null, false);
                Swal.fire({ icon: 'success', title: res.title, html: (res.content || '').replace(/\n/g, '<br>') });
            } else if (res.duplicate) {
                Swal.fire({
                    icon: 'warning',
                    title: res.title,
                    text: res.content,
                    showCancelButton: true,
                    showDenyButton: true,
                    confirmButtonText: 'แทนที่ชุดเดิม',
                    denyButtonText: 'นำเข้าเป็นชุดใหม่',
                    cancelButtonText: 'ยกเลิก'
                }).then(function(r) {
                    if (r.isConfirmed) {
                        submitImport({ replace_id: res.existing_id });
                    } else if (r.isDenied) {
                        submitImport({ force_new: true });
                    }
                });
            } else {
                Swal.fire({ icon: 'error', title: res.title || 'ผิดพลาด', html: (res.content || '').replace(/\n/g, '<br>') });
            }
        }).fail(function(xhr) {
            resetButton(btn);
            ajaxFail(xhr, $('#FormImport'));
        });
    }

    $('#FormImport').on('submit', function(e) {
        e.preventDefault();
        submitImport({});
    });

    $('body').on('click', '.btn-delete', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'ลบ Packing list นี้?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'ลบ',
            cancelButtonText: 'ยกเลิก'
        }).then(function(r) {
            if (!r.isConfirmed) return;
            $.ajax({
                method: "DELETE",
                url: url_gb + "/admin/{{ $route_locale }}/PackingForm/" + id,
                dataType: "json",
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            }).done(function(res) {
                if (res.status == 1) {
                    Swal.fire('สำเร็จ', res.content || '', 'success');
                    tablePackingForm.ajax.reload(null, false);
                } else {
                    Swal.fire('ผิดพลาด', res.content || '', 'error');
                }
            }).fail(function(xhr) { ajaxFail(xhr, ""); });
        });
    });
</script>
@endpush
