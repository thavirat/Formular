@php
    use App\Help;
    $logo = null;
    if (isset($settings['logo'])) {
        $l = json_decode($settings['logo']);
        $logo = (is_array($l) && count($l) > 0) ? $l[0] : null;
    }
    $isDebit = $cn->doc_type === 'debit';
    $cur = optional($cn->currency)->symbol ?: (optional($cn->currency)->name ?: '');
    $docTitle = $isDebit ? 'DEBIT NOTE' : 'CREDIT NOTE';
    $curWord = $cur === 'EUR' ? 'EURO' : ($cur === 'USD' ? 'US DOLLARS' : strtoupper($cur ?: 'DOLLARS'));
    $ROWS = 12; // จำนวนแถวตารางขั้นต่ำ
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $docTitle }} {{ $cn->doc_no }}</title>
    <style>
        @page { margin: 8mm; }
        body { font-family: 'garuda', sans-serif; font-size: 11px; color: #000; margin: 0; }
        .bold { font-weight: bold; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .box { border: 1px solid #000; }
        .hdr td { vertical-align: top; padding: 2px 4px; }
        .field { border: 1px solid #000; padding: 3px 6px; }
        .lbl { font-weight: bold; }
        .items { width: 100%; border-collapse: collapse; margin-top: 4px; }
        .items th, .items td { border: 1px solid #000; padding: 3px 5px; }
        .items th { text-align: center; background: #eee; font-weight: bold; }
        .title-box { border: 1px solid #000; font-size: 20px; font-weight: bold; text-align: center; padding: 8px; }
    </style>
</head>
<body>

<table width="100%" class="hdr">
    <tr>
        <td width="52%">
            {{-- DOC + Number + Date --}}
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <td class="lbl" width="70">DOC</td>
                    <td class="field" width="90">{{ $isDebit ? 'DEBIT' : 'CREDIT' }}</td>
                    <td class="lbl text-center" width="80">Number</td>
                    <td class="field">{{ $cn->doc_no }}</td>
                </tr>
                <tr><td colspan="4" style="height:4px;"></td></tr>
                <tr>
                    <td class="lbl">Date</td>
                    <td class="field" colspan="3">{{ optional($cn->doc_date)->format('d/m/Y') }}</td>
                </tr>
            </table>

            <div class="bold" style="font-size:14px; margin:8px 0 4px;">Buyer</div>
            <table cellpadding="0" cellspacing="0" width="100%">
                @foreach([
                    ['Company', $cn->company_name],
                    ['Address', $cn->address],
                    ['Address', $cn->address2],
                    ['Country', $cn->country],
                    ['Phone', $cn->phone],
                    ['Refer', $cn->refer],
                    ['Contact Name', $cn->contact_name],
                ] as $row)
                <tr>
                    <td class="lbl" width="90" style="padding:2px 0;">{{ $row[0] }}</td>
                    <td class="field" style="height:16px;">{{ $row[1] }}</td>
                </tr>
                @endforeach
            </table>
        </td>
        <td width="48%" class="text-center">
            <div class="title-box" style="width:70%; margin:0 0 8px auto;">{{ $docTitle }}</div>
            @if($logo)
                <img src="{{ asset('uploads/SettingSystem/'.$logo) }}" height="48"><br>
            @endif
            <div class="bold" style="font-size:14px;">FORMULA INTERTRADE CO.,LTD.</div>
            <div>119 MOTORWAY ROAD THAP CHANG,</div>
            <div>SAPHAN SUNG, BANGKOK 10250, THAILAND.</div>
            <div>TEL. : 063-525-2242</div>
            <div>TAX ID: 0105538048542</div>
        </td>
    </tr>
</table>

<table class="items">
    <thead>
        <tr>
            <th width="18%">Invoice #</th>
            <th>Description</th>
            <th width="11%">Quantity</th>
            <th width="15%">Unit Price</th>
            <th width="17%">{{ $cur }}<br>Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach($cn->items as $it)
        <tr>
            <td>{{ $it->invoice_no }}</td>
            <td>{{ $it->description ?: $it->part_no }}</td>
            <td class="text-right">{{ rtrim(rtrim(number_format($it->qty,2),'0'),'.') }}</td>
            <td class="text-right">{{ $cur }} {{ number_format($it->unit_price,2) }}</td>
            <td class="text-right">{{ $cur }} {{ number_format($it->amount,2) }}</td>
        </tr>
        @endforeach
        @for($i = $cn->items->count(); $i < $ROWS; $i++)
        <tr><td style="height:18px;">&nbsp;</td><td></td><td></td><td></td><td></td></tr>
        @endfor
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" rowspan="2" style="vertical-align:top;">{{ $curWord }}: {{ Help::numberToWords($cn->total, $curWord) }} ONLY</td>
            <td class="bold text-center">Total</td>
            <td class="bold text-right">{{ $cur }} {{ number_format($cn->total,2) }}</td>
        </tr>
        <tr><td></td><td></td></tr>
    </tfoot>
</table>

<table width="62%" style="margin-top:10px; border-collapse:collapse;">
    <tr>
        <td style="border:1px solid #000; padding:6px 8px; height:90px; vertical-align:top;">
            <span class="bold">Reason for</span>
            &nbsp;&nbsp;[{!! $isDebit ? 'X' : '&nbsp;' !!}] Debit
            &nbsp;&nbsp;[{!! !$isDebit ? 'X' : '&nbsp;' !!}] Credit
            <div style="margin-top:8px;">{!! nl2br(e($cn->reason)) !!}</div>
        </td>
    </tr>
</table>
<table width="62%" style="margin-top:18px;">
    <tr>
        <td class="bold" width="110" style="vertical-align:bottom;">Authorized By:</td>
        <td style="border-bottom:1px solid #000; height:38px; vertical-align:bottom; text-align:center;">
            {{ optional($cn->authorizedBy)->name ?: trim(optional($cn->authorizedBy)->firstname.' '.optional($cn->authorizedBy)->lastname) }}
        </td>
    </tr>
</table>

</body>
</html>
