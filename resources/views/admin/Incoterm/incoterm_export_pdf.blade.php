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
                                 <th style="text-align:center; width:25;">รหัส</th>
                             <th style="text-align:center; width:25;">รายละเอียด</th>
                                    </tr>
        </thead>
        <tbody>
            @foreach($result as $i => $re)
            <tr>
                <td style="text-align:center; width:5;">{{ $i+1 }}</td>
                            <td>{{ $re->code }}</td>
                                <td>{{ $re->description }}</td>
                    </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>