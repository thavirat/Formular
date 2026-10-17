@extends('catalog.layout')
@section('title', $product->catalogName().' — FORMULA')

@php
    $en = strtoupper(optional($product->subCategory)->name_en ?? '');
    $art = 'generic';
    if (str_contains($en,'CONDENSER')) $art='condenser';
    elseif (str_contains($en,'COMPRESSOR')) $art='compressor';
    elseif (str_contains($en,'EVAPORATOR')) $art='evaporator';
    elseif (str_contains($en,'DRIER')||str_contains($en,'ACCUMULATOR')) $art='drier';
    $dim = fn($v) => $v !== null && (float)$v != 0 ? rtrim(rtrim(number_format((float)$v,2),'0'),'.') : null;
    $name = $product->catalogName();
    $alt  = $product->catalogAltName();
@endphp

@section('content')
<div class="wrap section">
  <div class="crumb">
    <a href="{{ route('catalog.home') }}">{{ __('catalog.nav_catalog') }}</a> /
    <a href="{{ route('catalog.products') }}">FORMULA</a> /
    {{ $product->code }}
  </div>

  <div class="detailgrid">
    <div class="productimg"><svg role="img" aria-label="{{ $name }}"><use href="#{{ $art }}"/></svg></div>
    <div>
      @if($product->subCategory && $product->subCategory->catalogName())<span class="pill">{{ $product->subCategory->catalogName() }}</span>@endif
      <h1>{{ $name ?: $product->code }}</h1>
      <p class="code">FORMULA / {{ $product->code }}</p>
      @if($alt && $alt !== $name)<p style="color:var(--muted);font-size:13px">{{ $alt }}</p>@endif

      <table>
        <tr><td style="width:170px">{{ __('catalog.d_code') }}</td><td>{{ $product->code }}</td></tr>
        <tr><td>{{ __('catalog.d_category') }}</td><td>{{ optional($product->subCategory)->catalogName() ?: '—' }}</td></tr>
        @if($product->brand)<tr><td>{{ __('catalog.d_brand') }}</td><td>{{ $product->brand->name }}</td></tr>@endif
        @if($product->drawing)<tr><td>{{ __('catalog.d_drawing') }}</td><td>{{ $product->drawing }}</td></tr>@endif
        @if($dim($product->width) || $dim($product->height) || $dim($product->length))
          <tr><td>{{ __('catalog.d_dimension') }}</td><td>{{ $dim($product->width) ?? '—' }} × {{ $dim($product->height) ?? '—' }} × {{ $dim($product->length) ?? '—' }}</td></tr>
        @endif
        @if($dim($product->weight))<tr><td>{{ __('catalog.d_weight') }}</td><td>{{ $dim($product->weight) }}</td></tr>@endif
        @if($dim($product->cube))<tr><td>{{ __('catalog.d_cbm') }}</td><td>{{ $dim($product->cube) }}</td></tr>@endif
        <tr><td>{{ __('catalog.d_oe') }}</td><td>
          @forelse($product->oeNumbers as $oe){{ $oe->oe_number }}@if($oe->maker) ({{ $oe->maker }})@endif @empty<span style="color:var(--muted)">{{ __('catalog.d_oe_pending') }}</span>@endforelse
        </td></tr>
      </table>
    </div>
  </div>

  <h2 style="font-size:20px">{{ __('catalog.fitment_title') }}</h2>
  @if($product->fitments->count())
    <table>
      <thead><tr><th>{{ __('catalog.fitment_make') }}</th><th>{{ __('catalog.fitment_model') }}</th><th>{{ __('catalog.fitment_year') }}</th><th>{{ __('catalog.fitment_note') }}</th></tr></thead>
      <tbody>
        @foreach($product->fitments as $f)
          <tr>
            <td>{{ optional($f->make)->name }}</td>
            <td>{{ optional($f->model)->name ?: '—' }}</td>
            <td>{{ $f->year_start }}@if($f->year_end && $f->year_end != $f->year_start)–{{ $f->year_end }}@endif</td>
            <td>{{ $f->note }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <p class="notice">{{ __('catalog.fitment_none') }}</p>
  @endif

  @if($related->count())
    <h2 style="font-size:20px;margin-top:36px">{{ __('catalog.related_title') }}</h2>
    <div class="products">
      @foreach($related as $p)
        @include('catalog._card', ['p' => $p])
      @endforeach
    </div>
  @endif
</div>
@endsection
