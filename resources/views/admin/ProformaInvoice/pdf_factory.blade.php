@php
    use App\Help;

    /* ---------- โลโก้ (ดึงจาก Setting เดิม) ---------- */
    $logo = null;
    if (isset($settings['logo'])) {
        $logo = json_decode($settings['logo']);
        $logo = (is_array($logo) && count($logo) > 0 ? $logo[0] : null);
    }

    /* ---------- เตรียมข้อมูล ---------- */

    // สกุลเงินที่โชว์ในคอลัมน์ เช่น (USD)
    $cur = optional($pi->currency)->symbol ?: (optional($pi->currency)->name ?: '');

    // ป้ายหน่วยของแต่ละรายการ (ดึงจากสินค้า) ใช้ทำหัวคอลัมน์ QUANTITY และยอดรวมแยกหน่วย
    $unitOf = function ($item) {
        return optional($item->product)->unit
            ?: (optional(optional($item->product)->unitProduct)->name ?: 'PCS');
    };

    // จัดกลุ่มรายการตามหมวดสินค้า (Product Category) โดยคงลำดับ seq
    $groups = [];
    foreach ($pi->Products as $item) {
        $cat = optional(optional($item->product)->category)->name_en
            ?: (optional(optional($item->product)->category)->name ?: 'OTHER');
        $groups[$cat][] = $item;
    }

    // รวมจำนวนแยกตามหน่วย เช่น 4,619 PCS / 43 UNITS
    $unitTotals = [];
    foreach ($pi->Products as $item) {
        $u = $unitOf($item);
        $unitTotals[$u] = ($unitTotals[$u] ?? 0) + (float) $item->qty;
    }

    $subtotal = $pi->subtotal ?: $pi->Products->sum('total_price');
    $total    = $pi->total ?: $subtotal;

    // ผู้ลงนามฝ่ายส่งออก
    $issuer = optional($pi->createdBy)->name ?: '';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>PROFORMA INVOICE {{ $pi->doc_no }}</title>
    <style>
        @page {
            margin: 58mm 8mm 8mm 8mm;   /* บน ขวา ล่าง ซ้าย — เว้นบนไว้ให้หัวเอกสารที่ซ้ำทุกหน้า */
            header: html_docheader;
        }

        body {
            font-family: 'garuda', sans-serif;
            font-size: 11px;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .text-center { text-align: center; }
        .text-right  { text-align: right; }
        .text-left   { text-align: left; }
        .bold        { font-weight: bold; }
        .underline   { text-decoration: underline; }

        /* ---------- หัวเอกสาร ---------- */
        .company-name { font-size: 18px; font-weight: bold; }
        .company-addr { font-size: 11px; }
        .doc-title    { font-size: 15px; font-weight: bold; text-decoration: underline; }

        .head-box { width: 100%; border-collapse: collapse; }
        .head-box td { border: 1px solid #000; vertical-align: top; padding: 4px 6px; }

        /* ---------- ตารางรายการ ---------- */
        .items { width: 100%; border-collapse: collapse; font-size: 11px; border-top: 1px solid #000; border-bottom: 1px solid #000; }
        /* หัวตาราง: เส้นครบทุกด้าน (แนวตั้งทุกคอลัมน์ + เส้นบน + เส้นล่างหัวตาราง) */
        .items th { border: 1px solid #000; padding: 2px 4px; text-align: center; vertical-align: middle; font-weight: bold; }
        /* เนื้อหา: ไม่มีเส้นระหว่างบรรทัด, เส้นแนวตั้งเฉพาะคอลัมน์ที่ใส่ class .vline */
        .items td { padding: 2px 4px; }
        .items td.vline { border-left: 1px solid #000; border-right: 1px solid #000; }
        .items td.text-right { white-space: nowrap; }
        .cat-row td { font-weight: bold; text-align: left; border-left: 1px solid #000; border-right: 1px solid #000; }

        /* ---------- ท้ายเอกสาร ---------- */
        .terms { width: 100%; font-size: 11px; margin-top: 4px; }
        .terms td { padding: 1px 2px; vertical-align: top; }
        .sign-box { width: 100%; border-collapse: collapse; margin-top: 6px; font-size: 11px; }
        .sign-box td { border: 1px solid #000; padding: 6px 8px; vertical-align: top; height: 70px; }
    </style>
</head>
<body>

{{-- ===================== หัวเอกสาร (ซ้ำทุกหน้า) ===================== --}}
<htmlpageheader name="docheader">
    <table width="100%" style="border:none;">
        <tr>
            <td width="75%" class="text-center" style="border:none;">
                @if ($logo)
                    <img src="{{ asset('uploads/SettingSystem/'.$logo) }}" height="38" style="vertical-align:middle;">
                @endif
                <span class="company-name">FORMULA INTERTRADE CO.,LTD.</span>
            </td>
            <td width="25%" style="border:none; font-size:11px;">
                TEL.&nbsp;&nbsp;&nbsp;: 063-525-2242<br>
                FAX.&nbsp;&nbsp;&nbsp;: -
            </td>
        </tr>
        <tr>
            <td colspan="2" class="text-center company-addr" style="border:none;">
                119 MOTORWAY ROAD THAP CHANG, SAPHAN SUNG, BANGKOK 10250, THAILAND.
            </td>
        </tr>
    </table>

    <div class="text-center doc-title" style="margin:4px 0 6px 0;">
        PROFORMA INVOICE NO. {{ $pi->doc_no }}
    </div>

    <table class="head-box">
        <tr>
            <td width="42%">
                <span class="bold">Sold to</span><br>
                {{ $pi->company_name }}<br>
                {!! nl2br(e($pi->address)) !!}
            </td>
            <td width="33%">
                <span class="bold">SHIPPING MARKS</span><br>
                {{-- ป้ายมาร์ค: ใช้รหัส ship_to_code ถ้ามี ไม่งั้นใช้ชื่อย่อจากชื่อบริษัท --}}
                {{ $pi->ship_to_code ?: \Illuminate\Support\Str::before($pi->company_name, ' ') }}<br>
                C/NO.1-UP {{-- hardcode ไว้ก่อน --}}
            </td>
            <td width="25%">
                PAGE&nbsp;&nbsp;{PAGENO}/{nbpg}<br><br>
                DATE :&nbsp;{{ $pi->doc_date ? \Carbon\Carbon::parse($pi->doc_date)->format('d/m/Y') : '' }}<br><br>
                REF. P/O&nbsp;: {{ $pi->customer_po }}
            </td>
        </tr>
    </table>
</htmlpageheader>

{{-- ===================== ตารางรายการสินค้า ===================== --}}
<table class="items">
    <thead>
        <tr>
            <th width="4%">ITM</th>
            <th width="13%">FORMULA P/NO.</th>
            <th width="29%">DESCRIPTION</th>
            <th width="9%">DRAWING</th>
            <th width="11%">CUSTOMER P/NO.</th>
            <th width="11%">QUANTITY</th>
            <th width="10%">UNIT PRICE</th>
            <th width="14%">AMOUNT</th>
        </tr>
    </thead>
    <tbody>
        @php $itm = 0; @endphp
        @forelse($groups as $catName => $items)
            <tr class="cat-row">
                <td></td>
                <td colspan="4">{{ $catName }}</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            @foreach($items as $item)
                @php $itm++; @endphp
                <tr>
                    <td class="text-center vline">{{ $itm }}</td>
                    <td>{{ $item->part_no ?: optional($item->product)->code }}</td>
                    <td>{{ $item->detail_eng }}</td>
                    <td class="text-center">{{ $item->drawing }}</td>
                    <td class="text-center">{{ $item->cus_code }}</td>
                    <td class="text-right vline">{{ number_format($item->qty, 0) }} {{ $unitOf($item) }}</td>
                    <td class="vline">
                        <table width="100%" style="border:none; border-collapse:collapse;"><tr>
                            <td style="border:none; padding:0;" class="text-left">{{ $cur }}</td>
                            <td style="border:none; padding:0;" class="text-right">{{ number_format($item->price_per_item, 2) }}</td>
                        </tr></table>
                    </td>
                    <td class="vline">
                        <table width="100%" style="border:none; border-collapse:collapse;"><tr>
                            <td style="border:none; padding:0;" class="text-left">{{ $cur }}</td>
                            <td style="border:none; padding:0;" class="text-right">{{ number_format($item->total_price, 2) }}</td>
                        </tr></table>
                    </td>
                </tr>
            @endforeach
        @empty
            <tr><td colspan="8" class="text-center" style="padding:15px;">ไม่มีรายการสินค้า</td></tr>
        @endforelse

        {{-- ยอดรวมจำนวนแยกตามหน่วย --}}
        @foreach($unitTotals as $unit => $sumQty)
            <tr>
                <td class="vline"></td>
                <td class="text-center bold" colspan="4">********** TOTAL **********</td>
                <td class="text-center bold vline">{{ number_format($sumQty, 0) }} {{ $unit }}</td>
                @if($loop->first)
                    <td class="text-right bold vline">SUBTOTAL</td>
                    <td class="bold vline">
                        <table width="100%" style="border:none; border-collapse:collapse;"><tr>
                            <td style="border:none; padding:0;" class="text-left bold">{{ $cur }}</td>
                            <td style="border:none; padding:0;" class="text-right bold">{{ number_format($subtotal, 2) }}</td>
                        </tr></table>
                    </td>
                @else
                    <td class="vline"></td>
                    <td class="vline"></td>
                @endif
            </tr>
        @endforeach

        {{-- ===================== TOTAL (แถวสุดท้าย ปิดท้ายด้วยเส้นล่าง) ===================== --}}
        <tr>
            <td colspan="5" class="bold" style="border-left:1px solid #000; border-bottom:1px solid #000;">TOTAL :&nbsp;( {{ Help::numberToWords($total, 'DOLLARS') }} )</td>
            <td class="vline" style="border-bottom:1px solid #000;"></td>
            <td class="text-right bold vline" style="border-bottom:1px solid #000;">TOTAL</td>
            <td class="bold vline" style="border-bottom:1px solid #000;">
                <table width="100%" style="border:none; border-collapse:collapse;"><tr>
                    <td style="border:none; padding:0;" class="text-left bold">{{ $cur }}</td>
                    <td style="border:none; padding:0;" class="text-right bold">{{ number_format($total, 2) }}</td>
                </tr></table>
            </td>
        </tr>
    </tbody>
</table>

<div class="bold underline" style="margin-top:6px;">TERM &amp; CONDITIONS</div>
<table class="terms">
    <tr>
        <td width="14%" class="bold">PRICE</td>
        <td>: {{ optional($pi->incoterm)->code }} SINGAPORE {{-- ปลายทาง hardcode ไว้ก่อน --}}</td>
    </tr>
    <tr>
        <td class="bold">PAYMENT</td>
        <td>: {{ optional($pi->creditPayment)->name }}</td>
    </tr>
    <tr>
        <td class="bold">SHIPMENT</td>
        <td>: BY [&nbsp;&nbsp;] SEAFREIGHT&nbsp;&nbsp;&nbsp;[&nbsp;&nbsp;] AIRFREIGHT &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TO _______________</td>
    </tr>
    <tr>
        <td class="bold">REMARKS</td>
        <td>: {{ $pi->ship_remark }}</td>
    </tr>
</table>

{{-- ===================== ช่องลงนาม ===================== --}}
<table class="sign-box">
    <tr>
        <td width="50%">
            <span class="bold">CONFIRMED AND ACCEPTED BY</span>
            <div style="margin-top:24px;">MANAGER&nbsp;________________________</div>
            <div style="margin-top:6px;">DATE :&nbsp;________________________</div>
        </td>
        <td width="50%" class="text-center">
            <span class="bold">{{ trim(optional($pi->createdBy)->firstname . ' ' . optional($pi->createdBy)->lastname) }}</span>
            <div style="margin-top:30px;">________________________</div>
            <div class="bold">{{ $issuer }}</div>
            <div>EXPORT DIVISION</div>
        </td>
    </tr>
</table>
<div style="font-size:10px; margin-top:2px;" class="bold">
    PLS CONFIRM WITH YR SIGNATURE &amp; COMPANY'S CHOPS BY RETURN COPY
</div>

</body>
</html>
