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
    @endphp

    <div style="position: fixed; top: 65px; right: -70px;">
        {PAGENO} / {nbpg}
    </div>

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
            <td>
                {{ $ProformaInvoice->doc_no }}
            </td>
            <th>
                Sale Rep
            </th>
            <td>
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
            <td colspan="3">
                {{ $ProformaInvoice->customer_name }}
            </td>
        </tr>
        <tr style="background-color: rgb(237, 237, 249);">
            <th width="15%">
                <b>PO NO.</b>
            </th>
            <td width="15%">
                {{ $ProformaInvoice->po_no }}
            </td>
            <th width="20%">
                SHIPMENT DATE
            </th>
            <td>
                {{ date('d/m/Y' , strtotime($ProformaInvoice->shipment_date)) }}
            </td>
            <th>
                <b>Qty</b>
            </th>
            <td width="12%"></td>
        </tr>

    @foreach($ProformaInvoice->products as $key => $product)
    <tr>
        <td style="border-bottom: 1px solid #999; padding-top: 5px; padding-bottom: 5px;" valign="top" align="center">{{ $product->seq }}</td>
        <td style="border-bottom: 1px solid #999; padding-top: 5px; padding-bottom: 5px; color: blue;" valign="top" align="center">{{ $key + 1 }}</td>
        <td style="border-bottom: 1px solid #999; padding-top: 5px; padding-bottom: 5px;">{{ $product->part_no }}</td>
        <td style="border-bottom: 1px solid #999; padding-top: 5px; padding-bottom: 5px;" colspan="2" >{{ $product->name_th }}</td>
        <td style="border-bottom: 1px solid #999; padding-top: 5px; padding-bottom: 5px;" align="center">{{ number_format($product->qty,0) }}</td>
        <td style="border-bottom: 1px solid #999; padding-top: 5px; padding-bottom: 5px;">{{ $product->drawing }}</td>
    </tr>
    @endforeach
    </table>
</body>
</html>
