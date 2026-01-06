<table cellspacing="0">
    <thead>
        <tr>
             <th style="text-align:center; width:25;">รหัส</th>
             <th style="text-align:center; width:25;">รายละเอียด</th>
        </tr>
    </thead>
    <tbody>
        @foreach($result as $re)
        <tr>
            <td>{{ $re->code }}</td>
            <td>{{ $re->description }}</td>
        </tr>
        @endforeach
    </tbody>
</table>