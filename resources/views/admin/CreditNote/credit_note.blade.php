@extends('admin.layouts.default')

@section('title', $currentMenu->title)

@section('body')
<div class="page-content container-fluid container-plus">
    <div class="page-header mb-2 pb-2 flex-column flex-sm-row align-items-start align-items-sm-center py-25 px-1">
        <h1 class="page-title text-primary-d2 text-140">{{ $currentMenu->title }}</h1>
        <div class="page-tools mt-3 mt-sm-0 mb-sm-n1">
            @if( $my_menu_permission[$currentMenu->url]['c'] == 'T' )
                <a href="{{ url('admin/'.$route_locale.'/CreditNote/create') }}" class="btn btn-light-green btn-h-green btn-a-green border-0 radius-3 py-2 text-600 text-90">
                    <span class="d-none d-sm-inline mr-1">สร้าง Credit Note</span>
                    <i class="fa fa-plus text-110 w-2 h-2"></i>
                </a>
            @endif
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card dcard mb-2">
                <div class="card-body py-2">
                    <div class="row align-items-end">
                        <div class="col-md-6">
                            <label class="text-90 mb-1">ค้นหา (ชื่อลูกค้า / เลขที่ / Invoice / Refer)</label>
                            <input type="text" id="filter_keyword" class="form-control form-control-sm" placeholder="พิมพ์เพื่อค้นหา...">
                        </div>
                        <div class="col-md-3">
                            <button type="button" id="filter_reset" class="btn btn-sm btn-outline-secondary"><i class="fa fa-times"></i> ล้าง</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card dcard">
                <div class="card-body p-0">
                    <table id="tableCreditNote" class="table table-striped-primary table-borderless border-0 mb-0 w-100 table-hover">
                        <thead>
                            <tr class="bgc-primary-d1 text-white">
                                <th class="text-center" width="5%">#</th>
                                <th>เลขที่ / วันที่</th>
                                <th>ลูกค้า</th>
                                <th>Refer</th>
                                <th class="text-right">ยอดรวม</th>
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
@endsection

@push('scripts')
<script type="text/javascript">
    var tableCreditNote = $('#tableCreditNote').DataTable({
        ajax: {
            url: url_gb + "/admin/{{ $route_locale }}/CreditNote/Lists",
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: function(d) {
                d._token = $('meta[name="csrf-token"]').attr('content');
                d.keyword = $('#filter_keyword').val();
            }
        },
        columns: [
            { data: "DT_RowIndex", searchable: false, orderable: false, className: "text-center" },
            { data: "doc_info", name: 'doc_no', className: "align-middle" },
            { data: "customer", name: 'company_name', className: "align-middle" },
            { data: "refer", name: 'refer', className: "align-middle" },
            { data: "total", name: 'total', className: "text-right align-middle" },
            { data: "action_btns", searchable: false, orderable: false, className: "text-center align-middle" }
        ],
        order: [[1, "desc"]]
    });

    var kwTimer = null;
    $('#filter_keyword').on('keyup', function() {
        clearTimeout(kwTimer);
        kwTimer = setTimeout(function() { tableCreditNote.ajax.reload(); }, 350);
    });
    $('#filter_reset').on('click', function() {
        $('#filter_keyword').val('');
        tableCreditNote.ajax.reload();
    });

    $('#tableCreditNote').on('click', '.btn-delete', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'ยืนยันการลบ?', icon: 'warning', showCancelButton: true,
            confirmButtonText: 'ลบ', cancelButtonText: 'ยกเลิก', confirmButtonColor: '#d33'
        }).then(function(res) {
            if (!res.value) return;
            $.ajax({
                method: 'POST',
                url: url_gb + "/admin/{{ $route_locale }}/CreditNote/" + id,
                data: { _method: 'DELETE', _token: $('meta[name="csrf-token"]').attr('content') },
                dataType: 'json'
            }).done(function(r) {
                if (r.status == 1) { tableCreditNote.ajax.reload(null, false); Swal.fire('สำเร็จ', r.content, 'success'); }
                else Swal.fire(r.title || 'ผิดพลาด', r.content || '', 'error');
            });
        });
    });
</script>
@endpush
