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
                                 <th style="text-align:center; width:25;">หมวด</th>
                             <th style="text-align:center; width:25;">ยี่ห้อ</th>
                             <th style="text-align:center; width:25;">Design</th>
                             <th style="text-align:center; width:25;">หน่วย</th>
                             <th style="text-align:center; width:25;">รหัสสินค้า</th>
                             <th style="text-align:center; width:25;">รหัสอะไหล่</th>
                             <th style="text-align:center; width:25;">ชื่อไทย</th>
                             <th style="text-align:center; width:25;">ชื่ออังกฤษ</th>
                             <th style="text-align:center; width:25;">ชื่อจีน</th>
                             <th style="text-align:center; width:25;">Drawing</th>
                                     <th style="text-align:center; width:25;">กว้าง</th>
                             <th style="text-align:center; width:25;">ยาว</th>
                             <th style="text-align:center; width:25;">สูง</th>
                             <th style="text-align:center; width:25;">น้ำหนัก</th>
                             <th style="text-align:center; width:25;">คิว</th>
                             <th style="text-align:center; width:25;">เปิดใช้งาน</th>
                                    </tr>
        </thead>
        <tbody>
            @foreach($result as $i => $re)
            <tr>
                <td style="text-align:center; width:5;">{{ $i+1 }}</td>
                            <td>{{ $re->category_id }}</td>
                                <td>{{ $re->brand_id }}</td>
                                <td>{{ $re->design_id }}</td>
                                <td>{{ $re->unit_id }}</td>
                                <td>{{ $re->code }}</td>
                                <td>{{ $re->part_no }}</td>
                                <td>{{ $re->name_th }}</td>
                                <td>{{ $re->name_en }}</td>
                                <td>{{ $re->name_cn }}</td>
                                <td>{{ $re->drawing }}</td>
                                <td>{{ $re->width }}</td>
                                <td>{{ $re->height }}</td>
                                <td>{{ $re->length }}</td>
                                <td>{{ $re->weight }}</td>
                                <td>{{ $re->cube }}</td>
                                <td>{{ $re->active }}</td>
                    </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>