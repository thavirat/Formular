@extends('admin.layouts.default')

@section('title', 'รายงานยอดออก')

@section('body')
@php $lang = $route_locale; @endphp
<div class="page-content container-fluid container-plus">
    <div class="page-header mb-2 pb-2 flex-column flex-sm-row align-items-start align-items-sm-center">
        <h1 class="page-title text-primary-d2 text-140"><i class="fa fa-ship mr-2"></i>ยอดออก (Export / Shipped)</h1>
        <div class="page-tools mt-2 mt-sm-0">
            <a href="{{ url('admin/'.$lang.'/Report') }}" class="btn btn-sm btn-outline-secondary"><i class="fa fa-arrow-left"></i> กลับ</a>
            <button type="button" id="btn-export" class="btn btn-sm btn-success"><i class="fa fa-file-excel"></i> Export Excel</button>
        </div>
    </div>

    {{-- ฟิลเตอร์ --}}
    <div class="card dcard mb-2"><div class="card-body py-2"><div class="row align-items-end">
        <div class="col-md-2"><label class="text-90 mb-1">ปี</label>
            <select id="f_year" class="form-control form-control-sm">
                <option value="">ทั้งหมด</option>
                @foreach($years as $y)<option value="{{ $y }}" {{ $y==date('Y')?'selected':'' }}>{{ $y }}</option>@endforeach
            </select></div>
        <div class="col-md-2"><label class="text-90 mb-1">ภูมิภาค</label>
            <select id="f_region" class="form-control form-control-sm">
                <option value="all">ทั้งหมด</option>
                @foreach($Regions as $r)<option value="{{ $r }}">{{ $r }}</option>@endforeach
            </select></div>
        <div class="col-md-3"><label class="text-90 mb-1">ลูกค้า</label>
            <input type="text" id="f_customer" class="form-control form-control-sm" placeholder="ชื่อลูกค้า"></div>
        <div class="col-md-2"><label class="text-90 mb-1">จาก</label>
            <input type="date" id="f_start" class="form-control form-control-sm"></div>
        <div class="col-md-2"><label class="text-90 mb-1">ถึง</label>
            <input type="date" id="f_end" class="form-control form-control-sm"></div>
        <div class="col-md-1"><button type="button" id="f_reset" class="btn btn-sm btn-outline-secondary"><i class="fa fa-times"></i></button></div>
    </div></div></div>

    {{-- สรุป --}}
    <div class="row">
        <div class="col-md-3 col-6 mb-2"><div class="card dcard"><div class="card-body py-2 text-center">
            <div class="text-90 text-muted">จำนวน Shipment</div><div class="text-170 text-primary-d1 font-bolder" id="s_count">0</div></div></div></div>
        <div class="col-md-3 col-6 mb-2"><div class="card dcard"><div class="card-body py-2 text-center">
            <div class="text-90 text-muted">CBM รวม</div><div class="text-170 text-info-d1 font-bolder" id="s_cbm">0.00</div></div></div></div>
        <div class="col-md-3 col-6 mb-2"><div class="card dcard"><div class="card-body py-2 text-center">
            <div class="text-90 text-muted">น้ำหนักรวม (G.W.)</div><div class="text-170 text-secondary-d1 font-bolder" id="s_weight">0.00</div></div></div></div>
        <div class="col-md-3 col-6 mb-2"><div class="card dcard"><div class="card-body py-2">
            <div class="text-90 text-muted mb-1">ยอดเงิน (แยกสกุล)</div><div id="s_amount" class="text-90"></div></div></div></div>
    </div>

    {{-- กราฟ --}}
    <div class="row">
        <div class="col-md-8 mb-2"><div class="card dcard"><div class="card-body">
            <div class="text-90 text-muted mb-1">ยอดออกรายเดือน (CBM)</div>
            <canvas id="chartMonthly" height="90"></canvas>
        </div></div></div>
        <div class="col-md-4 mb-2"><div class="card dcard"><div class="card-body">
            <div class="text-90 text-muted mb-1">แยกตามภูมิภาค (CBM)</div>
            <canvas id="chartRegion" height="180"></canvas>
        </div></div></div>
    </div>

    {{-- ตาราง --}}
    <div class="card dcard"><div class="card-body p-0">
        <table class="table table-sm table-striped-primary mb-0 w-100">
            <thead><tr class="bgc-primary-d1 text-white">
                <th>Invoice</th><th>วันที่</th><th>ETD</th><th>ลูกค้า</th><th>ประเทศ</th><th>ภูมิภาค</th>
                <th class="text-right">CBM</th><th class="text-right">G.W.</th><th class="text-right">ยอดเงิน</th>
            </tr></thead>
            <tbody id="ar-rows"><tr><td colspan="9" class="text-center py-3 text-muted">กำลังโหลด...</td></tr></tbody>
        </table>
    </div></div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
