@php
    $en = strtoupper(optional($p->subCategory)->name_en ?? '');
    $art = 'generic';
    if (str_contains($en, 'CONDENSER')) $art = 'condenser';
    elseif (str_contains($en, 'COMPRESSOR')) $art = 'compressor';
    elseif (str_contains($en, 'EVAPORATOR')) $art = 'evaporator';
    elseif (str_contains($en, 'DRIER') || str_contains($en, 'ACCUMULATOR')) $art = 'drier';
    $badge = optional($p->subCategory)->catalogName();
    $name = $p->catalogName();
    $alt  = $p->catalogAltName();
    $url = route('catalog.products.show', $p->id);
@endphp
<article class="card">
    <a href="{{ $url }}" class="productimg">
        @if($badge)<span class="badge">{{ $badge }}</span>@endif
        <svg role="img" aria-label="{{ $name }}"><use href="#{{ $art }}"/></svg>
    </a>
    <div class="cardbody">
        <span class="code">{{ $p->code }}</span>
        <h3><a href="{{ $url }}">{{ $name ?: $p->code }}</a></h3>
        <p>@if($alt && $alt !== $name){{ $alt }}@endif</p>
        <a class="cardbutton" href="{{ $url }}">{{ __('catalog.view_detail') }} <span style="float:right">↗</span></a>
    </div>
</article>
