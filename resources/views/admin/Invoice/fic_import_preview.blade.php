@extends('admin.layouts.default')

@section('title', $pageTitle)

@section('body')
@php
    $updates = $lines->whereIn('action', ['update', 'ambiguous']);
    $credits = $lines->where('action', 'credit_note');
    $skips   = $lines->where('action', 'skipped_no_packing');
    $applied = $import->status === 'applied';
@endphp
<div class="page-content container-fluid container-plus">
    <div class="page-header mb-2 pb-2 flex-column flex-sm-row align-items-start align-items-sm-center py-25 px-1">
        <h1 class="page-title text-primary-d2 text-140">
            พรีวิว FIC Import
            <span class="text-secondary text-100 ml-2">{{ $import->source_filename }}</span>
        </h1>
        <div class="ml-sm-auto mt-2 mt-sm-0">
            <a href="{{ url('admin/'.$route_locale.'/Invoice') }}" class="btn btn-light btn-sm">
                <i class="fa fa-arrow-left mr-1"></i> กลับหน้า Invoice
            </a>
        </div>
    </div>

    @if (session('fic_error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session('fic_error') }}
        </div>
    @endif

    @if ($applied)
        <div class="alert alert-info">batch นี้ apply ไปแล้วเมื่อ {{ optional($import->applied_at)->format('Y-m-d H:i') }} — แสดงเพื่อดูย้อนหลัง</div>
    @endif

    {{-- สรุป --}}
    <div class="card dcard mb-3">
        <div class="card-body py-3">
            <div class="d-flex flex-wrap" style="gap:.5rem">
                <span class="badge badge-secondary text-95 p-2">แถวข้อมูลทั้งไฟล์: {{ number_format($import->total_rows) }}</span>
                <span class="badge badge-primary text-95 p-2">อัปเดต packing: {{ number_format($import->update_rows) }}</span>
                <span class="badge badge-warning text-95 p-2">กำกวม (เลือกเอง): {{ number_format($import->ambiguous_rows) }}</span>
                <span class="badge badge-danger text-95 p-2">Credit Note (คืนของ): {{ number_format($import->credit_rows) }}</span>
                <span class="badge badge-light border text-95 p-2">ข้าม (ไม่มี packing): {{ number_format($import->skipped_no_packing_rows) }}</span>
                <span class="badge badge-light border text-95 p-2">ข้าม (ไม่เจอ PI): {{ number_format($import->skipped_no_pi_rows) }}</span>
            </div>
        </div>
    </div>

    <form action="{{ url('admin/'.$route_locale.'/Invoice/FicImport/'.$import->id.'/Apply') }}" method="POST" id="formApply">
        @csrf

        {{-- อัปเดต packing --}}
        <div class="card dcard mb-3">
            <div class="card-header"><h5 class="mb-0 text-primary-d2">จะอัปเดต packing ({{ $updates->count() }})</h5></div>
            <div class="card-body p-0">
                <table class="table table-sm table-striped mb-0">
                    <thead>
                        <tr class="bgc-primary-d1 text-white">
                            <th class="text-center" width="40">#</th>
                            <th>FIC Invoice</th>
                            <th>PI (Po No)</th>
                            <th>Part No</th>
                            <th>รายละเอียด</th>
                            <th>Packing ที่จะอัปเดต</th>
                            <th class="text-center">Qty เดิม → ใหม่</th>
                            <th class="text-right">ราคา (FIC)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($updates as $i => $line)
                            @php
                                $cands = collect($line->candidates ?? []);
                                $sel = $cands->firstWhere('detail_id', (int) $line->matched_detail_id);
                                $oldQty = $sel['qty'] ?? ($cands->first()['qty'] ?? null);
                            @endphp
                            <tr>
                                <td class="text-center">{{ $i + 1 }}</td>
                                <td>{{ $line->inv_num }}</td>
                                <td>{{ $line->matched_pi_doc_no }}<div class="text-muted text-90">{{ $line->po_no }}</div></td>
                                <td>{{ $line->item }}</td>
                                <td class="text-90">{{ \Illuminate\Support\Str::limit($line->fic_desc, 40) }}</td>
                                <td>
                                    @if ($line->action === 'ambiguous' && !$applied)
                                        <select name="detail_choice[{{ $line->id }}]" class="form-control form-control-sm js-choice" data-line="{{ $line->id }}">
                                            @foreach ($cands as $c)
                                                <option value="{{ $c['detail_id'] }}"
                                                    data-qty="{{ $c['qty'] }}"
                                                    {{ (int) $line->matched_detail_id === (int) $c['detail_id'] ? 'selected' : '' }}>
                                                    {{ $c['packing_doc_no'] }} (กล่อง {{ $c['from'] }}-{{ $c['to'] }}, qty {{ (int) $c['qty'] }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="badge badge-warning mt-1">เลือกใบ</span>
                                    @else
                                        {{ $sel['packing_doc_no'] ?? ($cands->first()['packing_doc_no'] ?? '-') }}
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="js-oldqty-{{ $line->id }}">{{ $oldQty !== null ? (int) $oldQty : '-' }}</span>
                                    <i class="fa fa-arrow-right text-muted mx-1"></i>
                                    <b class="text-success">{{ (int) abs($line->qty) }}</b>
                                </td>
                                <td class="text-right">{{ number_format((float) $line->price, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted py-3">— ไม่มี —</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Credit Note --}}
        <div class="card dcard mb-3">
            <div class="card-header"><h5 class="mb-0 text-danger-d2">จะออก Credit Note — คืนของ ({{ $credits->count() }} รายการ)</h5></div>
            <div class="card-body p-0">
                <table class="table table-sm table-striped mb-0">
                    <thead>
                        <tr class="bgc-danger-d1 text-white">
                            <th class="text-center" width="40">#</th>
                            <th>FIC Invoice</th>
                            <th>PI (Po No)</th>
                            <th>Part No</th>
                            <th>รายละเอียด</th>
                            <th class="text-center">Qty (คืน)</th>
                            <th class="text-right">ราคา</th>
                            <th class="text-right">รวม</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($credits as $i => $line)
                            <tr>
                                <td class="text-center">{{ $i + 1 }}</td>
                                <td>{{ $line->inv_num }}</td>
                                <td>{{ $line->matched_pi_doc_no }}</td>
                                <td>{{ $line->item }}</td>
                                <td class="text-90">{{ \Illuminate\Support\Str::limit($line->fic_desc, 40) }}</td>
                                <td class="text-center text-danger"><b>{{ (int) abs($line->qty) }}</b></td>
                                <td class="text-right">{{ number_format((float) $line->price, 2) }}</td>
                                <td class="text-right">{{ number_format(abs($line->qty) * (float) $line->price, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted py-3">— ไม่มี —</td></tr>
                        @endforelse
                    </tbody>
                </table>
                @if ($credits->count())
                    <div class="p-2 text-muted text-90">* รวมเป็น Credit Note ตามเลข FIC (1 เลข FIC = 1 ใบ) ด้วยโมดูล Credit Note เดิม</div>
                @endif
            </div>
        </div>

        {{-- ข้าม --}}
        @if ($skips->count())
            <div class="card dcard mb-3">
                <div class="card-header"><h5 class="mb-0 text-muted">ข้าม — เจอ PI แต่ไม่มี packing ({{ $skips->count() }})</h5></div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr class="bgc-secondary-l3">
                                <th class="text-center" width="40">#</th>
                                <th>FIC Invoice</th>
                                <th>PI (Po No)</th>
                                <th>Part No</th>
                                <th>รายละเอียด</th>
                                <th class="text-center">Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($skips as $i => $line)
                                <tr>
                                    <td class="text-center">{{ $i + 1 }}</td>
                                    <td>{{ $line->inv_num }}</td>
                                    <td>{{ $line->matched_pi_doc_no }}</td>
                                    <td>{{ $line->item }}</td>
                                    <td class="text-90">{{ \Illuminate\Support\Str::limit($line->fic_desc, 40) }}</td>
                                    <td class="text-center">{{ (int) $line->qty }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        @if (!$applied)
            <div class="d-flex mb-4">
                <button type="submit" class="btn btn-success" id="btnApply">
                    <i class="fa fa-check mr-1"></i> ยืนยัน apply
                </button>
                <button type="submit" class="btn btn-outline-danger ml-2"
                    formaction="{{ url('admin/'.$route_locale.'/Invoice/FicImport/'.$import->id.'/Cancel') }}"
                    formnovalidate>
                    <i class="fa fa-times mr-1"></i> ยกเลิก (ลบ batch)
                </button>
            </div>
        @endif
    </form>
</div>
@endsection

@push('scripts')
<script>
    // อัปเดตตัวเลข qty เดิม ตาม packing ที่เลือก (แถวกำกวม)
    $(document).on('change', '.js-choice', function () {
        var line = $(this).data('line');
        var qty = $(this).find('option:selected').data('qty');
        $('.js-oldqty-' + line).text(parseInt(qty, 10));
    });

    // กันกดซ้ำ + ยืนยัน
    $('#formApply').on('submit', function (e) {
        var btn = document.activeElement;
        if (btn && btn.id === 'btnApply') {
            if (!confirm('ยืนยัน apply? ระบบจะอัปเดต packing และออก Credit Note ตามรายการนี้')) {
                e.preventDefault();
                return false;
            }
            $('#btnApply').prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> กำลัง apply...');
        }
    });
</script>
@endpush
