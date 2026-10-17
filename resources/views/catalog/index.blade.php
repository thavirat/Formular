@extends('catalog.layout')
@section('title', 'FORMULA — '.__('catalog.list_title'))

@section('content')
<section class="hero"><div class="wrap">
  <div>
    <div class="eyebrow">{{ __('catalog.hero_eyebrow') }}</div>
    <h1>{!! __('catalog.hero_title') !!}</h1>
    <p>{{ __('catalog.hero_desc') }}</p>
  </div>
  <div class="art"><svg role="img" aria-label="condenser"><use href="#condenser"/></svg></div>
</div></section>

<div class="wrap"><div class="searchbox">
  <form action="{{ route('catalog.products') }}" method="GET">
    <div class="fields">
      <label style="width:100%">{{ __('catalog.search_label') }}
        <input name="q" placeholder="{{ __('catalog.search_placeholder') }}">
      </label>
      <button class="primary">{{ __('catalog.search_btn') }} →</button>
    </div>
  </form>
  <p class="hint">{{ __('catalog.search_hint') }}</p>
</div></div>

<section class="section wrap" id="categories">
  <div class="sectionhead">
    <div><h2>{{ __('catalog.categories_title') }}</h2><p>{{ __('catalog.categories_desc') }}</p></div>
    <a class="textlink" href="{{ route('catalog.products') }}">{{ __('catalog.view_all') }} ↗</a>
  </div>
  <div class="cats">
    @foreach($categories as $cat)
      @php
        $en = strtoupper($cat->name_en ?? '');
        $art = 'generic';
        if (str_contains($en,'CONDENSER')) $art='condenser';
        elseif (str_contains($en,'COMPRESSOR')) $art='compressor';
        elseif (str_contains($en,'EVAPORATOR')) $art='evaporator';
        elseif (str_contains($en,'DRIER')||str_contains($en,'ACCUMULATOR')) $art='drier';
      @endphp
      <a class="cat" href="{{ route('catalog.products', ['sub' => $cat->id]) }}">
        <svg role="img" aria-label="{{ $cat->name_en }}"><use href="#{{ $art }}"/></svg>
        <span><strong>{{ $cat->catalogName() }}</strong><small>{{ number_format($cat->products_count) }} {{ __('catalog.items') }}</small></span>
      </a>
    @endforeach
  </div>
</section>

<section class="gray section"><div class="wrap">
  <div class="sectionhead"><div><div class="eyebrow">{{ __('catalog.featured_eyebrow') }}</div><h2 style="margin-top:7px">{{ __('catalog.featured_title') }}</h2></div>
    <a class="textlink" href="{{ route('catalog.products') }}">{{ __('catalog.open_catalogue') }} ↗</a></div>
  <div class="products">
    @foreach($featured as $p)
      @include('catalog._card', ['p' => $p])
    @endforeach
  </div>
</div></section>
@endsection
