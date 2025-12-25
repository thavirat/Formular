<table cellspacing="0">
    <thead>
        <tr>
             <th style="text-align:center; width:25;">ชื่อ</th>
             <th style="text-align:center; width:25;">สัญลักษณ์</th>
             <th style="text-align:center; width:25;">Buying Sight</th>
             <th style="text-align:center; width:25;">Transfer</th>
             <th style="text-align:center; width:25;">Selling</th>
             <th style="text-align:center; width:25;">Mid Rate</th>
        </tr>
    </thead>
    <tbody>
        @foreach($result as $re)
        <tr>
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