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
    <div class="title">ใบสรุปจำนวนคิว</div>
    <div class="subtitle">Quotation No. {{ $Quotation->doc_no }}</div>

    <div class="meta">
        วันที่เอกสาร: {{ $Quotation->doc_date ? date('d/m/Y', strtotime($Quotation->doc_date)) : '-' }}
    </div>

    @php $grandCube = 0; @endphp
    <table>
        <thead>
            <tr>
                <th width="40">ลำดับ</th>
                <th width="130">รหัสสินค้า</th>
                <th>ชื่อสินค้า</th>
                <th width="65">กว้าง</th>
                <th width="65">ยาว</th>
                <th width="65">สูง</th>
                <th width="70">Qty/Item</th>
                <th width="55">Qty</th>
                <th width="85">คิว</th>
            </tr>
        </thead>
        <tbody>
            @forelse($Quotation->products as $product)
                @php
                    $width = (float) ($product->width ?? 0);
                    $length = (float) ($product->length ?? 0);
                    $height = (float) ($product->height ?? 0);
                    $qtyPerItem = (float) ($product->qty_per_item ?? 1);
                    $qty = (float) ($product->qty ?? 0);
                    $cubePerUnit = (float) ($product->cube ?? 0);
                    // width/length/height เก็บเป็นมิลลิเมตร จึงแปลงเป็นลูกบาศก์เมตรก่อน
                    $cubeFromDimension = ($width * $length * $height) / 1000000000;
                    $lineCube = $cubePerUnit > 0
                        ? ($cubePerUnit * $qty * $qtyPerItem)
                        : ($cubeFromDimension * $qty * $qtyPerItem);
                    $grandCube += $lineCube;
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $product->part_no ?: '-' }}</td>
                    <td>{{ $product->product_name ?: $product->detail_eng ?: '-' }}</td>
                    <td class="text-right">{{ number_format($width, 2) }}</td>
                    <td class="text-right">{{ number_format($length, 2) }}</td>
                    <td class="text-right">{{ number_format($height, 2) }}</td>
                    <td class="text-right">{{ number_format($qtyPerItem, 2) }}</td>
                    <td class="text-right">{{ number_format($qty, 0) }}</td>
                    <td class="text-right">{{ number_format($lineCube, 4) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">ไม่พบรายการสินค้า</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot class="tfoot">
            <tr>
                <td colspan="8" class="text-right">คิวรวม</td>
                <td class="text-right">{{ number_format($grandCube, 4) }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
