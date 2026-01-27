@extends('admin.layouts.default')

@section('title', $currentMenu->title)

@section('css')
<style>
    .product-select-container {
        width: 200px !important; /* ปรับตัวเลขความกว้างตามต้องการ */
        min-width: 200px !important;
        max-width: 200px !important;
    }




    .select2-container--default .select2-selection--single .select2-selection__rendered {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endsection

@section('body')
<div class="page-content container container-plus">
    <div class="page-header mb-2 pb-2 flex-column flex-sm-row align-items-start align-items-sm-center py-25 px-1">
        <h1 class="page-title text-primary-d2 text-140">{{__('Create Quotaion')}} </h1>
        <div class="page-tools mt-3 mt-sm-0 mb-sm-n1">

        </div>
    </div>


    <div class="row mt-3">
        <div class="col-12">
            <div class="card dcard">
                <div class="card-body p-3">
                    <form action="" id="form-quotation-create" method="POST">
                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label for="customer_id">{{__('Select Customer')}} <span class="text-danger">*</span></label>
                                <select name="customer_id" id="customer_id" class="form-control" required>
                                    <option value="">{{__('Select Customer')}}</option>
                                    @foreach($Customers as $customer)
                                        <option value="{{$customer->id}}">{{$customer->company_name}} - {{$customer->contact_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-3"></div>
                        <div class="col-3">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="doc_date">{{__('Document Date')}} <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text" name="doc_date" class="form-control init-date" id="doc_date" value="{{ date('Y-m-d') }}" readonly>
                                            <div class="input-group-addon input-group-append remove_date_time">
                                                <div class="input-group-text">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                            <div class="input-group-addon input-group-append">
                                                <div class="input-group-text">
                                                    <i class="far fa-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-3"></div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="contact_name">{{__('Contact Name')}}</label>
                                        <input type="text" name="contact_name" id="contact_name" class="form-control"  >
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="company_name">{{__('Company Name')}}</label>
                                        <input type="text" name="company_name" id="company_name" class="form-control" required >
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="tax_id">{{__('Tax ID')}}</label>
                                        <input type="text" name="tax_id" id="tax_id" class="form-control" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="address">{{__('Address')}}</label>
                                        <textarea name="address" id="address" class="form-control" cols="30" rows="5" style="height: 124px;"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="fax_no">{{__('Fax No')}}</label>
                                        <input type="text" name="fax_no" id="fax_no" class="form-control" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="incoterm_id">{{__('Incoterm')}} <span class="text-danger">*</span></label>
                                        <select name="incoterm_id" id="incoterm_id" class="form-control select2" required>
                                            <option value="">{{__('Select Incoterm')}}</option>
                                            @foreach($Incoterms as $incoterm)
                                                <option value="{{$incoterm->id}}">{{$incoterm->code}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="currency_id">{{__('Currency')}} <span class="text-danger">*</span></label>
                                        <select name="currency_id" id="currency_id" class="form-control select2" required>
                                            <option value="">{{__('Select Currency')}}</option>
                                            @foreach($Currencies as $currency)
                                                <option value="{{$currency->id}}">{{$currency->symbol}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="credit_payment_id">{{__('Credit Payment')}} <span class="text-danger">*</span></label>
                                        <select name="credit_payment_id" id="credit_payment_id" class="form-control select2" required>
                                            <option value="">{{__('Select Credit Payment')}}</option>
                                            @foreach($CreditPayments as $CreditPayment)
                                                <option value="{{$CreditPayment->id}}">{{$CreditPayment->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-bordered" id="productTable">
                                <thead>
                                    <tr>
                                        <th width="5%" class="text-center">{{__('ITM')}}</th>
                                        <th width="15%" class="product-select-container">{{__('Part No.')}}</th>
                                        <th width="8%">{{__('Drawing')}}</th>
                                        <th width="8%">{{__('Cus.Code')}}</th>
                                        <th>{{__('Descript')}}</th>
                                        <th width="8%">{{__('Qty')}}</th>
                                        <th width="10%">{{__('Unit Price')}}</th>
                                        <th width="7%">{{__('Disc %')}}</th>
                                        <th width="10%">{{__('Disc Amt')}}</th>
                                        <th width="10%">{{__('Amount')}} (<span class="show_currency"></span>)</th>
                                        <th width="5%"></th> </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2">
                                            <button type="button" class="btn btn-primary mb-2" id="addRow">
                                                <i class="fa fa-plus"></i> เพิ่มแถว
                                            </button>
                                        </th>
                                        <th colspan="5" class="text-right">Total Amount (<span class="show_currency"></span>)</th>
                                        <th><input type="text" id="grand_total" name="grand_total" class="form-control" readonly></th>
                                    </tr>
                                    <tr>
                                        <th colspan="8" class="text-center">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fa fa-save"></i> {{__('Save Quotation')}}
                                            </button>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    </form>

                </div><!-- /.card-body -->
            </div><!-- /.card -->
        </div><!-- /.col -->
    </div>

</div>


@endsection

@push('scripts')
<script type="text/javascript">
$( document ).ready(function() {
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
                }else{
                    swal("{{__('Warning')}}", res.msg , "warning");
                }

            }).fail(function(res){
                ajaxFail(res , "");
            });
        }
    });



    function initSelect2(element) {
        element.select2({
            placeholder: 'กรุณาเลือกคู่ค้าและสกุลเงินก่อน...',
            width: 'resolve',
            dropdownAutoWidth: false,
            containerCssClass: 'fixed-select2',
            ajax: {
                url: url_gb + "/admin/{{$lang}}/Product/Search",
                dataType: 'json',
                delay: 250,
                transport: function (params, success, failure) {
                    // ตรวจสอบเงื่อนไขก่อนส่ง Request
                    var customer_id = $('#customer_id').val();
                    var currency_id = $('#currency_id').val();

                    if (!customer_id || !currency_id) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'กรุณากรอกข้อมูลให้ครบ',
                            text: 'คุณต้องเลือก "ลูกค้า" และ "สกุลเงิน" ก่อนค้นหาสินค้า',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        return false; // ระงับการส่ง AJAX
                    }

                    // ถ้าครบถุกลักษณะ ให้ส่ง AJAX ปกติ
                    var $request = $.ajax(params);
                    $request.then(success);
                    $request.fail(failure);
                    return $request;
                },
                data: function (params) {
                    return {
                        q: params.term,
                        customer_id: $('#customer_id').val(),
                        currency_id: $('#currency_id').val()
                    };
                },
                processResults: function (data) {
                    return { results: data.items };
                },
                cache: false
            }
        });

        element.on('select2:select', function (e) {
            var data = e.params.data;
            var row = $(this).closest('tr');

            // ล็อคการเปลี่ยนลูกค้าและสกุลเงินเมื่อมีการเลือกสินค้าแล้วเพื่อป้องกันยอดคำนวณผิดพลาด
            $('#customer_id, #currency_id').attr('readonly', true).css('pointer-events', 'none');

            row.find('input[name="drawing[]"]').val(data.drawing);
            row.find('input[name="description[]"]').val(data.description);
            row.find('input[name="unit_price[]"]').val(data.price);
            row.find('input[name="customer_code[]"]').val(data.cus_code);

            calculateRow(row);
        });
    }


    $('#addRow').click(function() {
        var rowCount = $('#productTable tbody tr').length + 1;
        var newRow = `
            <tr>
                <td class="text-center">${rowCount}</td>
                <td class="product-select-container"><select class="form-control product" name="product[]" required></select></td>
                <td><input type="text" class="form-control" name="drawing[]"></td>
                <td><input type="text" class="form-control" name="customer_code[]"></td>
                <td><input type="text" class="form-control" name="description[]"></td>
                <td><input type="number" class="form-control qty" name="qty[]" value="1" min="1" step="any"></td>
                <td><input type="number" class="form-control unit_price" name="unit_price[]" value="0" step="any"></td>
                <td><input type="number" class="form-control disc_percent" name="disc_percent[]" value="0" min="0" max="100" step="any"></td>
                <td><input type="number" class="form-control disc_amount" name="disc_amount[]" value="0" step="any"></td>
                <td><input type="number" class="form-control amount" name="amount[]" readonly></td>
                <td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm removeRow"><i class="fa fa-trash"></i></button></td>
            </tr>`;

        var $newRow = $(newRow);
        $('#productTable tbody').append($newRow);
        initSelect2($newRow.find('.product'));
    });

    function calculateRow(row) {
        var qty = parseFloat(row.find('.qty').val()) || 0;
        var price = parseFloat(row.find('.unit_price').val()) || 0;
        var discPercent = parseFloat(row.find('.disc_percent').val()) || 0;
        var discAmount = parseFloat(row.find('.disc_amount').val()) || 0;

        // คำนวณราคาก่อนหักส่วนลด
        var totalBeforeDiscount = qty * price;

        // ถ้ามีการใส่ % ให้คำนวณเงินส่วนลดจาก % (Priority 1)
        if (discPercent > 0) {
            discAmount = (totalBeforeDiscount * discPercent) / 100;
            row.find('.disc_amount').val(discAmount.toFixed(2));
        }

        // คำนวณยอดสุทธิของแถว
        var amount = totalBeforeDiscount - discAmount;

        row.find('.amount').val(amount.toFixed(2));
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        var grandTotal = 0;
        $('.amount').each(function() {
            grandTotal += parseFloat($(this).val()) || 0;
        });
        // แสดงผลรวมตัวเลขแบบมี comma
        $('#grand_total').val(grandTotal.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
    }

    $('body').on('input', '.qty, .unit_price, .disc_percent, .disc_amount', function() {
        var row = $(this).closest('tr');

        // Logic พิเศษ: ถ้าแก้ช่อง "จำนวนเงินส่วนลด" ให้ล้างช่อง "%" (ป้องกันการสับสน)
        if($(this).hasClass('disc_amount')){
            row.find('.disc_percent').val(0);
        }

        calculateRow(row);
    });

    $('#addRow').click();

    $('body').on('change', '#currency_id', function(){
        var currencyText = $(this).find('option:selected').text();
        $('.show_currency').text(currencyText);
    });


    $('body').on('submit', '#form-quotation-create', function(e){
        e.preventDefault();
        var form = $(this);
        loadingButton(form.find('button[type=submit]'));
        $.ajax({
            method: "POST",
            url: url_gb+"/admin/Quotation",
            dataType : "json",
            data: form.serialize()
        }).done(function( res ) {
            resetButton(form.find('button[type=submit]'));
            if(res.status == 1){
                Swal.fire(res.title, res.content,'success');
                window.location.href = url_gb+"/admin/{{$lang}}/Quotation";
            }else{
                Swal.fire(res.title, res.content,'error');
            }
        }).fail(function(res){
            ajaxFail(res , form);
        });
    });

    $('body').on('click', '.removeRow', function(){
        $(this).closest('tr').remove();
        calculateGrandTotal();
    });


});
</script>
@endpush