$(function(){
    var lang = "{{ $lang }}";
    var base = url_gb + "/admin/" + lang + "/Report/ExportOrder";
    var chMonthly=null, chRegion=null;
    var palette = ['#4e79a7','#f28e2b','#59a14f','#e15759','#76b7b2','#edc948','#b07aa1','#9c755f'];

    function params(){
        return { year:$('#f_year').val(), region:$('#f_region').val(), customer:$('#f_customer').val(), start_date:$('#f_start').val(), end_date:$('#f_end').val() };
    }
    function fnum(n){ return Number(n).toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2}); }

    function load(){
        $.get(base + "/Data", params()).done(function(d){
            // summary
            $('#s_count').text(d.summary.count);
            $('#s_cbm').text(fnum(d.summary.cbm));
            $('#s_weight').text(fnum(d.summary.weight));
            var ah = (d.summary.by_currency||[]).map(function(c){ return '<div><b>'+(c.currency||'-')+'</b> '+fnum(c.amount)+'</div>'; }).join('');
            $('#s_amount').html(ah || '<span class="text-muted">-</span>');

            // chart monthly
            var mLabels = d.monthly.map(function(m){ return m.month; });
            var mData = d.monthly.map(function(m){ return m.cbm; });
            if(chMonthly) chMonthly.destroy();
            chMonthly = new Chart(document.getElementById('chartMonthly').getContext('2d'), {
                type:'bar',
                data:{ labels:mLabels, datasets:[{ label:'CBM', data:mData, backgroundColor:'#4e79a7' }] },
                options:{ responsive:true, maintainAspectRatio:true, legend:{display:false}, scales:{ yAxes:[{ ticks:{ beginAtZero:true } }] } }
            });

            // chart region
            var rLabels = d.byRegion.map(function(r){ return r.region; });
            var rData = d.byRegion.map(function(r){ return r.cbm; });
            if(chRegion) chRegion.destroy();
            chRegion = new Chart(document.getElementById('chartRegion').getContext('2d'), {
                type:'doughnut',
                data:{ labels:rLabels, datasets:[{ data:rData, backgroundColor:palette }] },
                options:{ responsive:true, maintainAspectRatio:true, legend:{position:'bottom'} }
            });

            // table
            if(!d.rows.length){ $('#ar-rows').html('<tr><td colspan="9" class="text-center py-3 text-muted">ไม่มีข้อมูล</td></tr>'); return; }
            var h='';
            d.rows.forEach(function(r){
                h += '<tr>'
                  + '<td class="font-bolder">'+ (r.invoice_no||'') +'</td>'
                  + '<td>'+ (r.doc_date||'') +'</td><td>'+ (r.etd||'') +'</td>'
                  + '<td>'+ $('<div>').text(r.customer||'').html() +'</td>'
                  + '<td>'+ (r.country||'') +'</td><td>'+ (r.region||'') +'</td>'
                  + '<td class="text-right">'+ fnum(r.cbm) +'</td>'
                  + '<td class="text-right">'+ fnum(r.weight) +'</td>'
                  + '<td class="text-right font-bolder">'+ (r.currency||'') +' '+ fnum(r.amount) +'</td>'
                  + '</tr>';
            });
            $('#ar-rows').html(h);
        });
    }
    function q(){ return $.param(params()); }
    $('#btn-export').on('click', function(){ window.location = base + "/Excel?" + q(); });
    $('#f_year, #f_region, #f_start, #f_end').on('change', load);
    var t=null; $('#f_customer').on('keyup', function(){ clearTimeout(t); t=setTimeout(load,350); });
    $('#f_reset').on('click', function(){ $('#f_year').val('{{ date('Y') }}'); $('#f_region').val('all'); $('#f_customer').val(''); $('#f_start').val(''); $('#f_end').val(''); load(); });
    load();
});
</script>
@endpush
