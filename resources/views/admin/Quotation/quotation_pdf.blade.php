<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        body{
            font-family: 'garuda';
            font-size: 12px;
        }
    </style>
</head>
<body>
    <table width="100%">
        <tr>
            <th align="center" style="font-size: 120%;">
                FORMULA INTERTRADE CO., LTD.
            </th>
        </tr>
        <tr>
            <td align="center">
                119 Motorway Road, Thap Chang, Saphan Sung, Bangkok 10250, Thailand
            </td>
        </tr>
        <tr>
            <td align="center">
                Tel: +66 63 525 2242
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <th align="center" style="font-size: 120%;">
                QUOTATION
            </th>
        </tr>
    </table>
    <table width="100%" border="0">
        <tr>
            <td ali>
                <table width="100%">
                    <tr>
                        <th align="left">Customer:</th>
                    </tr>
                    <tr>
                        <td>{{ $Quotation->company_name }}</td>
                    </tr>
                    <tr>
                        <td>{{ $Quotation->address }}</td>
                    </tr>
                </table>
            </td>
            <td></td>
            <td>
                <table width="100%">
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <th align="right">Quotation No.:</th>
                        <td>{{ $Quotation->doc_no }}</td>
                    </tr>
                    <tr>
                        <th align="right">Date:</th>
                        <td>{{ $Quotation->doc_date }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table width="100%" border="1" cellspacing="0" cellpadding="5" style="margin-top: 10px; border-collapse: collapse;">
        <tr>
            <th>ITM.</th>
            <th>Part No.</th>
            <th>Drawing</th>
            <th>Cus.Code</th>
            <th>Description</th>
            <th>Qty</th>
            <th>Unit Price ({{$Quotation->symbol}})</th>
            <th>Amount ({{$Quotation->symbol}})</th>
        </tr>
        @foreach($Quotation->products as $product)
        <tr>
            <td align="center">{{ $loop->iteration }}</td>
            <td>{{ $product->part_no }}</td>
            <td>{{ $product->drawing }}</td>
            <td>{{ $product->cus_code }}</td>
            <td>{{ $product->detail_eng }}</td>
            <td align="right">{{ number_format($product->qty , 2) }}</td>
            <td align="right">{{ number_format($product->price_per_item , 2) }}</td>
            <td align="right">{{ number_format($product->total_price , 2) }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="8" style="border: none;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="5" style="border: none;">
                ({{ Help::numberToWords($Quotation->total , $Quotation->currency_name) }})
            </td>
            <td colspan="2" align="right" style="border: none;"><strong>Total ({{$Quotation->symbol}})</strong></td>
            <td align="right" style="border: none;"><strong>{{ number_format($Quotation->total , 2) }}</strong></td>
        </tr>
    </table>
    <table style="margin-top: 10px;">
        <tr>
            <th align="left">
                TERMS & CONDITIONS
            </th>
        </tr>
        <tr>
            <td>
                • Currency: {{$Quotation->symbol}}
            </td>
        </tr>
        <tr>
            <td>
                • Payment: {{$Quotation->credit_payment_name}}
            </td>
        </tr>
        <tr>
            <td>
                • Delivery Time: 45-60 days after order confirmation
            </td>
        </tr>
        <tr>
            <td>
                • A fee of {{$Quotation->symbol}} 15.00 per pallet applies
            </td>
        </tr>
        <tr>
            <td>
                • Order is less than USD15,000 will have charge USD120 for shipping & handling fee  (Sea freight)
            </td>
        </tr>
        <tr>
            <td>
                • Quotation Validity: 30 Days
            </td>
        </tr>
    </table>

    <table width="100%" style="margin-top: 10px;">
        <tr>
            <td width="30%" align="center">
                <br>
                <br>
                __________________________<br>

                Confirmed and Accepted<br>
                COOLDRIVE KNOXFIELD WMS
            </td>
            <td width="40%"></td>
            <td width="30%" align="center">
                <br>
                <br>
                __________________________<br>

                {{$Quotation->firstname}} {{$Quotation->lastname}}<br>
                (Sales Executive)
            </td>
        </tr>
    </table>

</body>
</html>
