@extends('admin.layouts.default')

@section('title', $currentMenu->title)

@php
    $isEdit = $PriceSetting !== null;
    // map ค่าที่มีอยู่ (ตอนแก้ไข)
    $rateOf = [];  $mulOf = [];  $qtyBaseLevel = null;
    if ($isEdit) {
        foreach ($PriceSetting->rates as $r) { $rateOf[$r->currency_id] = $r->rate; }
        foreach ($PriceSetting->levels as $l) { $mulOf[$l->level_id] = $l->multiplier; if ($l->is_qty_base) $qtyBaseLevel = $l->level_id; }
    }
    $qtyRows = $isEdit ? $PriceSetting->qtys : collect([(object)['min_qty'=>1,'giveaway'=>0]]);
    if ($qtyBaseLevel === null && $CustomerLevels->count()) $qtyBaseLevel = $CustomerLevels->first()->id;
@endphp

@section('body')
<div class="page-content container-fluid container-plus">
    <div class="page-header mb-2 pb-2">
        <h1 class="page-title text-primary-d2 text-140">{{ $isEdit ? 'แก้ไข' : 'เพิ่ม' }}ชุดราคา</h1>
    </div>

    <form id="form-price-setting">
        @if($isEdit)<input type="hidden" id="ps_id" value="{{ $PriceSetting->id }}">@endif

        <div class="card dcard mb-3"><div class="card-body">
            <div class="row">
                <div class="col-md-3"><div class="form-group">
                    <label>วันที่เริ่ม</label>
                    <div class="input-group">
                        <input type="text" name="start_date" class="form-control init-date" value="{{ $isEdit && $PriceSetting->start_date ? date('Y-m-d', strtotime($PriceSetting->start_date)) : '' }}" readonly>
                        <div class="input-group-append remove_date_time"><div class="input-group-text"><i class="fa fa-times"></i></div></div>
                        <div class="input-group-append"><div class="input-group-text"><i class="far fa-calendar"></i></div></div>
                    </div>
                </div></div>
                <div class="col-md-3"><div class="form-group">
                    <label>วันที่สิ้นสุด</label>
                    <div class="input-group">
                        <input type="text" name="end_date" class="form-control init-date" value="{{ $isEdit && $PriceSetting->end_date ? date('Y-m-d', strtotime($PriceSetting->end_date)) : '' }}" readonly>
                        <div class="input-group-append remove_date_time"><div class="input-group-text"><i class="fa fa-times"></i></div></div>
                        <div class="input-group-append"><div class="input-group-text"><i class="far fa-calendar"></i></div></div>
                    </div>
                </div></div>
                <div class="col-md-3"><div class="form-group">
                    <label>สถานะ</label>
                    <select name="active" class="form-control">
                        <option value="T" {{ $isEdit && $PriceSetting->active=='F' ? '' : 'selected' }}>ใช้งาน</option>
                        <option value="F" {{ $isEdit && $PriceSetting->active=='F' ? 'selected' : '' }}>ปิด</option>
                    </select>
                </div></div>
                <div class="col-md-3"><div class="form-group">
                    <label class="text-primary">ราคาสมมติ (COST) — ทดสอบดูผลลัพธ์</label>
                    <input type="number" step="any" id="sample_price" class="form-control text-primary" value="834.46">
                </div></div>
            </div>
        </div></div>

        <div class="row">
            {{-- ===== ตั้งค่า ===== --}}
            <div class="col-md-6">
                <div class="card dcard mb-3"><div class="card-body">
                    <h5 class="text-primary-d2">เรตสกุลเงิน <small class="text-muted">(ราคา = COST ÷ เรต)</small></h5>
                    <table class="table table-sm table-bordered mb-0">
                        <thead class="bgc-grey-l3"><tr><th>สกุลเงิน</th><th width="45%">เรต</th></tr></thead>
                        <tbody>
                        @foreach($Currencies as $cur)
                            <tr>
                                <td class="align-middle">{{ $cur->symbol }} <small class="text-muted">{{ $cur->name }}</small></td>
                                <td><input type="number" step="any" class="form-control form-control-sm rate-input"
                                    data-cur="{{ $cur->id }}" name="rate[{{ $cur->id }}]"
                                    value="{{ $rateOf[$cur->id] ?? '' }}"></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div></div>

                <div class="card dcard mb-3"><div class="card-body">
                    <h5 class="text-primary-d2">ตัวคูณระดับลูกค้า <small class="text-muted">(× ตัวคูณ)</small></h5>
                    <table class="table table-sm table-bordered mb-0">
                        <thead class="bgc-grey-l3"><tr><th>ระดับลูกค้า</th><th width="30%">ตัวคูณ</th><th width="25%" class="text-center">ฐานจำนวน</th></tr></thead>
                        <tbody>
                        @foreach($CustomerLevels as $lvl)
                            <tr>
                                <td class="align-middle">{{ $lvl->name }}</td>
                                <td><input type="number" step="any" class="form-control form-control-sm mul-input"
                                    data-lvl="{{ $lvl->id }}" name="multiplier[{{ $lvl->id }}]"
                                    value="{{ $mulOf[$lvl->id] ?? '' }}"></td>
                                <td class="text-center align-middle">
                                    <input type="radio" name="qty_base" value="{{ $lvl->id }}" {{ $qtyBaseLevel == $lvl->id ? 'checked' : '' }}>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <small class="text-muted">* "ฐานจำนวน" = ระดับที่ใช้คำนวณราคาตามจำนวน (เจ้าใหญ่/BIG)</small>
                </div></div>

                <div class="card dcard mb-3"><div class="card-body">
                    <h5 class="text-primary-d2">แถม ตามระดับจำนวน <small class="text-muted">(แถม X ต่อ 100 → × 100 ÷ (100+X))</small></h5>
                    <table class="table table-sm table-bordered mb-0" id="qtyTable">
                        <thead class="bgc-grey-l3"><tr><th>จำนวน ≥</th><th width="35%">แถม</th><th width="40"></th></tr></thead>
                        <tbody>
                        @foreach($qtyRows as $q)
                            <tr>
                                <td><input type="number" class="form-control form-control-sm qty-min" name="min_qty[]" value="{{ $q->min_qty }}" {{ $q->min_qty==1 ? 'readonly' : '' }}></td>
                                <td><input type="number" step="any" class="form-control form-control-sm qty-give" name="giveaway[]" value="{{ $q->giveaway }}"></td>
                                <td class="text-center">
                                    @if($q->min_qty != 1)
                                        <button type="button" class="btn btn-outline-danger btn-sm removeQty"><i class="fa fa-trash"></i></button>
                                    @else
                                        <span class="text-muted small">ล็อก</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot><tr><td colspan="3"><button type="button" class="btn btn-outline-primary btn-sm" id="addQty"><i class="fa fa-plus"></i> เพิ่มระดับจำนวน</button></td></tr></tfoot>
                    </table>
                </div></div>
            </div>

            {{-- ===== ผลลัพธ์ (preview) ===== --}}
            <div class="col-md-6">
                <div class="card dcard mb-3 bgc-blue-l5"><div class="card-body">
                    <h5 class="text-primary-d2">ผลลัพธ์ราคา (คำนวณจากราคาสมมติ)</h5>

                    <div class="text-bold mt-2">ตามระดับลูกค้า (ออเดอร์เล็ก)</div>
                    <table class="table table-sm table-bordered mb-3">
                        <thead class="bgc-grey-l3"><tr><th>สกุล \ ระดับ</th>@foreach($CustomerLevels as $lvl)<th class="text-right">{{ $lvl->name }}</th>@endforeach</tr></thead>
                        <tbody>
                        @foreach($Currencies as $cur)
                            <tr>
                                <td class="text-bold">{{ $cur->symbol }}</td>
                                @foreach($CustomerLevels as $lvl)
                                    <td class="text-right" id="plv_{{ $cur->id }}_{{ $lvl->id }}">-</td>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="text-bold">ตามระดับจำนวน (ใช้ฐาน BIG)</div>
                    <table class="table table-sm table-bordered mb-0" id="qtyPreview">
                        <thead class="bgc-grey-l3"><tr id="qtyPreviewHead"><th>สกุล \ จำนวน</th></tr></thead>
                        <tbody id="qtyPreviewBody"></tbody>
                    </table>
                </div></div>

                <div class="text-right">
                    <a href="{{ url('admin/'.$lang.'/PriceSetting') }}" class="btn btn-secondary"><i class="fa fa-times"></i> ยกเลิก</a>
                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> บันทึก</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
