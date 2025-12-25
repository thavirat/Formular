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
                                 <th style="text-align:center; width:25;">ชื่อ</th>
                             <th style="text-align:center; width:25;">สัญลักษณ์</th>
                             <th style="text-align:center; width:25;">Buying Sight</th>
                             <th style="text-align:center; width:25;">Transfer</th>
                             <th style="text-align:center; width:25;">Selling</th>
                             <th style="text-align:center; width:25;">Mid Rate</th>
                                    </tr>
        </thead>
        <tbody>
            @foreach($result as $i => $re)
            <tr>
                <td style="text-align:center; width:5;">{{ $i+1 }}</td>
                            <td>{{ $re->name }}</td>
                                <td>{{ $re->symbol }}</td>
                                <td>{{ $re->buying_sight }}</td>
                                <td>{{ $re->buying_transfer }}</td>
                                <td>{{ $re->selling }}</td>
                                <td>{{ $re->mid_rate }}</td>
                    </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>