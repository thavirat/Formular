@extends('admin.layouts.default')

@section('title', __('Edit Quotation') . ' #' . $Quotation->doc_no)

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
</style>
@endsection

@section('body')
<div class="page-content container container-plus">
    <div class="page-header mb-2 pb-2 flex-column flex-sm-row align-items-start align-items-sm-center py-25 px-1">
        <h1 class="page-title text-primary-d2 text-140">
            {{__('Edit Quotation')}} <small class="text-dark-m3">#{{ $Quotation->doc_no }}</small>
        </h1>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card dcard">
                <div class="card-body p-3">
                    <form action="" id="form-quotation-edit" method="POST">
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="customer_id">{{__('Select Customer')}} <span class="text-danger">*</span></label>
                                    <select name="customer_id" id="customer_id" class="form-control select2" required>
                                        <option value="">{{__('Select Customer')}}</option>
                                        @foreach($Customers as $customer)
                                            <option value="{{$customer->id}}" {{ $Quotation->customer_id == $customer->id ? 'selected' : '' }}>
                                                {{$customer->company_name}} - {{$customer->contact_name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3"></div>
                            <div class="col-md-3"></div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="doc_date">{{__('Document Date')}} <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="doc_date" class="form-control init-date" id="doc_date" value="{{ date('Y-m-d', strtotime($Quotation->doc_date)) }}" readonly>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><i class="far fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="contact_name">{{__('Contact Name')}}</label>
                                    <input type="text" name="contact_name" id="contact_name" class="form-control" value="{{$Quotation->contact_name}}">
                                </div>
                                <div class="form-group">
                                    <label for="company_name">{{__('Company Name')}}</label>
                                    <input type="text" name="company_name" id="company_name" class="form-control" value="{{$Quotation->company_name}}" required>
                                </div>
                                <div class="form-group">
                                    <label for="tax_id">{{__('Tax ID')}}</label>
                                    <input type="text" name="tax_id" id="tax_id" class="form-control" value="{{$Quotation->tax_id}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="address">{{__('Address')}}</label>
                                    <textarea name="address" id="address" class="form-control" rows="5">{{$Quotation->address}}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="fax_no">{{__('Fax No')}}</label>
                                    <input type="text" name="fax_no" id="fax_no" class="form-control" value="{{$Quotation->fax_no}}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="incoterm_id">{{__('Incoterm')}} <span class="text-danger">*</span></label>
                                    <select name="incoterm_id" id="incoterm_id" class="form-control select2" required>
                                        <option value="">{{__('Select Incoterm')}}</option>
                                        @foreach($Incoterms as $incoterm)
                                            <option value="{{$incoterm->id}}" {{ $Quotation->incoterm_id == $incoterm->id ? 'selected' : '' }}>{{$incoterm->code}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="currency_id">{{__('Currency')}} <span class="text-danger">*</span></label>
                                    <select name="currency_id" id="currency_id" class="form-control select2" required>
                                        <option value="">{{__('Select Currency')}}</option>
                                        @foreach($Currencies as $currency)
                                            <option value="{{$currency->id}}" {{ $Quotation->currency_id == $currency->id ? 'selected' : '' }}>{{$currency->symbol}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="credit_payment_id">{{__('Credit Payment')}} <span class="text-danger">*</span></label>
                                    <select name="credit_payment_id" id="credit_payment_id" class="form-control select2" required>
                                        <option value="">{{__('Select Credit Payment')}}</option>
                                        @foreach($CreditPayments as $CreditPayment)
                                            <option value="{{$CreditPayment->id}}" {{ $Quotation->credit_payment_id == $CreditPayment->id ? 'selected' : '' }}>{{$CreditPayment->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <table class="table table-bordered table-striped" id="productTable">
                                    <thead class="bgc-primary-d1 text-white text-90">
                                        <tr>
                                            <th width="5%" class="text-center">{{__('ITM')}}</th>
                                            <th class="part-no-col">{{__('Part No.')}}</th>
                                            <th width="10%">{{__('Drawing')}}</th>
                                            <th width="10%">{{__('Cus.Code')}}</th>
                                            <th>{{__('Descript')}}</th>
                                            <th width="10%">{{__('Qty')}}</th>
                                            <th width="12%">{{__('Unit Price')}}</th>
                                            <th width="15%">{{__('Amount')}} (<span class="show_currency">{{ $Quotation->symbol }}</span>)</th>
                                            <th width="5%"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($Quotation->products as $item)
                                        <tr>
                                            <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                            <td>
                                                <input type="text" class="form-control part-no-input" name="part_no[]" value="{{ $item->part_no }}" placeholder="Type Part No & Enter" autocomplete="off">
                                                <input type="hidden" name="product[]" class="product-id" value="{{ $item->product_id }}">
                                            </td>
                                            <td><input type="text" class="form-control drawing" name="drawing[]" value="{{ $item->drawing }}"></td>
                                            <td><input type="text" class="form-control customer_code" name="customer_code[]" value="{{ $item->cus_code }}"></td>
                                            <td><input type="text" class="form-control description" name="description[]" value="{{ $item->detail_eng }}"></td>
                                            <td><input type="number" class="form-control qty" name="qty[]" value="{{ $item->qty }}" step="any" min="0"></td>
                                            <td><input type="number" class="form-control unit_price" name="unit_price[]" value="{{ $item->price_per_item }}" step="any" min="0"></td>
                                            <td><input type="text" class="form-control amount text-right" name="amount[]" value="{{ number_format($item->total_price, 2) }}" readonly tabindex="-1"></td>
                                            <td class="text-center align-middle">
                                                <button type="button" class="btn btn-outline-danger btn-sm removeRow" tabindex="-1"><i class="fa fa-trash"></i></button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2">
                                                <button type="button" class="btn btn-outline-primary btn-sm mb-2" id="addRow">
                                                    <i class="fa fa-plus"></i> เพิ่มแถว (Add Row)
                                                </button>
                                            </th>
                                            <th colspan="5" class="text-right align-middle text-110">Total Amount (<span class="show_currency">{{ $Quotation->symbol }}</span>) :</th>
                                            <th>
                                                <input type="text" id="grand_total" name="grand_total" class="form-control text-right text-bold text-primary text-120" readonly value="{{ number_format($Quotation->total, 2) }}">
                                            </th>
                                            <th></th>
                                        </tr>
                                        <tr>
                                            <th colspan="9" class="text-center pt-4">
                                                <button type="submit" class="btn btn-success btn-lg px-5">
                                                    <i class="fa fa-save"></i> {{__('Update Quotation')}}
                                                </button>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
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
<script type="text/javascript">
$(document).ready(function() {

    // --- 1. จัดการข้อมูลลูกค้า ---
    $('#customer_id').on('change', function(){
        var customer_id = $(this).val();
        if(customer_id){
            $.ajax({
                method: "GET",
                url: url_gb+"/admin/Customer/"+customer_id,
                dataType: 'json',
            }).done(function( res ) {
                if(res.status == 1){
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

    $('#currency_id').on('change', function(){
        $('.show_currency').text($(this).find('option:selected').text());
    });

    // --- 2. ฟังก์ชันหลักสำหรับตารางสินค้า ---

    $('#addRow').click(function() {
        var rowCount = $('#productTable tbody tr').length + 1;
        var newRow = `
            <tr>
                <td class="text-center align-middle">${rowCount}</td>
                <td>
                    <input type="text" class="form-control part-no-input" name="part_no[]" placeholder="Type Part No & Enter" autocomplete="off">
                    <input type="hidden" name="product[]" class="product-id">
                </td>
                <td><input type="text" class="form-control drawing" name="drawing[]"></td>
                <td><input type="text" class="form-control customer_code" name="customer_code[]"></td>
                <td><input type="text" class="form-control description" name="description[]"></td>
                <td><input type="number" class="form-control qty" name="qty[]" value="1" step="any" min="0"></td>
                <td><input type="number" class="form-control unit_price" name="unit_price[]" value="0" step="any" min="0"></td>
                <td><input type="text" class="form-control amount text-right" name="amount[]" readonly tabindex="-1"></td>
                <td class="text-center align-middle">
                    <button type="button" class="btn btn-outline-danger btn-sm removeRow" tabindex="-1"><i class="fa fa-trash"></i></button>
                </td>
            </tr>`;
        $('#productTable tbody').append(newRow);

        $('#productTable tbody tr:last').find('.part-no-input').focus();
    });

    $('body').on('click', '.removeRow', function(){
        $(this).closest('tr').remove();
        $('#productTable tbody tr').each(function(index){
            $(this).find('td:first').text(index + 1);
        });
        calculateGrandTotal();
    });

    // --- 3. Logic Focus & Navigation ---
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

    $('body').on('keypress', '.part-no-input, .drawing, .customer_code, .description, .qty, .unit_price', function(e) {
        if (e.which == 13) {
            e.preventDefault();
            var input = $(this);
            var row = input.closest('tr');

            if (input.hasClass('part-no-input')) {
                checkDuplicatePartNo(input);
                searchProduct(input, row);
            } else {
                var className = input.attr('class').split(' ').find(c => ['drawing', 'customer_code', 'description', 'qty', 'unit_price'].includes(c));
                focusNextInput(row, className);
            }
        }
    });

    // --- 4. Check Duplicate ---
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

    // --- 5. ค้นหาสินค้า (AJAX) ---
    function searchProduct(input, row) {
        var partNo = input.val().trim();

        if (partNo.length < 8) {
            Swal.fire({ icon: 'warning', title: 'แจ้งเตือน', text: 'กรุณาระบุรหัสสินค้าอย่างน้อย 8 หลัก' });
            return false;
        }

        var customer_id = $('#customer_id').val();
        var currency_id = $('#currency_id').val();

        if (!customer_id || !currency_id) {
            Swal.fire({ icon: 'error', title: 'ข้อมูลไม่ครบ', text: 'กรุณาเลือก "ลูกค้า" และ "สกุลเงิน" ก่อนเริ่มทำรายการ' });
            return false;
        }

        input.addClass('input-loading');

        $.ajax({
            method: "GET",
            url: url_gb + "/admin/{{$lang}}/Product/Search",
            dataType: 'json',
            data: {
                q: partNo,
                customer_id: customer_id,
                currency_id: currency_id
            }
        }).done(function(res) {
            input.removeClass('input-loading');
            var items = res.items ? res.items : res;

            if (items && items.length > 0) {
                var data = items[0];

                row.find('.product-id').val(data.id);
                row.find('.drawing').val(data.drawing);
                row.find('.customer_code').val(data.cus_code);
                row.find('.description').val(data.description);
                row.find('.unit_price').val(data.price);

                $('#customer_id, #currency_id').attr('readonly', true).css('pointer-events', 'none');

                calculateRow(row);
                focusNextInput(row, 'part-no-input');

            } else {
                Swal.fire({ icon: 'warning', title: 'ไม่พบข้อมูล', text: 'ไม่พบรหัสสินค้า: ' + partNo });
                input.val('').focus();
            }

        }).fail(function(res) {
            input.removeClass('input-loading');
            ajaxFail(res, "");
        });
    }

    // --- 6. คำนวณเงิน ---
    function calculateRow(row) {
        var qty = parseFloat(row.find('.qty').val()) || 0;
        var price = parseFloat(row.find('.unit_price').val()) || 0;

        var amount = qty * price;
        row.find('.amount').val(amount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        var grandTotal = 0;
        $('.amount').each(function() {
            var val = $(this).val().replace(/,/g, '');
            grandTotal += parseFloat(val) || 0;
        });

        $('#grand_total').val(grandTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
    }

    $('body').on('input', '.qty, .unit_price', function() {
        calculateRow($(this).closest('tr'));
    });


    // --- 7. Submit (PUT) ---
    $('body').on('submit', '#form-quotation-edit', function(e){
        e.preventDefault();
        var form = $(this);
        loadingButton(form.find('button[type=submit]'));

        $.ajax({
            method: "PUT",
            url: url_gb+"/admin/Quotation/{{$Quotation->id}}",
            dataType : "json",
            data: form.serialize()
        }).done(function( res ) {
            resetButton(form.find('button[type=submit]'));
            if(res.status == 1){
                Swal.fire(res.title, res.content,'success').then(() => {
                    window.location.href = url_gb+"/admin/{{$lang}}/Quotation";
                });
            }else{
                Swal.fire(res.title, res.content,'error');
            }
        }).fail(function(res){
            ajaxFail(res , form);
        });
    });

    // เริ่มต้น: ถ้ามีข้อมูลลูกค้าอยู่แล้วให้ล็อคไว้เลย (เพื่อความชัวร์ในการแก้ไข)
    if($('#customer_id').val()){
        $('#customer_id, #currency_id').attr('readonly', true).css('pointer-events', 'none');
    }

    // คำนวณยอดเงินรวมอีกครั้งตอนโหลดหน้า
    calculateGrandTotal();
});
</script>
@endpush
