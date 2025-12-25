<table cellspacing="0">
    <thead>
        <tr>
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
        @foreach($result as $re)
        <tr>
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