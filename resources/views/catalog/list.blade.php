@extends('catalog.layout')
@section('title', __('catalog.list_title').' — FORMULA')

@section('content')
<div class="wrap section">
  <div class="crumb"><a href="{{ route('catalog.home') }}">{{ __('catalog.nav_catalog') }}</a></div>
  <div class="sectionhead">
    <div>
      <div class="eyebrow">FORMULA CATALOGUE</div>
      <h1 style="font-size:28px;margin:8px 0">{{ __('catalog.list_title') }}</h1>
      <p class="hint" style="margin:0">{{ __('catalog.found', ['count' => number_format($products->total())]) }}@if($q) · {{ __('catalog.for_query', ['q' => $q]) }}@endif</p>
    </div>
    @if($q || $sub)<a class="textlink" href="{{ route('catalog.products') }}">{{ __('catalog.clear_filter') }} ↺</a>@endif
  </div>

  <form action="{{ route('catalog.products') }}" method="GET" style="margin-bottom:24px">
    <div class="fields" style="max-width:620px">
      <label style="width:100%"><input name="q" value="{{ $q }}" placeholder="{{ __('catalog.search_short') }}"></label>
      <button class="primary">{{ __('catalog.search_btn') }} →</button>
    </div>
  </form>

  <div class="listing">
    <aside class="filter">
      <h3>{{ __('catalog.filter_heading') }}</h3>
      <a href="{{ route('catalog.products', array_filter(['q' => $q])) }}" class="{{ $sub ? '' : 'on' }}">{{ __('catalog.all_products') }}</a>
      @foreach($categories as $cat)
        <a href="{{ route('catalog.products', array_filter(['q' => $q, 'sub' => $cat->id])) }}"
           class="{{ $sub === $cat->id ? 'on' : '' }}">
          {{ $cat->catalogName() }} ({{ number_format($cat->products_count) }})
        </a>
      @endforeach
    </aside>

    <div>
      @if($products->count())
        <div class="products">
          @foreach($products as $p)
            @include('catalog._card', ['p' => $p])
          @endforeach
        </div>

        @if($products->hasPages())
          <div class="pager">
            @if($products->onFirstPage())<span class="disabled">← {{ __('catalog.prev') }}</span>
            @else<a href="{{ $products->previousPageUrl() }}">← {{ __('catalog.prev') }}</a>@endif
            <span class="cur">{{ $products->currentPage() }} / {{ $products->lastPage() }}</span>
            @if($products->hasMorePages())<a href="{{ $products->nextPageUrl() }}">{{ __('catalog.next') }} →</a>
            @else<span class="disabled">{{ __('catalog.next') }} →</span>@endif
          </div>
        @endif
      @else
        <div class="empty">
          <h2>{{ __('catalog.empty_title') }}</h2>
          <p class="hint">{{ __('catalog.empty_hint') }}</p>
          <a class="primary" style="display:inline-block;margin-top:12px" href="{{ route('catalog.products') }}">{{ __('catalog.empty_btn') }}</a>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
