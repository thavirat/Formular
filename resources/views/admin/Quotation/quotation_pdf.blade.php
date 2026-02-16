<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $Quotation->doc_no }}</title>
    <style>
        @page {
            margin-top: 1cm;
            margin-bottom: 2cm;
            footer: pageFooter;
        }
        body {
            font-family: 'garuda';
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        .text-uppercase { text-transform: uppercase; }

        /* Header Section */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            text-align: center;
        }
        .logo-in-line {
            height: 50px;
            vertical-align: middle;
            margin-right: 15px;
        }
        .company-name-main {
            font-size: 20px;
            font-weight: bold;
            color: #000;
            vertical-align: middle;
            display: inline-block;
            margin: 0;
        }
        .company-address-detail {
            font-size: 10px;
            color: #333;
            line-height: 1.5;
        }

        .document-title {
            font-size: 24px;
            color: #000;
            margin: 15px 0;
            letter-spacing: 2px;
        }

        /* Info Section */
        .info-table {
            width: 100%;
            margin-top: 20px;
            vertical-align: top;
        }
        .info-box {
            width: 50%;
            vertical-align: top;
        }
        .label {
            color: #666;
            width: 100px;
            display: inline-block;
        }

        /* Product Table */
        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .product-table th {
            background-color: #f2f2f2;
            border: 1px solid #ccc;
            padding: 8px 5px;
            font-size: 10px;
        }
        .product-table td {
            border: 1px solid #ccc;
            padding: 5px;
            font-size: 10px;
        }

        /* Summary Section */
        .summary-table {
            width: 100%;
            margin-top: 10px;
        }
        .terms-section {
            width: 65%;
            vertical-align: top;
            font-size: 10px;
        }

        /* Signature Section */
        .signature-table {
            width: 100%;
            margin-top: 50px;
        }
        .signature-box {
            width: 33%;
            text-align: center;
        }
        .signature-line {
            border-bottom: 1px solid #000;
            width: 80%;
            margin: 0 auto 5px;
            height: 40px;
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

    <htmlpagefooter name="pageFooter">
        <table width="100%" style="border-top: 1px solid #ccc; padding-top: 5px;">
            <tr>
                <td align="center" style="font-size: 10px; color: #666;">
                    Page {PAGENO} of {nbpg}
                </td>
            </tr>
        </table>
    </htmlpagefooter>

    <table class="header-table">
        <tr>
            <td align="right" width="200px">
                <img src="{{ asset('uploads/SettingSystem/'.$logo) }}" class="logo-in-line">
            </td>
            <td align="left">
                <div class="company-address-detail">
                    <span class="company-name-main">FORMULA INTERTRADE CO., LTD.</span>
                    <div>119 Motorway Road, Thap Chang, Saphan Sung, Bangkok 10250, Thailand</div>
                    <div>Tel: {{$Quotation->mobile}} | Email: {{$Quotation->email}} | Tax ID: 0105538048542</div>
                </div>
            </td>
        </tr>
    </table>

    <div class="text-center document-title text-bold">QUOTATION</div>

    <table class="info-table">
        <tr>
            <td class="info-box" style="border: 1px solid #ccc; padding: 10px; border-radius: 5px;">
                <div class="text-bold" style="margin-bottom: 5px; color: #444; border-bottom: 1px solid #eee;">CUSTOMER DETAILS</div>
                <div class="text-bold">{{ $Quotation->company_name }}</div>
                <div style="margin-top: 5px;">{{ $Quotation->address }}</div>
                <div style="margin-top: 5px;">Attn: {{ $Quotation->contact_name }}</div>
            </td>
            <td width="5%"></td>
            <td class="info-box">
                <table width="100%">
                    <tr>
                        <td class="label text-bold">Quotation No.</td>
                        <td>: {{ $Quotation->doc_no }}</td>
                    </tr>
                    <tr>
                        <td class="label text-bold">Date</td>
                        <td>: {{ date('d/m/Y', strtotime($Quotation->doc_date)) }}</td>
                    </tr>
                    <tr>
                        <td class="label text-bold">Currency</td>
                        <td>: {{ $Quotation->currency_name }} ({{$Quotation->symbol}})</td>
                    </tr>
                    <tr>
                        <td class="label text-bold">Payment Term</td>
                        <td>: {{ $Quotation->credit_payment_name }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="product-table">
        <thead>
            <tr>
                <th width="30">ITM.</th>
                <th width="100">Part No.</th>
                <th width="70">Dwg.</th>
                <th>Description</th>
                <th width="40">Qty</th>
                <th width="80">Unit Price</th>
                <th width="90">Amount</th>
            </tr>
        </thead>
        <tbody>
            @php
                $maxRows = 18;
                $currentRows = count($Quotation->products);
                $emptyRows = $maxRows - $currentRows;
            @endphp

            @foreach($Quotation->products as $product)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>
                    <div class="text-bold">{{ $product->part_no }}</div>
                    <div style="font-size: 8px; color: #666;">Cus: {{ $product->cus_code }}</div>
                </td>
                <td class="text-center">{{ $product->drawing ?: '-' }}</td>
                <td>{{ $product->detail_eng }}</td>
                <td class="text-right">{{ number_format($product->qty , 0) }}</td>
                <td class="text-right">{{ number_format($product->price_per_item , 2) }}</td>
                <td class="text-right">{{ number_format($product->total_price , 2) }}</td>
            </tr>
            @endforeach

            @if($emptyRows > 0)
                @for($i = 0; $i < $emptyRows; $i++)
                <tr>
                    <td class="text-center">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                @endfor
            @endif
        </tbody>
        <tfoot>
            <tr style="background-color: #eee;">
                <td colspan="4" style="vertical-align: middle; border: none; padding-top: 5px;">
                    <span class="text-bold">Total in Words:</span>
                    <em>({{ Help::numberToWords($Quotation->total , $Quotation->currency_name) }})</em>
                </td>
                <td colspan="2" class="text-right text-bold" style="font-size: 12px;">Total ({{$Quotation->symbol}})</td>
                <td class="text-right text-bold" style="font-size: 12px; border: 2px solid #333;">
                    {{ number_format($Quotation->total , 2) }}
                </td>
            </tr>
        </tfoot>
    </table>

    <table class="summary-table">
        <tr>
            <td class="terms-section">
                <div class="text-bold" style="text-decoration: underline; margin-bottom: 5px;">TERMS & CONDITIONS</div>
                <div>• Delivery Time: 45-60 days after order confirmation</div>
                <div>• Shipping: A fee of {{$Quotation->symbol}} 15.00 per pallet applies</div>
                <div>• Freight: Order < USD15,000 charge USD120 (Sea freight)</div>
                <div>• Validity: Quotation valid for 30 Days</div>
            </td>
        </tr>
    </table>

    <table class="signature-table">
        <tr>
            <td class="signature-box">
                <div class="signature-line"></div>
                <div class="text-bold text-uppercase">Confirmed and Accepted</div>
                <div style="font-size: 9px;">(Authorized Signature & Stamp)</div>
                <div style="margin-top: 5px;">Date: ____/____/____</div>
            </td>
            <td width="33%"></td>
            <td class="signature-box">
                <div class="signature-line"></div>
                <div class="text-bold">{{$Quotation->firstname}} {{$Quotation->lastname}}</div>
                <div>{{$Quotation->department_name}}</div>
                <div class="text-bold text-uppercase">FORMULA INTERTRADE CO., LTD.</div>
            </td>
        </tr>
    </table>

</body>
</html>
