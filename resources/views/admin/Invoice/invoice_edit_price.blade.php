@extends('admin.layouts.default')

@section('title', $pageTitle)

@php
    $curSymbol = optional(optional(optional($packingForm->details->first())->piProduct)->pi)->currency->symbol ?? '';
@endphp

@section('body')
<div class="page-content container-fluid container-plus">
    <div class="page-header mb-2 pb-2 flex-column flex-sm-row align-items-start align-items-sm-center py-25 px-1">
        <h1 class="page-title text-primary-d2 text-140">{{ $pageTitle }}</h1>
        <div class="page-tools mt-3 mt-sm-0 mb-sm-n1">
            <a href="{{ url('admin/'.$route_locale.'/PackingForm/'.$packingForm->id) }}" class="btn btn-light-secondary border-0 radius-3 py-2 text-600 text-90 mr-1">
                <i class="fa fa-arrow-left mr-1"></i> กลับ
            </a>
            <a href="{{ url('admin/'.$route_locale.'/Invoice/'.$packingForm->id.'/pdf') }}" target="_blank" class="btn btn-light-success border-0 radius-3 py-2 text-600 text-90 mr-1">
                <i class="fa fa-file-invoice-dollar mr-1"></i> ดู Invoice
            </a>
            <button type="button" id="btn-save-price" class="btn btn-primary border-0 radius-3 py-2 text-600 text-90">
                <i class="fa fa-save mr-1"></i> บันทึกราคา
            </button>
        </div>
    </div>

    <div class="card dcard mb-3">
        <div class="card-body py-2">
            <div class="row text-90 align-items-center">
                <div class="col-md-3"><span class="text-muted">Invoice No. :</span> <b>{{ $packingForm->invoice_no ?: $packingForm->doc_no }}</b></div>
                <div class="col-md-4"><span class="text-muted">ลูกค้า :</span> <b>{{ $packingForm->customer_name ?: '-' }}</b></div>
                <div class="col-md-2"><span class="text-muted">สกุลเงิน :</span> <b>{{ $curSymbol ?: '-' }}</b></div>
                <div class="col-md-3">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend"><span class="input-group-text bgc-warning-l3 text-dark-m1"><i class="fa fa-percent mr-1"></i> Under Value</span></div>
                        <input type="number" step="0.01" min="0" max="100" id="under_value_percent" class="form-control text-right" value="{{ $packingForm->under_value_percent !== null ? $packingForm->under_value_percent : '' }}" placeholder="%">
                        <div class="input-group-append"><button type="button" id="btn-apply-pct" class="btn btn-warning" title="คำนวณราคา Under Value จาก % (ราคาปกติ × %)"><i class="fa fa-calculator"></i> คำนวณ</button></div>
                    </div>
                    <small class="text-muted text-85">คีย์ % แล้วกด “คำนวณ” จะเติมช่อง Under Value ให้ทุกแถว (แก้รายแถวทีหลังได้)</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card dcard">
        <div class="card-body p-0">
            <table class="table table-striped-primary table-borderless border-0 mb-0 w-100">
                <thead>
                    <tr class="bgc-primary-d1 text-white">
                        <th class="text-center" width="4%">#</th>
                        <th width="13%">Part No</th>
                        <th>รายละเอียด</th>
                        <th class="text-center" width="8%">จำนวน</th>
                        <th class="text-right" width="15%">ราคาปกติ/หน่วย</th>
                        <th class="text-right bgc-warning-d1" width="15%">Under Value/หน่วย</th>
                        <th class="text-right bgc-success-d1" width="15%">Freight (รวม)/หน่วย</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($packingForm->details as $i => $detail)
                        @php
                            $fallback = optional($detail->piProduct)->price_per_item;
                            $price = $detail->unit_price !== null ? $detail->unit_price : $fallback;
                        @endphp
                        <tr>
                            <td class="text-center align-middle">{{ $i + 1 }}</td>
                            <td class="align-middle">{{ $detail->part_no }}</td>
                            <td class="align-middle text-90">{{ $detail->description }}</td>
                            <td class="text-center align-middle qty-cell" data-qty="{{ (float) $detail->qty }}">{{ number_format((float) $detail->qty) }}</td>
                            <td class="text-right align-middle">
                                <input type="hidden" name="detail_id[]" value="{{ $detail->id }}">
                                <input type="number" step="0.01" min="0"
                                       class="form-control form-control-sm text-right price-input price-normal"
                                       name="unit_price[]" value="{{ $price !== null ? $price : '' }}">
                            </td>
                            <td class="text-right align-middle">
                                <input type="number" step="0.01" min="0"
                                       class="form-control form-control-sm text-right price-under"
                                       name="under_value_price[]" value="{{ $detail->under_value_price !== null ? $detail->under_value_price : '' }}">
                            </td>
                            <td class="text-right align-middle">
                                <input type="number" step="0.01" min="0"
                                       class="form-control form-control-sm text-right price-freight"
                                       name="freight_price[]" value="{{ $detail->freight_price !== null ? $detail->freight_price : '' }}">
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-4">ไม่มีรายการ</td></tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="bgc-grey-l3 text-dark text-600">
                        <td colspan="4" class="text-right">TOTAL ({{ $curSymbol ?: '' }})</td>
                        <td class="text-right"><span id="total-normal">0.00</span></td>
                        <td class="text-right"><span id="total-under">0.00</span></td>
                        <td class="text-right"><span id="total-freight">0.00</span></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="text-85 text-muted mt-2 px-1">
        <i class="fa fa-info-circle mr-1"></i> ราคาทั้ง 3 ชุดจะใช้ตอนเปิด <b>Invoice PDF</b> (เลือกราคาในหน้ารายละเอียด Packing List) — ช่องที่เว้นว่างจะใช้ราคาปกติแทน
    </div>
