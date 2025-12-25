<table cellspacing="0">
    <thead>
        <tr>
             <th style="text-align:center; width:25;">Contact Name</th>
             <th style="text-align:center; width:25;">Company Name</th>
             <th style="text-align:center; width:25;">Address</th>
             <th style="text-align:center; width:25;">Tax ID</th>
             <th style="text-align:center; width:25;">Phone</th>
             <th style="text-align:center; width:25;">Mobile</th>
             <th style="text-align:center; width:25;">Fax</th>
             <th style="text-align:center; width:25;">Remark</th>
        </tr>
    </thead>
    <tbody>
        @foreach($result as $re)
        <tr>
            <td>{{ $re->contact_name }}</td>
            <td>{{ $re->company_name }}</td>
            <td>{{ $re->address }}</td>
            <td>{{ $re->tax_id }}</td>
            <td>{{ $re->phone }}</td>
            <td>{{ $re->mobile }}</td>
            <td>{{ $re->fax }}</td>
            <td>{{ $re->remark }}</td>
        </tr>
        @endforeach
    </tbody>
</table>