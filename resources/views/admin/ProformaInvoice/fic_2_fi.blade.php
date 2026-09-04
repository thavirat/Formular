@php
    // Cust PO No. column ให้แสดงเลข FA (เลข PI เปลี่ยนตัวหน้า PI -> FA)
    $faNo = \Illuminate\Support\Str::startsWith($ProformaInvoice->doc_no ?? '', 'PI')
        ? 'FA' . substr($ProformaInvoice->doc_no, 2)
        : ($ProformaInvoice->doc_no ?? '-');
@endphp
<table>

    <tr>
        <td>Cust_Code</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>Ship To Cust Name</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>{{ $ProformaInvoice->customer->code ?? '-' }}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>{{ $ProformaInvoice->customer->company_name ?? '-' }}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>{{ $ProformaInvoice->customer->address ?? '-' }}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    @for($i=0; $i<5; $i++)
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    @endfor
    <tr>
        <th>Cust PO No.</th>
        <th>Sale</th>
        <th>Ship Date</th>
        <th>Ship To PO</th>
        <th>ITM	Part No</th>
        <th>Qty</th>
        <th>Unit Price</th>
        <th>Customer Part</th>
        <th>Description</th>
        <th>UNIT</th>
        <th>DWG</th>
        <th>Fac No</th>
        <th>Shipping</th>
        <th>Marks</th>
        <th>Remark</th>
    </tr>
    @foreach($ProformaInvoice->products as $product)
    <tr>
        <td>{{ $faNo }}</td>
        <td>{{ $ProformaInvoice->createdBy->firstname ?? '-' }} {{ $ProformaInvoice->createdBy->firstname ?? '-' }}</td>
        <td>{{ $ProformaInvoice->ship_date ?? '-' }}</td>
        <td>{{ $ProformaInvoice->customer_po ?? '-' }}</td>
        <td>{{ $product->part_no }}</td>
        <td>{{ $product->qty }}</td>
        <td>{{ $product->product_cost }}</td>
        <td>{{ $product->customer_part }}</td>
        <td>{{ $product->detail_eng }}</td>
        <td>{{ $product->unit_name }}</td>
        <td>{{ $product->drawing }}</td>
        <td>{{ $product->fac_no }}</td>
        <td>{{ $product->shipping }}</td>
        <td>{{ $product->marks }}</td>
        <td>{{ $product->remark }}</td>
    </tr>
    @endforeach

</table>
