@extends('admin.layouts.default')

@section('title', $currentMenu->title)

@section('body')
<div class="page-content container-fluid container-plus">
    <div class="page-header mb-2 pb-2">
        <h1 class="page-title text-primary-d2 text-140">{{ $currentMenu->title }}</h1>
    </div>

    {{-- สรุป --}}
    <div class="row" id="ar-summary">
        <div class="col-md-3 col-6 mb-2"><div class="card dcard"><div class="card-body py-2 text-center">
            <div class="text-90 text-muted">ยอดค้างชำระรวม</div>
            <div class="text-160 text-danger-d1 font-bolder"><span id="s_outstanding">{{ number_format($summary['outstanding'],2) }}</span></div>
            <div class="text-80 text-muted"><span id="s_count">{{ $summary['count'] }}</span> ใบ</div>
        </div></div></div>
        <div class="col-md-3 col-6 mb-2"><div class="card dcard"><div class="card-body py-2 text-center">
            <div class="text-90 text-muted">เกินกำหนด (Overdue)</div>
            <div class="text-160 text-danger font-bolder"><span id="s_overdue">{{ number_format($summary['overdue'],2) }}</span></div>
        </div></div></div>
        <div class="col-md-6 col-12 mb-2"><div class="card dcard"><div class="card-body py-2">
            <div class="text-90 text-muted mb-1">ยอดค้างตามอายุหนี้ (Aging)</div>
            <div class="d-flex justify-content-between text-90 flex-wrap">
                <span class="badge badge-success p-2 m-1">ยังไม่ครบ: <b id="s_current">{{ number_format($summary['b_current'],2) }}</b></span>
                <span class="badge badge-warning p-2 m-1">1-30: <b id="s_b30">{{ number_format($summary['b30'],2) }}</b></span>
                <span class="badge badge-warning p-2 m-1">31-60: <b id="s_b60">{{ number_format($summary['b60'],2) }}</b></span>
                <span class="badge badge-danger p-2 m-1">61-90: <b id="s_b90">{{ number_format($summary['b90'],2) }}</b></span>
                <span class="badge badge-danger p-2 m-1">90+: <b id="s_b90p">{{ number_format($summary['b90p'],2) }}</b></span>
            </div>
        </div></div></div>
    </div>

    {{-- ฟิลเตอร์ --}}
    <div class="card dcard mb-2"><div class="card-body py-2">
        <div class="row align-items-end">
            <div class="col-md-3"><label class="text-90 mb-1">ลูกค้า</label>
                <select id="f_customer" class="form-control form-control-sm select2">
                    <option value="all">ทั้งหมด</option>
                    @foreach($Customers as $c)<option value="{{ $c->id }}">{{ $c->company_name }}</option>@endforeach
                </select></div>
            <div class="col-md-2"><label class="text-90 mb-1">ภูมิภาค</label>
                <select id="f_region" class="form-control form-control-sm">
                    <option value="all">ทั้งหมด</option>
                    @foreach($Regions as $r)<option value="{{ $r }}">{{ $r }}</option>@endforeach
                </select></div>
            <div class="col-md-2"><label class="text-90 mb-1">สถานะ</label>
                <select id="f_status" class="form-control form-control-sm">
                    <option value="outstanding">ค้างชำระ</option>
                    <option value="overdue">เกินกำหนด</option>
                    <option value="all">ทั้งหมด</option>
                </select></div>
            <div class="col-md-2"><label class="text-90 mb-1">วันที่เอกสาร (จาก)</label>
                <input type="date" id="f_start" class="form-control form-control-sm"></div>
            <div class="col-md-2"><label class="text-90 mb-1">ถึง</label>
                <input type="date" id="f_end" class="form-control form-control-sm"></div>
            <div class="col-md-1"><button type="button" id="f_reset" class="btn btn-sm btn-outline-secondary"><i class="fa fa-times"></i></button></div>
        </div>
    </div></div>

    <div class="card dcard"><div class="card-body p-0">
        <table id="tableAR" class="table table-striped-primary table-borderless border-0 mb-0 w-100 table-hover">
            <thead>
                <tr class="bgc-primary-d1 text-white">
                    <th class="text-center" width="4%">#</th>
                    <th>ลูกค้า</th>
                    <th>Invoice / วันที่</th>
                    <th class="text-center">เครดิต</th>
                    <th class="text-center">ครบกำหนด</th>
                    <th class="text-center">เกิน(วัน)</th>
                    <th class="text-center">Aging</th>
                    <th class="text-right">ยอดรวม</th>
                    <th class="text-right">ชำระแล้ว</th>
                    <th class="text-right">คงค้าง</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div></div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    var tableAR = $('#tableAR').DataTable({
        ajax: {
            url: url_gb + "/admin/{{ $route_locale }}/AccountReceivable/Lists",
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: function(d){
                d._token = $('meta[name="csrf-token"]').attr('content');
                d.customer_id = $('#f_customer').val();
                d.region = $('#f_region').val();
                d.status = $('#f_status').val();
                d.start_date = $('#f_start').val();
                d.end_date = $('#f_end').val();
            }
        },
        columns: [
            { data: "DT_RowIndex", searchable:false, orderable:false, className:"text-center" },
            { data: "customer", name:'company_name', className:"align-middle" },
            { data: "invoice", name:'doc_no', className:"align-middle" },
            { data: "credit_term", className:"text-center align-middle" },
            { data: "due_date", className:"text-center align-middle" },
            { data: "days_overdue", className:"text-center align-middle" },
            { data: "aging", className:"text-center align-middle" },
            { data: "total_amt", className:"text-right align-middle" },
            { data: "paid_amt", className:"text-right align-middle text-muted" },
            { data: "outstanding_amt", className:"text-right align-middle" }
        ],
        order: [[4, "asc"]]
    });

    function refreshSummary(){
        $.get(url_gb + "/admin/{{ $route_locale }}/AccountReceivable/Summary", {
            customer_id: $('#f_customer').val(), region: $('#f_region').val(), status: $('#f_status').val(),
            start_date: $('#f_start').val(), end_date: $('#f_end').val()
        }).done(function(s){
            var f = function(n){ return Number(n).toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2}); };
            $('#s_outstanding').text(f(s.outstanding)); $('#s_overdue').text(f(s.overdue)); $('#s_count').text(s.count);
            $('#s_current').text(f(s.b_current)); $('#s_b30').text(f(s.b30)); $('#s_b60').text(f(s.b60));
            $('#s_b90').text(f(s.b90)); $('#s_b90p').text(f(s.b90p));
        });
    }
    function reload(){ tableAR.ajax.reload(); refreshSummary(); }
    try { $('#f_customer').select2({ width:'100%' }); } catch(e){}
    $('#f_customer, #f_region, #f_status, #f_start, #f_end').on('change', reload);
    $('#f_reset').on('click', function(){
        $('#f_customer').val('all').trigger('change.select2'); $('#f_region').val('all');
        $('#f_status').val('outstanding'); $('#f_start').val(''); $('#f_end').val(''); reload();
    });
</script>
@endpush
