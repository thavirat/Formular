@extends('admin.layouts.default')

@section('title', $currentMenu->title)

@section('body')
@php $lang = $route_locale; @endphp
<div class="page-content container-fluid container-plus">
    <div class="page-header mb-2 pb-2">
        <h1 class="page-title text-primary-d2 text-140"><i class="fa fa-chart-line mr-2"></i>{{ $currentMenu->title }}</h1>
    </div>

    <div class="row">
        <div class="col-md-4 col-sm-6 mb-3">
            <a href="{{ url('admin/'.$lang.'/Report/ExportOrder') }}" class="text-decoration-none">
                <div class="card dcard h-100 report-card">
                    <div class="card-body text-center py-4">
                        <div class="text-160 text-primary-d1 mb-2"><i class="fa fa-ship"></i></div>
                        <h5 class="text-dark-m2 font-bolder">ยอดออก (Export / Shipped)</h5>
                        <p class="text-90 text-muted mb-0">สรุปการส่งออกจาก Packing List — กราฟรายเดือน/ภูมิภาค + ยอดเงิน · Export Excel</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4 col-sm-6 mb-3">
            <a href="{{ url('admin/'.$lang.'/AccountReceivable') }}" class="text-decoration-none">
                <div class="card dcard h-100 report-card">
                    <div class="card-body text-center py-4">
                        <div class="text-160 text-danger-d1 mb-2"><i class="fa fa-hand-holding-usd"></i></div>
                        <h5 class="text-dark-m2 font-bolder">ลูกหนี้การค้า (AR)</h5>
                        <p class="text-90 text-muted mb-0">ยอดค้างชำระ / เกินกำหนด / aging รายลูกค้า</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
<style>.report-card{transition:.15s;cursor:pointer}.report-card:hover{box-shadow:0 6px 18px rgba(0,0,0,.12);transform:translateY(-2px)}</style>
@endsection
