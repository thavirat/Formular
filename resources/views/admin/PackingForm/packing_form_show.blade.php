@extends('admin.layouts.default')

@section('title', $currentMenu->title.' : '.$packingForm->doc_no)

@php
    $can_u = isset($my_menu_permission[$currentMenu->url]['u']) && $my_menu_permission[$currentMenu->url]['u'] == 'T';
    $can_d = isset($my_menu_permission[$currentMenu->url]['d']) && $my_menu_permission[$currentMenu->url]['d'] == 'T';
    $base  = url('admin/'.$route_locale.'/PackingForm/'.$packingForm->id);
    $listUrl = url('admin/'.$route_locale.'/PackingForm');
    $fmt = fn ($v, $d = 2) => $v !== null && $v !== '' ? number_format((float) $v, $d) : '-';
@endphp

@section('body')
<div class="page-content container-fluid container-plus">

    {{-- ===== Page header ===== --}}
    <div class="page-header mb-2 pb-2 flex-column flex-sm-row align-items-start align-items-sm-center py-25 px-1">
        <div>
            <a href="{{ $listUrl }}" class="text-grey-m2 text-decoration-none text-90"><i class="fa fa-arrow-left mr-1"></i> กลับไปหน้ารายการ</a>
            <h1 class="page-title text-primary-d2 text-150 mb-0 mt-1">
                <i class="fa fa-folder-open text-primary-m1 mr-1"></i> {{ $packingForm->doc_no }}
            </h1>
            <div class="text-90 text-muted mt-1">
                {{ $packingForm->customer_name ?: '-' }}
                @if($packingForm->invoice_no)
                    <span class="mx-1">·</span> Invoice No. <span class="text-600">{{ $packingForm->invoice_no }}</span>
                @endif
                <span class="mx-1">·</span> {{ $packingForm->doc_date ? $packingForm->doc_date->format('d/m/Y') : '-' }}
            </div>
        </div>
        <div class="page-tools mt-3 mt-sm-0 ml-sm-auto">
            @if($can_u)
                <a href="{{ $base.'/edit' }}" class="btn btn-warning btn-h-warning border-0 radius-3 px-3 py-2 text-600 text-90 mr-1">
                    <i class="fa fa-edit mr-1"></i> แก้ไขเอกสาร
                </a>
            @endif
            @if($can_d)
                <button type="button" id="btn-delete-pf" class="btn btn-danger btn-h-danger border-0 radius-3 px-3 py-2 text-600 text-90">
                    <i class="fa fa-trash mr-1"></i> ลบ
                </button>
            @endif
        </div>
    </div>

    {{-- ===== Section: งานเอกสาร (Documents) ===== --}}
    <div class="card dcard mb-3 border-l-4 brc-primary-m3">
        <div class="card-body py-3">
            <h5 class="text-primary-d2 text-130 mb-3"><i class="fa fa-print mr-1"></i> งานเอกสาร</h5>
            <div class="row">
                {{-- Packing List --}}
                <div class="col-lg-6 mb-3 mb-lg-0">
                    <div class="border-1 brc-info-l2 radius-2 p-3 h-100 bgc-info-l5">
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge badge-info text-100 px-2 py-1"><i class="fa fa-box mr-1"></i> Packing List</span>
                        </div>
                        <div class="d-flex flex-wrap" style="gap:.5rem;">
                            <a href="{{ $base.'/pdf/customer' }}" target="_blank" class="btn btn-info btn-h-info text-90 px-3 py-25">
                                <i class="fa fa-file-pdf mr-1"></i> ดู / พิมพ์ <span class="text-muted-l3">(ลูกค้า)</span>
                            </a>
                            <a href="{{ $base.'/pdf/customer?desc=master' }}" target="_blank" class="btn btn-outline-info text-90 px-3 py-25">
                                <i class="fa fa-file-pdf mr-1"></i> ดู / พิมพ์ <span>(Master)</span>
                            </a>
                            <a href="{{ $base.'/excel?variant=customer' }}" target="_blank" class="btn btn-outline-success text-90 px-3 py-25">
                                <i class="fa fa-file-excel mr-1"></i> Excel
                            </a>
                        </div>
                        <div class="text-85 text-muted mt-2">(ลูกค้า) = ใช้ description ที่ลูกค้าตั้ง · (Master) = description จากคลังสินค้า</div>
                    </div>
                </div>
                {{-- Invoice --}}
                <div class="col-lg-6">
                    <div class="border-1 brc-success-l2 radius-2 p-3 h-100 bgc-success-l5">
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge badge-success text-100 px-2 py-1"><i class="fa fa-file-invoice-dollar mr-1"></i> Invoice (บัญชี)</span>
                            @if($can_u)
                                <a href="{{ url('admin/'.$route_locale.'/Invoice/'.$packingForm->id.'/EditPrice') }}" class="btn btn-sm btn-warning btn-h-warning text-85 px-2 py-1 ml-auto">
                                    <i class="fa fa-dollar-sign mr-1"></i> แก้ไขราคา
                                </a>
                            @endif
                        </div>

                        {{-- เลือกชุดราคาสำหรับ Invoice PDF --}}
                        <div class="mb-2">
                            <span class="text-85 text-muted mr-1">ราคาที่ใช้:</span>
                            <div class="btn-group btn-group-sm" role="group" id="inv-price-group">
                                <button type="button" class="btn btn-outline-secondary active" data-price="normal">ปกติ</button>
                                <button type="button" class="btn btn-outline-secondary" data-price="under_value">Under Value</button>
                                <button type="button" class="btn btn-outline-secondary" data-price="freight">Freight</button>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap" style="gap:.5rem;">
                            <a href="{{ $base.'/pdf/accounting' }}" target="_blank" data-desc="customer" class="btn btn-success btn-h-success text-90 px-3 py-25 inv-pdf-link">
                                <i class="fa fa-file-pdf mr-1"></i> ดู / พิมพ์ <span>(ลูกค้า)</span>
                            </a>
                            <a href="{{ $base.'/pdf/accounting?desc=master' }}" target="_blank" data-desc="master" class="btn btn-outline-success text-90 px-3 py-25 inv-pdf-link">
                                <i class="fa fa-file-pdf mr-1"></i> ดู / พิมพ์ <span>(Master)</span>
                            </a>
                            <a href="{{ $base.'/excel?variant=accounting' }}" target="_blank" class="btn btn-outline-success text-90 px-3 py-25">
                                <i class="fa fa-file-excel mr-1"></i> Excel
                            </a>
                        </div>
                        <div class="text-85 text-muted mt-2">ใบ Invoice สำหรับบัญชี — เลือกชุดราคา (ปกติ / Under Value / Freight) ก่อนเปิด PDF · Excel ใช้ราคาปกติ</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- ===== Section: ข้อมูลส่วนหัว (Header) ===== --}}
        <div class="col-lg-8 mb-3">
            <div class="card dcard h-100">
                <div class="card-body py-3">
                    <h5 class="text-primary-d2 text-120 mb-3"><i class="fa fa-info-circle mr-1"></i> ข้อมูลส่วนหัว</h5>

                    <h6 class="text-secondary-d1 text-95 mb-2 border-b-1 brc-grey-l2 pb-1">ลูกค้า</h6>
                    <div class="row">
                        @include('admin.PackingForm._show_field', ['label' => 'ชื่อลูกค้า', 'value' => $packingForm->customer_name, 'col' => 6])
                        @include('admin.PackingForm._show_field', ['label' => 'To', 'value' => $packingForm->to, 'col' => 3])
                        @include('admin.PackingForm._show_field', ['label' => 'ประเทศ', 'value' => $packingForm->country, 'col' => 3])
                        @include('admin.PackingForm._show_field', ['label' => 'โทรศัพท์', 'value' => $packingForm->customer_phone, 'col' => 6])
                        @include('admin.PackingForm._show_field', ['label' => 'ที่อยู่', 'value' => $packingForm->customer_address, 'col' => 6])
                    </div>

                    <h6 class="text-secondary-d1 text-95 mb-2 mt-2 border-b-1 brc-grey-l2 pb-1">เอกสาร / การขนส่ง</h6>
                    <div class="row">
                        @include('admin.PackingForm._show_field', ['label' => 'เลขที่ Packing List', 'value' => $packingForm->doc_no, 'col' => 3])
                        @include('admin.PackingForm._show_field', ['label' => 'Invoice No.', 'value' => $packingForm->invoice_no, 'col' => 3])
                        @include('admin.PackingForm._show_field', ['label' => 'วันที่เอกสาร', 'value' => $packingForm->doc_date ? $packingForm->doc_date->format('d/m/Y') : null, 'col' => 3])
                        @include('admin.PackingForm._show_field', ['label' => 'ETD (Sailing)', 'value' => $packingForm->sailing_date ? $packingForm->sailing_date->format('d/m/Y') : null, 'col' => 3])
                        @include('admin.PackingForm._show_field', ['label' => 'สถานที่ออก (Place)', 'value' => $packingForm->place_of_issue, 'col' => 3])
                        @include('admin.PackingForm._show_field', ['label' => 'Port of Loading', 'value' => $packingForm->shipped_from, 'col' => 3])
                        @include('admin.PackingForm._show_field', ['label' => 'Vessel', 'value' => $packingForm->per_vessel, 'col' => 3])
                        @include('admin.PackingForm._show_field', ['label' => 'Port of Discharge', 'value' => $packingForm->port_of_discharge, 'col' => 3])
                        @include('admin.PackingForm._show_field', ['label' => 'L/C No.', 'value' => $packingForm->lc_no, 'col' => 3])
                        @include('admin.PackingForm._show_field', ['label' => 'Terms of Payment', 'value' => $packingForm->term_of_payment, 'col' => 3])
                        @include('admin.PackingForm._show_field', ['label' => 'Declaration No.', 'value' => $packingForm->declaration_no, 'col' => 3])
                        @include('admin.PackingForm._show_field', ['label' => 'ไฟล์ต้นทาง', 'value' => $packingForm->source_filename, 'col' => 3])
                    </div>
                    @if(!empty($packingForm->marks))
                    <div class="row">
                        @include('admin.PackingForm._show_field', ['label' => 'Marks & No.', 'value' => $packingForm->marks, 'col' => 12])
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ===== Section: สรุปยอด (Totals) ===== --}}
        <div class="col-lg-4 mb-3">
            <div class="card dcard h-100">
                <div class="card-body py-3">
                    <h5 class="text-primary-d2 text-120 mb-3"><i class="fa fa-calculator mr-1"></i> สรุปยอดท้ายเอกสาร</h5>
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="bgc-primary-l5 radius-2 py-3">
                                <div class="text-170 text-primary-d2 text-600">{{ $fmt($packingForm->pkg, 0) }}</div>
                                <div class="text-90 text-muted">Pkg (หีบห่อ)</div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="bgc-secondary-l4 radius-2 py-3">
                                <div class="text-170 text-secondary-d2 text-600">{{ $fmt($packingForm->qty, 0) }}</div>
                                <div class="text-90 text-muted">Qty รวม</div>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="bgc-info-l5 radius-2 py-3">
                                <div class="text-170 text-info-d2 text-600">{{ $fmt($packingForm->cubic_meter, 3) }}</div>
                                <div class="text-90 text-muted">CBM รวม (ลบ.ม.)</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bgc-success-l5 radius-2 py-2">
                                <div class="text-140 text-success-d2 text-600">{{ $fmt($packingForm->weight_nw) }}</div>
                                <div class="text-85 text-muted">N.W. (KG)</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bgc-success-l5 radius-2 py-2">
                                <div class="text-140 text-success-d2 text-600">{{ $fmt($packingForm->weight_gw) }}</div>
                                <div class="text-85 text-muted">G.W. (KG)</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Section: รายการสินค้า (Line items) ===== --}}
    <div class="card dcard mb-3">
        <div class="card-body py-3">
            <h5 class="text-primary-d2 text-120 mb-3">
                <i class="fa fa-list mr-1"></i> รายการสินค้า
                <span class="badge badge-sm badge-primary ml-1">{{ $packingForm->details_count }} รายการ</span>
            </h5>
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-striped-primary mb-0 w-100 text-90">
                    <thead>
                        <tr class="bgc-primary-d1 text-white text-center">
                            <th width="40">#</th>
                            <th>Mark &amp; No.</th>
                            <th>PI</th>
                            <th>Part No.</th>
                            <th>Cus Part No.</th>
                            <th>Description</th>
                            <th width="110">W × L × H</th>
                            <th width="70">Qty</th>
                            <th width="60">UOM</th>
                            <th width="80">CBM</th>
                            <th width="80">N.W.</th>
                            <th width="80">G.W.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($packingForm->details as $i => $d)
                            <tr>
                                <td class="text-center">{{ $i + 1 }}</td>
                                <td>{{ $d->mark_no ?: '-' }}</td>
                                <td>{{ optional(optional($d->piProduct)->pi)->doc_no ?: '-' }}</td>
                                <td>{{ $d->part_no ?: '-' }}</td>
                                <td>{{ $d->cus_part_no ?: '-' }}</td>
                                <td>{{ $d->description ?: '-' }}</td>
                                <td class="text-center">{{ $fmt($d->width) }} × {{ $fmt($d->lenght) }} × {{ $fmt($d->height) }}</td>
                                <td class="text-right">{{ $fmt($d->qty, 0) }}</td>
                                <td class="text-center">{{ $d->uom ?: '-' }}</td>
                                <td class="text-right">{{ $fmt($d->cubic_meter, 3) }}</td>
                                <td class="text-right">{{ $fmt($d->weight_nw) }}</td>
                                <td class="text-right">{{ $fmt($d->weight_gw) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="12" class="text-center text-muted py-3">ไม่มีรายการสินค้า</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // ---- เลือกชุดราคา -> ปรับลิงก์ Invoice PDF ----
    (function () {
        var invBase = "{{ $base }}/pdf/accounting";
        var curPrice = 'normal';
        function buildHref(desc) {
            var params = [];
            if (desc === 'master') params.push('desc=master');
            if (curPrice !== 'normal') params.push('price=' + curPrice);
            return invBase + (params.length ? ('?' + params.join('&')) : '');
        }
        function refresh() {
            document.querySelectorAll('.inv-pdf-link').forEach(function (a) {
                a.setAttribute('href', buildHref(a.getAttribute('data-desc')));
            });
        }
        document.querySelectorAll('#inv-price-group button').forEach(function (btn) {
            btn.addEventListener('click', function () {
                document.querySelectorAll('#inv-price-group button').forEach(function (b) { b.classList.remove('active'); });
                btn.classList.add('active');
                curPrice = btn.getAttribute('data-price');
                refresh();
            });
        });
        refresh();
    })();

    $('#btn-delete-pf').on('click', function () {
        Swal.fire({
            title: 'ลบ Packing list นี้?',
            html: 'เอกสาร <b>{{ $packingForm->doc_no }}</b> จะถูกลบถาวร',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'ลบ',
            cancelButtonText: 'ยกเลิก',
            confirmButtonColor: '#d33'
        }).then(function (r) {
            if (!r.isConfirmed) return;
            $.ajax({
                method: "DELETE",
                url: "{{ $base }}",
                dataType: "json",
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            }).done(function (res) {
                if (res.status == 1) {
                    Swal.fire({ icon: 'success', title: 'สำเร็จ', text: res.content || '' }).then(function () {
                        window.location = "{{ $listUrl }}";
                    });
                } else {
                    Swal.fire('ผิดพลาด', res.content || '', 'error');
                }
            }).fail(function (xhr) { ajaxFail(xhr, ""); });
        });
    });
</script>
@endpush
