<table cellspacing="0">
    <thead>
        <tr>
             <th style="text-align:center; width:25;">ลูกค้า</th>
             <th style="text-align:center; width:25;">สินค้า</th>
             <th style="text-align:center; width:25;">รหัสสินค้า</th>
        </tr>
    </thead>
    <tbody>
        @foreach($result as $re)
        <tr>
            <td>{{ $re->customer_id }}</td>
            <td>{{ $re->product_id }}</td>
            <td>{{ $re->code }}</td>
        </tr>
        @endforeach
    </tbody>
</table>