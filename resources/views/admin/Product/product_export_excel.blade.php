<table cellspacing="0">
    <thead>
        <tr>
             <th style="text-align:center; width:25;">Category</th>
             <th style="text-align:center; width:25;">Product ID</th>
             <th style="text-align:center; width:25;">Name TH</th>
             <th style="text-align:center; width:25;">Name ENG</th>
             <th style="text-align:center; width:25;">Drawing</th>
             <th style="text-align:center; width:25;">Width</th>
             <th style="text-align:center; width:25;">Height</th>
             <th style="text-align:center; width:25;">Length</th>
             <th style="text-align:center; width:25;">Weight</th>
             <th style="text-align:center; width:25;">Sub Category</th>
        </tr>
    </thead>
    <tbody>
        @foreach($result as $re)
        <tr>
            <td>{{ $re->category_id }}</td>
            <td>{{ $re->code }}</td>
            <td>{{ $re->name_th }}</td>
            <td>{{ $re->name_en }}</td>
            <td>{{ $re->drawing }}</td>
            <td>{{ $re->width }}</td>
            <td>{{ $re->height }}</td>
            <td>{{ $re->length }}</td>
            <td>{{ $re->weight }}</td>
            <td>{{ $re->sub_category_id }}</td>
        </tr>
        @endforeach
    </tbody>
</table>