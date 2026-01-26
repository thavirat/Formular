<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <style>
        @media print {
            body { margin: 0; padding: 20px; font-size: 12px; }
            .no-print { display: none; }
        }
        .invoice-box { max-width: 800px; margin: auto; padding: 10px; border: 1px solid #eee; }
        .w-100 { width: 100%; }
        .table-items { border-collapse: collapse; margin-top: 20px; }
        .table-items th { background: #eee; border: 1px solid #ddd; padding: 8px; text-align: center; }
        .table-items td { border: 1px solid #ddd; padding: 8px; vertical-align: top; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .border-double { border-bottom: 3px double #000; }
        .shipping-marks { line-height: 1.4; min-height: 100px; }
    </style>
</head>
<body>
<div class="invoice-box">
    <table cellpadding="0" cellspacing="0" class="w-100">
        <tr>
            <td width="50%">
                <h2 class="text-bold">PROFORMA INVOICE</h2>
                <div class="shipping-marks border p-2 mt-2">
                    <strong>SHIPPING MARKS:</strong><br>
                    {{ $pi->company_name }}<br>
                    {{ $pi->address }}<br>
                    AUSTRALIA [cite: 7]
                </div>
            </td>
            <td width="50%" class="align-top text-right">
                <table class="w-100 table-bordered">
                    <tr>
                        <td class="bg-light p-1">Doc No:</td>
                        <td class="p-1">{{ $pi->doc_no }}</td> [cite: 2]
                    </tr>
                    <tr>
                        <td class="bg-light p-1">Date:</td>
                        <td class="p-1">{{ date('d/m/Y', strtotime($pi->doc_date)) }}</td> [cite: 2]
                    </tr>
                    <tr>
                        <td class="bg-light p-1">Cust PO:</td>
                        <td class="p-1">{{ $pi->quotation_id }}</td> [cite: 2]
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="table-items w-100 mt-4">
        <thead>
            <tr>
                <th width="5%">ITM</th> [cite: 12]
                <th width="10%">Qty</th> [cite: 13]
                <th width="20%">Part No / Customer P/No</th> [cite: 13, 16]
                <th>Description / Drawing</th> [cite: 15]
                <th width="15%">Unit Price</th> [cite: 14]
                <th width="15%">Amount</th> [cite: 14]
            </tr>
        </thead>
        <tbody>
            @foreach($pi->Products as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td> [cite: 17, 18]
                <td class="text-center">{{ number_format($item->qty) }} PCS</td> [cite: 18]
                <td>
                    <div class="text-bold">{{ $item->part_no }}</div>
                    <div class="text-muted text-80">Ref: {{ $item->cus_code }}</div> [cite: 18]
                </td>
                <td>
                    <div>{{ $item->detail_eng }}</div> [cite: 18]
                    <small>DWG: {{ $item->drawing }}</small> [cite: 15, 18]
                </td>
                <td class="text-right">{{ number_format($item->price_per_item, 2) }}</td> [cite: 18]
                <td class="text-right">{{ number_format($item->total_price, 2) }}</td> [cite: 18]
            </tr>
            @endforeach
        </tbody>
        {{-- <tfoot>
            <tr>
                <td colspan="5" class="text-right text-bold font-italic">TOTAL AMOUNT ({{ $pi->currency->symbol }})</td>
                <td class="text-right text-bold border-double">{{ number_format($pi->total, 2) }}</td>
            </tr>
        </tfoot> --}}
    </table>

    <div class="remarks mt-4 p-2 border">
        <strong>REMARKS:</strong><br>
        1. Packing in Box Type "A" [cite: 20]<br>
        2. Print JAY AIR on boxes [cite: 21]<br>
        3. Attach JAY AIR stickers with Part No. and Barcode [cite: 22, 23]
    </div>
</div>
</body>
</html>
