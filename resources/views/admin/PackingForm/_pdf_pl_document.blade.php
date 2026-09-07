@php
    /** @var \App\Models\PackingForm $packingForm */
    /** @var string $variant customer|accounting */
    $variant = $variant ?? 'customer';
    $isAccounting = $variant === 'accounting';

    /** @var string $descSource master|customer — เลือกว่า description ใช้ master ของสินค้า หรือ master ของลูกค้า */
    $descSource = $descSource ?? 'customer';
    $custDescMap = $custDescMap ?? [];

    // ข้อความ description ต่อแถว ตามแหล่งที่เลือก
    $descText = function ($line) use ($descSource, $custDescMap) {
        $p = $line->piProduct?->product;
        $master = trim((string) ($p?->name_th ?: $p?->name_en ?: ''));
        if ($descSource === 'master') {
            return $master;
        }
        // customer: master ของลูกค้า (ถ้ามี) -> description ที่คีย์ในใบ -> ชื่อ master
        $cid = $line->piProduct?->pi?->customer_id;
        $pid = $line->piProduct?->product_id;
        if ($cid && $pid && isset($custDescMap[$cid][$pid]) && trim((string) $custDescMap[$cid][$pid]) !== '') {
            return trim((string) $custDescMap[$cid][$pid]);
        }
        $own = trim((string) ($line->description ?? ''));
        return $own !== '' ? $own : $master;
    };

    $logo = null;
    if (isset($settings['logo'])) {
        $logoFiles = json_decode($settings['logo']);
        $logo = (is_array($logoFiles) && count($logoFiles) > 0) ? $logoFiles[0] : null;
    }

    $fmt = function ($v, $dec = 2) {
        if ($v === null || $v === '') {
            return '';
        }
        return number_format((float) $v, $dec);
    };
    $fmtInt = function ($v) {
        if ($v === null || $v === '') {
            return '';
        }
        return number_format((int) $v);
    };
    $markNo = function ($line) use ($fmtInt) {
        if ($line->from !== null && $line->to !== null) {
            return $fmtInt($line->from).' - '.$fmtInt($line->to);
        }
        if ($line->from_co) {
            return $line->from_co;
        }
        return '';
    };
    $customerDesc = function ($line) use ($descText) {
        $part = trim((string) ($line->part_no ?? ''));
        $desc = trim((string) $descText($line));
        if ($desc === '') {
            return $part;
        }
        if ($part !== '' && strlen($desc) <= 55) {
            return $part.' '.$desc;
        }
        if ($part !== '') {
            return $part.' '.\Illuminate\Support\Str::limit($desc, 55);
        }
        return \Illuminate\Support\Str::limit($desc, 80);
    };
    /** ราคาจาก PI ที่ผูกกับแถว (pi_product_id) */
    $linePricing = function ($line) use ($fmt) {
        $pip = $line->piProduct;
        $currency = $pip?->pi?->currency;
        $unitPrice = $pip?->price_per_item;
        $amount = $pip?->total_price;
        if (($amount === null || $amount === '') && $unitPrice !== null && $unitPrice !== '' && $line->qty) {
            $amount = (float) $unitPrice * (float) $line->qty;
        }

        return [
            'symbol' => $currency?->symbol ?? '',
            'unit_price' => $unitPrice !== null && $unitPrice !== '' ? $fmt($unitPrice) : '',
            'amount' => $amount !== null && $amount !== '' ? $fmt($amount) : '',
        ];
    };
    $lineUom = function ($line) {
        $uom = trim((string) ($line->uom ?? ''));
        if ($uom === '' && $line->piProduct?->product?->unitProduct) {
            $uom = trim((string) ($line->piProduct->product->unitProduct->name ?? ''));
        }

        return $uom;
    };
    /** จำนวนรวมต่อแถว (คูณช่วงกล่อง from–to เมื่อมี) */
    $lineTotalQty = function ($line) {
        $qty = (float) ($line->qty ?? 0);
        if ($line->from !== null && $line->to !== null) {
            $packages = (int) $line->to - (int) $line->from + 1;
            if ($packages > 0) {
                return $qty * $packages;
            }
        }

        return $qty;
    };
    // รวมจำนวนแยกตามหน่วย (แสดงแถว ***** TOTAL ***** ล่างตาราง ทั้ง Packing List และ Invoice)
    // - Invoice (accounting): รวม qty ตามที่แสดงในคอลัมน์ QUANTITY (= $line->qty)
    // - Packing List: รวมจำนวนรวมต่อแถว (คูณช่วงกล่อง from-to)
    $qtyByUom = [];
    $sumDetailNw = 0.0;
    $sumDetailGw = 0.0;
    foreach ($packingForm->details as $line) {
        $uom = $lineUom($line);
        if ($uom === '') {
            continue;
        }
        $qtyVal = $isAccounting ? (float) ($line->qty ?? 0) : $lineTotalQty($line);
        $qtyByUom[$uom] = ($qtyByUom[$uom] ?? 0) + $qtyVal;
        $sumDetailNw += (float) ($line->weight_nw ?? 0);
        $sumDetailGw += (float) ($line->weight_gw ?? 0);
    }

    // FA ต่อรายการ = doc_no ของ PI ที่ผูก สลับ PI -> FA (เลขเต็ม); ไม่มี PI = ''
    $faOf = function ($line) {
        $doc = $line->piProduct?->pi?->doc_no;
        if (!$doc) {
            return '';
        }
        return \Illuminate\Support\Str::startsWith($doc, 'PI') ? 'FA'.substr($doc, 2) : $doc;
    };

    // จัดกลุ่มซ้อน: FA (หัวใหญ่) -> Product Category (ย่อย) -> รายการ; คงลำดับเดิม
    $faGroups = [];
    foreach ($packingForm->details as $line) {
        $fa = $faOf($line);
        $cat = $line->piProduct?->product?->category?->name_en
            ?: ($line->piProduct?->product?->category?->name ?? '');
        $catKey = $cat !== '' ? $cat : '__NONE__';
        $faGroups[$fa][$catKey][] = $line;
    }
    // เลข FA ที่ไม่ซ้ำ (ไว้เติมใน marks)
    $faList = array_values(array_filter(array_keys($faGroups), fn($f) => $f !== ''));

    // จำนวนคาร์ตันรวม = ผลรวมจำนวนกล่องทุกแถว Σ(to − from + 1) — คำนวณสด ไม่พึ่งค่า pkg (M8)
    // + ผลรวมน้ำหนัก NET/GROSS (สำหรับ Invoice) + incoterm จาก PI (บรรทัด FOB)
    $totalCartons = 0;
    $sumWeightNw = 0.0;
    $sumWeightGw = 0.0;
    $incotermCode = '';
    foreach ($packingForm->details as $line) {
        if ($line->from !== null && $line->to !== null) {
            $pk = (int) $line->to - (int) $line->from + 1;
            if ($pk > 0) {
                $totalCartons += $pk;
            }
        }
        $sumWeightNw += (float) ($line->weight_nw ?? 0);
        $sumWeightGw += (float) ($line->weight_gw ?? 0);
        if ($incotermCode === '') {
            $incotermCode = (string) ($line->piProduct?->pi?->incoterm?->code ?? '');
        }
    }

    // Remark ตาม PI: เก็บ remark ของ PI แต่ละใบที่ปรากฏในรายการ ตามลำดับที่พบ (PI แรก + PI ใหม่ที่โผล่ต่อ)
    $piRemarks = [];
    foreach ($packingForm->details as $line) {
        $pi = $line->piProduct?->pi;
        if ($pi && !array_key_exists($pi->id, $piRemarks)) {
            $rms = $pi->remarks;
            if ($rms && $rms->count() > 0) {
                $piRemarks[$pi->id] = ['doc_no' => $pi->doc_no, 'remarks' => $rms];
            }
        }
    }

    // ยอดรวมเงิน (สำหรับ Invoice)
    $sumAmount = 0.0;
    $curSymbol = '';
    if ($isAccounting) {
        foreach ($packingForm->details as $line) {
            $pip = $line->piProduct;
            if ($curSymbol === '') {
                $curSymbol = $pip?->pi?->currency?->symbol ?? '';
            }
            $amt = $pip?->total_price;
            if (($amt === null || $amt === '') && $pip?->price_per_item && $line->qty) {
                $amt = (float) $pip->price_per_item * (float) $line->qty;
            }
            $sumAmount += (float) $amt;
        }
    }

    // ค่าบริการอื่น (แสดงบน Invoice) + ยอดรวมสุทธิ
    $servicesTotal = 0.0;
    foreach ($packingForm->services as $sv) {
        $servicesTotal += (float) $sv->amount;
    }
    $grandTotal = $sumAmount + $servicesTotal;

    $docTitle = $isAccounting ? 'INVOICE' : 'PACKING LIST';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>PACKING LIST {{ $packingForm->doc_no }}</title>
    <style>
        @page { header: html_plheader; }
        body {
            font-family: 'garuda', sans-serif;
            font-size: 10px;
            color: #111;
            margin: 0;
            padding: 0;
        }
        .doc-title { font-size: 15px; font-weight: bold; text-align: center; }
        .marks-block { font-weight: bold; margin: 4px 0 6px 0; line-height: 1.4; }
        .items .fa-row td { font-weight: bold; text-align: left; background: #dcdcdc; }
        .items .cat-row td { font-weight: bold; text-align: left; background: #f1f1f1; }
        .header-top {
            width: 100%;
            border-collapse: collapse;
        }
        .header-top td {
            vertical-align: top;
            padding: 0;
        }
        .header-top .col-logo {
            width: 50px;
            padding-right: 10px;
        }
        .header-top .col-company {
            padding-left: 0;
        }
        .company-logo {
            height: 35px;
            width: auto;
        }
        .company-en { font-size: 12px; font-weight: bold; }
        .company-th { font-size: 11px; }
        .company-addr { font-size: 9px; line-height: 1.35; }
        .doc-title {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            margin: 6px 0 4px;
            letter-spacing: 0.5px;
        }
        .pl-date {
            text-align: center;
            font-size: 11px;
            margin-bottom: 6px;
        }
        .header-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }
        .header-grid td {
            vertical-align: top;
            padding: 2px 4px;
            font-size: 9px;
            line-height: 1.4;
        }
        .lbl { font-weight: bold; white-space: nowrap; }
        .items {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
            border: 1px solid #000;   /* กรอบนอกตาราง */
        }
        /* หัวตาราง: เส้นครบทุกด้าน (มีเส้นใต้หัว) */
        .items th {
            border: 1px solid #000;
            vertical-align: top;
            padding: 4px 3px;
            text-align: center;
            font-weight: bold;
        }
        /* เนื้อหา: เส้นครบทุกด้าน (หัว/ท้าย/หัวกลุ่ม/ยอดรวม คงเส้นไว้) */
        .items td {
            border: 1px solid #000;
            vertical-align: top;
            padding: 3px 3px;
        }
        /* เฉพาะแถว "รายการสินค้า": ตัดเส้นนอนระหว่างแถวออก (คงเส้นตั้ง) */
        .items tr.item-row td {
            border-top: none;
            border-bottom: none;
        }
        /* ตารางซ้อนในเซลล์ (เช่น UNIT PRICE) ไม่ต้องมีเส้น */
        .items td table td,
        .items td table th {
            border: none !important;
            padding: 0;
        }
        /* คอลัมน์ย่อย QUANTITY (จำนวน | หน่วย) รวมเป็นช่องเดียว ไม่มีเส้นแบ่งกลาง */
        .items .col-qty-num { border-right: none; }
        .items .col-qty-uom { border-left: none; }
        /* แถวสรุป (***** TOTAL *****): ไม่มีเส้นบน/ล่าง ไหลต่อจากรายการสินค้า (เหลือเส้นปิดแถวสุดท้ายเส้นเดียว) */
        .items tfoot td {
            font-weight: bold;
            border-top: none;
            border-bottom: none;
        }
        .items .mark-row td {
            border-bottom: none;
            padding-bottom: 1px;
        }
        .items .desc-row td {
            padding-top: 0;
            padding-bottom: 5px;
            font-size: {{ $isAccounting ? '8px' : '9px' }};
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .from-co-row td {
            font-weight: bold;
            padding-top: 6px;
            padding-bottom: 4px;
        }
        .totals {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
            font-size: 9px;
        }
        .totals td {
            border: 1px solid #999;
            padding: 4px 6px;
        }
    </style>
</head>
<body>

<htmlpageheader name="plheader">
<table width="100%" class="header-top" cellpadding="0" cellspacing="0" style="margin-bottom: 6px;">
    <tr>
        @if($logo)
        <td class="col-logo" width="50" valign="top">
            <img src="{{ asset('uploads/SettingSystem/'.$logo) }}" alt="" class="company-logo" height="40">
        </td>
        @endif
        <td class="col-company" valign="top">
            <span class="company-en">FORMULA INTERTRADE CO.,LTD.</span><br>
            <span class="company-th">บริษัท ฟอร์มูล่า อินเตอร์เทรด จำกัด</span><br>
            <span class="company-addr">119 MOTORWAY ROAD THAP CHANG, SAPHAN SUNG, BANGKOK 10250, THAILAND.</span>
        </td>
        <td width="130" valign="top" class="text-right company-addr">
            TEL. : 063-525-2242
        </td>
    </tr>
</table>

<table width="100%" style="margin-bottom:4px;">
    <tr>
        <td width="22%"></td>
        <td width="56%" class="doc-title">{{ $docTitle }}</td>
        <td width="22%" class="text-right" style="vertical-align:middle;">PAGE {PAGENO} / {nbpg}</td>
    </tr>
</table>

<table class="header-grid">
    <tr>
        <td width="55%">
            <span class="lbl">By order and for account of :</span><br>
            <span class="text-bold">{{ $packingForm->customer_name ?: '-' }}</span><br>
            @if($packingForm->customer_address)
                {!! nl2br(e($packingForm->customer_address)) !!}<br>
            @elseif($packingForm->to)
                {{ $packingForm->to }}<br>
            @endif
            @if($packingForm->customer_phone)
                TEL:{{ $packingForm->customer_phone }}
            @endif
            @if($packingForm->country)
                <br>{{ $packingForm->country }}
            @endif
        </td>
        <td width="45%">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="lbl" width="38%">{{ $isAccounting ? 'Invoice Date' : 'Packing List Date' }}</td>
                    <td>{{ $packingForm->doc_date ? $packingForm->doc_date->format('d/m/Y') : '' }}</td>
                </tr>
                <tr>
                    <td class="lbl">{{ $isAccounting ? 'Invoice No.' : 'Packing List No.' }}</td>
                    <td>{{ $packingForm->invoice_no ?: $packingForm->doc_no ?: '-' }}</td>
                </tr>
                @if($isAccounting)
                <tr>
                    <td class="lbl">Declaration No.</td>
                    <td>{{ $packingForm->declaration_no ?: '-' }}</td>
                </tr>
                @endif
                <tr>
                    <td class="lbl">Port of Loading</td>
                    <td>{{ $packingForm->shipped_from ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="lbl">Port of Discharge</td>
                    <td>{{ $packingForm->port_of_discharge ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="lbl">Estimated Time of Departure</td>
                    <td>{{ optional($packingForm->sailing_date ?: $packingForm->doc_date)->format('d/m/Y') ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="lbl">Vessel Name</td>
                    <td>{{ $packingForm->per_vessel ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="lbl">L/C No.</td>
                    <td>{{ $packingForm->lc_no ?: '' }}</td>
                </tr>
                <tr>
                    <td class="lbl">Terms of Payment</td>
                    <td>{{ $packingForm->term_of_payment ?: '' }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</htmlpageheader>

<table class="items{{ !$isAccounting && count($qtyByUom) > 0 ? ' items-has-foot' : '' }}">
    <thead>
        @if($isAccounting)
        <tr>
            <th width="12%">Mark&amp;No.</th>
            <th>DESCRIPTIONS</th>
            <th width="12%">QUANTITY</th>
            <th width="13%">UNIT PRICE</th>
            <th width="13%">AMOUNT</th>
        </tr>
        @else
        <tr>
            <th width="12%">Mark&amp;No.</th>
            <th>DESCRIPTIONS</th>
            <th colspan="2" width="15%">QUANTITY</th>
            <th width="9%">TOTAL QTY.</th>
            <th width="10%">N.W.(KGS.)</th>
            <th width="10%">G.W.(KGS.)</th>
        </tr>
        @endif
    </thead>
    <tbody>
        @php $k = 0; @endphp
        @php $hasMarkHead = trim((string) $packingForm->marks) !== '' || count($faList); @endphp
        @if($hasMarkHead || $totalCartons)
        {{-- แถว Marks + FA (เต็มความกว้าง เริ่มคอลัมน์แรก ชิดซ้าย) --}}
        @if($hasMarkHead)
        <tr>
            <td colspan="{{ $isAccounting ? 5 : 7 }}" style="vertical-align:top; font-weight:bold; text-align:left; {{ $totalCartons ? 'border-bottom:none;' : '' }}">
                {!! nl2br(e(trim((string) $packingForm->marks))) !!}
                @if(count($faList))<br>{{ implode(' , ', $faList) }}@endif
            </td>
        </tr>
        @endif
        {{-- แถว TOTAL NO. OF PACKAGES (เต็มความกว้าง เริ่มคอลัมน์แรก ชิดซ้ายสุด) --}}
        @if($totalCartons)
        <tr>
            <td colspan="{{ $isAccounting ? 5 : 7 }}" style="text-align:left; {{ $hasMarkHead ? 'border-top:none;' : '' }}">(TOTAL NO. OF PACKAGES : {{ number_format($totalCartons) }} CARTONS)</td>
        </tr>
        @endif
        @endif
        @forelse($faGroups as $fa => $cats)
            @if($fa !== '')
            <tr class="fa-row"><td colspan="{{ $isAccounting ? 5 : 7 }}">MARK &amp; NO. : {{ $fa }}</td></tr>
            @endif
            @foreach($cats as $catKey => $catLines)
            @if($catKey !== '__NONE__')
            <tr class="cat-row"><td colspan="{{ $isAccounting ? 5 : 7 }}">{{ $catKey }}</td></tr>
            @endif
            @foreach($catLines as $line)
            @php($price = $linePricing($line)) @php($k++)
            <tr class="item-row">
                <td align="center">{{ $isAccounting ? $k : $markNo($line) }}</td>
                <td>
                    @if($isAccounting)
                        {{ trim(($line->part_no ?? '').' '.$descText($line)) }}
                    @else
                        {{ $customerDesc($line) }}
                    @endif
                </td>
                @if($isAccounting)
                <td class="text-center">{{ $line->qty }} {{ $lineUom($line) }}</td>
                <td class="text-right" style="white-space:nowrap;">{{ trim(($price['symbol'] ?? '').' '.($price['unit_price'] ?? '')) }}</td>
                <td class="text-right" style="white-space:nowrap;">{{ $price['amount'] }}</td>
                @else
                <td class="text-right col-qty-num" width="8%">
                    @if($line->qty !== null && $line->qty !== '')
                        {{ $fmtInt($line->qty) }}
                    @endif
                </td>
                <td class="text-center col-qty-uom" width="8%">{{ $lineUom($line) }}</td>
                <td class="text-right">{{ $fmtInt($lineTotalQty($line)) }}</td>
                <td class="text-right">{{ $fmt($line->weight_nw) }}</td>
                <td class="text-right">{{ $fmt($line->weight_gw) }}</td>
                @endif
            </tr>
            @endforeach
            @endforeach
        @empty
            <tr>
                <td colspan="{{ $isAccounting ? 5 : 7 }}" class="text-center">ไม่มีรายการ</td>
            </tr>
        @endforelse
        {{-- Invoice: ถ้าไม่มีหน่วยให้สรุป ปิดเส้นล่างใต้แถวสุดท้ายเอง (item-row ตัด border-bottom ไว้) --}}
        @if($isAccounting && count($faGroups) && count($qtyByUom) === 0)
        <tr><td colspan="5" style="border:none; border-top:1px solid #000; padding:0; height:1px; line-height:1px;"></td></tr>
        @endif
    </tbody>
    @if(count($qtyByUom) > 0)
    <tfoot>
        {{-- สรุปจำนวนแยกตามหน่วย: แยกเซลล์ทุกคอลัมน์ (เส้นตั้งครบ), แถวแรกมีเส้นบน = เส้นล่างของรายการสินค้าแถวสุดท้าย --}}
        @foreach($qtyByUom as $uom => $total)
        @php($bt = $loop->first ? 'border-top:1px solid #000;' : '')
        <tr class="row-qty-summary">
            @if($isAccounting)
            <td style="{{ $bt }}"></td>
            <td class="text-center text-bold" style="{{ $bt }}">***** TOTAL *****</td>
            <td class="text-center text-bold" style="{{ $bt }}">{{ $fmt($total) }} {{ $uom }}</td>
            <td style="{{ $bt }}"></td>
            <td style="{{ $bt }}"></td>
            @else
            <td style="{{ $bt }}"></td>
            <td class="text-center text-bold" style="{{ $bt }}">***** TOTAL *****</td>
            <td class="col-qty-num" style="{{ $bt }}"></td>
            <td class="col-qty-uom" style="{{ $bt }}"></td>
            <td class="text-right text-bold" style="{{ $bt }}">{{ $fmt($total) }} {{ $uom }}</td>
            <td style="{{ $bt }}"></td>
            <td style="{{ $bt }}"></td>
            @endif
        </tr>
        @endforeach
        @if(!$isAccounting)
        <tr class="row-total">
            <td colspan="5" class="text-bold">TOTAL : {{ number_format($totalCartons) }} CARTONS</td>
            <td class="text-right">{{ $fmt($sumDetailNw) }}</td>
            <td class="text-right">{{ $fmt($sumDetailGw) }}</td>
        </tr>
        @endif
        {{-- เส้นปิดแถวสุดท้ายเส้นเดียว --}}
        <tr><td colspan="{{ $isAccounting ? 5 : 7 }}" style="border:none; border-top:1px solid #000; padding:0; height:1px; line-height:1px;"></td></tr>
    </tfoot>
    @endif
</table>

@if($isAccounting)
<table width="100%" style="margin-top:6px;">
    @if($packingForm->services->count() > 0)
        <tr>
            <td></td>
            <td width="22%" class="text-right">SUBTOTAL</td>
            <td width="16%" class="text-right">{{ $curSymbol }} {{ $fmt($sumAmount) }}</td>
        </tr>
        @foreach($packingForm->services as $sv)
        <tr>
            <td></td>
            <td class="text-right">{{ $sv->name }}</td>
            <td class="text-right">{{ $curSymbol }} {{ $fmt($sv->amount) }}</td>
        </tr>
        @endforeach
    @endif
    <tr>
        <td></td>
        <td width="42%" class="text-right text-bold">{{ $incotermCode ?: 'FOB' }} - {{ $packingForm->shipped_from ?: '(Port of Loading)' }} to {{ $packingForm->port_of_discharge ?: '(Port of Discharge)' }}</td>
        <td width="16%" class="text-right text-bold">{{ $curSymbol }} {{ $fmt($grandTotal) }}</td>
    </tr>
</table>
<table width="100%" style="margin-top:4px;">
    <tr><td class="text-bold">COUNTRY OF ORIGIN : THAILAND</td></tr>
    <tr><td class="text-bold">NET WEIGHT : {{ $fmt($sumWeightNw) }} KGS.</td></tr>
    <tr><td class="text-bold">GROSS WEIGHT : {{ $fmt($sumWeightGw) }} KGS.</td></tr>
    <tr><td class="text-bold" style="padding-top:4px;">TOTAL : {{ \App\Help::numberToWords($grandTotal, ($curSymbol === 'USD' ? 'US DOLLARS' : strtoupper($curSymbol ?: 'DOLLARS'))) }}</td></tr>
</table>
@else
<table class="totals">
    <tr>
        <td width="14%" class="text-bold text-center">PKG</td>
        <td width="14%" class="text-bold text-center">N.W.</td>
        <td width="14%" class="text-bold text-center">G.W.</td>
        <td width="14%" class="text-bold text-center">N.T.</td>
        <td width="14%" class="text-bold text-center">G.T.</td>
        <td width="14%" class="text-bold text-center">CBM</td>
        <td width="16%" class="text-bold text-center">QTY</td>
    </tr>
    <tr>
        <td class="text-center">{{ $fmtInt($totalCartons) }}</td>
        <td class="text-center">{{ $fmt($packingForm->weight_nw) }}</td>
        <td class="text-center">{{ $fmt($packingForm->weight_gw) }}</td>
        <td class="text-center">{{ $fmt($packingForm->weight_nt) }}</td>
        <td class="text-center">{{ $fmt($packingForm->weight_gt) }}</td>
        <td class="text-center">{{ $fmt($packingForm->cubic_meter) }}</td>
        <td class="text-center">{{ $fmtInt($packingForm->qty) }}</td>
    </tr>
</table>

{{-- ===== Remark ตาม PI: PI แรก + PI ใหม่ที่โผล่ในรายการ (ต่อท้าย ไม่ขึ้นหน้าใหม่) ===== --}}
@if(count($piRemarks) > 0)
<table width="100%" style="margin-top:8px; font-size:9px;">
    @foreach($piRemarks as $pr)
        <tr>
            <td style="vertical-align:top; padding:2px 0;">
                <span class="text-bold">REMARK @if(count($piRemarks) > 1)({{ $pr['doc_no'] }})@endif :</span>
                @foreach($pr['remarks'] as $rm)
                    <div style="padding-left:10px;">{{ $loop->iteration }}. {{ $rm->remark }}</div>
                @endforeach
            </td>
        </tr>
    @endforeach
</table>
@endif
@endif

</body>
</html>
