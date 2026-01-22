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
                                 <th style="text-align:center; width:25;">Category</th>
                                                     <th style="text-align:center; width:25;">Product ID</th>
                                     <th style="text-align:center; width:25;">Name TH</th>
                             <th style="text-align:center; width:25;">Name ENG</th>
                                     <th style="text-align:center; width:25;">Drawing</th>
                                     <th style="text-align:center; width:25;">Width</th>
                             <th style="text-align:center; width:25;">Height</th>
                             <th style="text-align:center; width:25;">Length</th>
                             <th style="text-align:center; width:25;">Weight</th>
                                                                     <th style="text-align:center; width:25;">Sub Category</th>
                                    </tr>
        </thead>
        <tbody>
            @foreach($result as $i => $re)
            <tr>
                <td style="text-align:center; width:5;">{{ $i+1 }}</td>
                            <td>{{ $re->category_id }}</td>
                                <td>{{ $re->code }}</td>
                                <td>{{ $re->name_th }}</td>
                                <td>{{ $re->name_en }}</td>
                                <td>{{ $re->drawing }}</td>
                                <td>{{ $re->width }}</td>
                                <td>{{ $re->height }}</td>
                                <td>{{ $re->length }}</td>
                                <td>{{ $re->weight }}</td>
                                <td>{{ $re->sub_category_id }}</td>
                    </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>