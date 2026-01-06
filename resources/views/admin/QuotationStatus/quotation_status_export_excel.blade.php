<table cellspacing="0">
    <thead>
        <tr>
             <th style="text-align:center; width:25;">ชื่อ</th>
        </tr>
    </thead>
    <tbody>
        @foreach($result as $re)
        <tr>
            <td>{{ $re->name }}</td>
        </tr>
        @endforeach
    </tbody>
</table>