<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        body {
            font-family: "Garuda";
            font-size: 12px;
            line-height: 14px;
        }

        table, td, th {
            border: 1px solid black;
            height: 30px;
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
                             <th style="text-align:center; width:25;">Account No</th>
                             <th style="text-align:center; width:25;">Account Name</th>
                             <th style="text-align:center; width:25;">Account Type</th>
                             <th style="text-align:center; width:25;">Branch</th>
                                    </tr>
        </thead>
        <tbody>
            @foreach($result as $i => $re)
            <tr>
                <td style="text-align:center; width:5;">{{ $i+1 }}</td>
                            <td>{{ $re->bank_name }}</td>
                                <td>{{ $re->account_no }}</td>
                                <td>{{ $re->account_name }}</td>
                                <td>{{ $re->account_type }}</td>
                                <td>{{ $re->account_branch }}</td>
                    </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>