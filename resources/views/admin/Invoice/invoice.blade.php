@extends('admin.layouts.default')

@section('title', $pageTitle)

@section('body')
<div class="page-content container-fluid container-plus">
    <div class="page-header mb-2 pb-2 flex-column flex-sm-row align-items-start align-items-sm-center py-25 px-1">
        <h1 class="page-title text-primary-d2 text-140">{{ $pageTitle }}</h1>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card dcard">
                <div class="card-body p-0">
                    <table id="tableInvoice" class="table table-striped-primary table-borderless border-0 mb-0 w-100 table-hover">
                        <thead>
                            <tr class="bgc-primary-d1 text-white">
                                <th class="text-center" width="5%">#</th>
                                <th>เลขที่ Invoice / วันที่</th>
                                <th>ลูกค้า</th>
                                <th class="text-center">แถว</th>
                                <th class="text-center" width="200">จัดการ</th>
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
<script>
    $('#tableInvoice').DataTable({
        ajax: {
            url: url_gb + "/admin/{{ $route_locale }}/Invoice/Lists",
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: function(d) {
                d._token = $('meta[name="csrf-token"]').attr('content');
            }
        },
        columns: [
            { data: "DT_RowIndex", searchable: false, orderable: false, className: "text-center" },
            { data: "doc_info", name: "invoice_no", className: "align-middle" },
            { data: "customer_info", name: "customer_name", className: "align-middle" },
            { data: "lines", name: "details_count", className: "text-center align-middle" },
            { data: "action_btns", searchable: false, orderable: false, className: "text-center align-middle" }
        ],
        order: [[1, "desc"]]
    });
</script>
@endpush