</div>
@endsection

@push('scripts')
<script>
    var INVOICE_UPDATE_URL = url_gb + "/admin/{{ $route_locale }}/Invoice/{{ $packingForm->id }}/UpdatePrice";

    function fmt(n) {
        return (isFinite(n) ? n : 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function rowQty($row) { return parseFloat($row.find('.qty-cell').data('qty')) || 0; }

    function recalc() {
        var tN = 0, tU = 0, tF = 0;
        $('.price-normal').each(function () {
            var $row = $(this).closest('tr');
            var qty = rowQty($row);
            var pn = parseFloat($(this).val()) || 0;
            var pu = parseFloat($row.find('.price-under').val());
            var pf = parseFloat($row.find('.price-freight').val());
            // ช่องว่าง -> ใช้ราคาปกติแทน (ให้ตรงกับ PDF)
            if (!isFinite(pu)) pu = pn;
            if (!isFinite(pf)) pf = pn;
            tN += qty * pn;
            tU += qty * pu;
            tF += qty * pf;
        });
        $('#total-normal').text(fmt(tN));
        $('#total-under').text(fmt(tU));
        $('#total-freight').text(fmt(tF));
    }

    $(document).on('input', '.price-normal, .price-under, .price-freight', recalc);
    recalc();

    // คำนวณ Under Value จาก % รวม = ราคาปกติ × %
    $('#btn-apply-pct').on('click', function () {
        var pct = parseFloat($('#under_value_percent').val());
        if (!isFinite(pct) || pct <= 0) {
            if (typeof Swal !== 'undefined') Swal.fire({ icon: 'warning', title: 'ใส่ % ก่อน', text: 'กรุณากรอกเปอร์เซ็นต์ Under Value (มากกว่า 0)' });
            return;
        }
        $('.price-normal').each(function () {
            var $row = $(this).closest('tr');
            var pn = parseFloat($(this).val());
            if (isFinite(pn)) {
                $row.find('.price-under').val((pn * pct / 100).toFixed(2));
            }
        });
        recalc();
    });

    $('#btn-save-price').on('click', function () {
        var btn = $(this);
        var detail_id = [], unit_price = [], under_value_price = [], freight_price = [];
        $('.price-normal').each(function () {
            var $row = $(this).closest('tr');
            detail_id.push($row.find('input[name="detail_id[]"]').val());
            unit_price.push($(this).val());
            under_value_price.push($row.find('.price-under').val());
            freight_price.push($row.find('.price-freight').val());
        });

        if (typeof loadingButton === 'function') loadingButton(btn);
        $.ajax({
            url: INVOICE_UPDATE_URL,
            type: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: {
                _method: 'PUT',
                _token: $('meta[name="csrf-token"]').attr('content'),
                detail_id: detail_id,
                unit_price: unit_price,
                under_value_price: under_value_price,
                freight_price: freight_price,
                under_value_percent: $('#under_value_percent').val()
            }
        }).done(function (res) {
            if (typeof resetButton === 'function') resetButton(btn);
            if (res.status == 1) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'success', title: res.title || 'สำเร็จ', text: res.content || '', timer: 1500, showConfirmButton: false });
                } else { alert(res.content || 'บันทึกแล้ว'); }
            } else {
                if (typeof Swal !== 'undefined') { Swal.fire({ icon: 'error', title: res.title || 'ผิดพลาด', text: res.content || '' }); }
                else { alert(res.content || 'ผิดพลาด'); }
            }
        }).fail(function (xhr) {
            if (typeof resetButton === 'function') resetButton(btn);
            if (typeof ajaxFail === 'function') { ajaxFail(xhr, ''); }
            else { alert('บันทึกไม่สำเร็จ'); }
        });
    });
</script>
@endpush
