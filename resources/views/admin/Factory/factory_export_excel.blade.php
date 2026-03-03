<table cellspacing="0">
    <thead>
        <tr>
             <th style="text-align:center; width:25;">Code</th>
             <th style="text-align:center; width:25;">Name</th>
        </tr>
    </thead>
    <tbody>
        @foreach($result as $re)
        <tr>
            <td>{{ $re->code }}</td>
            <td>{{ $re->name }}</td>
        </tr>
        @endforeach
    </tbody>
</table>