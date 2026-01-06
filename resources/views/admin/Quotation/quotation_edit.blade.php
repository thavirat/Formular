@extends('admin.layouts.default')

@section('title', $currentMenu->title)

@push('css')
<style>
    .product-select-container {
        width: 200px !important; /* ปรับตัวเลขความกว้างตามต้องการ */
        min-width: 200px !important;
    }

    /* จัดการเรื่องข้อความที่ยาวเกินไปให้แสดงเป็น ... (Ellipsis) */
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endpush

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
                                        <option value="{{$customer->id}}" {{ $Quotation->customer_id == $customer->id ? 'selected' : '' }}>{{$customer->company_name}} - {{$customer->contact_name}}</option>
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
                                            <input type="text" name="doc_date" class="form-control init-date" id="doc_date" value="{{ date('Y-m-d' , strtotime($Quotation->doc_date)) }}" readonly>
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
                                        <input type="text" name="contact_name" id="contact_name" class="form-control" value="{{$Quotation->contact_name}}" >
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="company_name">{{__('Company Name')}}</label>
                                        <input type="text" name="company_name" id="company_name" class="form-control" value="{{$Quotation->company_name}}" required >
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="tax_id">{{__('Tax ID')}}</label>
                                        <input type="text" name="tax_id" id="tax_id" class="form-control" value="{{$Quotation->tax_id}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="address">{{__('Address')}}</label>
                                        <textarea name="address" id="address" class="form-control" cols="30" rows="5" style="height: 124px;">{{$Quotation->address}}</textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="fax_no">{{__('Fax No')}}</label>
                                        <input type="text" name="fax_no" id="fax_no" class="form-control" value="{{$Quotation->fax_no}}">
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
                                                <option value="{{$incoterm->id}}" {{ $Quotation->incoterm_id == $incoterm->id ? 'selected' : '' }}>{{$incoterm->code}}</option>
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
                                                <option value="{{$currency->id}}" {{ $Quotation->currency_id == $currency->id ? 'selected' : '' }}>{{$currency->symbol}}</option>
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
                                                <option value="{{$CreditPayment->id}}" {{ $Quotation->credit_payment_id == $CreditPayment->id ? 'selected' : '' }}>{{$CreditPayment->name}}</option>
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
                                        <th >{{__('ITM')}}</th>
                                        <th width="15%" class="product-select-container">{{__('Part No.')}}</th>
                                        <th width="10%">{{__('Drawing')}}</th>
                                        <th width="10%">{{__('Cus.Code')}}</th>
                                        <th>{{__('Descript')}}</th>
                                        <th width="10%">{{__('Qty')}}</th>
                                        <th width="10%">{{__('Unit Price')}} (<span class="show_currency">{{ $Quotation->symbol }}</span>)</th>
                                        <th width="10%">{{__('Amount')}} (<span class="show_currency">{{ $Quotation->symbol }}</span>)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($Quotation->products as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="product-select-container">
                                            <select class="form-control product" name="product[]" required>
                                                <option value="{{ $item->product_id }}" selected>{{ $item->part_no }} : {{ $item->detail_eng }}</option>
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control" name="drawing[]" value="{{ $item->drawing }}"></td>
                                        <td><input type="text" class="form-control" name="customer_code[]" value="{{ $item->cus_code }}"></td>
                                        <td><input type="text" class="form-control" name="description[]" value="{{ $item->detail_eng }}"></td>
                                        <td><input type="number" class="form-control qty" name="qty[]" value="{{ $item->qty }}"></td>
                                        <td><input type="number" class="form-control unit_price" name="unit_price[]" value="{{ $item->price_per_item }}"></td>
                                        <td><input type="number" class="form-control amount" name="amount[]" value="{{ $item->qty * $item->total_price }}" readonly></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2">
                                            <button type="button" class="btn btn-primary mb-2" id="addRow">
                                                <i class="fa fa-plus"></i> เพิ่มแถว
                                            </button>
                                        </th>
                                        <th colspan="5" class="text-right">Total Amount (<span class="show_currency">{{ $Quotation->symbol }}</span>)</th>
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
            placeholder: 'เลือกสินค้า...',
            width: '100%',
            ajax: {
                url: url_gb + "/admin/{{$lang}}/Product/Search",
                dataType: 'json',
                dropdownAutoWidth: false,
                delay: 250,
                data: function (params) {
                    return { q: params.term };
                },
                processResults: function (data) {
                    return { results: data.items };
                },
                cache: true
            }
        });

        // เมื่อเลือกสินค้าแล้ว ให้เอาค่าไปใส่ในช่องต่างๆ
        element.on('select2:select', function (e) {
            console.log(e);
            var data = e.params.data; // ข้อมูลที่ Return มาจาก Server
            var row = $(this).closest('tr');

            row.find('input[name="drawing[]"]').val(data.drawing);
            row.find('input[name="description[]"]').val(data.description);
            row.find('input[name="unit_price[]"]').val(data.price);

            calculateRow(row); // คำนวณยอดทันทีที่เลือก
        });
    }


    $('#addRow').click(function() {

        var rowCount = $('#productTable tbody tr').length + 1;
        var newRow = `
            <tr>
                <td>${rowCount}</td>
                <td class="product-select-container"><select class="form-control product" name="product[]"></select></td>
                <td><input type="text"  class="form-control" name="drawing[]"></td>
                <td><input type="text" class="form-control" name="customer_code[]"></td>
                <td><input type="text" class="form-control" name="description[]"></td>
                <td><input type="number" class="form-control qty" name="qty[]" value="1"></td>
                <td><input type="number" class="form-control unit_price" name="unit_price[]"></td>
                <td><input type="number" class="form-control amount" name="amount[]" readonly></td>
            </tr>`;

        var $newRow = $(newRow);
        $('#productTable tbody').append($newRow);

        // ผูก Select2 ให้กับแถวใหม่ที่เพิ่งสร้าง
        initSelect2($newRow.find('.product'));
    });

    function calculateRow(row) {
        var qty = parseFloat(row.find('.qty').val()) || 0;
        var price = parseFloat(row.find('.unit_price').val()) || 0;
        var amount = qty * price;
        row.find('.amount').val(amount.toFixed(2));
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        var grandTotal = 0;
        $('.amount').each(function() {
            grandTotal += parseFloat($(this).val()) || 0;
        });
        $('#grand_total').val(grandTotal.toLocaleString(undefined, {minimumFractionDigits: 2}));
    }

    $('body').on('input', '.qty, .unit_price', function() {
        calculateRow($(this).closest('tr'));
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
            method: "PUT",
            url: url_gb+"/admin/Quotation/{{$Quotation->id}}",
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


    $('.product').each(function() {
        initSelect2($(this));
    });

    calculateGrandTotal();


});
</script>
@endpush
