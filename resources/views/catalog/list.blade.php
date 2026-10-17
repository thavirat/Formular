@extends('catalog.layout')
@section('title', 'แคตตาล็อกสินค้า — FORMULA')

@section('content')
<div class="wrap section">
  <div class="crumb"><a href="{{ route('catalog.home') }}">หน้าหลัก</a> / แคตตาล็อกสินค้า</div>
  <div class="sectionhead">
    <div>
      <div class="eyebrow">FORMULA CATALOGUE</div>
      <h1 style="font-size:28px;margin:8px 0">แคตตาล็อกอะไหล่แอร์รถยนต์</h1>
      <p class="hint" style="margin:0">พบ {{ number_format($products->total()) }} รายการ@if($q) · ค้นหา “{{ $q }}”@endif</p>
    </div>
    @if($q || $sub)<a class="textlink" href="{{ route('catalog.products') }}">ล้างตัวกรอง ↺</a>@endif
  </div>

  <form action="{{ route('catalog.products') }}" method="GET" style="margin-bottom:24px">
    <div class="fields" style="max-width:620px">
      <label style="width:100%"><input name="q" value="{{ $q }}" placeholder="ค้นหารหัส / ชื่อสินค้า"></label>
      <button class="primary">ค้นหา →</button>
    </div>
  </form>

  <div class="listing">
    <aside class="filter">
      <h3>หมวดหมู่สินค้า</h3>
      <a href="{{ route('catalog.products', array_filter(['q' => $q])) }}" class="{{ $sub ? '' : 'on' }}">สินค้าทั้งหมด</a>
      @foreach($categories as $cat)
        <a href="{{ route('catalog.products', array_filter(['q' => $q, 'sub' => $cat->id])) }}"
           class="{{ $sub === $cat->id ? 'on' : '' }}">
          {{ $cat->name_th ?: $cat->name_en }} ({{ number_format($cat->products_count) }})
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
            @if($products->onFirstPage())<span class="disabled">← ก่อนหน้า</span>
            @else<a href="{{ $products->previousPageUrl() }}">← ก่อนหน้า</a>@endif
            <span class="cur">{{ $products->currentPage() }} / {{ $products->lastPage() }}</span>
            @if($products->hasMorePages())<a href="{{ $products->nextPageUrl() }}">ถัดไป →</a>
            @else<span class="disabled">ถัดไป →</span>@endif
          </div>
        @endif
      @else
        <div class="empty">
          <h2>ไม่พบสินค้า</h2>
          <p class="hint">ลองเปลี่ยนคำค้น หรือเลือกหมวดหมู่อื่น</p>
          <a class="primary" style="display:inline-block;margin-top:12px" href="{{ route('catalog.products') }}">ดูสินค้าทั้งหมด</a>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
