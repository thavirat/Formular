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
                             <th style="text-align:center; width:25;">Currency</th>
                             <th style="text-align:center; width:25;">Credit</th>
                             <th style="text-align:center; width:25;">Balance</th>
                             <th style="text-align:center; width:25;">Date Start</th>
                             <th style="text-align:center; width:25;">Date End</th>
                             <th style="text-align:center; width:25;">Remark</th>
                                    </tr>
        </thead>
        <tbody>
            @foreach($result as $i => $re)
            <tr>
                <td style="text-align:center; width:5;">{{ $i+1 }}</td>
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
</body>
</html>