@extends('admin.layouts.default')

@section('title', 'สร้าง Packing List')

@push('styles')
<style>
    .packing-detail-scroll { overflow-x: auto; }
    #detailTable .form-control-sm { min-width: 4.5rem; }
    #detailTable td { vertical-align: middle; }
    .form-control:focus {
        border-color: #67bbb9;
        box-shadow: 0 0 0 0.2rem rgba(103, 187, 185, 0.25);
    }
    .input-loading {
        background-color: #f8f9fa;
        border-color: #ffc107 !important;
        color: #ffc107;
    }
    .select2-container--default .select2-selection--single { height: calc(1.5em + 0.5rem + 2px); min-height: 31px; }
    .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 1.5; padding-top: 2px; }
</style>
@endpush

@section('body')
<div class="page-content container-fluid container-plus">
    <div class="page-header mb-2 pb-2 flex-column flex-sm-row align-items-start align-items-sm-center py-25 px-1">
        <h1 class="page-title text-primary-d2 text-140">สร้าง Packing List</h1>
        <div class="page-tools mt-3 mt-sm-0">
            <a href="{{ url('admin/'.$admin_lang_slash.'PackingForm') }}" class="btn btn-light-secondary border-0 radius-3 py-2 text-600 text-90">
                <i class="fa fa-arrow-left"></i> กลับรายการ
            </a>
        </div>
    </div>

    <div class="card dcard">
        <div class="card-body p-3">
            <form id="form-packing-create">
                @csrf

                @include('admin.PackingForm._header_form_create')

                <h5 class="text-primary-d2 mt-3 mb-2">รายการสินค้า</h5>
                <div class="table-responsive packing-detail-scroll">
                    <table class="table table-bordered table-sm" id="detailTable">
                        <thead class="bgc-primary-d1 text-white text-90">
                            <tr>
                                <th width="3%">แถว</th>
                                <th width="4%">From</th>
                                <th width="4%">To</th>
                                <th>Part no</th>
                                <th style="min-width:200px">PI / บรรทัด</th>
                                <th>Cus part</th>
                                <th style="min-width:140px">รายละเอียด</th>
                                <th>Formular</th>
                                <th>W</th>
                                <th>L</th>
                                <th>H</th>
                                <th>Qty</th>
                                <th>CBM</th>
                                <th>N.W.</th>
                                <th>G.W.</th>
                                <th>N.T.</th>
                                <th>G.T.</th>
                                <th>UOM</th>
                                <th>From CO</th>
                                <th width="3%"></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary mb-3" id="btn-add-row"><i class="fa fa-plus"></i> เพิ่มแถว (Add Row)</button>

                <div class="text-center mt-2">
                    <button type="submit" class="btn btn-success btn-lg px-5"><i class="fa fa-save"></i> บันทึก Packing List</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {

    var packingPiSearchUrl = url_gb + "/admin/{{ $admin_lang_slash }}PackingForm/SearchPiProduct";
    var rowCounter = 0;

    function updateRowNumbers() {
        $('#detailTable tbody tr').each(function(index) {
            $(this).find('.row-number').text(index + 1);
        });
    }

    function getRowPartNo($row) {
        return ($row.find('input.part-no-input').val() || '').trim();
    }

    function initPiLineSelect($select) {
        if ($select.hasClass('select2-hidden-accessible')) {
            $select.select2('destroy');
        }
        $select.select2({
            placeholder: 'พิมพ์เลข PI...',
            allowClear: true,
            width: '100%',
            minimumInputLength: 0,
            selectOnClose: false,
            ajax: {
                url: packingPiSearchUrl,
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term || '',
                        part_no: getRowPartNo($select.closest('tr'))
                    };
                },
                transport: function(params, success, failure) {
                    var part = getRowPartNo($select.closest('tr'));
                    if (!part) {
                        Swal.fire({ icon: 'warning', title: 'กรอก Part no ในแถวนี้ก่อน', toast: true, position: 'top-end', timer: 2200, showConfirmButton: false });
                        return;
                    }
                    return $.ajax(params).then(success).fail(failure);
                },
                processResults: function(data) {
                    return {
                        results: (data.items || []).map(function(i) {
                            return { id: String(i.id), text: i.text };
                        })
                    };
                },
                cache: false
            }
        });

        $select.off('select2:open.packing').on('select2:open.packing', function() {
            setTimeout(function() {
                var field = document.querySelector('.select2-container--open .select2-search__field');
                if (field) field.focus();
            }, 0);
        });

        $select.off('select2:select.packing').on('select2:select.packing', function() {
            var $self = $(this);
            setTimeout(function() { focusNextField($self); }, 0);
        });

        var $selection = $select.next('.select2-container').find('.select2-selection');
        $selection.attr('tabindex', '0').addClass('packing-tab-s2');

        $selection.off('keydown.packing').on('keydown.packing', function(e) {
            if (e.key === 'Tab') {
                e.preventDefault();
                $select.select2('close');
                if (e.shiftKey) {
                    focusPrevField($select);
                } else {
                    focusNextField($select);
                }
            } else if (e.key.length === 1 && !e.ctrlKey && !e.metaKey && !e.altKey) {
                if (!$select.data('select2').isOpen()) {
                    $select.select2('open');
                }
            }
        });

        $selection.off('click.packing').on('click.packing', function() {
            if (!$select.data('select2').isOpen()) {
                $select.select2('open');
            }
        });
    }

    function clearPiSelect($row) {
        var $sel = $row.find('.select2-pi-line');
        if ($sel.hasClass('select2-hidden-accessible')) {
            $sel.select2('destroy');
        }
        $sel.empty();
        initPiLineSelect($sel);
    }

    function packingFocusables() {
        return $('#form-packing-create').find('input.packing-tab:visible:not([readonly]):not([disabled]), .select2-selection.packing-tab-s2:visible');
    }

    function focusField($el) {
        if ($el.hasClass('select2-selection')) {
            $el.closest('.select2-container').prev('select.select2-pi-line').select2('open');
        } else if ($el.hasClass('select2-pi-line')) {
            $el.select2('open');
        } else {
            $el.focus();
        }
    }

    function focusNextField($current) {
        var $focusables = packingFocusables();
        var $target = $current.hasClass('select2-pi-line') ? $current.next('.select2-container').find('.select2-selection') : $current;
        var idx = $focusables.index($target);
        if (idx >= 0 && idx < $focusables.length - 1) {
            focusField($focusables.eq(idx + 1));
        } else if (idx === $focusables.length - 1) {
            $('#btn-add-row').click();
        }
    }

    function focusPrevField($current) {
        var $focusables = packingFocusables();
        var $target = $current.hasClass('select2-pi-line') ? $current.next('.select2-container').find('.select2-selection') : $current;
        var idx = $focusables.index($target);
        if (idx > 0) {
            focusField($focusables.eq(idx - 1));
        }
    }

    $(document).on('keydown', '.select2-container--open .select2-search__field', function(e) {
        if (e.key !== 'Tab') return;
        e.preventDefault();
        var $select = $('.select2-container--open').prev('select.select2-pi-line');
        $select.select2('close');
        setTimeout(function() {
            if (e.shiftKey) {
                focusPrevField($select);
            } else {
                focusNextField($select);
            }
        }, 0);
    });

    $('#btn-add-row').on('click', function() {
        rowCounter++;
        var newRow = `
        <tr class="detail-row">
            <td class="text-center row-number">${rowCounter}</td>
            <td><input type="number" class="form-control form-control-sm packing-tab" name="from[]" min="0" step="1"></td>
            <td><input type="number" class="form-control form-control-sm packing-tab" name="line_to[]" min="0" step="1"></td>
            <td>
                <input type="hidden" name="detail_id[]" value="">
                <input type="text" class="form-control form-control-sm part-no-input packing-tab" name="part_no[]" required>
            </td>
            <td><select class="form-control form-control-sm select2-pi-line packing-tab" name="pi_product_id[]" data-placeholder="พิมพ์เลข PI..."></select></td>
            <td><input type="text" class="form-control form-control-sm packing-tab" name="cus_part_no[]"></td>
            <td><input type="text" class="form-control form-control-sm packing-tab" name="description[]"></td>
            <td><input type="text" class="form-control form-control-sm packing-tab" name="formular_number[]"></td>
            <td><input type="number" class="form-control form-control-sm packing-tab" name="width[]" min="0" step="0.01"></td>
            <td><input type="number" class="form-control form-control-sm packing-tab" name="lenght[]" min="0" step="0.01"></td>
            <td><input type="number" class="form-control form-control-sm packing-tab" name="height[]" min="0" step="0.01"></td>
            <td><input type="number" class="form-control form-control-sm packing-tab" name="detail_qty[]" value="0" min="0" step="1" required></td>
            <td><input type="number" class="form-control form-control-sm packing-tab" name="detail_cubic_meter[]" min="0" step="0.01"></td>
            <td><input type="number" class="form-control form-control-sm packing-tab" name="detail_weight_nw[]" min="0" step="0.01"></td>
            <td><input type="number" class="form-control form-control-sm packing-tab" name="detail_weight_gw[]" min="0" step="0.01"></td>
            <td><input type="number" class="form-control form-control-sm packing-tab" name="detail_weight_nt[]" min="0" step="0.01"></td>
            <td><input type="number" class="form-control form-control-sm packing-tab" name="detail_weight_gt[]" min="0" step="0.01"></td>
            <td><input type="text" class="form-control form-control-sm packing-tab" name="uom[]"></td>
            <td><input type="text" class="form-control form-control-sm packing-tab" name="from_co[]"></td>
            <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger btn-remove-row" tabindex="-1"><i class="fa fa-trash"></i></button></td>
        </tr>`;
        var $tr = $(newRow);
        $('#detailTable tbody').append($tr);
        initPiLineSelect($tr.find('.select2-pi-line'));
        updateRowNumbers();
        $tr.find('input.packing-tab').first().focus();
    });

    $('body').on('click', '.btn-remove-row', function() {
        var $tr = $(this).closest('tr');
        var $sel = $tr.find('.select2-pi-line');
        if ($sel.hasClass('select2-hidden-accessible')) {
            $sel.select2('destroy');
        }
        $tr.remove();
        updateRowNumbers();
    });

    $('body').on('change', '#detailTable input.part-no-input', function() {
        clearPiSelect($(this).closest('tr'));
    });

    $('body').on('keydown', '#form-packing-create input.packing-tab', function(e) {
        if (e.key === 'Tab' && !e.shiftKey) {
            e.preventDefault();
            focusNextField($(this));
        } else if (e.key === 'Tab' && e.shiftKey) {
            e.preventDefault();
            focusPrevField($(this));
        }
    });

    $('#btn-add-row').click();

    $('#form-packing-create').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var btn = form.find('button[type=submit]');
        loadingButton(btn);
        $.ajax({
            method: "POST",
            url: url_gb + "/admin/{{ $admin_lang_slash }}PackingForm",
            data: form.serialize(),
            dataType: "json"
        }).done(function(res) {
            resetButton(btn);
            if (res.status == 1) {
                Swal.fire(res.title, res.content, 'success').then(function() {
                    window.location.href = url_gb + "/admin/{{ $admin_lang_slash }}PackingForm";
                });
            } else {
                Swal.fire(res.title, res.content, 'error');
            }
        }).fail(function(xhr) {
            resetButton(btn);
            ajaxFail(xhr, form);
        });
    });
});
</script>
@endpush
