<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Packing List {{ $packingForm->doc_no }}</title>
    <style>
        @page {
            margin: 8mm 6mm 10mm 6mm;
        }
        body {
            font-family: 'garuda', sans-serif;
            font-size: 9px;
            color: #222;
            margin: 0;
            padding: 0;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .text-bold { font-weight: bold; }
        .company-name {
            font-size: 14px;
            font-weight: bold;
        }
        .doc-title {
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 1px;
            margin: 8px 0 10px;
        }
        .header-info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .header-info td {
            padding: 2px 4px;
            vertical-align: top;
        }
        .label {
            color: #444;
            font-weight: bold;
            width: 70px;
            display: inline-block;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
        }
        .items-table th {
            background: #2c3e50;
            color: #fff;
            border: 1px solid #2c3e50;
            padding: 4px 2px;
            text-align: center;
            vertical-align: middle;
        }
        .items-table td {
            border: 1px solid #bbb;
            padding: 3px 2px;
            vertical-align: top;
        }
        .items-table tbody tr:nth-child(even) {
            background: #f7f7f7;
        }
        .totals-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 9px;
        }
        .totals-table th,
        .totals-table td {
            border: 1px solid #999;
            padding: 5px 6px;
        }
        .totals-table th {
            background: #ecf0f1;
            text-align: center;
        }
        .nowrap { white-space: nowrap; }
    </style>
</head>
<body>
@php
    $fmt = function ($v, $dec = 2) {
        if ($v === null || $v === '') {
            return '-';
        }
        return number_format((float) $v, $dec);
    };
    $fmtInt = function ($v) {
        if ($v === null || $v === '') {
            return '-';
        }
        return number_format((int) $v);
    };
@endphp

<table width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td class="company-name">FORMULA INTERTRADE CO., LTD.</td>
        <td class="text-right text-bold">PACKING LIST</td>
    </tr>
    <tr>
        <td colspan="2" class="text-center doc-title">PACKING LIST</td>
    </tr>
</table>

<table class="header-info">
    <tr>
        <td width="50%">
            <span class="label">TO :</span> {{ $packingForm->to ?: '-' }}<br>
            <span class="label">CUSTOMER :</span> {{ $packingForm->customer_name ?: '-' }}<br>
            <span class="label">COUNTRY :</span> {{ $packingForm->country ?: '-' }}
        </td>
        <td width="50%" class="text-right">
            <span class="label">DOC NO. :</span> {{ $packingForm->doc_no }}<br>
            <span class="label">DATE :</span>
            {{ $packingForm->doc_date ? $packingForm->doc_date->format('d/m/Y') : '-' }}
        </td>
    </tr>
</table>

<table class="items-table">
    <thead>
        <tr>
            <th width="3%">#</th>
            <th width="4%">From</th>
            <th width="4%">To</th>
            <th width="9%">PI No.</th>
            <th width="9%">Part No.</th>
            <th width="8%">Cus Part</th>
            <th width="14%">Description</th>
            <th width="7%">Formular</th>
            <th width="3%">W</th>
            <th width="3%">L</th>
            <th width="3%">H</th>
            <th width="4%">Qty</th>
            <th width="5%">CBM</th>
            <th width="5%">N.W.</th>
            <th width="5%">G.W.</th>
            <th width="4%">N.T.</th>
            <th width="4%">G.T.</th>
            <th width="4%">UOM</th>
            <th width="5%">From CO</th>
        </tr>
    </thead>
    <tbody>
        @forelse($packingForm->details as $i => $line)
            @php
                $piDoc = $line->piProduct && $line->piProduct->pi ? $line->piProduct->pi->doc_no : '';
            @endphp
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td class="text-center">{{ $line->from ?? '-' }}</td>
                <td class="text-center">{{ $line->to ?? '-' }}</td>
                <td class="nowrap">{{ $piDoc ?: '-' }}</td>
                <td>{{ $line->part_no ?: '-' }}</td>
                <td>{{ $line->cus_part_no ?: '-' }}</td>
                <td>{{ $line->description ?: '-' }}</td>
                <td class="text-center">{{ $line->formular_number ?: '-' }}</td>
                <td class="text-right">{{ $fmt($line->width) }}</td>
                <td class="text-right">{{ $fmt($line->lenght) }}</td>
                <td class="text-right">{{ $fmt($line->height) }}</td>
                <td class="text-right">{{ $fmtInt($line->qty) }}</td>
                <td class="text-right">{{ $fmt($line->cubic_meter) }}</td>
                <td class="text-right">{{ $fmt($line->weight_nw) }}</td>
                <td class="text-right">{{ $fmt($line->weight_gw) }}</td>
                <td class="text-right">{{ $fmt($line->weight_nt) }}</td>
                <td class="text-right">{{ $fmt($line->weight_gt) }}</td>
                <td class="text-center">{{ $line->uom ?: '-' }}</td>
                <td class="text-center">{{ $line->from_co ?: '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="18" class="text-center">ไม่มีรายการ</td>
            </tr>
        @endforelse
    </tbody>
</table>

<table class="totals-table">
    <tr>
        <th width="14%">PKG</th>
        <th width="14%">N.W.</th>
        <th width="14%">G.W.</th>
        <th width="14%">N.T.</th>
        <th width="14%">G.T.</th>
        <th width="14%">CBM</th>
        <th width="16%">QTY</th>
    </tr>
    <tr>
        <td class="text-center text-bold">{{ $fmtInt($packingForm->pkg) }}</td>
        <td class="text-center text-bold">{{ $fmt($packingForm->weight_nw) }}</td>
        <td class="text-center text-bold">{{ $fmt($packingForm->weight_gw) }}</td>
        <td class="text-center text-bold">{{ $fmt($packingForm->weight_nt) }}</td>
        <td class="text-center text-bold">{{ $fmt($packingForm->weight_gt) }}</td>
        <td class="text-center text-bold">{{ $fmt($packingForm->cubic_meter) }}</td>
        <td class="text-center text-bold">{{ $fmtInt($packingForm->qty) }}</td>
    </tr>
</table>
</body>
</html>
