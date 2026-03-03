<!DOCTYPE html>
<html lang="th">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Export PO #{{ $ProformaInvoice->doc_no }}</title>
    <style>
        @page {
            margin: 5mm 5mm 5mm 5mm; /* บน ขวา ล่าง ซ้าย (ปรับเลขให้น้อยลงเพื่อลดขอบ) */
        }

        body {
            font-family: 'garuda', sans-serif;
            font-size: 14px; /* ปรับขนาดให้พอดีหน้ากระดาษ */
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

    <table  width="100%"   cellpadding="0" cellspacing="0" style="background-color: rgb(255, 243, 216);">
        <tr>
            <th style="width: 25%;" align="left" >
                @if ($logo)
                    <img src="{{ asset('uploads/SettingSystem/'.$logo) }}" height="50">
                @endif
            </th>
            <th align="center" valign="middle" style="font-size: 200%; font-weight: bold;">
                <b>EXPORT PO</b>
            </th>
            <th align="right" style="width: 25%; text-align: right;" valign="middle">
                    PAGE {PAGENO} of {nbpg}
            </th>
        </tr>
    </table>
    <table width="100%" style="background-color: rgb(255, 243, 216);">
        <tr>
            <td align="center" width="25%">
                <b><u>DocuNo</u></b>
            </td>
            <td>{{$ProformaInvoice->doc_no}}</td>
            <td width="25%">
                <div>
                    <b><u>Sale Rep</u></b>
                </div>
                <div>
                    {{$ProformaInvoice->sale_prefix}}{{$ProformaInvoice->sale_firstname}} {{$ProformaInvoice->sale_lastname}}
                </div>

            </td>
        </tr>
        <tr>
            <td align="center">
                <b><u>CustName</u></b>
            </td>
            <td>{{$ProformaInvoice->company_name}}</td>
            <td>

            </td>
        </tr>
        <tr>
            <td align="center">
                <b><u>ShipDate</u></b>
            </td>
            <td>{{$ProformaInvoice->ship_date}}</td>
            <td>

            </td>
        </tr>
        <tr>
            <td align="center">
                <b><u>ShipToCode</u></b>
            </td>
            <td>{{$ProformaInvoice->ship_to_code}}</td>
            <td>

            </td>
        </tr>
        <tr>
            <td align="center">
                <b><u>CustPONo</u></b>
            </td>
            <td>{{$ProformaInvoice->customer_po}}</td>
            <td>

            </td>
        </tr>
        <tr>
            <td align="center">
                <b><u>SHIPPING MARKS</u></b>
            </td>
            <td>{{$ProformaInvoice->ship_remark}}</td>
            <td>

            </td>
        </tr>
    </table>
    <table width="100%" style="border-collapse: collapse;">
    <tr>
        <th width="5%" style="padding-bottom: 5px; background-color: rgb(255, 243, 216);"><u>NO</u></th>
        <th width="5%" style="padding-bottom: 5px; background-color: rgb(255, 243, 216);"><u>ITM</u></th>
        <th width="5%" style="padding-bottom: 5px; background-color: rgb(255, 243, 216);" align="right"><u>QTY</u><br><u>Total</u></th>
        <th width="20%" style="padding-bottom: 5px; padding-left: 5px; background-color: rgb(255, 243, 216);"  align="left"><u>Part No</u><br><u><span style="color: green;">ราคาต่อหน่วย</span></u></th>
        <th width="45%" style="padding-bottom: 5px; background-color: rgb(255, 243, 216);"><u>Description</u><br><span style="color: blue;"><u>DWG</u></span></th>
        <th width="20%" style="padding-bottom: 5px; background-color: rgb(255, 243, 216);"><u>Customer.</u><br><u>P/NO.</u></th>
    </tr>
    @foreach($ProformaInvoice->products as $key => $product)
    <tr>
        <td style="border-bottom: 1px dashed #999; padding-top: 5px; padding-bottom: 5px; color: blue;" valign="top" align="center">{{ $key + 1 }}</td>
        <td style="border-bottom: 1px dashed #999; padding-top: 5px; padding-bottom: 5px;" valign="top" align="center">{{ $product->seq }}</td>
        <td style="border-bottom: 1px dashed #999; padding-top: 5px; padding-bottom: 5px;" align="right">{{ number_format($product->qty, 0) }}<br><span style="color: rgb(107, 73, 73);">{{ number_format($product->qty, 0) }}</span></td>
        <td style="border-bottom: 1px dashed #999; padding-top: 5px; padding-bottom: 5px;">{{ $product->part_no }}<br>{{ $product->unit_name }} <span style="color: red;">{{ number_format($product->price_per_item, 2) }}</span></td>
        <td style="border-bottom: 1px dashed #999; padding-top: 5px; padding-bottom: 5px;" valign="top">{{ $product->name_en }}</td>
        <td style="border-bottom: 1px dashed #999; padding-top: 5px; padding-bottom: 5px;" valign="top" align="center">{{ $product->drawing }}</td>
    </tr>
    @endforeach
</table>
</body>

</html>
