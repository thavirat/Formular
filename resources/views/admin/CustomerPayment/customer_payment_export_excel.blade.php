<table cellspacing="0">
    <thead>
        <tr>
             <th style="text-align:center; width:25;">Bank</th>
             <th style="text-align:center; width:25;">Payment Method</th>
             <th style="text-align:center; width:25;">Currency</th>
             <th style="text-align:center; width:25;">Ref No</th>
             <th style="text-align:center; width:25;">Payment Date</th>
             <th style="text-align:center; width:25;">Remark</th>
             <th style="text-align:center; width:25;">Amount</th>
             <th style="text-align:center; width:25;">Amount Bath</th>
             <th style="text-align:center; width:25;">Exchange Rate</th>
             <th style="text-align:center; width:25;">Photo</th>
        </tr>
    </thead>
    <tbody>
        @foreach($result as $re)
        <tr>
            <td>{{ $re->bank_account_id }}</td>
            <td>{{ $re->payment_method_id }}</td>
            <td>{{ $re->currency_id }}</td>
            <td>{{ $re->reference_no }}</td>
            <td>{{ $re->payment_date }}</td>
            <td>{{ $re->payment_note }}</td>
            <td>{{ $re->amount }}</td>
            <td>{{ $re->amount_bath }}</td>
            <td>{{ $re->exchange_rate }}</td>
            <td>{{ $re->photo }}</td>
        </tr>
        @endforeach
    </tbody>
</table>