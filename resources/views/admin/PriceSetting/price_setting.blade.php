@extends('admin.layouts.default')

@section('title', $currentMenu->title)

@section('body')
<div class="page-content container-fluid container-plus">
    <div class="page-header mb-2 pb-2 flex-column flex-sm-row align-items-start align-items-sm-center py-25 px-1">
        <h1 class="page-title text-primary-d2 text-140">{{ $currentMenu->title }}</h1>
        <div class="page-tools mt-3 mt-sm-0 mb-sm-n1">
            @if( $my_menu_permission[$currentMenu->url]['c'] == 'T' )
                <a href="{{ url('admin/'.$lang.'/PriceSetting/create') }}"
                   class="btn btn-light-green btn-h-green btn-a-green border-0 radius-3 py-2 text-600 text-90">
                    <span class="d-none d-sm-inline mr-1">เพิ่มชุดราคา</span>
                    <i class="fa fa-plus text-110 w-2 h-2"></i>
                </a>
            @endif
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card dcard">
                <div class="card-body p-0">
                    <table id="tablePriceSetting" class="table table-border-x brc-secondary-l4 border-0 mb-0 w-100">
                        <thead class="text-dark-tp3 bgc-grey-l4 text-90 border-b-1 brc-transparent">
                            <tr>
                                <th class="text-center" width="5%">ลำดับ</th>
                                <th>ช่วงเวลา (เริ่ม – สิ้นสุด)</th>
                                <th class="text-center" width="12%">สถานะ</th>
                                <th class="text-center" width="15%">#</th>
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
    var tablePriceSetting = $('#tablePriceSetting').dataTable({
        "ajax": { "url": url_gb+"/admin/PriceSetting/Lists", "type": "POST" },
        "drawCallback": function () { $('[data-toggle="tooltip"]').tooltip(); },
        "responsive": false,
        "columns": [
            {"data": "DT_RowIndex", 'searchable': false, 'orderable': false, "class": "text-center"},
            {"data": "period", "name": 'start_date'},
            {"data": "status", "name": 'active', "searchable": false, "class": "text-center"},
            {"data": "action", "name": "action", "searchable": false, "sortable": false, "class": "text-center"},
        ]
    });

    $('body').on('click', '.btn-delete', function () {
        var id = $(this).data('id');
        Swal.fire({
            title: 'ลบชุดราคานี้?', text: "ลบแล้วนำกลับไม่ได้", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33',
            cancelButtonText: 'ยกเลิก', confirmButtonText: 'ลบ'
        }).then((say) => {
            if (say.isConfirmed) {
                $.ajax({ method: "DELETE", url: url_gb+"/admin/PriceSetting/"+id, dataType: 'json' })
                .done(function (res) {
                    if (res.status == 1) { Swal.fire(res.title, res.content, 'success'); tablePriceSetting.api().ajax.reload(); }
                    else { Swal.fire(res.title, res.content, 'warning'); }
                }).fail(function (res) { ajaxFail(res, ""); });
            }
        });
    });
</script>
@endpush
