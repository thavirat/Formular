@php
    /** @var \App\Models\PackingFormDetail|null $detail */
    $detail = $detail ?? null;
    $pip = $detail?->piProduct;
    $piLabel = '';
    if ($detail?->pi_product_id) {
        $doc = $pip && $pip->pi ? $pip->pi->doc_no : '';
        $pno = $pip ? ($pip->part_no ?: optional($pip->product)->code) : $detail->part_no;
        $piLabel = trim(($doc ? $doc.' — ' : '').($pno ?: ''));
    }
@endphp
<tr class="detail-row">
    <td><input type="text" class="form-control form-control-sm" name="excel_row_display[]" value="{{ $detail?->excel_row }}" readonly tabindex="-1"></td>
    <td><input type="number" class="form-control form-control-sm packing-tab" name="from[]" value="{{ $detail?->from }}" min="0" step="1"></td>
    <td><input type="number" class="form-control form-control-sm packing-tab" name="line_to[]" value="{{ $detail?->to }}" min="0" step="1"></td>
    <td>
        <input type="hidden" name="detail_id[]" value="{{ $detail?->id }}">
        <select class="form-control form-control-sm select2-pi-line packing-tab" name="pi_product_id[]" data-placeholder="พิมพ์เลข PI...">
            @if($detail?->pi_product_id)
                <option value="{{ (int) $detail->pi_product_id }}" selected>{{ e($piLabel) }}</option>
            @endif
        </select>
    </td>
    <td><input type="text" class="form-control form-control-sm part-no-input packing-tab" name="part_no[]" value="{{ $detail?->part_no }}" required></td>
    <td><input type="text" class="form-control form-control-sm packing-tab" name="cus_part_no[]" value="{{ $detail?->cus_part_no }}"></td>
    <td><input type="text" class="form-control form-control-sm packing-tab" name="description[]" value="{{ $detail?->description }}"></td>
    <td><input type="text" class="form-control form-control-sm packing-tab" name="formular_number[]" value="{{ $detail?->formular_number }}"></td>
    <td><input type="number" class="form-control form-control-sm packing-tab" name="width[]" value="{{ $detail?->width }}" min="0" step="0.01"></td>
    <td><input type="number" class="form-control form-control-sm packing-tab" name="lenght[]" value="{{ $detail?->lenght }}" min="0" step="0.01"></td>
    <td><input type="number" class="form-control form-control-sm packing-tab" name="height[]" value="{{ $detail?->height }}" min="0" step="0.01"></td>
    <td><input type="number" class="form-control form-control-sm packing-tab" name="detail_qty[]" value="{{ $detail?->qty ?? 0 }}" min="0" step="1" required></td>
    <td><input type="number" class="form-control form-control-sm packing-tab" name="detail_cubic_meter[]" value="{{ $detail?->cubic_meter }}" min="0" step="0.01"></td>
    <td><input type="number" class="form-control form-control-sm packing-tab" name="detail_weight_nw[]" value="{{ $detail?->weight_nw }}" min="0" step="0.01"></td>
    <td><input type="number" class="form-control form-control-sm packing-tab" name="detail_weight_gw[]" value="{{ $detail?->weight_gw }}" min="0" step="0.01"></td>
    <td><input type="number" class="form-control form-control-sm packing-tab" name="detail_weight_nt[]" value="{{ $detail?->weight_nt }}" min="0" step="0.01"></td>
    <td><input type="number" class="form-control form-control-sm packing-tab" name="detail_weight_gt[]" value="{{ $detail?->weight_gt }}" min="0" step="0.01"></td>
    <td><input type="text" class="form-control form-control-sm packing-tab" name="uom[]" value="{{ $detail?->uom }}"></td>
    <td><input type="text" class="form-control form-control-sm packing-tab" name="from_co[]" value="{{ $detail?->from_co }}"></td>
    <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger btn-remove-row" tabindex="-1"><i class="fa fa-trash"></i></button></td>
</tr>
