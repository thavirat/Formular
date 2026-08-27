@extends('admin.layouts.default')

@section('title', ($isEdit ? 'แก้ไข' : 'สร้าง').' Credit Note')

@section('body')
@php $lang = config('app.locale'); @endphp
<div class="page-content container-fluid container-plus">
    <div class="page-header mb-2 pb-2">
        <h1 class="page-title text-primary-d2 text-140">{{ $isEdit ? 'แก้ไข' : 'สร้าง' }} Credit Note / Debit Note</h1>
    </div>

    <div class="card dcard">
        <div class="card-body p-3">
            <form id="form-credit-note" data-id="{{ $isEdit ? $creditNote->id : '' }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>DOC <span class="text-danger">*</span></label>
                            <select name="doc_type" id="doc_type" class="form-control">
                                <option value="credit" {{ $creditNote->doc_type=='credit'?'selected':'' }}>CREDIT</option>
                                <option value="debit" {{ $creditNote->doc_type=='debit'?'selected':'' }}>DEBIT</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Number</label>
                            <input type="text" id="doc_no_display" class="form-control" value="{{ $suggested_doc_no }}" readonly>
                            <small class="text-muted">สร้างอัตโนมัติ (C/N หรือ D/N ตามชนิด)</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Date <span class="text-danger">*</span></label>
                            <input type="date" name="doc_date" id="doc_date" class="form-control" value="{{ optional($creditNote->doc_date)->format('Y-m-d') ?: date('Y-m-d') }}">
                        </div>
                    </div>
                </div>

                <hr class="my-2">
                <h5 class="text-secondary">Buyer</h5>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>เลือกลูกค้า</label>
                            <select name="customer_id" id="customer_id" class="form-control select2">
                                <option value="">— เลือกลูกค้า —</option>
                                @foreach($Customers as $c)
                                    <option value="{{ $c->id }}"
                                        data-company="{{ $c->company_name }}"
                                        data-address="{{ $c->address }}"
                                        data-tax="{{ $c->tax_id }}"
                                        data-phone="{{ $c->phone ?: $c->mobile }}"
                                        data-contact="{{ $c->contact_name }}"
                                        {{ $creditNote->customer_id == $c->id ? 'selected' : '' }}>
                                        {{ $c->code ? $c->code.' - ' : '' }}{{ $c->company_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-2"><label>Company <span class="text-danger">*</span></label>
                            <input type="text" name="company_name" id="company_name" class="form-control" value="{{ $creditNote->company_name }}"></div>
                        <div class="form-group mb-2"><label>Address</label>
                            <input type="text" name="address" id="address" class="form-control" value="{{ $creditNote->address }}"></div>
                        <div class="form-group mb-2"><label>Address 2</label>
                            <input type="text" name="address2" id="address2" class="form-control" value="{{ $creditNote->address2 }}"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-2"><label>Country</label>
                            <input type="text" name="country" id="country" class="form-control" value="{{ $creditNote->country }}"></div>
                        <div class="form-group mb-2"><label>Phone</label>
                            <input type="text" name="phone" id="phone" class="form-control" value="{{ $creditNote->phone }}"></div>
                        <div class="row">
                            <div class="col-6"><div class="form-group mb-2"><label>Tax ID</label>
                                <input type="text" name="tax_id" id="tax_id" class="form-control" value="{{ $creditNote->tax_id }}"></div></div>
                            <div class="col-6"><div class="form-group mb-2"><label>Refer</label>
                                <input type="text" name="refer" id="refer" class="form-control" value="{{ $creditNote->refer }}" placeholder="อ้างอิงใบกำกับ"></div></div>
                        </div>
                        <div class="form-group mb-2"><label>Contact Name</label>
                            <input type="text" name="contact_name" id="contact_name" class="form-control" value="{{ $creditNote->contact_name }}"></div>
                    </div>
                </div>

                <hr class="my-2">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <h5 class="text-secondary mb-0">รายการ <span id="cur_label" class="badge badge-secondary">{{ optional($creditNote->currency)->symbol }}</span></h5>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="btn-add-item"><i class="fa fa-plus"></i> เพิ่มรายการ</button>
                </div>
                <table class="table table-bordered table-sm" id="itemTable">
                    <thead>
                        <tr>
                            <th width="20%">Invoice #</th>
                            <th>Description (สินค้า)</th>
                            <th width="9%">Quantity</th>
                            <th width="13%">Unit Price</th>
                            <th width="13%">Amount</th>
                            <th width="40"></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-right">Total</th>
                            <th class="text-right"><span id="grand_total">0.00</span></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>

                <div class="row mt-2">
                    <div class="col-md-8">
                        <div class="form-group"><label>Reason for ({{ 'Debit/Credit' }})</label>
                            <textarea name="reason" id="reason" class="form-control" rows="3">{{ $creditNote->reason }}</textarea></div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label>Authorized By</label>
                            <select name="authorized_by" id="authorized_by" class="form-control select2">
                                <option value="">—</option>
                                @foreach($Admins as $a)
                                    <option value="{{ $a->id }}" {{ $creditNote->authorized_by == $a->id ? 'selected' : '' }}>{{ $a->name ?: trim($a->firstname.' '.$a->lastname) ?: $a->nickname }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-3">
                    <a href="{{ url('admin/'.$lang.'/CreditNote') }}" class="btn btn-secondary px-4"><i class="fa fa-arrow-left"></i> กลับ</a>
                    <button type="submit" class="btn btn-success btn-lg px-5"><i class="fa fa-save"></i> บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
$(function(){
    var lang = "{{ $lang }}";
    var base = url_gb + "/admin/" + lang + "/CreditNote";
    var invoicesCache = [];   // PI ของลูกค้าที่เลือก
    var existing = @json($isEdit ? $creditNote->items : []);

    // เปิด select2 (ค้นหาได้) สำหรับลูกค้า + ผู้อนุมัติ
    try {
        $('#customer_id').select2({ width: '100%', placeholder: '— เลือกลูกค้า —' });
        $('#authorized_by').select2({ width: '100%', placeholder: '—' });
    } catch (e) {}

    /* ---------- เลข doc_no ตามชนิด ---------- */
    function refreshDocNo(){
        @if(!$isEdit)
        $.get(base + "/SuggestDocNo", { doc_type: $('#doc_type').val(), year: ($('#doc_date').val()||'').substring(0,4) })
            .done(function(r){ if(r.doc_no) $('#doc_no_display').val(r.doc_no); });
        @endif
    }
    $('#doc_type, #doc_date').on('change', refreshDocNo);

    /* ---------- เลือกลูกค้า -> เติมข้อมูล + โหลด invoice ---------- */
    function fillCustomer(){
        var o = $('#customer_id option:selected');
        if(o.val()){
            $('#company_name').val(o.data('company')||'');
            $('#address').val(o.data('address')||'');
            $('#tax_id').val(o.data('tax')||'');
            $('#phone').val(o.data('phone')||'');
            $('#contact_name').val(o.data('contact')||'');
        }
    }
    function loadInvoices(cb){
        var cid = $('#customer_id').val();
        invoicesCache = [];
        if(!cid){ if(cb)cb(); return; }
        $.get(base + "/CustomerInvoices", { customer_id: cid }).done(function(r){
            invoicesCache = r.items || [];
            if(cb) cb();
        });
    }
    $('#customer_id').on('change', function(){
        fillCustomer();
        loadInvoices(function(){
            // รีเซ็ต dropdown invoice ในทุกแถว
            $('#itemTable tbody tr').each(function(){ fillInvoiceOptions($(this).find('.sel-invoice'), ''); });
        });
    });

    function invoiceOptionsHtml(selected){
        var h = '<option value="">— เลือก Invoice —</option>';
        invoicesCache.forEach(function(iv){
            var label = iv.doc_no + (iv.customer_po ? ' ('+iv.customer_po+')' : '');
            h += '<option value="'+iv.id+'" data-docno="'+ (iv.doc_no||'') +'" data-cur="'+(iv.currency_symbol||'')+'" '+(String(iv.id)===String(selected)?'selected':'')+'>'+label+'</option>';
        });
        return h;
    }
    function fillInvoiceOptions($sel, selected){ $sel.html(invoiceOptionsHtml(selected)); }

    /* ---------- แถวรายการ ---------- */
    function addItemRow(data){
        data = data || {};
        var $tr = $(
            '<tr>'+
              '<td><select class="form-control form-control-sm sel-invoice" name="pi_id[]"></select>'+
                  '<input type="hidden" name="invoice_no[]" class="in-invoice-no">'+
              '</td>'+
              '<td><select class="form-control form-control-sm sel-product" name="pi_product_id[]"><option value="">— เลือก Invoice ก่อน —</option></select>'+
                  '<input type="hidden" name="description[]" class="in-desc"><input type="hidden" name="part_no[]" class="in-part">'+
              '</td>'+
              '<td><input type="text" class="form-control form-control-sm text-right in-qty" name="qty[]" value="'+(data.qty||'1')+'"></td>'+
              '<td><input type="text" class="form-control form-control-sm text-right in-price" name="unit_price[]" value="'+(data.unit_price||'')+'"></td>'+
              '<td><input type="text" class="form-control form-control-sm text-right in-amount" readonly value="'+(data.amount||'')+'"></td>'+
              '<td class="text-center"><button type="button" class="btn btn-xs btn-outline-danger btn-rm" tabindex="-1"><i class="fa fa-trash"></i></button></td>'+
            '</tr>'
        );
        $('#itemTable tbody').append($tr);
        fillInvoiceOptions($tr.find('.sel-invoice'), data.pi_id||'');
        $tr.find('.in-invoice-no').val(data.invoice_no||'');
        $tr.find('.in-desc').val(data.description||'');
        $tr.find('.in-part').val(data.part_no||'');
        // ถ้ามีข้อมูลเดิม (edit) โหลดสินค้าให้เลือกด้วย
        if(data.pi_id){
            loadProducts($tr.find('.sel-product'), data.pi_id, data.pi_product_id, function(){
                $tr.find('.in-desc').val(data.description||'');
                $tr.find('.in-part').val(data.part_no||'');
            });
        }
        return $tr;
    }

    function loadProducts($sel, piId, selected, cb){
        if(!piId){ $sel.html('<option value="">— เลือก Invoice ก่อน —</option>'); if(cb)cb(); return; }
        $.get(base + "/InvoiceProducts", { pi_id: piId }).done(function(r){
            var h = '<option value="">— เลือกสินค้า —</option>';
            (r.items||[]).forEach(function(p){
                var desc = (p.part_no||p.product_code||'') + ' ' + (p.detail_eng||p.name_en||p.detail_thai||'');
                h += '<option value="'+p.pi_product_id+'" data-desc="'+ $('<div>').text(desc.trim()).html() +'" data-part="'+(p.part_no||'')+'" data-price="'+(p.price_per_item||0)+'" data-qty="'+(p.qty||1)+'" '+(String(p.pi_product_id)===String(selected)?'selected':'')+'>'+ $('<div>').text(desc.trim()).html() +'</option>';
            });
            $sel.html(h);
            if(r.currency_symbol){ $('#cur_label').text(r.currency_symbol); }
            if(cb) cb();
        });
    }

    // เปลี่ยน Invoice -> โหลดสินค้า + เก็บ invoice_no snapshot
    $('#itemTable').on('change', '.sel-invoice', function(){
        var $tr = $(this).closest('tr');
        var o = $(this).find('option:selected');
        $tr.find('.in-invoice-no').val(o.data('docno')||'');
        if(o.data('cur')) $('#cur_label').text(o.data('cur'));
        loadProducts($tr.find('.sel-product'), $(this).val(), '');
        $tr.find('.in-desc, .in-part, .in-price').val('');
        $tr.find('.in-amount').val('');
        calcTotal();
    });
    // เปลี่ยนสินค้า -> เติม desc/price อัตโนมัติ
    $('#itemTable').on('change', '.sel-product', function(){
        var $tr = $(this).closest('tr');
        var o = $(this).find('option:selected');
        $tr.find('.in-desc').val(o.data('desc')||'');
        $tr.find('.in-part').val(o.data('part')||'');
        if(o.val()){
            $tr.find('.in-price').val(parseFloat(o.data('price')||0).toFixed(2));
            if(!$tr.find('.in-qty').val() || $tr.find('.in-qty').val()=='0') $tr.find('.in-qty').val(o.data('qty')||1);
        }
        calcRow($tr); calcTotal();
    });
    $('#itemTable').on('input', '.in-qty, .in-price', function(){ calcRow($(this).closest('tr')); calcTotal(); });
    $('#itemTable').on('click', '.btn-rm', function(){ $(this).closest('tr').remove(); calcTotal(); });
    $('#btn-add-item').on('click', function(){ addItemRow(); });

    function num(v){ return parseFloat(String(v).replace(/,/g,''))||0; }
    function calcRow($tr){ $tr.find('.in-amount').val((num($tr.find('.in-qty').val())*num($tr.find('.in-price').val())).toFixed(2)); }
    function calcTotal(){
        var t=0; $('#itemTable tbody tr').each(function(){ t+=num($(this).find('.in-amount').val()); });
        $('#grand_total').text(t.toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2}));
    }

    /* ---------- init ---------- */
    loadInvoices(function(){
        if(existing && existing.length){
            existing.forEach(function(it){ addItemRow(it); });
        } else { addItemRow(); }
        calcTotal();
    });

    /* ---------- submit ---------- */
    $('#form-credit-note').on('submit', function(e){
        e.preventDefault();
        var form=$(this), btn=form.find('button[type=submit]');
        loadingButton(btn);
        var id = form.data('id');
        var url = base + (id ? '/'+id : '');
        var data = form.serialize() + '&_token=' + $('meta[name="csrf-token"]').attr('content');
        if(id) data += '&_method=PUT';
        $.ajax({ method:'POST', url:url, data:data, dataType:'json' }).done(function(r){
            resetButton(btn);
            if(r.status==1){
                Swal.fire('สำเร็จ', r.content, 'success').then(function(){ window.location = base; });
            } else { Swal.fire(r.title||'ผิดพลาด', r.content||'', 'error'); }
        }).fail(function(xhr){ resetButton(btn); ajaxFail(xhr, form); });
    });
});
</script>
@endpush