$(document).ready(function() {
    var currencies = {!! $Currencies->map(fn($c)=>['id'=>$c->id,'symbol'=>$c->symbol])->values()->toJson() !!};
    var levels     = {!! $CustomerLevels->map(fn($l)=>['id'=>$l->id,'name'=>$l->name])->values()->toJson() !!};

    function num(v){ return parseFloat(String(v==null?'':v).replace(/,/g,'')) || 0; }

    function recompute() {
        var sample = num($('#sample_price').val());
        // ตัวคูณของระดับฐาน (BIG)
        var baseMul = num($('.mul-input[data-lvl="'+$('input[name="qty_base"]:checked').val()+'"]').val());

        // ราคาตามระดับลูกค้า
        currencies.forEach(function(cur){
            var rate = num($('.rate-input[data-cur="'+cur.id+'"]').val());
            levels.forEach(function(lvl){
                var mul = num($('.mul-input[data-lvl="'+lvl.id+'"]').val());
                var p = rate>0 ? sample*mul/rate : 0;
                $('#plv_'+cur.id+'_'+lvl.id).text(rate>0 ? p.toFixed(2) : '-');
            });
        });

        // ราคาตามระดับจำนวน (ฐาน BIG) -> สร้างตารางใหม่
        var tiers = [];
        $('#qtyTable tbody tr').each(function(){
            var m = parseInt($(this).find('.qty-min').val())||0;
            var g = num($(this).find('.qty-give').val());  // แถม
            if (m>0) tiers.push({min:m, give:g});
        });
        tiers.sort(function(a,b){ return a.min-b.min; });

        var head = '<th>สกุล \\ จำนวน</th>';
        tiers.forEach(function(t){ head += '<th class="text-right">≥ '+t.min+(t.give>0?' (แถม '+t.give+')':'')+'</th>'; });
        $('#qtyPreviewHead').html(head);

        var body = '';
        currencies.forEach(function(cur){
            var rate = num($('.rate-input[data-cur="'+cur.id+'"]').val());
            body += '<tr><td class="text-bold">'+cur.symbol+'</td>';
            tiers.forEach(function(t){
                var p = (rate>0) ? (sample*baseMul/rate*100/(100+t.give)) : 0;
                body += '<td class="text-right">'+(rate>0?p.toFixed(2):'-')+'</td>';
            });
            body += '</tr>';
        });
        $('#qtyPreviewBody').html(body);
    }

    // เพิ่ม/ลบ ระดับจำนวน
    $('#addQty').on('click', function(){
        $('#qtyTable tbody').append(
            '<tr>'+
            '<td><input type="number" class="form-control form-control-sm qty-min" name="min_qty[]" placeholder="เช่น 100"></td>'+
            '<td><input type="number" step="any" class="form-control form-control-sm qty-give" name="giveaway[]" value="0"></td>'+
            '<td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm removeQty"><i class="fa fa-trash"></i></button></td>'+
            '</tr>');
    });
    $('body').on('click', '.removeQty', function(){ $(this).closest('tr').remove(); recompute(); });
    $('body').on('input change', '.rate-input, .mul-input, .qty-min, .qty-give, #sample_price, input[name="qty_base"]', recompute);

    recompute();

    // บันทึก
    $('#form-price-setting').on('submit', function(e){
        e.preventDefault();
        var form = $(this);
        var id = $('#ps_id').val();
        var method = id ? 'PUT' : 'POST';
        var url = url_gb + '/admin/PriceSetting' + (id ? '/'+id : '');
        loadingButton(form.find('button[type=submit]'));
        $.ajax({ method: method, url: url, dataType:'json', data: form.serialize() })
        .done(function(res){
            resetButton(form.find('button[type=submit]'));
            if(res.status==1){
                Swal.fire(res.title, res.content, 'success').then(function(){
                    window.location = url_gb + '/admin/{{ $lang }}/PriceSetting';
                });
            } else { Swal.fire(res.title, res.content, 'error'); }
        }).fail(function(res){ ajaxFail(res, form); });
    });
});
</script>
@endpush
