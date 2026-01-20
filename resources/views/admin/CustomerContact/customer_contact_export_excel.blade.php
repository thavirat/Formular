<table cellspacing="0">
    <thead>
        <tr>
             <th style="text-align:center; width:25;">Name</th>
             <th style="text-align:center; width:25;">Mobile</th>
             <th style="text-align:center; width:25;">Email</th>
        </tr>
    </thead>
    <tbody>
        @foreach($result as $re)
        <tr>
            <td>{{ $re->customer_id }}</td>
            <td>{{ $re->name }}</td>
            <td>{{ $re->mobile }}</td>
            <td>{{ $re->email }}</td>
        </tr>
        @endforeach
    </tbody>
</table>