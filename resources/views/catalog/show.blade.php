@extends('catalog.layout')
@section('title', ($product->name_th ?: $product->name_en).' — FORMULA')

@php
    $en = strtoupper(optional($product->subCategory)->name_en ?? '');
    $art = 'generic';
    if (str_contains($en,'CONDENSER')) $art='condenser';
    elseif (str_contains($en,'COMPRESSOR')) $art='compressor';
    elseif (str_contains($en,'EVAPORATOR')) $art='evaporator';
    elseif (str_contains($en,'DRIER')||str_contains($en,'ACCUMULATOR')) $art='drier';
    $dim = fn($v) => $v !== null && (float)$v != 0 ? rtrim(rtrim(number_format((float)$v,2),'0'),'.') : null;
@endphp

@section('content')
<div class="wrap section">
  <div class="crumb">
    <a href="{{ route('catalog.home') }}">หน้าหลัก</a> /
    <a href="{{ route('catalog.products') }}">แคตตาล็อก</a> /
    {{ $product->code }}
  </div>

  <div class="detailgrid">
    <div class="productimg"><svg role="img" aria-label="ภาพประกอบ {{ $product->name_th }}"><use href="#{{ $art }}"/></svg></div>
    <div>
      @if($product->subCategory)<span class="pill">{{ $product->subCategory->name_en ?: $product->subCategory->name_th }}</span>@endif
      <h1>{{ $product->name_th ?: $product->name_en }}</h1>
      <p class="code">FORMULA / {{ $product->code }}</p>
      @if($product->name_en && $product->name_th)<p style="color:var(--muted);font-size:13px">{{ $product->name_en }}</p>@endif

      <table>
        <tr><td style="width:160px">รหัสสินค้า</td><td>{{ $product->code }}</td></tr>
        <tr><td>หมวดหมู่</td><td>{{ optional($product->subCategory)->name_th ?: '—' }}{{ optional($product->subCategory)->name_en ? ' / '.$product->subCategory->name_en : '' }}</td></tr>
        @if($product->brand)<tr><td>ยี่ห้อ/แบรนด์</td><td>{{ $product->brand->name }}</td></tr>@endif
        @if($product->drawing)<tr><td>Drawing</td><td>{{ $product->drawing }}</td></tr>@endif
        @if($dim($product->width) || $dim($product->height) || $dim($product->length))
          <tr><td>ขนาด (กว้าง×สูง×ยาว)</td><td>{{ $dim($product->width) ?? '—' }} × {{ $dim($product->height) ?? '—' }} × {{ $dim($product->length) ?? '—' }}</td></tr>
        @endif
        @if($dim($product->weight))<tr><td>น้ำหนัก</td><td>{{ $dim($product->weight) }}</td></tr>@endif
        @if($dim($product->cube))<tr><td>ปริมาตร (CBM)</td><td>{{ $dim($product->cube) }}</td></tr>@endif
        <tr><td>เลข OE อ้างอิง</td><td>
          @forelse($product->oeNumbers as $oe){{ $oe->oe_number }}@if($oe->maker) ({{ $oe->maker }})@endif @empty<span style="color:var(--muted)">รอข้อมูลที่ยืนยันแล้ว</span>@endforelse
        </td></tr>
      </table>
    </div>
  </div>

  <h2 style="font-size:20px">รุ่นรถที่รองรับ</h2>
  @if($product->fitments->count())
    <table>
      <thead><tr><th>ยี่ห้อ</th><th>รุ่น</th><th>ปี</th><th>หมายเหตุ</th></tr></thead>
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
    <p class="notice">ยังไม่มีข้อมูลรุ่นรถที่รองรับสำหรับสินค้านี้ — จะเพิ่มเมื่อ import ข้อมูล fitment</p>
  @endif

  @if($related->count())
    <h2 style="font-size:20px;margin-top:36px">สินค้าในหมวดเดียวกัน</h2>
    <div class="products">
      @foreach($related as $p)
        @include('catalog._card', ['p' => $p])
      @endforeach
    </div>
  @endif
</div>
@endsection
