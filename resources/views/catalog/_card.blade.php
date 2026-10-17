@php
    $en = strtoupper(optional($p->subCategory)->name_en ?? '');
    $art = 'generic';
    if (str_contains($en, 'CONDENSER')) $art = 'condenser';
    elseif (str_contains($en, 'COMPRESSOR')) $art = 'compressor';
    elseif (str_contains($en, 'EVAPORATOR')) $art = 'evaporator';
    elseif (str_contains($en, 'DRIER') || str_contains($en, 'ACCUMULATOR')) $art = 'drier';
    $badge = optional($p->subCategory)->name_en ?: optional($p->subCategory)->name_th;
    $url = route('catalog.products.show', $p->id);
@endphp
<article class="card">
    <a href="{{ $url }}" class="productimg">
        @if($badge)<span class="badge">{{ $badge }}</span>@endif
        <svg role="img" aria-label="ภาพประกอบ {{ $p->name_th }}"><use href="#{{ $art }}"/></svg>
    </a>
    <div class="cardbody">
        <span class="code">{{ $p->code }}</span>
        <h3><a href="{{ $url }}">{{ $p->name_th ?: $p->name_en }}</a></h3>
        <p>{{ $p->name_en }}@if(optional($p->subCategory)->name_th)<br>{{ $p->subCategory->name_th }}@endif</p>
        <a class="cardbutton" href="{{ $url }}">ดูรายละเอียดสินค้า <span style="float:right">↗</span></a>
    </div>
</article>
