{{-- props: $label, $value, $col (bootstrap col width out of 12) --}}
<div class="col-md-{{ $col ?? 4 }} mb-2">
    <div class="text-85 text-muted">{{ $label }}</div>
    <div class="text-600 text-grey-d2" style="white-space:pre-line;word-break:break-word;">{{ ($value !== null && $value !== '') ? $value : '-' }}</div>
</div>
