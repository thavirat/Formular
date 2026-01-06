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
                                         <th style="text-align:center; width:25;">Customer</th>
                                                     <th style="text-align:center; width:25;">Contact Name</th>
                             <th style="text-align:center; width:25;">Company Name</th>
                             <th style="text-align:center; width:25;">Tax ID</th>
                             <th style="text-align:center; width:25;">Address</th>
                             <th style="text-align:center; width:25;">Phone</th>
                             <th style="text-align:center; width:25;">Mobile</th>
                             <th style="text-align:center; width:25;">Fax No</th>
                                                                             <th style="text-align:center; width:25;">Currency</th>
                             <th style="text-align:center; width:25;">Credit Payment</th>
                    </tr>
        </thead>
        <tbody>
            @foreach($result as $i => $re)
            <tr>
                <td style="text-align:center; width:5;">{{ $i+1 }}</td>
                            <td>{{ $re->customer_id }}</td>
                                <td>{{ $re->contact_name }}</td>
                                <td>{{ $re->company_name }}</td>
                                <td>{{ $re->tax_id }}</td>
                                <td>{{ $re->address }}</td>
                                <td>{{ $re->phone }}</td>
                                <td>{{ $re->mobile }}</td>
                                <td>{{ $re->fax_no }}</td>
                                <td>{{ $re->incoterm_id }}</td>
                                <td>{{ $re->currency_id }}</td>
                                <td>{{ $re->credit_payment_id }}</td>
                    </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>