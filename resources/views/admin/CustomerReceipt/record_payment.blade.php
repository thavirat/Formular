@extends('admin.layouts.default')

@section('title', ($currentMenu->title ?? 'Customer Receipt') . ' — ชำระเงิน')

@php
    $cust = $pi->customer;
@endphp

@section('body')
<div class="page-content container container-plus">
    <div class="page-header mb-2 pb-2 flex-column flex-sm-row align-items-start align-items-sm-center py-25 px-1">
        <h1 class="page-title text-primary-d2 text-140">ชำระเงิน</h1>
        <div class="page-tools mt-3 mt-sm-0">
            <a href="{{ url('admin/' . $lang . '/CustomerReceipt') }}" class="btn btn-light-secondary btn-h-secondary">
                <i class="fa fa-arrow-left mr-1"></i> กลับรายการ
            </a>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <div class="card dcard h-100">
                <div class="card-header border-0 bgc-primary-d2 text-white">
                    <h5 class="card-title mb-0 text-110 test-white">ข้อมูลลูกค้า</h5>
                </div>
                <div class="card-body text-90">
                    <dl class="row mb-0">
                        <dt class="col-sm-4 text-grey-d1">ชื่อบริษัท</dt>
                        <dd class="col-sm-8 mb-2">{{ $cust->company_name ?? $pi->company_name ?? '—' }}</dd>
                        <dt class="col-sm-4 text-grey-d1">เลขประจำตัวผู้เสียภาษี</dt>
                        <dd class="col-sm-8 mb-2">{{ $cust->tax_id ?? $pi->tax_id ?? '—' }}</dd>
                        <dt class="col-sm-4 text-grey-d1">ที่อยู่</dt>
                        <dd class="col-sm-8 mb-2">{{ $cust->address ?? $pi->address ?? '—' }}</dd>
                        <dt class="col-sm-4 text-grey-d1">ผู้ติดต่อ</dt>
                        <dd class="col-sm-8 mb-2">{{ $cust->contact_name ?? $pi->contact_name ?? '—' }}</dd>
                        <dt class="col-sm-4 text-grey-d1">โทรศัพท์</dt>
                        <dd class="col-sm-8 mb-2">{{ $cust->phone ?? $pi->phone ?? '—' }}</dd>
                        <dt class="col-sm-4 text-grey-d1">มือถือ</dt>
                        <dd class="col-sm-8 mb-0">{{ $cust->mobile ?? $pi->mobile ?? '—' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card dcard h-100">
                <div class="card-header border-0 bgc-blue-d2 text-white">
                    <h5 class="card-title mb-0 text-110">รายละเอียดใบ PI</h5>
                </div>
                <div class="card-body text-90">
                    <dl class="row mb-0">
                        <dt class="col-sm-4 text-grey-d1">เลขที่ PI</dt>
                        <dd class="col-sm-8 mb-2 font-bolder text-primary-d2">{{ $pi->doc_no ?? '—' }}</dd>
                        <dt class="col-sm-4 text-grey-d1">วันที่เอกสาร</dt>
                        <dd class="col-sm-8 mb-2">{{ $pi->doc_date ?? '—' }}</dd>
                        <dt class="col-sm-4 text-grey-d1">ยอดรวม PI</dt>
                        <dd class="col-sm-8 mb-2 text-110 text-success-d2 font-bolder">{{ number_format((float) ($pi->total ?? 0), 2) }}</dd>
                        <dt class="col-sm-4 text-grey-d1">สกุลเงิน</dt>
                        <dd class="col-sm-8 mb-2">{{ $pi->currency->name ?? '—' }}</dd>
                        <dt class="col-sm-4 text-grey-d1">ยอดชำระแล้ว</dt>
                        <dd class="col-sm-8 mb-2 text-110 text-blue-d2 font-bolder">
                            <span id="piPaidAmountDisplay">{{ number_format($piPaidTotal ?? 0, 2) }}</span>
                            <span class="text-85 text-grey-d2 font-normal">(ผลรวม amount รายการชำระ)</span>
                        </dd>
                        <dt class="col-sm-4 text-grey-d1">ชำระแล้ว (บาท)</dt>
                        <dd class="col-sm-8 mb-2">
                            <span id="piPaidBathDisplay">{{ number_format($piPaidTotalBath ?? 0, 2) }}</span>
                        </dd>
                        <dt class="col-sm-4 text-grey-d1">ยอดคงเหลือ</dt>
                        <dd class="col-sm-8 mb-0 text-105 text-orange-d2 font-bolder">
                            <span id="piBalanceDisplay">{{ number_format(max(0, (float) ($pi->total ?? 0) - (float) ($piPaidTotal ?? 0)), 2) }}</span>
                            <span class="text-85 text-grey-d2 font-normal">(ยอด PI − ยอดชำระแล้ว)</span>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="card dcard">
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap py-3">
            <h5 class="mb-0 text-110 text-primary-d2">รายการชำระเงิน (Customer Payment)</h5>
            @if(!empty($canAddCustomerPayment))
                <button type="button" class="btn btn-light-green btn-h-green btn-a-green border-0 radius-3 py-2 text-600 text-90" id="btnAddRecordCustomerPayment">
                    <span class="d-none d-sm-inline mr-1">เพิ่มข้อมูล</span>
                    <i class="fa fa-plus text-110 w-2 h-2"></i>
                </button>
            @endif
        </div>
        <div class="card-body p-0">
            <table id="tableRecordCustomerPayment" class="table table-border-x brc-secondary-l4 border-0 mb-0 w-100">
                <thead class="text-dark-tp3 bgc-grey-l4 text-90 border-b-1 brc-transparent">
                    <tr>
                        <th class="text-center" width="5%">ลำดับ</th>
                        <th>PI No</th>
                        <th>Bank</th>
                        <th>Payment Method</th>
                        <th>Currency</th>
                        <th>Ref No</th>
                        <th>Payment Date</th>
                        <th>Remark</th>
                        <th>Amount</th>
                        <th>Amount Bath</th>
                        <th>Exchange Rate</th>
                        <th>Photo</th>
                        <th class="text-center">#</th>
                    </tr>
                </thead>
                <tbody class="mt-1"></tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modals จาก Customer Payment — ผูก pi_id กับใบ PI ปัจจุบัน --}}
