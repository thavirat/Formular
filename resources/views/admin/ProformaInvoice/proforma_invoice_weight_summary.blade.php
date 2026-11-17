<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PI Weight / CBM Summary</title>
    <style>
        @page { sheet-size: A4-L; margin: 1.2cm; }
        body { font-family: 'garuda'; font-size: 11px; color: #222; }
        .title { text-align: center; font-weight: bold; font-size: 20px; margin-bottom: 2px; }
        .subtitle { text-align: center; font-size: 11px; margin-bottom: 14px; color: #555; }
        .pi-head { margin: 14px 0 4px; font-size: 11px; }
        .pi-head .doc { font-weight: bold; font-size: 13px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #666; padding: 4px 6px; }
        th { background: #efefef; text-align: center; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .sub td { font-weight: bold; background: #f7f7f7; }
        .grand td { font-weight: bold; background: #e7eefc; font-size: 12px; }
        .grand-wrap { margin-top: 16px; }
    </style>
</head>
<body>
    <div class="title">Summary of CBM &amp; Weight by Order</div>
    <div class="subtitle">สรุปคิว (CBM) และน้ำหนัก — รวม {{ $pis->count() }} PI · พิมพ์ {{ date('d/m/Y H:i') }}</div>

    @php $grandCube = 0; $grandWeight = 0; $grandQty = 0; @endphp

    @foreach($pis as $pi)
        @php $faNo = \Illuminate\Support\Str::startsWith($pi->doc_no, 'PI') ? 'FA'.substr($pi->doc_no, 2) : $pi->doc_no; @endphp
        <div class="pi-head">
            <span class="doc">{{ $pi->doc_no }}</span>
            <span style="color:#888;"> ({{ $faNo }})</span>
            &nbsp;·&nbsp; {{ $pi->customer_name ?: $pi->company_name }}
            &nbsp;·&nbsp; วันที่ {{ $pi->doc_date ? date('d/m/Y', strtotime($pi->doc_date)) : '-' }}
        </div>
        <table>
            <thead>
                <tr>
                    <th width="32">ITM.</th>
                    <th width="110">Part No.</th>
                    <th width="90">Dwg.</th>
                    <th>Description</th>
                    <th width="52">W</th>
                    <th width="52">L</th>
                    <th width="52">H</th>
                    <th width="58">Qty/ลัง</th>
                    <th width="48">Qty</th>
                    <th width="72">CBM</th>
                    <th width="62">นน./หน่วย</th>
                    <th width="78">นน.รวม (KG)</th>
                </tr>
            </thead>
            <tbody>
                @php $piCube = 0; $piWeight = 0; $piQty = 0; @endphp
                @forelse($pi->products as $product)
                    @php
                        $w = (float) ($product->p_width ?? 0);
                        $l = (float) ($product->p_length ?? 0);
                        $h = (float) ($product->p_height ?? 0);
                        $content = (float) ($product->p_content ?? 0);
                        $unitWeight = (float) ($product->p_weight ?? 0);
                        $qty = (float) ($product->qty ?? 0);
                        // CBM = (กว้าง/100 * ยาว/100 * สูง/100) * จำนวน / จำนวนต่อลัง (เหมือนใบเสนอราคา)
                        $cartonVol = ($w / 100) * ($l / 100) * ($h / 100);
                        $lineCube = $content > 0 ? ($cartonVol * $qty / $content) : 0;
                        $lineWeight = $unitWeight * $qty;
                        $piCube += $lineCube; $piWeight += $lineWeight; $piQty += $qty;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $product->part_no ?: '-' }}</td>
                        <td class="text-center">{{ $product->drawing ?: '-' }}</td>
                        <td>{{ $product->product_name ?: $product->detail_eng ?: '-' }}</td>
                        <td class="text-right">{{ number_format($w, 2) }}</td>
                        <td class="text-right">{{ number_format($l, 2) }}</td>
                        <td class="text-right">{{ number_format($h, 2) }}</td>
                        <td class="text-center">{{ $content > 0 ? number_format($content, 0) : '-' }}</td>
                        <td class="text-right">{{ number_format($qty, 0) }}</td>
                        <td class="text-right">{{ number_format($lineCube, 3) }}</td>
                        <td class="text-right">{{ $unitWeight > 0 ? number_format($unitWeight, 2) : '-' }}</td>
                        <td class="text-right">{{ number_format($lineWeight, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="12" class="text-center">ไม่มีรายการสินค้า</td></tr>
                @endforelse
                <tr class="sub">
                    <td colspan="8" class="text-right">รวม {{ $pi->doc_no }}</td>
                    <td class="text-right">{{ number_format($piQty, 0) }}</td>
                    <td class="text-right">{{ number_format($piCube, 3) }}</td>
                    <td></td>
                    <td class="text-right">{{ number_format($piWeight, 2) }}</td>
                </tr>
            </tbody>
        </table>
        @php $grandCube += $piCube; $grandWeight += $piWeight; $grandQty += $piQty; @endphp
    @endforeach

    <div class="grand-wrap">
        <table>
            <tr class="grand">
                <td style="width:60%" class="text-right">รวมทั้งหมด ({{ $pis->count() }} PI)</td>
                <td class="text-center" width="70">Qty: {{ number_format($grandQty, 0) }}</td>
                <td class="text-right" width="130">CBM รวม: {{ number_format($grandCube, 3) }}</td>
                <td class="text-right" width="140">น้ำหนักรวม: {{ number_format($grandWeight, 2) }} KG</td>
            </tr>
        </table>
    </div>
</body>
</html>
