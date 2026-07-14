@php
    /* ---------- โลโก้ (ดึงจาก Setting เดิม) ---------- */
    $logo = null;
    if (isset($settings['logo'])) {
        $logo = json_decode($settings['logo']);
        $logo = (is_array($logo) && count($logo) > 0 ? $logo[0] : null);
    }

    $pi = $ProformaInvoice;
    $cur = optional($pi->currency)->symbol ?: 'USD';
@endphp
<!DOCTYPE html>
<html lang="th">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>EXPORT ORDER FORM {{ $pi->doc_no }}</title>
    <style>
        @page {
            margin: 80mm 7mm 8mm 7mm;   /* เว้นบนไว้ให้หัวเอกสาร (กล่องข้อมูล) ที่ซ้ำทุกหน้า */
            header: html_faheader;
        }

        body { font-family: 'garuda', sans-serif; font-size: 11px; color: #000; margin: 0; padding: 0; }

        .text-center { text-align: center; }
        .text-right  { text-align: right; }
        .bold        { font-weight: bold; }

        .company-name { font-size: 17px; font-weight: bold; }
        .doc-title    { font-size: 14px; font-weight: bold; }

        .box { width: 100%; border-collapse: collapse; }
        .box td { border: 1px solid #00008b; padding: 3px 6px; vertical-align: top; font-size: 11px; }

        .items { width: 100%; border-collapse: collapse; font-size: 10.5px; }
        .items th, .items td { border: 1px solid #000; padding: 2px 4px; }
        .items thead th { text-align: center; vertical-align: middle; font-weight: bold; }

        .remark-list { margin: 6px 0 0 0; padding-left: 16px; font-size: 11px; }
        .remark-list li { margin-bottom: 2px; }
        .sign td { padding-top: 18px; font-size: 11px; }
    </style>
</head>
<body>

{{-- ===================== หัวเอกสาร (ซ้ำทุกหน้า) ===================== --}}
<htmlpageheader name="faheader">
    {{-- บริษัท + ติดต่อ --}}
    <table width="100%" style="border:none;">
        <tr>
            <td width="72%" style="border:none;">
                @if ($logo)
                    <img src="{{ asset('uploads/SettingSystem/'.$logo) }}" height="38" style="vertical-align:middle;">
                @endif
                <span class="company-name">FORMULA INTERTRADE CO.,LTD.</span><br>
                <span style="font-size:11px;">119 MOTORWAY ROAD THAP CHANG, SAPHAN SUNG, BANGKOK 10250, THAILAND.</span>
            </td>
            <td width="28%" style="border:none; font-size:11px;">
                <span class="bold">Tel</span>&nbsp;&nbsp;: 063-525-2242<br>
                <span class="bold">Fax</span>&nbsp;&nbsp;: -
            </td>
        </tr>
    </table>

    <table width="100%" style="border:none;">
        <tr>
            <td width="78%" class="text-center doc-title" style="border:none;">EXPORT ORDER FORM</td>
            <td width="22%" class="text-right" style="border:none;">PAGE {PAGENO} / {nbpg}</td>
        </tr>
    </table>
    <table width="100%" style="border:none; margin-bottom:3px;">
        <tr>
            <td width="55%" style="border:none;"><span class="bold">DOCUMENT NO. :</span> {{ $pi->doc_no }}</td>
            <td width="45%" style="border:none;"><span class="bold">DATE :</span> {{ $pi->doc_date ? \Carbon\Carbon::parse($pi->doc_date)->format('d/m/Y') : '' }}</td>
        </tr>
    </table>

    {{-- กล่องข้อมูลคำสั่ง --}}
    <table class="box">
        <tr>
            <td width="50%"><span class="bold">EXPORT ORDER NO. :</span>&nbsp;&nbsp;{{ $pi->doc_no }}</td>
            <td width="50%"><span class="bold">EXPECTED SHIPMENT DATE :</span> {{ $pi->ship_date ? \Carbon\Carbon::parse($pi->ship_date)->format('d/m/Y') : '' }}</td>
        </tr>
        <tr>
            <td><span class="bold">CUSTOMER :</span> {{ $pi->customer_name ?: $pi->company_name }}</td>
            <td><span class="bold">SALE REP :</span>&nbsp;&nbsp;&nbsp;&nbsp;{{ optional($pi->createdBy)->name }}</td>
        </tr>
        <tr>
            <td><span class="bold">COUNTRY :</span> {{ $pi->ship_to_code }}</td>
            <td><span class="bold">SHIPMENT BY :</span></td>
        </tr>
        <tr>
            <td rowspan="5" valign="top">
                <span class="bold">SHIPPING MARKS</span><br>
                &nbsp;&nbsp;{!! nl2br(e($pi->ship_remark)) !!}<br>
                &nbsp;&nbsp;C/NO.1-UP {{-- hardcode ไว้ก่อน --}}
            </td>
            <td><span class="bold">PAYMENT BY :</span> {{ optional($pi->creditPayment)->name }}</td>
        </tr>
        <tr><td><span class="bold">LASTEST (LC) SHIPMENT :</span></td></tr>
        <tr><td><span class="bold">PURCHASE ORDER NO. :</span> {{ $pi->customer_po }}</td></tr>
        <tr><td><span class="bold">Priority :</span></td></tr>
        <tr><td style="height:22px;"><span class="bold">Remarks :</span></td></tr>
    </table>
</htmlpageheader>

{{-- ===================== ตารางรายการสินค้า ===================== --}}
<table class="items">
    <thead>
        <tr>
            <th width="5%">ITM</th>
            <th width="7%">Fac No.</th>
            <th width="10%">QUANTITY</th>
            <th width="15%">FORMULA<br>PART NUMBER</th>
            <th width="11%">DRAWING</th>
            <th width="40%">DESCRIPTION</th>
            <th width="12%">CUSTOMER<br>P/NO.</th>
        </tr>
    </thead>
    <tbody>
        @forelse($pi->products as $i => $item)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td class="text-center">{{ $item->fac_no }}</td>
                <td class="text-center bold">{{ number_format($item->qty, 0) }} {{ $item->unit_name ?: 'PCS' }}</td>
                <td class="text-center">{{ $item->part_no }}</td>
                <td class="text-center">{{ $item->drawing }}</td>
                <td>{{ $item->detail_thai ?: $item->detail_eng }}</td>
                <td class="text-center">{{ $item->cus_code }}</td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center" style="padding:15px;">ไม่มีรายการสินค้า</td></tr>
        @endforelse
    </tbody>
</table>

{{-- ===================== หมายเหตุการผลิต + ลงนาม (ให้อยู่หน้าเดียวกัน) ===================== --}}
<div style="page-break-inside: avoid;">
<ol class="remark-list">
    @foreach($pi->remarks as $rm)
        <li>{{ $rm->remark }}</li>
    @endforeach
</ol>
<div class="bold" style="margin-left:6px;">**สินค้าเคลม</div>

{{-- ===================== วันที่พิมพ์ + ลงนาม ===================== --}}
<table width="100%" style="margin-top:10px;">
    <tr>
        <td style="border:none;"></td>
        <td width="40%" class="text-right bold" style="border:none;">Print Date / Time : {{ date('d/m/Y  H:i:s') }}</td>
    </tr>
</table>
<table width="100%" class="sign">
    <tr>
        <td width="50%" style="border:none;"></td>
        <td width="50%" style="border:none;"><span class="bold">ISSUED BY :</span> ______________________________</td>
    </tr>
    <tr>
        <td style="border:none;"></td>
        <td style="border:none;"><span class="bold">APPROVED BY :</span> ______________________________</td>
    </tr>
</table>
</div>

</body>
</html>
