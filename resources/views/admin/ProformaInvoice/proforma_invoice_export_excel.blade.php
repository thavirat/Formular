<table cellspacing="0">
    <thead>
        <tr>
             <th style="text-align:center; width:25;">เลขที่เอกสาร</th>
             <th style="text-align:center; width:25;">วันที่เอกสาร</th>
             <th style="text-align:center; width:25;">บริษัท</th>
             <th style="text-align:center; width:25;">ยอดรวม</th>
             <th style="text-align:center; width:25;">ผู้บันทึก</th>
        </tr>
    </thead>
    <tbody>
        @foreach($result as $re)
        <tr>
            <td>{{ $re->doc_no }}</td>
        </tr>
        @endforeach
    </tbody>
</table>