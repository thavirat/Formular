<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        table, td, th {
            border: 1px solid black;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th {
            height: 70px;
        }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th style="text-align:center; width:5;">No.</th>
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
            @foreach($result as $i => $re)
            <tr>
                <td style="text-align:center; width:5;">{{ $i+1 }}</td>
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
</body>
</html>