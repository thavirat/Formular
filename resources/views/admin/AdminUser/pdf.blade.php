<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        body{
            font-family: 'thsarabun';
            /* font-size: 20px; */
        }

        th, td{
            padding: 2px;
        }
    </style>
</head>
<body>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" >
        <thead>
            <tr>
                <th>ลำดับ</th>
                <th>ชื่อ</th>
                <th>นามสกุล</th>
                <th>ชื่อเล่น</th>
                <th>เบอร์โทร</th>
                <th>สถานะ</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($AdminUsers as $key => $AdminUser)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $AdminUser->firstname }}</td>
                    <td>{{ $AdminUser->lastname }}</td>
                    <td>{{ $AdminUser->nickname }}</td>
                    <td>{{ $AdminUser->mobile }}</td>
                    <td>{{ $AdminUser->active == 'T' ? 'เปิดใช้งาน' : 'ปิดใช้งาน' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
