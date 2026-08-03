<!DOCTYPE html>
<html lang="th">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Export PO #{{ $ProformaInvoice->doc_no }}</title>
    <style>
        @page {
            margin: 5mm; /* ขอบกระดาษ 5mm ทุกด้าน */
        }

        body {
            font-family: 'garuda', sans-serif;
            font-size: 14px;
            color: #333333;
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
    @php
        $logo = null;
        if ( isset($settings['logo']) ) {
            $logo = json_decode($settings['logo']);
            $logo = ( sizeof( $logo ) > 0 ? $logo[0] : null );
        }
        // เลขเอกสาร FA = เลข PI เปลี่ยนตัวหน้า PI -> FA (ส่วนหลังเหมือนกัน)
        $faNo = \Illuminate\Support\Str::startsWith($ProformaInvoice->doc_no, 'PI')
            ? 'FA'.substr($ProformaInvoice->doc_no, 2)
            : $ProformaInvoice->doc_no;
        // จัดกลุ่มสินค้าตามโรงงาน (Fac No.) — ไม่มี Fac No. ไว้กลุ่ม '-'
        $groups = $ProformaInvoice->products->groupBy(fn($p) => ($p->fac_no !== null && $p->fac_no !== '') ? $p->fac_no : '-');
    @endphp

    <div style="position: fixed; top: 59px; right: -75px;">
        Page {PAGENO} of {nbpg}
    </div>

    @foreach($groups as $facNo => $items)
    @if(!$loop->first)
        <div style="page-break-before: always;"></div>
    @endif
    <table width="100%" cellpadding="5" cellspacing="0" >
        <tr style="background-color: rgb(237, 237, 249);">
            <th style="width: 10%;" align="left" rowspan="3">
                @if ($logo)
                    <img src="{{ asset('uploads/SettingSystem/'.$logo) }}" height="50">
                @endif
            </th>
            <th align="left">
                <b>EXPORT NO.</b>
            </th>
            <td style="color: blue;">
                {{ $faNo }}
            </td>
            <th align="right">
                Sale Rep
            </th>
            <td style="color: blue;">
                {{ $ProformaInvoice->sale_prefix }}{{ $ProformaInvoice->sale_firstname }} {{ $ProformaInvoice->sale_lastname }}
            </td>
            <th colspan="2" rowspan="2" style="font-size: 110%;">
                <b>Export Production</b>
            </th>
        </tr>
        <tr style="background-color: rgb(237, 237, 249);">
            <th align="left">
                <b>CUSTOMER</b>
            </th>
            <td colspan="3" style="color: blue;">
                {{ $ProformaInvoice->customer_name }}
            </td>
        </tr>
        <tr style="background-color: rgb(237, 237, 249);">
            <th width="15%">
                <b>PO NO.</b>
            </th>
            <td width="15%" style="color: blue;">
                {{ $ProformaInvoice->customer_po }}
            </td>
            <th width="20%">
                SHIPMENT DATE
            </th>
            <td style="color: blue;">
                {{ date('d/m/Y' , strtotime($ProformaInvoice->shipment_date)) }}
            </td>
            <th>
                <b>Qty</b>
            </th>
            <td width="12%"></td>
        </tr>

        {{-- หัวกลุ่มโรงงาน --}}
        <tr style="background-color: rgb(237, 237, 249);">
            <td colspan="7" style="padding: 4px 6px;"><b>Fac No. : {{ $facNo }}</b></td>
        </tr>
        @foreach($items as $key => $product)
        <tr>
            <td style="border-bottom: 1px solid #999; padding-top: 5px; padding-bottom: 5px;" valign="top" align="center">{{ $product->seq }}</td>
            <td style="border-bottom: 1px solid #999; padding-top: 5px; padding-bottom: 5px; color: blue;" valign="top" align="center">{{ $key + 1 }}</td>
            <td style="border-bottom: 1px solid #999; padding-top: 5px; padding-bottom: 5px;" valign="top">{{ $product->part_no }}<div style="color: blue; font-size: 90%;">{{ $product->drawing }}</div></td>
            <td style="border-bottom: 1px solid #999; padding-top: 5px; padding-bottom: 5px;" colspan="2" valign="top" >{{ $product->name_th ?: $product->name_en }}</td>
            <td style="border-bottom: 1px solid #999; padding-top: 5px; padding-bottom: 5px;" align="center" valign="top">{{ number_format($product->qty,0) }}</td>
            <td style="border-bottom: 1px solid #999; padding-top: 5px; padding-bottom: 5px;" align="center" valign="top">{{ $product->cus_code }}</td>
        </tr>
        @endforeach
        {{-- สรุปจำนวนรวมของ fac นี้ (อยู่คอลัมน์เดียวกับ Qty) --}}
        <tr>
            <td colspan="5" align="right" style="border-top: 2px solid #333;"><b>รวม Fac {{ $facNo }}</b></td>
            <td align="center" style="border-top: 2px solid #333;"><b>{{ number_format($items->sum('qty'), 0) }}</b></td>
            <td style="border-top: 2px solid #333;"></td>
        </tr>
        {{-- หมายเหตุ ต่อท้ายกลุ่ม fac (ไม่ขึ้นหน้าใหม่ ประหยัดกระดาษ) --}}
        @if($ProformaInvoice->remarks->count())
        <tr>
            <td colspan="7" style="padding: 4px 8px;">
                <b>หมายเหตุ :</b>
                @foreach($ProformaInvoice->remarks as $rm)
                    <div style="padding-left: 12px;">{{ $loop->iteration }}. {{ $rm->remark }}</div>
                @endforeach
            </td>
        </tr>
        @endif
    </table>
    @endforeach
</body>
</html>
