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
            <a href="{{ url('admin/'.$route_locale.'/Invoice') }}" class="btn btn-light-secondary border-0 radius-3 py-2 text-600 text-90 mr-1">
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
            <div class="row text-90">
                <div class="col-md-4"><span class="text-muted">Invoice No. :</span> <b>{{ $packingForm->invoice_no ?: $packingForm->doc_no }}</b></div>
                <div class="col-md-5"><span class="text-muted">ลูกค้า :</span> <b>{{ $packingForm->customer_name ?: '-' }}</b></div>
                <div class="col-md-3"><span class="text-muted">สกุลเงิน :</span> <b>{{ $curSymbol ?: '-' }}</b></div>
            </div>
        </div>
    </div>

    <div class="card dcard">
        <div class="card-body p-0">
            <table class="table table-striped-primary table-borderless border-0 mb-0 w-100">
                <thead>
                    <tr class="bgc-primary-d1 text-white">
                        <th class="text-center" width="5%">#</th>
                        <th width="15%">Part No</th>
                        <th>รายละเอียด</th>
                        <th class="text-center" width="10%">จำนวน</th>
                        <th class="text-right" width="16%">ราคา/หน่วย</th>
                        <th class="text-right" width="16%">รวม (Amount)</th>
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
                                       class="form-control form-control-sm text-right price-input"
                                       name="unit_price[]" value="{{ $price !== null ? $price : '' }}">
                            </td>
                            <td class="text-right align-middle amount-cell">—</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4">ไม่มีรายการ</td></tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="bgc-grey-l3 text-dark">
                        <td colspan="5" class="text-right text-600">TOTAL</td>
                        <td class="text-right text-600"><span id="total-amount">0.00</span></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    var INVOICE_UPDATE_URL = url_gb + "/admin/{{ $route_locale }}/Invoice/{{ $packingForm->id }}/UpdatePrice";

    function fmt(n) {
        return (isFinite(n) ? n : 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function recalc() {
        var total = 0;
        $('.price-input').each(function () {
            var $row = $(this).closest('tr');
            var qty = parseFloat($row.find('.qty-cell').data('qty')) || 0;
            var price = parseFloat($(this).val()) || 0;
            var amount = qty * price;
            $row.find('.amount-cell').text(fmt(amount));
            total += amount;
        });
        $('#total-amount').text(fmt(total));
    }

    $(document).on('input', '.price-input', recalc);
    recalc();

    $('#btn-save-price').on('click', function () {
        var btn = $(this);
        var detail_id = [], unit_price = [];
        $('.price-input').each(function () {
            var $row = $(this).closest('tr');
            detail_id.push($row.find('input[name="detail_id[]"]').val());
            unit_price.push($(this).val());
        });

        if (typeof loadingButton === 'function') loadingButton(btn);
        $.ajax({
            url: INVOICE_UPDATE_URL,
            type: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: { _method: 'PUT', _token: $('meta[name="csrf-token"]').attr('content'), detail_id: detail_id, unit_price: unit_price }
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
