@php
    /** @var \App\Models\PackingForm $packingForm */
    $variant = $variant ?? 'customer';
    $isAccounting = $variant === 'accounting';
    $descSource = $descSource ?? 'customer';
    $custDescMap = $custDescMap ?? [];

    $descText = function ($line) use ($descSource, $custDescMap) {
        $p = $line->piProduct?->product;
        $master = trim((string) ($p?->name_th ?: $p?->name_en ?: ''));
        if ($descSource === 'master') {
            return $master;
        }
        $cid = $line->piProduct?->pi?->customer_id;
        $pid = $line->piProduct?->product_id;
        if ($cid && $pid && isset($custDescMap[$cid][$pid]) && trim((string) $custDescMap[$cid][$pid]) !== '') {
            return trim((string) $custDescMap[$cid][$pid]);
        }
        $own = trim((string) ($line->description ?? ''));
        return $own !== '' ? $own : $master;
    };
    $markNo = function ($line) {
        if ($line->from !== null && $line->to !== null) {
            return $line->from.' - '.$line->to;
        }
        return $line->from_co ?: '';
    };
    $lineUom = function ($line) {
        $uom = trim((string) ($line->uom ?? ''));
        if ($uom === '' && $line->piProduct?->product?->unitProduct) {
            $uom = trim((string) ($line->piProduct->product->unitProduct->name ?? ''));
        }
        return $uom;
    };
    $lineTotalQty = function ($line) {
        $qty = (float) ($line->qty ?? 0);
        if ($line->from !== null && $line->to !== null) {
            $pk = (int) $line->to - (int) $line->from + 1;
            if ($pk > 0) {
                return $qty * $pk;
            }
        }
        return $qty;
    };

    // ยอดรวม
    $qtyByUom = [];
    $sumNw = 0.0; $sumGw = 0.0; $totalCartons = 0; $sumAmount = 0.0; $curSymbol = '';
    foreach ($packingForm->details as $line) {
        $u = $lineUom($line);
        if ($u !== '') { $qtyByUom[$u] = ($qtyByUom[$u] ?? 0) + $lineTotalQty($line); }
        $sumNw += (float) ($line->weight_nw ?? 0);
        $sumGw += (float) ($line->weight_gw ?? 0);
        if ($line->from !== null && $line->to !== null) {
            $pk = (int) $line->to - (int) $line->from + 1;
            if ($pk > 0) { $totalCartons += $pk; }
        }
        $pip = $line->piProduct;
        if ($curSymbol === '') { $curSymbol = $pip?->pi?->currency?->symbol ?? ''; }
        $amt = $pip?->total_price;
        if (($amt === null || $amt === '') && $pip?->price_per_item && $line->qty) {
            $amt = (float) $pip->price_per_item * (float) $line->qty;
        }
        $sumAmount += (float) $amt;
    }
    $servicesTotal = 0.0;
    foreach ($packingForm->services as $sv) { $servicesTotal += (float) $sv->amount; }
    $grandTotal = $sumAmount + $servicesTotal;
    $cols = $isAccounting ? 5 : 7;
