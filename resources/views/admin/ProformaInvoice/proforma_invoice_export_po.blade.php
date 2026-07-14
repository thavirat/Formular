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
        // จัดกลุ่มสินค้าตามโรงงาน (Fac No.) -> เอกสาร 1 ชุด ต่อ 1 โรงงาน (รวมในไฟล์เดียว)
        $groups = $ProformaInvoice->products->groupBy(function ($p) {
            return ($p->fac_no !== null && $p->fac_no !== '') ? $p->fac_no : '-';
        });
    @endphp

    @foreach($groups as $facNo => $items)
    @if(!$loop->first)
        <div style="page-break-before: always;"></div>
    @endif

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
                <b><u>Fac No.</u></b>
            </td>
            <td><b>{{ $facNo }}</b></td>
            <td></td>
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
                <b><u>C/NO.</u></b>
            </td>
            <td>{{ $ProformaInvoice->cno ?: '1-UP' }}</td>
            <td></td>
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
                <b><u>Shipping Remark</u></b>
            </td>
            <td>{{ $ProformaInvoice->ship_remark }}</td>
            <td>

            </td>
        </tr>
    </table>
    <table width="100%" style="border-collapse: collapse;">
    <tr>
        <th width="5%" style="padding-bottom: 5px; background-color: rgb(255, 243, 216);"><u>NO</u></th>
        <th width="5%" style="padding-bottom: 5px; background-color: rgb(255, 243, 216);"><u>ITM</u></th>
        <th width="8%" style="padding-bottom: 5px; background-color: rgb(255, 243, 216);" align="right"><u>QTY</u></th>
        <th width="13%" style="padding-bottom: 5px; padding-left: 5px; background-color: rgb(255, 243, 216);"  align="left"><u>Part No</u><br><u><span style="color: green;">ต้นทุน</span></u></th>
        <th width="49%" style="padding-bottom: 5px; background-color: rgb(255, 243, 216);"><u>Description</u><br><span style="color: blue;"><u>DWG</u></span></th>
        <th width="20%" style="padding-bottom: 5px; background-color: rgb(255, 243, 216);"><u>Customer.</u><br><u>P/NO.</u></th>
    </tr>
    @foreach($items->sortBy('part_no') as $product)
    <tr>
        <td style="border-bottom: 1px dashed #999; padding-top: 5px; padding-bottom: 5px; color: blue;" valign="top" align="center">{{ $loop->iteration }}</td>
        <td style="border-bottom: 1px dashed #999; padding-top: 5px; padding-bottom: 5px;" valign="top" align="center">{{ $product->fa_itm }}</td>
        <td style="border-bottom: 1px dashed #999; padding-top: 5px; padding-bottom: 5px;" align="right">{{ number_format($product->qty, 0) }} {{ $product->unit_name }}</td>
        <td style="border-bottom: 1px dashed #999; padding-top: 5px; padding-bottom: 5px;">
            {{ $product->part_no }}
            <table width="100%" style="border:none; border-collapse:collapse;"><tr>
                <td style="border:none; padding:0; text-align:right; color:red;">{{ number_format($product->cost ?? 0, 2) }}</td>
            </tr></table>
        </td>
        <td style="border-bottom: 1px dashed #999; padding-top: 5px; padding-bottom: 5px;" valign="top">{{ $product->name_en }}<div style="color: blue; font-size: 90%;">{{ $product->drawing }}</div></td>
        <td style="border-bottom: 1px dashed #999; padding-top: 5px; padding-bottom: 5px;" valign="top" align="center">{{ $product->cus_code }}</td>
    </tr>
    @endforeach
</table>
    @endforeach

    {{-- ===== หมายเหตุ ไว้แผ่นสุดท้าย (คีย์เป็นข้อๆ ถ้าไม่มีใช้ Shipping Remark) ===== --}}
    @if($ProformaInvoice->remarks->count() || trim((string) $ProformaInvoice->ship_remark) !== '')
        <div style="page-break-before: always;"></div>
        <table width="100%" cellpadding="0" cellspacing="0" style="background-color: rgb(255, 243, 216);">
            <tr>
                <th align="center" valign="middle" style="font-size: 160%; font-weight: bold;"><b>REMARKS</b></th>
            </tr>
        </table>
        <table width="100%" style="margin-top: 10px;">
            @forelse($ProformaInvoice->remarks as $rm)
                <tr>
                    <td width="40" valign="top" align="right" style="padding: 4px 8px;">{{ $loop->iteration }}.</td>
                    <td valign="top" style="padding: 4px 8px;">{{ $rm->remark }}</td>
                </tr>
            @empty
                <tr>
                    <td valign="top" style="padding: 4px 8px;">{{ $ProformaInvoice->ship_remark }}</td>
                </tr>
            @endforelse
        </table>
    @endif
</body>

</html>
