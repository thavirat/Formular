@php
    /** @var \App\Models\PackingForm $packingForm */
    /** @var string $variant customer|accounting */
    $variant = $variant ?? 'customer';
    $isAccounting = $variant === 'accounting';

    $logo = null;
    if (isset($settings['logo'])) {
        $logoFiles = json_decode($settings['logo']);
        $logo = (is_array($logoFiles) && count($logoFiles) > 0) ? $logoFiles[0] : null;
    }

    $fmt = function ($v, $dec = 2) {
        if ($v === null || $v === '') {
            return '';
        }
        return number_format((float) $v, $dec);
    };
    $fmtInt = function ($v) {
        if ($v === null || $v === '') {
            return '';
        }
        return number_format((int) $v);
    };
    $markNo = function ($line) use ($fmtInt) {
        if ($line->from !== null && $line->to !== null) {
            return $fmtInt($line->from).' - '.$fmtInt($line->to);
        }
        if ($line->from_co) {
            return $line->from_co;
        }
        return '';
    };
    $customerDesc = function ($line) {
        $part = trim((string) ($line->part_no ?? ''));
        $desc = trim((string) ($line->description ?? ''));
        if ($desc === '') {
            return $part;
        }
        if ($part !== '' && strlen($desc) <= 55) {
            return $part.' '.$desc;
        }
        if ($part !== '') {
            return $part.' '.\Illuminate\Support\Str::limit($desc, 55);
        }
        return \Illuminate\Support\Str::limit($desc, 80);
    };
    /** ราคาจาก PI ที่ผูกกับแถว (pi_product_id) */
    $linePricing = function ($line) use ($fmt) {
        $pip = $line->piProduct;
        $currency = $pip?->pi?->currency;
        $unitPrice = $pip?->price_per_item;
        $amount = $pip?->total_price;
        if (($amount === null || $amount === '') && $unitPrice !== null && $unitPrice !== '' && $line->qty) {
            $amount = (float) $unitPrice * (float) $line->qty;
        }

        return [
            'symbol' => $currency?->symbol ?? '',
            'unit_price' => $unitPrice !== null && $unitPrice !== '' ? $fmt($unitPrice) : '',
            'amount' => $amount !== null && $amount !== '' ? $fmt($amount) : '',
        ];
    };
    $lineUom = function ($line) {
        $uom = trim((string) ($line->uom ?? ''));
        if ($uom === '' && $line->piProduct?->product?->unitProduct) {
            $uom = trim((string) ($line->piProduct->product->unitProduct->name ?? ''));
        }

        return $uom;
    };
    /** จำนวนรวมต่อแถว (คูณช่วงกล่อง from–to เมื่อมี) */
    $lineTotalQty = function ($line) {
        $qty = (float) ($line->qty ?? 0);
        if ($line->from !== null && $line->to !== null) {
            $packages = (int) $line->to - (int) $line->from + 1;
            if ($packages > 0) {
                return $qty * $packages;
            }
        }

        return $qty;
    };
    $qtyByUom = [];
    $sumDetailNw = 0.0;
    $sumDetailGw = 0.0;
    if (!$isAccounting) {
        foreach ($packingForm->details as $line) {
            $uom = $lineUom($line);
            if ($uom === '') {
                continue;
            }
            $qtyByUom[$uom] = ($qtyByUom[$uom] ?? 0) + $lineTotalQty($line);
            $sumDetailNw += (float) ($line->weight_nw ?? 0);
            $sumDetailGw += (float) ($line->weight_gw ?? 0);
        }
    }

    // จัดกลุ่มรายการตามหมวดสินค้า (Product Category) — คงลำดับเดิมภายในกลุ่ม
    $groups = [];
    foreach ($packingForm->details as $line) {
        $cat = $line->piProduct?->product?->category?->name_en
            ?: ($line->piProduct?->product?->category?->name ?? '');
        $key = $cat !== '' ? $cat : '__NONE__';
        $groups[$key][] = $line;
    }

    // ยอดรวมเงิน (สำหรับ Invoice)
    $sumAmount = 0.0;
    $curSymbol = '';
    if ($isAccounting) {
        foreach ($packingForm->details as $line) {
            $pip = $line->piProduct;
            if ($curSymbol === '') {
                $curSymbol = $pip?->pi?->currency?->symbol ?? '';
            }
            $amt = $pip?->total_price;
            if (($amt === null || $amt === '') && $pip?->price_per_item && $line->qty) {
                $amt = (float) $pip->price_per_item * (float) $line->qty;
            }
            $sumAmount += (float) $amt;
        }
    }

    $docTitle = $isAccounting ? 'INVOICE' : 'PACKING LIST';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>PACKING LIST {{ $packingForm->doc_no }}</title>
    <style>
        @page { header: html_plheader; }
        body {
            font-family: 'garuda', sans-serif;
            font-size: 10px;
            color: #111;
            margin: 0;
            padding: 0;
        }
        .doc-title { font-size: 15px; font-weight: bold; text-align: center; }
        .marks-block { font-weight: bold; margin: 4px 0 6px 0; line-height: 1.4; }
        .items .cat-row td { font-weight: bold; text-align: left; background: #f1f1f1; }
        .header-top {
            width: 100%;
            border-collapse: collapse;
        }
        .header-top td {
            vertical-align: top;
            padding: 0;
        }
        .header-top .col-logo {
            width: 50px;
            padding-right: 10px;
        }
        .header-top .col-company {
            padding-left: 0;
        }
        .company-logo {
            height: 35px;
            width: auto;
        }
        .company-en { font-size: 12px; font-weight: bold; }
        .company-th { font-size: 11px; }
        .company-addr { font-size: 9px; line-height: 1.35; }
        .doc-title {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            margin: 6px 0 4px;
            letter-spacing: 0.5px;
        }
        .pl-date {
            text-align: center;
            font-size: 11px;
            margin-bottom: 6px;
        }
        .header-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }
        .header-grid td {
            vertical-align: top;
            padding: 2px 4px;
            font-size: 9px;
            line-height: 1.4;
        }
        .lbl { font-weight: bold; white-space: nowrap; }
        .items {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }
        .items th,
        .items td {
            border: 1px solid #000;
            vertical-align: top;
        }
        .items th {
            padding: 4px 3px;
            text-align: center;
            font-weight: bold;
        }
        .items td {
            padding: 3px 3px;
        }
        /* ตารางซ้อนในเซลล์ (เช่น UNIT PRICE) ไม่ต้องมีเส้น */
        .items td table td,
        .items td table th {
            border: none !important;
            padding: 0;
        }
        /* คอลัมน์ย่อย QUANTITY (จำนวน | หน่วย) รวมเป็นช่องเดียว ไม่มีเส้นแบ่งกลาง */
        .items .col-qty-num { border-right: none; }
        .items .col-qty-uom { border-left: none; }
        .items tfoot td {
            font-weight: bold;
        }
        .items .mark-row td {
            border-bottom: none;
            padding-bottom: 1px;
        }
        .items .desc-row td {
            padding-top: 0;
            padding-bottom: 5px;
            font-size: {{ $isAccounting ? '8px' : '9px' }};
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .from-co-row td {
            font-weight: bold;
            padding-top: 6px;
            padding-bottom: 4px;
        }
        .totals {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
            font-size: 9px;
        }
        .totals td {
            border: 1px solid #999;
            padding: 4px 6px;
        }
    </style>
</head>
<body>

<htmlpageheader name="plheader">
<table width="100%" class="header-top" cellpadding="0" cellspacing="0" style="margin-bottom: 6px;">
    <tr>
        @if($logo)
        <td class="col-logo" width="50" valign="top">
            <img src="{{ asset('uploads/SettingSystem/'.$logo) }}" alt="" class="company-logo" height="40">
        </td>
        @endif
        <td class="col-company" valign="top">
            <span class="company-en">FORMULA INTERTRADE CO.,LTD.</span><br>
            <span class="company-th">บริษัท ฟอร์มูล่า อินเตอร์เทรด จำกัด</span><br>
            <span class="company-addr">119 MOTORWAY ROAD THAP CHANG, SAPHAN SUNG, BANGKOK 10250, THAILAND.</span>
        </td>
        <td width="130" valign="top" class="text-right company-addr">
            TEL. : 063-525-2242<br>
            FAX. : -
        </td>
    </tr>
</table>

<table width="100%" style="margin-bottom:4px;">
    <tr>
        <td width="78%" class="doc-title">{{ $docTitle }}</td>
        <td width="22%" class="text-right">PAGE {PAGENO} / {nbpg}</td>
    </tr>
</table>

<table class="header-grid">
    <tr>
        <td width="55%">
            <span class="lbl">By order and for account of :</span><br>
            <span class="text-bold">{{ $packingForm->customer_name ?: '-' }}</span><br>
            @if($packingForm->customer_address)
                {!! nl2br(e($packingForm->customer_address)) !!}<br>
            @elseif($packingForm->to)
                {{ $packingForm->to }}<br>
            @endif
            @if($packingForm->customer_phone)
                TEL:{{ $packingForm->customer_phone }}
            @endif
            @if($packingForm->country)
                <br>{{ $packingForm->country }}
            @endif
        </td>
        <td width="45%">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="lbl" width="38%">{{ $packingForm->place_of_issue ?: 'Bangkok' }}</td>
                    <td>{{ $packingForm->doc_date ? $packingForm->doc_date->format('d/m/Y') : '' }}</td>
                </tr>
                <tr>
                    <td class="lbl">Invoice No.</td>
                    <td>{{ $packingForm->invoice_no ?: $packingForm->doc_no ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="lbl">Sailing on/about</td>
                    <td>{{ $packingForm->sailing_date ? $packingForm->sailing_date->format('d/m/Y') : '-' }}</td>
                </tr>
                <tr>
                    <td class="lbl">Shipped from</td>
                    <td>{{ $packingForm->shipped_from ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="lbl">Per</td>
                    <td>{{ $packingForm->per_vessel ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="lbl">L/C No.</td>
                    <td>{{ $packingForm->lc_no ?: '' }}</td>
                </tr>
                <tr>
                    <td class="lbl">Issued by</td>
                    <td>{{ $packingForm->issued_by ?: '' }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</htmlpageheader>

<table class="items{{ !$isAccounting && count($qtyByUom) > 0 ? ' items-has-foot' : '' }}">
    <thead>
        @if($isAccounting)
        <tr>
            <th width="12%">Mark&amp;No.</th>
            <th>DESCRIPTIONS</th>
            <th width="12%">QUANTITY</th>
            <th width="13%">UNIT PRICE</th>
            <th width="13%">AMOUNT</th>
        </tr>
        @else
        <tr>
            <th width="12%">Mark&amp;No.</th>
            <th>DESCRIPTIONS</th>
            <th colspan="2" width="15%">QUANTITY</th>
            <th width="9%">TOTAL QTY.</th>
            <th width="10%">N.W.(KGS.)</th>
            <th width="10%">G.W.(KGS.)</th>
        </tr>
        @endif
    </thead>
    <tbody>
        @php $k = 0; @endphp
        @if(trim((string) $packingForm->marks) !== '' || $packingForm->pkg)
        <tr>
            <td style="vertical-align:top; font-weight:bold;">{!! nl2br(e(trim((string) $packingForm->marks))) !!}</td>
            <td colspan="{{ $isAccounting ? 4 : 6 }}" style="vertical-align:bottom;">
                @if($packingForm->pkg)(TOTAL NO. OF PACKAGES : {{ number_format($packingForm->pkg) }} CARTONS)@endif
            </td>
        </tr>
        @endif
        @forelse($groups as $catKey => $catLines)
            @if($catKey !== '__NONE__')
            <tr class="cat-row"><td colspan="{{ $isAccounting ? 5 : 7 }}">{{ $catKey }}</td></tr>
            @endif
            @foreach($catLines as $line)
            @php($price = $linePricing($line)) @php($k++)
            <tr>
                <td align="center">{{ $isAccounting ? $k : $markNo($line) }}</td>
                <td>
                    @if($isAccounting)
                        {{ trim(($line->part_no ?? '').' '.($line->description ?? '')) }}
                    @else
                        {{ $customerDesc($line) }}
                    @endif
                </td>
                @if($isAccounting)
                <td class="text-center">{{ $line->qty }}</td>
                <td>
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="32%">{{ $price['symbol'] }}</td>
                            <td class="text-right" style="white-space:nowrap;">{{ $price['unit_price'] }}</td>
                        </tr>
                    </table>
                </td>
                <td class="text-right" style="white-space:nowrap;">{{ $price['amount'] }}</td>
                @else
                <td class="text-right col-qty-num" width="8%">
                    @if($line->qty !== null && $line->qty !== '')
                        {{ $fmtInt($line->qty) }}
                    @endif
                </td>
                <td class="text-center col-qty-uom" width="8%">{{ $lineUom($line) }}</td>
                <td class="text-right">{{ $fmtInt($lineTotalQty($line)) }}</td>
                <td class="text-right">{{ $fmt($line->weight_nw) }}</td>
                <td class="text-right">{{ $fmt($line->weight_gw) }}</td>
                @endif
            </tr>
            @endforeach
        @empty
            <tr>
                <td colspan="{{ $isAccounting ? 5 : 7 }}" class="text-center">ไม่มีรายการ</td>
            </tr>
        @endforelse
    </tbody>
    @if(!$isAccounting && count($qtyByUom) > 0)
    <tfoot>
        <tr class="row-qty-summary">
            <td colspan="2"></td>
            <td colspan="2" class="text-right" style="line-height: 1.5;">
                @foreach($qtyByUom as $uom => $total)
                    {{ $fmtInt($total) }} {{ $uom }}@if(!$loop->last)<br>@endif
                @endforeach
            </td>
            <td colspan="3"></td>
        </tr>
        <tr class="row-total">
            <td colspan="5" class="text-right">TOTAL</td>
            <td class="text-right">{{ $fmt($sumDetailNw) }}</td>
            <td class="text-right">{{ $fmt($sumDetailGw) }}</td>
        </tr>
    </tfoot>
    @endif
</table>

@if($isAccounting)
<table width="100%" style="margin-top:6px;">
    <tr>
        <td class="text-bold">
            SAY {{ \App\Help::numberToWords($sumAmount, ($curSymbol === 'USD' ? 'US DOLLARS' : strtoupper($curSymbol ?: 'DOLLARS'))) }} ONLY.
        </td>
        <td width="22%" class="text-right text-bold">TOTAL</td>
        <td width="16%" class="text-right text-bold">{{ $curSymbol }} {{ $fmt($sumAmount) }}</td>
    </tr>
</table>
@else
<table class="totals">
    <tr>
        <td width="14%" class="text-bold text-center">PKG</td>
        <td width="14%" class="text-bold text-center">N.W.</td>
        <td width="14%" class="text-bold text-center">G.W.</td>
        <td width="14%" class="text-bold text-center">N.T.</td>
        <td width="14%" class="text-bold text-center">G.T.</td>
        <td width="14%" class="text-bold text-center">CBM</td>
        <td width="16%" class="text-bold text-center">QTY</td>
    </tr>
    <tr>
        <td class="text-center">{{ $fmtInt($packingForm->pkg) }}</td>
        <td class="text-center">{{ $fmt($packingForm->weight_nw) }}</td>
        <td class="text-center">{{ $fmt($packingForm->weight_gw) }}</td>
        <td class="text-center">{{ $fmt($packingForm->weight_nt) }}</td>
        <td class="text-center">{{ $fmt($packingForm->weight_gt) }}</td>
        <td class="text-center">{{ $fmt($packingForm->cubic_meter) }}</td>
        <td class="text-center">{{ $fmtInt($packingForm->qty) }}</td>
    </tr>
</table>
@endif

</body>
</html>
