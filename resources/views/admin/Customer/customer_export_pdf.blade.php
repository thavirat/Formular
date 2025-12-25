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
                                 <th style="text-align:center; width:25;">Contact Name</th>
                             <th style="text-align:center; width:25;">Company Name</th>
                             <th style="text-align:center; width:25;">Address</th>
                             <th style="text-align:center; width:25;">Tax ID</th>
                             <th style="text-align:center; width:25;">Phone</th>
                             <th style="text-align:center; width:25;">Mobile</th>
                             <th style="text-align:center; width:25;">Fax</th>
                             <th style="text-align:center; width:25;">Remark</th>
                                    </tr>
        </thead>
        <tbody>
            @foreach($result as $i => $re)
            <tr>
                <td style="text-align:center; width:5;">{{ $i+1 }}</td>
                            <td>{{ $re->contact_name }}</td>
                                <td>{{ $re->company_name }}</td>
                                <td>{{ $re->address }}</td>
                                <td>{{ $re->tax_id }}</td>
                                <td>{{ $re->phone }}</td>
                                <td>{{ $re->mobile }}</td>
                                <td>{{ $re->fax }}</td>
                                <td>{{ $re->remark }}</td>
                    </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>