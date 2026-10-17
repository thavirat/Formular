@extends('catalog.layout')
@section('title', 'FORMULA — แคตตาล็อกอะไหล่แอร์รถยนต์')

@section('content')
<section class="hero"><div class="wrap">
  <div>
    <div class="eyebrow">THE RIGHT PART. THE RIGHT FIT.</div>
    <h1>ค้นหาอะไหล่แอร์รถยนต์<br>Formula ได้ในที่เดียว</h1>
    <p>ค้นหาสินค้าจากรหัสสินค้าหรือชื่อ และเลือกดูตามหมวดหมู่อะไหล่ระบบปรับอากาศรถยนต์</p>
  </div>
  <div class="art"><svg role="img" aria-label="ภาพประกอบคอนเดนเซอร์"><use href="#condenser"/></svg></div>
</div></section>

<div class="wrap"><div class="searchbox">
  <form action="{{ route('catalog.products') }}" method="GET">
    <div class="fields">
      <label style="width:100%">ค้นหาด้วยรหัสสินค้า หรือชื่อสินค้า
        <input name="q" placeholder="เช่น 5205-0302-00 หรือ คอนเดนเซอร์ หรือ COMPRESSOR">
      </label>
      <button class="primary">ค้นหาสินค้า →</button>
    </div>
  </form>
  <p class="hint">ค้นจากข้อมูลสินค้าจริงในระบบ · หรือเลือกตามหมวดหมู่ด้านล่าง</p>
</div></div>

<section class="section wrap" id="categories">
  <div class="sectionhead">
    <div><h2>เลือกตามหมวดหมู่สินค้า</h2><p>สำรวจชิ้นส่วนในระบบปรับอากาศรถยนต์</p></div>
    <a class="textlink" href="{{ route('catalog.products') }}">ดูสินค้าทั้งหมด ↗</a>
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
        <span><strong>{{ $cat->name_th ?: $cat->name_en }}</strong><small>{{ $cat->name_en }} · {{ number_format($cat->products_count) }} รายการ</small></span>
      </a>
    @endforeach
  </div>
</section>

<section class="gray section"><div class="wrap">
  <div class="sectionhead"><div><div class="eyebrow">FEATURED</div><h2 style="margin-top:7px">สินค้าตัวอย่างในระบบ</h2></div>
    <a class="textlink" href="{{ route('catalog.products') }}">เปิดแคตตาล็อก ↗</a></div>
  <div class="products">
    @foreach($featured as $p)
      @include('catalog._card', ['p' => $p])
    @endforeach
  </div>
</div></section>
@endsection
