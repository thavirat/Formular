<table cellspacing="0">
    <thead>
        <tr>
@foreach ($name_in_table as $name)
@if ($name)
             <th style="text-align:center; width:25;">{{ $name }}</th>
@endif
@endforeach
        </tr>
    </thead>
    <tbody>
        @@foreach ($result as $re)
        <tr>
@foreach ($field_in_form as $field => $status)
@php
    $str = "{{ \$re-&gt;".$field." }}";
@endphp
@if ($status == 'on')
            <td>{!! html_entity_decode($str) !!}</td>
@endif
@endforeach
        </tr>
        @@endforeach
    </tbody>
</table>