@endphp
<table border="1">
    <tr><td colspan="{{ $cols }}"><b>FORMULA INTERTRADE CO.,LTD.</b></td></tr>
    <tr><td colspan="{{ $cols }}">119 MOTORWAY ROAD THAP CHANG, SAPHAN SUNG, BANGKOK 10250, THAILAND. TEL. : 063-525-2242</td></tr>
    <tr><td colspan="{{ $cols }}"><b>{{ $isAccounting ? 'INVOICE' : 'PACKING LIST' }}</b></td></tr>
    <tr><td colspan="{{ $cols }}"></td></tr>

    <tr>
        <td colspan="{{ $isAccounting ? 3 : 4 }}"><b>By order and for account of:</b> {{ $packingForm->customer_name }}</td>
        <td><b>Invoice Date</b></td>
        <td colspan="{{ $isAccounting ? 1 : 2 }}">{{ $packingForm->doc_date ? $packingForm->doc_date->format('d/m/Y') : '' }}</td>
    </tr>
    <tr>
        <td colspan="{{ $isAccounting ? 3 : 4 }}">{{ $packingForm->customer_address ?: $packingForm->to }} {{ $packingForm->country ? '('.$packingForm->country.')' : '' }}</td>
        <td><b>Invoice No.</b></td>
        <td colspan="{{ $isAccounting ? 1 : 2 }}">{{ $packingForm->invoice_no ?: $packingForm->doc_no }}</td>
    </tr>
    @if($isAccounting)
    <tr><td colspan="3"></td><td><b>Declaration No.</b></td><td>{{ $packingForm->declaration_no }}</td></tr>
    @endif
    <tr>
        <td colspan="{{ $isAccounting ? 3 : 4 }}"></td>
        <td><b>Port of Loading</b></td>
        <td colspan="{{ $isAccounting ? 1 : 2 }}">{{ $packingForm->shipped_from }}</td>
    </tr>
    <tr>
        <td colspan="{{ $isAccounting ? 3 : 4 }}"></td>
        <td><b>Port of Discharge</b></td>
        <td colspan="{{ $isAccounting ? 1 : 2 }}">{{ $packingForm->port_of_discharge }}</td>
    </tr>
    <tr>
        <td colspan="{{ $isAccounting ? 3 : 4 }}"></td>
        <td><b>ETD</b></td>
        <td colspan="{{ $isAccounting ? 1 : 2 }}">{{ optional($packingForm->sailing_date ?: $packingForm->doc_date)->format('d/m/Y') }}</td>
    </tr>
    <tr>
        <td colspan="{{ $isAccounting ? 3 : 4 }}"></td>
        <td><b>Vessel Name</b></td>
        <td colspan="{{ $isAccounting ? 1 : 2 }}">{{ $packingForm->per_vessel }}</td>
    </tr>
    <tr>
        <td colspan="{{ $isAccounting ? 3 : 4 }}"></td>
        <td><b>Terms of Payment</b></td>
        <td colspan="{{ $isAccounting ? 1 : 2 }}">{{ $packingForm->term_of_payment }}</td>
    </tr>
    <tr><td colspan="{{ $cols }}"></td></tr>

    {{-- หัวตาราง --}}
    @if($isAccounting)
    <tr>
        <td><b>Mark&amp;No.</b></td>
        <td><b>DESCRIPTIONS</b></td>
        <td><b>QUANTITY</b></td>
        <td><b>UNIT PRICE</b></td>
        <td><b>AMOUNT</b></td>
    </tr>
    @else
    <tr>
        <td><b>Mark&amp;No.</b></td>
        <td><b>DESCRIPTIONS</b></td>
        <td><b>QTY</b></td>
        <td><b>UNIT</b></td>
        <td><b>TOTAL QTY.</b></td>
        <td><b>N.W.(KGS.)</b></td>
        <td><b>G.W.(KGS.)</b></td>
    </tr>
    @endif

    {{-- แถว Marks --}}
    <tr>
        <td>{{ trim((string) $packingForm->marks) }}</td>
        <td colspan="{{ $cols - 1 }}">@if($totalCartons)(TOTAL NO. OF PACKAGES : {{ number_format($totalCartons) }} CARTONS)@endif</td>
    </tr>

    @php $no = 0; @endphp
    @foreach($packingForm->details as $line)
        @php $no++; @endphp
        <tr>
            <td>{{ $isAccounting ? $no : $markNo($line) }}</td>
            <td>{{ trim(($line->part_no ? $line->part_no.' ' : '').$descText($line)) }}</td>
            @if($isAccounting)
            <td>{{ $line->qty }}</td>
            <td>{{ $curSymbol }} {{ $line->piProduct?->price_per_item !== null ? number_format((float) $line->piProduct->price_per_item, 2) : '' }}</td>
            <td>{{ $curSymbol }} {{ $line->piProduct?->total_price !== null ? number_format((float) $line->piProduct->total_price, 2) : '' }}</td>
            @else
            <td>{{ $line->qty !== null ? number_format((int) $line->qty) : '' }}</td>
            <td>{{ $lineUom($line) }}</td>
            <td>{{ number_format($lineTotalQty($line)) }}</td>
            <td>{{ $line->weight_nw !== null ? number_format((float) $line->weight_nw, 2) : '' }}</td>
            <td>{{ $line->weight_gw !== null ? number_format((float) $line->weight_gw, 2) : '' }}</td>
            @endif
        </tr>
    @endforeach

    {{-- สรุป --}}
    @if($isAccounting)
        @foreach($packingForm->services as $sv)
        <tr>
            <td colspan="3"></td>
            <td>{{ $sv->name }}</td>
            <td>{{ $curSymbol }} {{ number_format((float) $sv->amount, 2) }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="3"></td>
            <td><b>TOTAL</b></td>
            <td><b>{{ $curSymbol }} {{ number_format($grandTotal, 2) }}</b></td>
        </tr>
    @else
        @foreach($qtyByUom as $uom => $total)
        <tr>
            <td colspan="2"><b>***** TOTAL *****</b></td>
            <td></td><td></td>
            <td><b>{{ number_format($total, 2) }} {{ $uom }}</b></td>
            <td></td><td></td>
        </tr>
        @endforeach
        <tr>
            <td colspan="4"><b>TOTAL : {{ number_format($totalCartons) }} CARTONS</b></td>
            <td></td>
            <td><b>{{ number_format($sumNw, 2) }}</b></td>
            <td><b>{{ number_format($sumGw, 2) }}</b></td>
        </tr>
        <tr>
            <td colspan="{{ $cols }}">PKG: {{ number_format($totalCartons) }} | Qty: {{ number_format((int) $packingForm->qty) }} | CBM: {{ number_format((float) $packingForm->cubic_meter, 2) }} | N.T.: {{ number_format((float) $packingForm->weight_nt, 2) }} | G.T.: {{ number_format((float) $packingForm->weight_gt, 2) }}</td>
        </tr>
    @endif
</table>
