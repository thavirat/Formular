@extends('admin.layouts.default')

@section('title', $currentMenu->title)

@section('css')
<style>
    .part-no-col {
        width: 180px !important;
        min-width: 180px !important;
    }

    .form-control:focus {
        border-color: #67bbb9;
        box-shadow: 0 0 0 0.2rem rgba(103, 187, 185, 0.25);
    }

    .input-loading {
        background-color: #f8f9fa;
        border-color: #ffc107 !important;
        color: #ffc107;
    }

    .cursor-move {
        cursor: grab;
    }
    .cursor-move:active {
        cursor: grabbing;
    }
</style>
@endsection

@section('body')
<div class="page-content container-fluid container-plus">
    <div class="page-header mb-2 pb-2 flex-column flex-sm-row align-items-start align-items-sm-center py-25 px-1">
        <h1 class="page-title text-primary-d2 text-140">{{__('Edit Proforma Invoice')}} #{{ $ProformaInvoice->doc_no }}</h1>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card dcard">
                <div class="card-body p-3">
                    <form action="" id="form-edit-pi" method="POST">
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="quotation_id" value="{{ $ProformaInvoice->quotation_id ?? '' }}">
                        <input type="hidden" id="pi_id" value="{{ $ProformaInvoice->id }}">

                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="customer_id">{{__('Select Customer')}} <span class="text-danger">*</span></label>
                                    <select name="customer_id" id="customer_id" class="form-control select2" >
                                        <option value="">{{__('Select Customer')}}</option>
                                        @foreach($Customers as $customer)
                                            <option value="{{$customer->id}}" {{ $ProformaInvoice->customer_id == $customer->id ? 'selected' : '' }}>
                                                {{$customer->code ? $customer->code.' - ' : ''}}{{$customer->company_name}} - {{$customer->contact_name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-3"></div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="doc_date">{{__('Document Date')}} <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="doc_date" class="form-control init-date" id="doc_date" value="{{ date('Y-m-d', strtotime($ProformaInvoice->doc_date)) }}" readonly>
                                        <div class="input-group-append remove_date_time">
                                            <div class="input-group-text"><i class="fa fa-times"></i></div>
                                        </div>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><i class="far fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="contact_name">{{__('Contact Name')}}</label>
                                    <input type="text" name="contact_name" id="contact_name" class="form-control" value="{{$ProformaInvoice->contact_name}}">
                                </div>
                                <div class="form-group">
                                    <label for="company_name">{{__('Company Name')}}</label>
                                    <input type="text" name="company_name" id="company_name" class="form-control" value="{{$ProformaInvoice->company_name}}" >
                                </div>
                                <div class="form-group">
                                    <label for="tax_id">{{__('Tax ID')}}</label>
                                    <input type="text" name="tax_id" id="tax_id" class="form-control" value="{{$ProformaInvoice->tax_id}}">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="address">{{__('Address')}}</label>
                                    <textarea name="address" id="address" class="form-control" rows="5" style="height: 124px;">{{$ProformaInvoice->address}}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="fax_no">{{__('Fax No')}}</label>
                                    <input type="text" name="fax_no" id="fax_no" class="form-control" value="{{$ProformaInvoice->fax_no}}">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="incoterm_id">{{__('Incoterm')}} <span class="text-danger">*</span></label>
                                    <select name="incoterm_id" id="incoterm_id" class="form-control select2" >
                                        @foreach($Incoterms as $incoterm)
                                            <option value="{{$incoterm->id}}" {{ $ProformaInvoice->incoterm_id == $incoterm->id ? 'selected' : '' }}>{{$incoterm->code}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="currency_id">{{__('Currency')}} <span class="text-danger">*</span></label>
                                    <select name="currency_id" id="currency_id" class="form-control select2" >
                                        @foreach($Currencies as $currency)
                                            <option value="{{$currency->id}}" {{ $ProformaInvoice->currency_id == $currency->id ? 'selected' : '' }}>{{$currency->symbol}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="credit_payment_id">{{__('Credit Payment')}} <span class="text-danger">*</span></label>
                                    <select name="credit_payment_id" id="credit_payment_id" class="form-control select2" >
                                        @foreach($CreditPayments as $CreditPayment)
                                            <option value="{{$CreditPayment->id}}" {{ $ProformaInvoice->credit_payment_id == $CreditPayment->id ? 'selected' : '' }}>{{$CreditPayment->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- ===== ข้อมูลการขนส่ง ===== --}}
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="ship_date">{{__('Ship Date')}}</label>
                                    <input type="text" name="ship_date" id="ship_date" class="form-control init-date" value="{{ $ProformaInvoice->ship_date }}">
                                </div>
                                <div class="form-group">
                                    <label for="ship_to_code">{{__('Ship To Code')}}</label>
                                    <input type="text" name="ship_to_code" id="ship_to_code" class="form-control" value="{{ $ProformaInvoice->ship_to_code }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="cno">C/NO.</label>
                                    <input type="text" name="cno" id="cno" class="form-control" value="{{ $ProformaInvoice->cno }}" placeholder="เช่น 1-UP">
                                </div>
                                <div class="form-group">
                                    <label for="customer_po">{{__('Customer PO')}}</label>
                                    <input type="text" name="customer_po" id="customer_po" class="form-control" value="{{ $ProformaInvoice->customer_po }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="ship_remark">{{__('Ship Remark')}} (Shipping Marks)</label>
                                    <textarea name="ship_remark" id="ship_remark" class="form-control" rows="3" placeholder="พิมพ์ได้หลายบรรทัด">{{ $ProformaInvoice->ship_remark }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="shipment_to">TO</label>
                                    <input type="text" name="shipment_to" id="shipment_to" class="form-control" value="{{ $ProformaInvoice->shipment_to }}" placeholder="ปลายทาง">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="text-bold d-block">{{__('Shipment')}}</label>
                                    @foreach($ShipmentMethods as $sm)
                                        <label class="mr-3">
                                            <input type="radio" name="shipment_method_id" value="{{ $sm->id }}" {{ $ProformaInvoice->shipment_method_id == $sm->id ? 'checked' : '' }}> {{ $sm->name }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <table class="table table-bordered" id="productTable">
                                    <thead class="bgc-primary-d1 text-white text-90">
                                        <tr>
                                            <th width="5%" class="text-center">{{__('ITM')}}</th>
                                            <th class="part-no-col">{{__('Part No.')}}</th>
                                            <th width="10%">{{__('Drawing')}}</th>
                                            <th width="10%">{{__('Cus.Code')}}</th>
                                            <th>{{__('Descript')}}</th>
                                            <th width="8%">{{__('Qty (to PI)')}}</th>
                                            <th width="10%">{{__('Unit Price')}}</th>
                                            <th width="12%">{{__('Amount')}}</th>
                                            <th width="5%"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($ProformaInvoice->products as $item)
                                        <tr>
                                            <td class="text-center align-middle">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-grip-vertical cursor-move text-muted mr-2" title="Drag to reorder"></i>
                                                    <input type="number" class="form-control text-center item-seq p-1" name="seq[]" value="{{ $item->seq }}" readonly style="width: 50px;">
                                                </div>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control part-no-input" name="part_no[]" value="{{ $item->part_no }}" placeholder="Type Part No" autocomplete="off">
                                                <input type="hidden" name="product[]" class="product-id" value="{{ $item->product_id }}">
                                            </td>
                                            <td><input type="text" class="form-control drawing" name="drawing[]" value="{{ $item->drawing }}"></td>
                                            <td><input type="text" class="form-control customer_code" name="customer_code[]" value="{{ $item->cus_code }}"></td>
                                            <td><input type="text" class="form-control description" name="description[]" value="{{ $item->detail_eng }}"></td>
                                            <td><input type="number" class="form-control qty" name="qty[]" value="{{ $item->qty }}" step="any"></td>
                                            <td><input type="number" class="form-control unit_price text-right" name="unit_price[]" value="{{ $item->price_per_item }}" step="any"></td>
                                            <td><input type="text" class="form-control amount text-right" name="amount[]" value="{{ $item->total_price }}" readonly tabindex="-1"></td>
                                            <td class="text-center align-middle"><button type="button" class="btn btn-outline-danger btn-sm removeRow" tabindex="-1"><i class="fa fa-trash"></i></button></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2">
                                                <button type="button" class="btn btn-primary btn-sm mb-2" id="addRow"><i class="fa fa-plus"></i> เพิ่มแถว</button>
                                            </th>
                                            <th colspan="5" class="text-right">Subtotal</th>
                                            <th><input type="text" id="subtotal_amount" name="subtotal" class="form-control text-bold text-right" value="{{ number_format($ProformaInvoice->subtotal, 2) }}" readonly></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>

                                {{-- ===== ค่าบริการอื่นๆ ===== --}}
                                <div class="row mt-2">
                                    <div class="col-md-7">
                                        <label class="text-bold">{{__('Other Services')}}</label>
                                        <table class="table table-bordered table-sm" id="serviceTable">
                                            <thead class="bgc-grey-l3"><tr><th>{{__('Service Description')}}</th><th width="35%">{{__('Amount')}}</th><th width="40"></th></tr></thead>
                                            <tbody>
                                                @foreach($ProformaInvoice->services as $sv)
                                                <tr>
                                                    <td><input type="text" class="form-control form-control-sm service-name" name="service_name[]" value="{{ $sv->name }}"></td>
                                                    <td><input type="number" step="any" class="form-control form-control-sm text-right service-amount" name="service_amount[]" value="{{ $sv->amount }}"></td>
                                                    <td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm removeService" tabindex="-1"><i class="fa fa-trash"></i></button></td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot><tr><td colspan="3"><button type="button" class="btn btn-outline-primary btn-sm" id="addService"><i class="fa fa-plus"></i> {{__('Add Service')}}</button></td></tr></tfoot>
                                        </table>
                                    </div>
                                    <div class="col-md-5">
                                        <table class="table table-borderless mb-0">
                                            <tr><td class="text-right text-bold">Subtotal</td><td class="text-right" id="subtotal_show" style="width:45%;">{{ number_format($ProformaInvoice->subtotal, 2) }}</td></tr>
                                            <tr><td class="text-right text-bold">Services</td><td class="text-right" id="services_show">0.00</td></tr>
                                            <tr><td class="text-right text-bold" style="font-size:1.1em;">Total</td>
                                                <td><input type="text" id="grand_total" name="grand_total" class="form-control text-bold text-primary text-right" value="{{ number_format($ProformaInvoice->total, 2) }}" readonly></td></tr>
                                        </table>
                                    </div>
                                </div>

                                {{-- ===== หมายเหตุ ===== --}}
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <label class="text-bold">{{__('Remarks')}}</label>
                                        <table class="table table-bordered table-sm" id="remarkTable">
                                            <thead class="bgc-grey-l3"><tr><th width="50" class="text-center">#</th><th>{{__('Remark')}}</th><th width="40"></th></tr></thead>
                                            <tbody>
                                                @foreach($ProformaInvoice->remarks as $rm)
                                                <tr>
                                                    <td class="text-center align-middle"><i class="fas fa-grip-vertical cursor-move text-muted remark-handle"></i></td>
                                                    <td><input type="text" class="form-control form-control-sm" name="remark_text[]" value="{{ $rm->remark }}"></td>
                                                    <td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm removeRemark" tabindex="-1"><i class="fa fa-trash"></i></button></td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot><tr><td colspan="3"><button type="button" class="btn btn-outline-primary btn-sm" id="addRemark"><i class="fa fa-plus"></i> {{__('Add Remark')}}</button></td></tr></tfoot>
                                        </table>
                                    </div>
                                </div>

                                <div class="text-center mt-3">
                                    <button type="submit" class="btn btn-warning"><i class="fa fa-save"></i> Update PI</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {

    function updateSequence() {
        $('#productTable tbody tr').each(function(index) {
            $(this).find('.item-seq').val(index + 1);
        });
    }

    $('#productTable tbody').sortable({
        handle: '.cursor-move',
        placeholder: "ui-state-highlight",
        stop: function(e, ui) {
            updateSequence();
        }
    });

    $('#customer_id').select2({
        placeholder: 'ค้นหาลูกค้า...',
        allowClear: true,
        width: '100%'
    });

    $('#customer_id').on('change', function() {
        var customer_id = $(this).val();
        if (customer_id) {
            $.ajax({
                method: "GET",
                url: url_gb + "/admin/Customer/" + customer_id,
                dataType: 'json',
            }).done(function(res) {
                if (res.status == 1) {
                    var data = res.content;
                    $('#contact_name').val(data.contact_name);
                    $('#company_name').val(data.company_name);
                    $('#tax_id').val(data.tax_id);
                    $('#address').val(data.address);
                    $('#fax_no').val(data.fax_no);
                }
            });
        }
    });

    $('#addRow').click(function() {
        var newRow = `
            <tr>
                <td class="text-center align-middle">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="fas fa-grip-vertical cursor-move text-muted mr-2" title="Drag to reorder"></i>
                        <input type="number" class="form-control text-center item-seq p-1" name="seq[]" readonly style="width: 50px;">
                    </div>
                </td>
                <td>
                    <input type="text" class="form-control part-no-input" name="part_no[]" placeholder="Type Part No" autocomplete="off">
                    <input type="hidden" name="product[]" class="product-id">
                </td>
                <td><input type="text" class="form-control drawing" name="drawing[]" placeholder="Type Drawing"></td>
                <td><input type="text" class="form-control customer_code" name="customer_code[]"></td>
                <td><input type="text" class="form-control description" name="description[]"></td>
                <td><input type="number" class="form-control qty" name="qty[]" value="1" step="any"></td>
                <td><input type="number" class="form-control unit_price text-right" name="unit_price[]" value="0.00" step="any" min="0"></td>
                <td><input type="text" class="form-control amount text-right" name="amount[]" readonly tabindex="-1"></td>
                <td class="text-center align-middle">
                    <button type="button" class="btn btn-outline-danger btn-sm removeRow" tabindex="-1"><i class="fa fa-trash"></i></button>
                </td>
            </tr>`;
        $('#productTable tbody').append(newRow);
        updateSequence();
        $('#productTable tbody tr:last').find('.part-no-input').focus();
    });

    $('body').on('click', '.removeRow', function() {
        $(this).closest('tr').remove();
        calculateGrandTotal();
        updateSequence();
    });

    $('body').on('blur', '.unit_price', function() {
        var val = parseFloat($(this).val()) || 0;
        $(this).val(val.toFixed(2));
    });

    function focusNextInput(currentRow, currentClass) {
        var focusOrder = ['part-no-input', 'drawing', 'customer_code', 'description', 'qty', 'unit_price'];
        var currentIndex = focusOrder.indexOf(currentClass);

        if (currentIndex !== -1 && currentIndex < focusOrder.length - 1) {
            currentRow.find('.' + focusOrder[currentIndex + 1]).focus().select();
        } else {
            var nextRow = currentRow.next('tr');
            if (nextRow.length > 0) {
                nextRow.find('.part-no-input').focus();
            } else {
                $('#addRow').click();
            }
        }
    }

    $('body').on('keydown', '.part-no-input, .drawing, .customer_code, .description, .qty, .unit_price', function(e) {
        if (e.which == 13 || e.which == 9) {
            e.preventDefault();
            var input = $(this);
            var row = input.closest('tr');

            if (input.hasClass('unit_price')) {
                var val = parseFloat(input.val()) || 0;
                input.val(val.toFixed(2));
            }

            if (input.hasClass('part-no-input') || input.hasClass('drawing')) {
                if (input.hasClass('part-no-input')) {
                    checkDuplicatePartNo(input);
                }
                searchProduct(input, row);
            } else {
                var className = input.attr('class').split(' ').find(c => ['customer_code', 'description', 'qty', 'unit_price'].includes(c));
                focusNextInput(row, className);
            }
        }
    });

    function checkDuplicatePartNo(input) {
        var currentVal = input.val().trim();
        var duplicateCount = 0;

        $('.part-no-input').each(function() {
            if ($(this).val().trim() === currentVal && currentVal !== "") {
                duplicateCount++;
            }
        });

        if (duplicateCount > 1) {
            Swal.fire({
                icon: 'warning',
                title: 'รายการซ้ำ',
                text: 'รหัสสินค้า ' + currentVal + ' มีอยู่ในรายการแล้ว',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        }
    }

    function searchProduct(input, row) {
        var query = input.val().trim();
        var isPartNo = input.hasClass('part-no-input');
        var currentClass = isPartNo ? 'part-no-input' : 'drawing';

        if (isPartNo && query.length < 3) {
            focusNextInput(row, currentClass); return;
        }
        if (!isPartNo && query.length < 3) {
            focusNextInput(row, currentClass); return;
        }

        var customer_id = $('#customer_id').val();
        var currency_id = $('#currency_id').val();

        if (!customer_id || !currency_id) {
            Swal.fire({ icon: 'error', title: 'ข้อมูลไม่ครบ', text: 'กรุณาเลือก "ลูกค้า" และ "สกุลเงิน" ก่อนเริ่มทำรายการ' });
            return false;
        }

        input.addClass('bg-light text-primary');

        $.ajax({
            method: "GET",
            url: url_gb + "/admin/{{ $admin_lang_slash }}Product/Search",
            dataType: 'json',
            data: {
                q: query,
                customer_id: customer_id,
                currency_id: currency_id
            }
        }).done(function(res) {
            input.removeClass('bg-light text-primary');
            var items = res.items ? res.items : res;

            if (items && items.length > 0) {
                var data = items[0];

                row.find('.part-no-input').val(data.code);
                row.find('.product-id').val(data.id);
                row.find('.drawing').val(data.drawing);
                row.find('.customer_code').val(data.cus_code);
                row.find('.description').val(data.description);

                var price = parseFloat(data.price) || 0;
                row.find('.unit_price').val(price.toFixed(2));

                $('#customer_id, #currency_id').attr('readonly', true).css('pointer-events', 'none');

                calculateRow(row);
                focusNextInput(row, currentClass);
            } else {
                Swal.fire({ icon: 'warning', title: 'ไม่พบข้อมูล', text: 'ไม่พบข้อมูล: ' + query });
                input.focus().select();
            }

        }).fail(function(res) {
            input.removeClass('bg-light text-primary');
            ajaxFail(res, "");
        });
    }

    function calculateRow(row) {
        var qty = parseFloat(row.find('.qty').val()) || 0;
        var price = parseFloat(row.find('.unit_price').val()) || 0;
        var amount = qty * price;
        row.find('.amount').val(amount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        calculateGrandTotal();
    }

    function fmt(n) {
        return (n || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }

    function calculateGrandTotal() {
        var subtotal = 0;
        $('.amount').each(function() {
            subtotal += parseFloat($(this).val().replace(/,/g, '')) || 0;
        });
        var services = 0;
        $('.service-amount').each(function() {
            services += parseFloat($(this).val().replace(/,/g, '')) || 0;
        });
        var grand = subtotal + services;
        $('#subtotal_amount').val(fmt(subtotal));
        $('#subtotal_show').text(fmt(subtotal));
        $('#services_show').text(fmt(services));
        $('#grand_total').val(fmt(grand));
    }

    $('body').on('input', '.qty, .unit_price', function() {
        calculateRow($(this).closest('tr'));
    });

    // ----- ค่าบริการอื่นๆ -----
    function serviceRow() {
        return '<tr>' +
            '<td><input type="text" class="form-control form-control-sm service-name" name="service_name[]" placeholder="เช่น ค่าขนส่ง"></td>' +
            '<td><input type="number" step="any" class="form-control form-control-sm text-right service-amount" name="service_amount[]" value="0"></td>' +
            '<td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm removeService" tabindex="-1"><i class="fa fa-trash"></i></button></td>' +
            '</tr>';
    }
    $('#addService').on('click', function() { $('#serviceTable tbody').append(serviceRow()); });
    $('body').on('click', '.removeService', function() { $(this).closest('tr').remove(); calculateGrandTotal(); });
    $('body').on('input', '.service-amount', calculateGrandTotal);

    // ----- หมายเหตุ (จำลำดับตามแถว) -----
    function remarkRow() {
        return '<tr>' +
            '<td class="text-center align-middle"><i class="fas fa-grip-vertical cursor-move text-muted remark-handle"></i></td>' +
            '<td><input type="text" class="form-control form-control-sm" name="remark_text[]"></td>' +
            '<td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm removeRemark" tabindex="-1"><i class="fa fa-trash"></i></button></td>' +
            '</tr>';
    }
    $('#addRemark').on('click', function() { $('#remarkTable tbody').append(remarkRow()); });
    $('body').on('click', '.removeRemark', function() { $(this).closest('tr').remove(); });
    if ($('#remarkTable tbody').sortable) { $('#remarkTable tbody').sortable({ handle: '.remark-handle', placeholder: 'ui-state-highlight' }); }

    if ($('#customer_id').val()) {
        $('#customer_id, #currency_id').attr('readonly', true).css('pointer-events', 'none');
    }

    calculateGrandTotal();
    updateSequence();

    $('body').on('submit', '#form-edit-pi', function(e) {
        e.preventDefault();
        var form = $(this);
        var pi_id = $('#pi_id').val();
        loadingButton(form.find('button[type=submit]'));

        $.ajax({
            method: "POST",
            url: url_gb + "/admin/ProformaInvoice/" + pi_id,
            dataType: "json",
            data: form.serialize()
        }).done(function(res) {
            resetButton(form.find('button[type=submit]'));
            if (res.status == 1) {
                Swal.fire(res.title, res.content, 'success').then(() => {
                    window.location.href = url_gb + "/admin/{{ $admin_lang_slash }}ProformaInvoice";
                });
            } else {
                Swal.fire(res.title, res.content, 'error');
            }
        }).fail(function(res) {
            ajaxFail(res, form);
        });
    });
});
</script>
@endpush
