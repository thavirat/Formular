<table>
    <thead>
        <tr>
            <th>doc_no</th>
            <th>doc_date</th>
            <th>customer_id</th>
            <th>customer_name</th>
            <th>contact_name</th>
            <th>company_name</th>
            <th>tax_id</th>
            <th>address</th>
            <th>phone</th>
            <th>mobile</th>
            <th>fax_no</th>
            <th>currency_id</th>
            <th>currency_name</th>
            <th>credit_payment_id</th>
            <th>credit_payment_name</th>
            <th>incoterm_id</th>
            <th>status_id</th>
            <th>item_no</th>
            <th>product_id</th>
            <th>part_no</th>
            <th>drawing</th>
            <th>cus_code</th>
            <th>description</th>
            <th>qty</th>
            <th>unit_price</th>
            <th>discount_percent</th>
            <th>discount_amount</th>
            <th>total_price</th>
        </tr>
    </thead>
    <tbody>
        @foreach($Quotation->products as $product)
            <tr>
                <td>{{ $Quotation->doc_no }}</td>
                <td>{{ $Quotation->doc_date }}</td>
                <td>{{ $Quotation->customer_id }}</td>
                <td>{{ $Quotation->company_name }}</td>
                <td>{{ $Quotation->contact_name }}</td>
                <td>{{ $Quotation->company_name }}</td>
                <td>{{ $Quotation->tax_id }}</td>
                <td>{{ $Quotation->address }}</td>
                <td>{{ $Quotation->phone }}</td>
                <td>{{ $Quotation->mobile }}</td>
                <td>{{ $Quotation->fax_no }}</td>
                <td>{{ $Quotation->currency_id }}</td>
                <td>{{ $Quotation->currency_name }}</td>
                <td>{{ $Quotation->credit_payment_id }}</td>
                <td>{{ $Quotation->credit_payment_name }}</td>
                <td>{{ $Quotation->incoterm_id }}</td>
                <td>{{ $Quotation->status_id }}</td>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $product->product_id }}</td>
                <td>{{ $product->part_no }}</td>
                <td>{{ $product->drawing }}</td>
                <td>{{ $product->cus_code }}</td>
                <td>{{ $product->detail_eng }}</td>
                <td>{{ $product->qty }}</td>
                <td>{{ $product->price_per_item }}</td>
                <td>{{ $product->discount_percents ?? 0 }}</td>
                <td>{{ $product->discount_amount ?? 0 }}</td>
                <td>{{ $product->total_price }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
