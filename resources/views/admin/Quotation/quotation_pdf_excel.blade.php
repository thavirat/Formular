<table>
    <tr>
        <td colspan="7" style="font-size: 20px; font-weight: bold; text-align: center;">FORMULA INTERTRADE CO., LTD.</td>
    </tr>
    <tr>
        <td colspan="7" style="text-align: center; font-size: 16px; font-weight: bold;">QUOTATION</td>
    </tr>
    <tr><td colspan="7"></td></tr>
    <tr>
        <td style="font-weight: bold;">Quotation No.</td>
        <td>{{ $Quotation->doc_no }}</td>
        <td></td>
        <td style="font-weight: bold;">Date</td>
        <td>{{ date('d/m/Y', strtotime($Quotation->doc_date)) }}</td>
        <td style="font-weight: bold;">Currency</td>
        <td>{{ $Quotation->currency_name }} ({{ $Quotation->symbol }})</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Customer</td>
        <td colspan="3">{{ $Quotation->company_name }}</td>
        <td style="font-weight: bold;">Contact</td>
        <td colspan="2">{{ $Quotation->contact_name }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Address</td>
        <td colspan="6">{{ $Quotation->address }}</td>
    </tr>
    <tr><td colspan="7"></td></tr>
</table>

<table>
    <thead>
        <tr>
            <th style="font-weight: bold; text-align:center; border:1px solid #000;">ITM.</th>
            <th style="font-weight: bold; text-align:center; border:1px solid #000;">Part No.</th>
            <th style="font-weight: bold; text-align:center; border:1px solid #000;">Dwg.</th>
            <th style="font-weight: bold; text-align:center; border:1px solid #000;">Description</th>
            <th style="font-weight: bold; text-align:center; border:1px solid #000;">Qty</th>
            <th style="font-weight: bold; text-align:center; border:1px solid #000;">Unit Price</th>
            <th style="font-weight: bold; text-align:center; border:1px solid #000;">Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach($Quotation->products as $product)
            <tr>
                <td style="text-align:center; border:1px solid #000;">{{ $loop->iteration }}</td>
                <td style="border:1px solid #000;">{{ $product->part_no }}@if(!empty($product->cus_code)) (Cus: {{ $product->cus_code }})@endif</td>
                <td style="text-align:center; border:1px solid #000;">{{ $product->drawing ?: '-' }}</td>
                <td style="border:1px solid #000;">{{ $product->detail_eng }}</td>
                <td style="text-align:right; border:1px solid #000;">{{ number_format($product->qty, 0) }}</td>
                <td style="text-align:right; border:1px solid #000;">{{ number_format($product->price_per_item, 2) }}</td>
                <td style="text-align:right; border:1px solid #000;">{{ number_format($product->total_price, 2) }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="5" style="border:1px solid #000; font-weight:bold;">Total in Words: ({{ Help::numberToWords($Quotation->total, $Quotation->currency_name) }})</td>
            <td style="text-align:right; border:1px solid #000; font-weight:bold;">Total ({{ $Quotation->symbol }})</td>
            <td style="text-align:right; border:1px solid #000; font-weight:bold;">{{ number_format($Quotation->total, 2) }}</td>
        </tr>
    </tbody>
</table>
