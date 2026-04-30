<table cellspacing="0">
    <thead>
        <tr>
             <th style="text-align:center; width:25;">Bank</th>
             <th style="text-align:center; width:25;">Account No</th>
             <th style="text-align:center; width:25;">Account Name</th>
             <th style="text-align:center; width:25;">Account Type</th>
             <th style="text-align:center; width:25;">Branch</th>
        </tr>
    </thead>
    <tbody>
        @foreach($result as $re)
        <tr>
            <td>{{ $re->bank_name }}</td>
            <td>{{ $re->account_no }}</td>
            <td>{{ $re->account_name }}</td>
            <td>{{ $re->account_type }}</td>
            <td>{{ $re->account_branch }}</td>
        </tr>
        @endforeach
    </tbody>
</table>