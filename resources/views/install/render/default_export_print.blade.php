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
    @foreach ($name_in_table as $name)
    @if ($name)
                 <th style="text-align:center; width:25;">{{ $name }}</th>
    @endif
    @endforeach
            </tr>
        </thead>
        <tbody>
            @@foreach ($result as $i => $re)
            <tr>
                <td style="text-align:center; width:5;">@{{ $i+1 }}</td>
    @foreach ($field_in_form as $field => $status)
    @php
        $str = "{{ \$re-&gt;".$field." }}";
    @endphp
    @if ($status == 'on')
                <td>{!! html_entity_decode($str) !!}</td>
    @endif
    @endforeach
            </tr>
            @@endforeach
        </tbody>
    </table>
</body>
</html>