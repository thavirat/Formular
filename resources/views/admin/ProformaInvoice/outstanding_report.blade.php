@extends('admin.layouts.default')

@section('title', 'รายงานค้างผลิต')

@section('css')
<style>
    #tableOutstanding thead th {
        background-color: #f1f4f9 !important;
        color: #5a6a85 !important;
        text-transform: none !important;
        font-size: 0.9rem;
        padding: 15px 10px !important;
    }
    #tableOutstanding tbody td {
        vertical-align: middle !important;
        padding-top: 10px !important;
        padding-bottom: 10px !important;
    }
</style>
@endsection

@section('body')
<div class="page-content container-fluid container-plus">
    <div class="page-header mb-2 pb-2 flex-column flex-sm-row align-items-start align-items-sm-center py-25 px-1">
        <h1 class="page-title text-primary-d2 text-140">
            <i class="fa fa-industry mr-2 text-orange-d1"></i>รายงานค้างผลิต
        </h1>
        <div class="page-tools mt-3 mt-sm-0 mb-sm-n1">
            <a href="{{ url('admin/ProformaInvoice') }}" class="btn btn-light-secondary btn-h-secondary btn-a-secondary border-0 radius-3 py-2 text-600 text-90">
                <i class="fa fa-arrow-left mr-1"></i> กลับหน้า PI
            </a>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card dcard">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="filter_start_date">วันที่เริ่มต้น</label>
                                <div class="input-group">
                                    <input type="text" class="form-control init-date" id="filter_start_date" value="" readonly>
                                    <div class="input-group-append btn-clear-date" style="cursor:pointer;" data-target="#filter_start_date">
                                        <div class="input-group-text text-danger-m1"><i class="fa fa-times"></i></div>
                                    </div>
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="far fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="filter_end_date">วันที่สิ้นสุด</label>
                                <div class="input-group">
                                    <input type="text" class="form-control init-date" id="filter_end_date" value="{{ date('Y-m-t') }}" readonly>
                                    <div class="input-group-append btn-clear-date" style="cursor:pointer;" data-target="#filter_end_date">
                                        <div class="input-group-text text-danger-m1"><i class="fa fa-times"></i></div>
                                    </div>
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="far fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="filter_customer">ลูกค้า</label>
                                <select id="filter_customer" class="form-control">
                                    <option value="all">ลูกค้าทั้งหมด</option>
                                    @foreach ($Customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="filter_show_mode">แสดงรายการ</label>
                                <select id="filter_show_mode" class="form-control">
                                    <option value="incomplete">ยังผลิตไม่ครบ</option>
                                    <option value="completed">ผลิตครบแล้ว</option>
                                    <option value="all">ทั้งหมด</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="form-group">
                                <button type="button" class="btn btn-primary" id="filter_button">
                                    <i class="fa fa-search mr-1"></i> กรอง
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card dcard">
                <div class="card-body p-0">
                    <table id="tableOutstanding" class="table table-striped-primary table-borderless border-0 mb-0 w-100 table-hover">
                        <thead>
                            <tr class="bgc-primary-d1 text-white">
                                <th class="text-center" width="4%">#</th>
                                <th>ลูกค้า</th>
                                <th>เลขที่ PI</th>
                                <th>วันที่เอกสาร</th>
                                <th>รหัสสินค้า</th>
                                <th class="text-right">จำนวนต้องผลิต</th>
                                <th class="text-right">ผลิตเสร็จ</th>
                                <th class="text-right">ค้างผลิต</th>
                                <th class="text-center" width="100">ความคืบหน้า</th>
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

    var tableOutstanding = $('#tableOutstanding').dataTable({
        "ajax": {
            "url": url_gb + "/admin/ProformaInvoice/OutstandingReport/Lists",
            "type": "POST",
            "data": function (d) {
                d.start_date = $('#filter_start_date').val();
                d.end_date = $('#filter_end_date').val();
                d.customer_id = $('#filter_customer').val();
                d.show_mode = $('#filter_show_mode').val();
            }
        },
        "responsive": false,
        "columns": [
            {"data": "DT_RowIndex", "searchable": false, "orderable": false, "className": "text-center"},
            {"data": "customer_name", "className": "align-middle"},
            {"data": "doc_no", "className": "align-middle text-primary-d2 text-600"},
            {"data": "doc_date", "className": "align-middle"},
            {"data": "part_no_display", "className": "align-middle text-600"},
            {"data": "qty_display", "className": "text-right align-middle"},
            {"data": "produced_qty_display", "className": "text-right align-middle text-success-d1"},
            {"data": "remaining", "className": "text-right align-middle"},
            {"data": "progress", "searchable": false, "orderable": false, "className": "align-middle text-center"}
        ],
        "order": [[3, "desc"]]
    });

    $('#filter_button').on('click', function () {
        tableOutstanding.api().ajax.reload();
    });

    $("#filter_customer").select2({
        placeholder: 'เลือกลูกค้า',
        allowClear: true
    });

    $("#filter_show_mode").select2({
        minimumResultsForSearch: Infinity
    });

</script>
@endpush
