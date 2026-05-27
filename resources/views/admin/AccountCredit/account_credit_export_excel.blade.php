<table cellspacing="0">
    <thead>
        <tr>
             <th style="text-align:center; width:25;">Bank</th>
             <th style="text-align:center; width:25;">Currency</th>
             <th style="text-align:center; width:25;">Credit</th>
             <th style="text-align:center; width:25;">Balance</th>
             <th style="text-align:center; width:25;">Date Start</th>
             <th style="text-align:center; width:25;">Date End</th>
             <th style="text-align:center; width:25;">Remark</th>
        </tr>
    </thead>
    <tbody>
        @foreach($result as $re)
        <tr>
            <td>{{ trim(($re->bank_name ?? '').' '.($re->account_no ?? '')) ?: '-' }}</td>
            <td>{{ $re->currency_name ? $re->currency_name.($re->currency_symbol ? ' ('.$re->currency_symbol.')' : '') : '-' }}</td>
            <td>{{ $re->credit_amount }}</td>
            <td>{{ $re->credit_balance }}</td>
            <td>{{ $re->date_start }}</td>
            <td>{{ $re->date_end }}</td>
            <td>{{ $re->remark }}</td>
        </tr>
        @endforeach
    </tbody>
</table>