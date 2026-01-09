<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        /* Header Section */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .logo {
            height: 50px;
        }
        .company-info {
            text-align: right;
            font-size: 10px;
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
            <td width="30%">
                <img src="{{ asset('uploads/SettingSystem/'.$logo) }}" class="logo">
            </td>
            <td class="company-info" width="70%">
                <div class="text-bold" style="font-size: 14px;">FORMULA INTERTRADE CO., LTD.</div>
                <div>119 Motorway Road, Thap Chang, Saphan Sung, Bangkok 10250, Thailand</div>
                <div>Tel: +66 63 525 2242 | Email: sales@formula.co.th</div>
                <div>Tax ID: 01055XXXXXXXXX</div>
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
                <th>Part No.</th>
                <th>Description</th>
                <th width="40">Qty</th>
                <th width="70">Unit Price</th>
                <th width="40">Disc%</th>
                <th width="70">Disc Amt</th>
                <th width="80">Amount</th>
            </tr>
        </thead>
        <tbody>
            @php
                $maxRows = 20;
                $currentRows = count($Quotation->products);
                $emptyRows = $maxRows - $currentRows;
                $subTotalBeforeDiscount = 0;
                $totalDiscountAmount = 0;
            @endphp

            @foreach($Quotation->products as $product)
            @php
                $subTotalBeforeDiscount += ($product->qty * $product->price_per_item);
                $totalDiscountAmount += $product->discount_amount;
            @endphp
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>
                    <div class="text-bold">{{ $product->part_no }}</div>
                    <div style="font-size: 8px; color: #666;">Cus: {{ $product->cus_code }} | Drw: {{ $product->drawing }}</div>
                </td>
                <td>{{ $product->detail_eng }}</td>
                <td class="text-right">{{ number_format($product->qty , 2) }}</td>
                <td class="text-right">{{ number_format($product->price_per_item , 2) }}</td>
                <td class="text-center">{{ $product->discount_percents > 0 ? number_format($product->discount_percents, 0).'%' : '-' }}</td>
                <td class="text-right">{{ $product->discount_amount > 0 ? number_format($product->discount_amount, 2) : '-' }}</td>
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
                    <td>&nbsp;</td>
                </tr>
                @endfor
            @endif
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" rowspan="3" style="vertical-align: top; border: none; padding-top: 10px;">
                    <span class="text-bold">Total in Words:</span> <br>
                    <em>({{ Help::numberToWords($Quotation->total , $Quotation->currency_name) }})</em>
                </td>
                <td colspan="2" class="text-right text-bold">Sub Total</td>
                <td class="text-right">{{ number_format($subTotalBeforeDiscount, 2) }}</td>
            </tr>
            <tr>
                <td colspan="2" class="text-right text-bold" style="color: red;">Discount</td>
                <td class="text-right" style="color: red;">- {{ number_format($totalDiscountAmount, 2) }}</td>
            </tr>
            <tr style="background-color: #eee;">
                <td colspan="2" class="text-right text-bold" style="font-size: 12px;">Grand Total ({{$Quotation->symbol}})</td>
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
                <div>Sales Executive</div>
                <div class="text-bold text-uppercase">FORMULA INTERTRADE CO., LTD.</div>
            </td>
        </tr>
    </table>

</body>
</html>
