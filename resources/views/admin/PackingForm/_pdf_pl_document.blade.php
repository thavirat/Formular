@php
    /** @var \App\Models\PackingForm $packingForm */
    /** @var string $variant customer|accounting */
    $variant = $variant ?? 'customer';
    $isAccounting = $variant === 'accounting';

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
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>PACKING LIST {{ $packingForm->doc_no }}</title>
    <style>
        @page { margin: 10mm 8mm 12mm 8mm; }
        body {
            font-family: 'garuda', sans-serif;
            font-size: 10px;
            color: #111;
            margin: 0;
            padding: 0;
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
        .items th {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding: 4px 3px;
            text-align: center;
            font-weight: bold;
        }
        .items td {
            padding: 3px 3px;
            vertical-align: top;
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

<table width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td width="70%">
            <span class="company-en">FORMULA INTERTRADE CO.,LTD.</span><br>
            <span class="company-th">บริษัท ฟอร์มูล่า อินเตอร์เทรด จำกัด</span><br>
            <span class="company-addr">119 MOTORWAY ROAD THAP CHANG, SAPHAN SUNG, BANGKOK 10250, THAILAND.</span>
        </td>
        <td width="30%" class="text-right company-addr">
            TEL. : 063-525-2242<br>
            FAX. : -
        </td>
    </tr>
</table>

<table class="header-grid">
    <tr>
        <td width="55%">
            <span class="lbl">BY order and for account of :</span>
            {{ $packingForm->place_of_issue ?: 'Bangkok' }}<br>
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
                    <td class="lbl" width="38%">Invoice No.</td>
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

<div class="doc-title">PACKING LIST</div>
<div class="pl-date">{{ $packingForm->doc_date ? $packingForm->doc_date->format('d/m/Y') : '' }}</div>

<table class="items">
    <thead>
        <tr>
            <th width="14%">Mark&amp;No.</th>
            <th width="18%">DESCRIPTIONS</th>
            <th width="14%">QUANTITY</th>
            <th width="14%">TOTAL QTY.</th>
            <th width="14%">N.W.(KGS.)</th>
            <th width="14%">G.W.(KGS.)</th>
        </tr>
    </thead>
    <tbody>
        @forelse($packingForm->details as $line)
            @if($line->from_co && $line->from === null && $line->to === null && !$line->part_no)
                <tr class="from-co-row">
                    <td colspan="6">{{ $line->from_co }}</td>
                </tr>
                @continue
            @endif
            <tr class="mark-row">
                <td>{{ $markNo($line) }}</td>
                <td></td>
                <td class="text-center">
                    @if($line->qty && $line->uom)
                        {{ $fmtInt($line->qty) }} {{ $line->uom }}
                    @elseif($line->uom)
                        {{ $line->uom }}
                    @endif
                </td>
                <td class="text-center">{{ $fmtInt($line->qty) }}</td>
                <td class="text-right">{{ $fmt($line->weight_nw) }}</td>
                <td class="text-right">{{ $fmt($line->weight_gw) }}</td>
            </tr>
            <tr class="desc-row">
                <td></td>
                <td colspan="5">
                    @if($isAccounting)
                        {!! nl2br(e($line->description ?: $line->part_no)) !!}
                        @if($line->part_no && $line->description)
                            <br><span style="font-size:8px;">{{ $line->part_no }}</span>
                        @endif
                    @else
                        {{ $customerDesc($line) }}
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">ไม่มีรายการ</td>
            </tr>
        @endforelse
    </tbody>
</table>

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

</body>
</html>