<div class="modal fade modal-lg" id="ModalAddRecordPayment" role="dialog" aria-labelledby="ModalAddRecordPaymentLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="FormAddRecordPayment" data-parsley-validate="true">
                <input type="hidden" name="pi_id" value="{{ $pi->id }}">
                <div class="modal-header">
                    <h5 class="modal-title text-primary-d3" id="ModalAddRecordPaymentLabel">เพิ่มข้อมูลการชำระเงิน</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="add_bank_account_id_rp">Bank</label>
                                <select name="bank_account_id" id="add_bank_account_id_rp" class="form-control autofocus">
                                    <option value="">เลือกกรุณาเลือก</option>
                                    @foreach($BankAccounts as $BankAccount)
                                    <option value="{{ $BankAccount->id }}">{{ $BankAccount->account_no }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="add_payment_method_id_rp">Payment Method</label>
                                <select name="payment_method_id" id="add_payment_method_id_rp" class="form-control">
                                    <option value="">เลือกกรุณาเลือก</option>
                                    @foreach($PaymentMethods as $PaymentMethod)
                                    <option value="{{ $PaymentMethod->id }}">{{ $PaymentMethod->name_en }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="add_currency_id_rp">Currency</label>
                                <select name="currency_id" id="add_currency_id_rp" class="form-control">
                                    <option value="">เลือกกรุณาเลือก</option>
                                    @foreach($Currencies as $Currency)
                                    <option value="{{ $Currency->id }}">{{ $Currency->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="add_reference_no_rp">Ref No</label>
                                <input type="text" name="reference_no" id="add_reference_no_rp" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="add_payment_date_rp">Payment Date</label>
                                <div class="input-group">
                                    <input type="text" name="payment_date" class="form-control init-date" id="add_payment_date_rp" readonly>
                                    <div class="input-group-addon input-group-append remove_date_time">
                                        <div class="input-group-text"><i class="fa fa-times" aria-hidden="true"></i></div>
                                    </div>
                                    <div class="input-group-addon input-group-append">
                                        <div class="input-group-text"><i class="far fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @include('admin.CustomerPayment.partials.payment_time_fields', ['hourId' => 'add_payment_time_hour_rp', 'minuteId' => 'add_payment_time_minute_rp'])
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="add_payment_note_rp">Remark</label>
                                <input type="text" name="payment_note" id="add_payment_note_rp" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="add_amount_rp">Amount</label>
                                <input type="text" name="amount" id="add_amount_rp" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="add_amount_bath_rp">Amount Bath</label>
                                <input type="text" name="amount_bath" id="add_amount_bath_rp" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="add_exchange_rate_rp">Exchange Rate</label>
                                <input type="text" name="exchange_rate" id="add_exchange_rate_rp" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="add_photo_rp">Photo</label>
                                <div id="orak_add_photo_rp">
                                    <div id="add_photo_rp" orakuploader="on"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-4" data-dismiss="modal"><i class="fa fa-window-close"></i> ปิด</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade modal-lg" id="ModalEditRecordPayment" role="dialog" aria-labelledby="ModalEditRecordPaymentLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="FormEditRecordPayment" data-parsley-validate="true">
                <input type="hidden" id="CustomerPayment_edit_id_rp">
                <input type="hidden" name="pi_id" id="edit_pi_id_rp" value="{{ $pi->id }}">
                <div class="modal-header">
                    <h5 class="modal-title text-primary-d3" id="ModalEditRecordPaymentLabel">แก้ไขข้อมูลการชำระเงิน</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="edit_bank_account_id_rp">Bank</label>
                                <select name="bank_account_id" id="edit_bank_account_id_rp" class="form-control">
                                    <option value="">เลือกกรุณาเลือก</option>
                                    @foreach($BankAccounts as $BankAccount)
                                    <option value="{{ $BankAccount->id }}">{{ $BankAccount->account_no }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="edit_payment_method_id_rp">Payment Method</label>
                                <select name="payment_method_id" id="edit_payment_method_id_rp" class="form-control">
                                    <option value="">เลือกกรุณาเลือก</option>
                                    @foreach($PaymentMethods as $PaymentMethod)
                                    <option value="{{ $PaymentMethod->id }}">{{ $PaymentMethod->name_en }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="edit_currency_id_rp">Currency</label>
                                <select name="currency_id" id="edit_currency_id_rp" class="form-control">
                                    <option value="">เลือกกรุณาเลือก</option>
                                    @foreach($Currencies as $Currency)
                                    <option value="{{ $Currency->id }}">{{ $Currency->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="edit_reference_no_rp">Ref No</label>
                                <input type="text" name="reference_no" id="edit_reference_no_rp" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="edit_payment_date_rp">Payment Date</label>
                                <div class="input-group">
                                    <input type="text" name="payment_date" class="form-control init-date" id="edit_payment_date_rp" readonly>
                                    <div class="input-group-addon input-group-append remove_date_time">
                                        <div class="input-group-text"><i class="fa fa-times" aria-hidden="true"></i></div>
                                    </div>
                                    <div class="input-group-addon input-group-append">
                                        <div class="input-group-text"><i class="far fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @include('admin.CustomerPayment.partials.payment_time_fields', ['hourId' => 'edit_payment_time_hour_rp', 'minuteId' => 'edit_payment_time_minute_rp'])
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="edit_payment_note_rp">Remark</label>
                                <input type="text" name="payment_note" id="edit_payment_note_rp" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="edit_amount_rp">Amount</label>
                                <input type="text" name="amount" id="edit_amount_rp" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="edit_amount_bath_rp">Amount Bath</label>
                                <input type="text" name="amount_bath" id="edit_amount_bath_rp" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="edit_exchange_rate_rp">Exchange Rate</label>
                                <input type="text" name="exchange_rate" id="edit_exchange_rate_rp" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="edit_photo_rp">Photo</label>
                                <div id="orak_edit_photo_rp">
                                    <div id="edit_photo_rp" orakuploader="on"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-4" data-dismiss="modal"><i class="fa fa-window-close"></i> ปิด</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    var piIdForPayment = {{ (int) $pi->id }};
    var piDocTotal = {{ (float) ($pi->total ?? 0) }};

    function refreshPaymentTotals() {
        $.getJSON(url_gb + "/admin/CustomerReceipt/PaymentTotals/" + piIdForPayment)
            .done(function (data) {
                if (!data || !data.ok) return;
                var paid = parseFloat(data.paid_amount) || 0;
                var bath = parseFloat(data.paid_bath) || 0;
                $('#piPaidAmountDisplay').text(paid.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                $('#piPaidBathDisplay').text(bath.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                var bal = Math.max(0, piDocTotal - paid);
                $('#piBalanceDisplay').text(bal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
            });
    }

    var tableRecordCustomerPayment = $('#tableRecordCustomerPayment').dataTable({
        "ajax": {
            "url": url_gb + "/admin/CustomerPayment/Lists",
            "type": "POST",
            "data": function (d) {
                d.pi_id = piIdForPayment;
            }
        },
        "drawCallback": function() {
            $('[data-toggle="tooltip"]').tooltip();
        },
        "responsive": false,
        "columns": [
            {"data": "DT_RowIndex", 'searchable': false, 'orderable': false, "class": "text-center"},
            {"data": "pi_doc_no", "name": 'pi_doc_no'},
            {"data": "bank_display", "name": 'bank_display'},
            {"data": "payment_method_label", "name": 'payment_method_label'},
            {"data": "currency_label", "name": 'currency_label'},
            {"data": "reference_no", "name": 'reference_no'},
            {"data": "payment_date", "name": 'payment_date'},
            {"data": "payment_note", "name": 'payment_note'},
            {"data": "amount", "name": 'amount'},
            {"data": "amount_bath", "name": 'amount_bath'},
            {"data": "exchange_rate", "name": 'exchange_rate'},
            {"data": "photo", "name": 'photo', "searchable": false, "orderable": false, "className": "align-middle"},
            {"data": "action", "name": "action", "searchable": false, "sortable": false, "class": "text-center"}
        ]
    });

    function initOrakuploaderAddRecordPayment() {
        $('#add_photo_rp').orakuploader({
            orakuploader_field_name: 'photo',
            orakuploader_finished: function () {}
        });
    }

    if ($('#btnAddRecordCustomerPayment').length) {
        $('#btnAddRecordCustomerPayment').on('click', function() {
            $('#FormAddRecordPayment')[0].reset();
            $('#FormAddRecordPayment input[name=pi_id]').val(piIdForPayment);
            $('#add_payment_time_hour_rp').val(0);
            $('#add_payment_time_minute_rp').val(0);
            $('#orak_add_photo_rp').html('<div id="add_photo_rp" orakuploader="on"></div>');
            initOrakuploaderAddRecordPayment();
            @if($pi->currency_id)
            $('#add_currency_id_rp').val({{ (int) $pi->currency_id }}).trigger('change');
            @endif
            $('#ModalAddRecordPayment').modal('show');
        });
    }

    $('body').on('submit', '#FormAddRecordPayment', function(e) {
        e.preventDefault();
        var form = $(this);
        loadingButton(form.find('button[type=submit]'));
        $.ajax({
            method: "POST",
            url: url_gb + "/admin/CustomerPayment",
            dataType: "json",
            data: form.serialize()
        }).done(function(res) {
            resetButton(form.find('button[type=submit]'));
            if (res.status == 1) {
                Swal.fire(res.title, res.content, 'success');
                resetFormCustom(form);
                tableRecordCustomerPayment.api().ajax.reload(null, false);
                refreshPaymentTotals();
                $('#ModalAddRecordPayment').modal('hide');
            } else {
                Swal.fire(res.title, res.content, 'error');
            }
        }).fail(function(res) {
            ajaxFail(res, form);
        });
    });

    $('body').on('submit', '#FormEditRecordPayment', function(e) {
        e.preventDefault();
        var id = $("#CustomerPayment_edit_id_rp").val();
        var form = $(this);
        loadingButton(form.find('button[type=submit]'));
        $.ajax({
            method: "PUT",
            url: url_gb + "/admin/CustomerPayment/" + id,
            dataType: 'json',
            data: form.serialize()
        }).done(function(res) {
            resetButton(form.find('button[type=submit]'));
            if (res.status == 1) {
                Swal.fire(res.title, res.content, 'success');
                resetFormCustom(form);
                tableRecordCustomerPayment.api().ajax.reload(null, false);
                refreshPaymentTotals();
                $('#ModalEditRecordPayment').modal('hide');
            } else {
                Swal.fire(res.title, res.content, 'error');
            }
        }).fail(function(res) {
            ajaxFail(res, form);
        });
    });

    $('#tableRecordCustomerPayment').on('click', '.btn-delete', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'คุณต้องการลบข้อมูลหรือไม่ ?',
            text: "ข้อมูลที่ถูกลบไปแล้วจะไม่สามารถนำกลับมาได้",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'ยกเลิก',
            confirmButtonText: 'ลบ'
        }).then((say) => {
            if (say.isConfirmed) {
                $.ajax({
                    method: "DELETE",
                    url: url_gb + "/admin/CustomerPayment/" + id,
                    dataType: 'json',
                }).done(function(res) {
                    if (res.status == 1) {
                        Swal.fire(res.title, res.content, 'success');
                        tableRecordCustomerPayment.api().ajax.reload(null, false);
                        refreshPaymentTotals();
                    } else {
                        Swal.fire(res.title, res.content, 'warning');
                    }
                }).fail(function(res) {
                    ajaxFail(res, "");
                });
            }
        });
    });

    $('#tableRecordCustomerPayment').on('click', '.btn-edit', function() {
        var id = $(this).data('id');
        $("#CustomerPayment_edit_id_rp").val(id);
        var btn = $(this);
        loadingButton(btn);
        $.ajax({
            method: "GET",
            url: url_gb + "/admin/CustomerPayment/" + id,
            dataType: 'json',
        }).done(function(res) {
            resetButton(btn);
            $("#edit_pi_id_rp").val(res.content.pi_id || piIdForPayment);
            $("#edit_bank_account_id_rp").val(res.content.bank_account_id).trigger('change.select2');
            $("#edit_payment_method_id_rp").val(res.content.payment_method_id).trigger('change.select2');
            $("#edit_currency_id_rp").val(res.content.currency_id).trigger('change.select2');
            $("#edit_reference_no_rp").val(res.content.reference_no);
            if (res.content.payment_date) {
                var pdm = String(res.content.payment_date).replace('T', ' ');
                if (pdm.indexOf(' ') !== -1) {
                    var parts = pdm.split(' ');
                    $("#edit_payment_date_rp").val(convertDateToThai(parts[0]));
                    var tm = parts[1].split(':');
                    $("#edit_payment_time_hour_rp").val(parseInt(tm[0], 10) || 0);
                    $("#edit_payment_time_minute_rp").val(parseInt(tm[1], 10) || 0);
                } else {
                    $("#edit_payment_date_rp").val(convertDateToThai(res.content.payment_date));
                    $("#edit_payment_time_hour_rp").val(0);
                    $("#edit_payment_time_minute_rp").val(0);
                }
            }
            $("#edit_payment_note_rp").val(res.content.payment_note);
            $("#edit_amount_rp").val(res.content.amount);
            $("#edit_amount_bath_rp").val(res.content.amount_bath);
            $("#edit_exchange_rate_rp").val(res.content.exchange_rate);
            $('#edit_photo_rp').closest('#orak_edit_photo_rp').html('<div id="edit_photo_rp" orakuploader="on"></div>');
            if (res.content.photo) {
                $('#edit_photo_rp').orakuploader({
                    orakuploader_field_name: 'photo',
                    orakuploader_maximum_uploads: 0,
                    orakuploader_attach_images: jQuery.parseJSON(res.content.photo),
                    orakuploader_finished: function () {}
                });
            } else {
                $('#edit_photo_rp').orakuploader({
                    orakuploader_field_name: 'photo',
                    orakuploader_finished: function () {}
                });
            }
            $('#ModalEditRecordPayment').modal('show');
        }).fail(function(res) {
            ajaxFail(res, "");
        });
    });

    $("#add_bank_account_id_rp").select2({ placeholder: 'กรุณาเลือก', allowClear: true, dropdownParent: $('#ModalAddRecordPayment') });
    $("#add_payment_method_id_rp").select2({ placeholder: 'กรุณาเลือก', allowClear: true, dropdownParent: $('#ModalAddRecordPayment') });
    $("#add_currency_id_rp").select2({ placeholder: 'กรุณาเลือก', allowClear: true, dropdownParent: $('#ModalAddRecordPayment') });
    $("#edit_bank_account_id_rp").select2({ placeholder: 'กรุณาเลือก', allowClear: true, dropdownParent: $('#ModalEditRecordPayment') });
    $("#edit_payment_method_id_rp").select2({ placeholder: 'กรุณาเลือก', allowClear: true, dropdownParent: $('#ModalEditRecordPayment') });
    $("#edit_currency_id_rp").select2({ placeholder: 'กรุณาเลือก', allowClear: true, dropdownParent: $('#ModalEditRecordPayment') });

    initOrakuploaderAddRecordPayment();
</script>
@endpush
