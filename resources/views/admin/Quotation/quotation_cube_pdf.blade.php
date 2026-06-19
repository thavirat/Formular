<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $Quotation->doc_no }} - CUBE</title>
    <style>
        @page {
            margin: 1.2cm;
        }
        body {
            font-family: 'garuda';
            font-size: 11px;
            color: #222;
        }
        .title {
            text-align: center;
            font-weight: bold;
            font-size: 20px;
            margin-bottom: 4px;
        }
        .subtitle {
            text-align: center;
            font-size: 11px;
            margin-bottom: 15px;
        }
        .meta {
            margin-bottom: 10px;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #666;
            padding: 5px 6px;
        }
        th {
            background: #efefef;
            text-align: center;
            font-weight: bold;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .tfoot td {
            font-weight: bold;
            background: #f7f7f7;
        }
    </style>
</head>
<body>
    <div class="title">Summary of CBM Volume by Order</div>
    <div class="subtitle">Quotation No. {{ $Quotation->doc_no }}</div>

    <div class="meta">
        Document Date: {{ $Quotation->doc_date ? date('d/m/Y', strtotime($Quotation->doc_date)) : '-' }}
    </div>

    @php $grandCube = 0; @endphp
    <table>
        <thead>
            <tr>
                <th width="40">ITM.</th>
                <th width="130">Part No.</th>
                <th>Description</th>
                <th width="65">W</th>
                <th width="65">L</th>
                <th width="65">H</th>
                <th width="70">Qty/Carton</th>
                <th width="55">Qty</th>
                <th width="85">CBM</th>
            </tr>
        </thead>
        <tbody>
            @forelse($Quotation->products as $product)
                @php
                    $width = (float) ($product->width ?? 0);
                    $length = (float) ($product->length ?? 0);
                    $height = (float) ($product->height ?? 0);
                    $qty = (float) ($product->qty ?? 0);
                    $content = (float) ($product->content ?? 0);
                    // คิว(CBM) = (กว้าง/100 * ยาว/100 * สูง/100) * จำนวนสั่งซื้อ / CONTENT(จำนวนต่อลัง) — ลูกค้าให้หารแต่ละด้านด้วย 100 ก่อน
                    $cartonVolume = ($width / 100) * ($length / 100) * ($height / 100);
                    $lineCube = $content > 0 ? ($cartonVolume * $qty / $content) : 0;
                    $grandCube += $lineCube;
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $product->part_no ?: '-' }}</td>
                    <td>{{ $product->product_name ?: $product->detail_eng ?: '-' }}</td>
                    <td class="text-right">{{ number_format($width, 2) }}</td>
                    <td class="text-right">{{ number_format($length, 2) }}</td>
                    <td class="text-right">{{ number_format($height, 2) }}</td>
                    <td class="text-center">{{ $content > 0 ? number_format($content, 0) : '-' }}</td>
                    <td class="text-right">{{ number_format($qty, 0) }}</td>
                    <td class="text-right">{{ number_format($lineCube, 4) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">No products found</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot class="tfoot">
            <tr>
                <td colspan="8" class="text-right">Total Volume (CBM)</td>
                <td class="text-right">{{ number_format($grandCube, 4) }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
