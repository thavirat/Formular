@extends('admin.layouts.default')

@section('title', $currentMenu->title)

@section('body')
<div class="page-content container-fluid container-plus">
    <div class="page-header mb-2 pb-2 flex-column flex-sm-row align-items-start align-items-sm-center py-25 px-1">
        <h1 class="page-title text-primary-d2 text-140">แก้ไข Packing List {{ $packingForm->doc_no }}</h1>
        <div class="page-tools mt-3 mt-sm-0">
            <a href="{{ url('admin/'.$admin_lang_slash.'PackingForm/'.$packingForm->id.'/pdf/customer') }}" class="btn btn-light-info border-0 radius-3 py-2 text-600 text-90 mr-1" target="_blank" title="PL ของลูกค้า">
                <i class="fa fa-file-pdf"></i> PL ลูกค้า
            </a>
            <a href="{{ url('admin/'.$admin_lang_slash.'PackingForm/'.$packingForm->id.'/pdf/accounting') }}" class="btn btn-light-secondary border-0 radius-3 py-2 text-600 text-90 mr-1" target="_blank" title="PL ของบัญชี">
                <i class="fa fa-file-pdf"></i> PL บัญชี
            </a>
            <a href="{{ url('admin/'.$admin_lang_slash.'PackingForm') }}" class="btn btn-light-secondary border-0 radius-3 py-2 text-600 text-90">
                <i class="fa fa-arrow-left"></i> กลับรายการ
            </a>
        </div>
    </div>

    <div class="card dcard">
        <div class="card-body p-3">
            <form id="form-packing-edit">
                @csrf
                <input type="hidden" name="_method" value="PUT">

                @include('admin.PackingForm._header_form', ['packingForm' => $packingForm])

                <p class="text-90 text-muted mb-2">เลือก PI: คลิกช่องแล้วพิมพ์เลข PI ได้ทันที (กรองตาม Part no ในแถว) — กด Tab เพื่อไปช่องถัดไป</p>

                <h5 class="text-primary-d2 mt-3 mb-2">รายการสินค้า</h5>
                <div class="table-responsive packing-detail-scroll">
                    <table class="table table-bordered table-sm" id="detailTable">
                        <thead class="bgc-grey-l4 text-nowrap">
                            <tr>
                                <th>แถว</th>
                                <th>From</th>
                                <th>To</th>
                                <th style="min-width:200px">PI / บรรทัด</th>
                                <th>Part no</th>
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
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($packingForm->details as $d)
                                @include('admin.PackingForm._detail_row', ['detail' => $d])
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-sm btn-primary mb-3" id="btn-add-row"><i class="fa fa-plus"></i> เพิ่มแถว</button>

                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> บันทึก</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .packing-detail-scroll { overflow-x: auto; }
    #detailTable .form-control-sm { min-width: 4.5rem; }
    #detailTable td { vertical-align: middle; }
    .select2-container--default .select2-selection--single { height: calc(1.5em + 0.5rem + 2px); min-height: 31px; }
    .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 1.5; padding-top: 2px; }
</style>
@endpush

@push('scripts')
<script type="text/template" id="detail-row-tpl">
@include('admin.PackingForm._detail_row', ['detail' => null])
</script>
<script>
    var packingPiSearchUrl = url_gb + "/admin/{{ $admin_lang_slash }}PackingForm/SearchPiProduct";

    function getRowPartNo($row) {
        return ($row.find('input.part-no-input').val() || '').trim();
    }

    function packingFocusables() {
        return $('#form-packing-edit').find('input.packing-tab:visible:not([readonly]):not([disabled]), .select2-selection.packing-tab-s2:visible');
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

    function clearPiSelect($row) {
        var $sel = $row.find('.select2-pi-line');
        if ($sel.hasClass('select2-hidden-accessible')) {
            $sel.select2('destroy');
        }
        $sel.empty();
        initPiLineSelect($sel);
    }

    function initPiLineSelect($select) {
        if ($select.hasClass('select2-hidden-accessible')) {
            $select.select2('destroy');
        }
        $select.select2({
            placeholder: $select.data('placeholder') || 'พิมพ์เลข PI...',
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
                if (field) {
                    field.focus();
                }
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

    $(document).on('keydown', '.select2-container--open .select2-search__field', function(e) {
        if (e.key !== 'Tab') {
            return;
        }
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

    $('#detailTable tbody .select2-pi-line').each(function() {
        initPiLineSelect($(this));
    });

    $('#btn-add-row').on('click', function() {
        var html = $('#detail-row-tpl').html().trim();
        var $tr = $(html);
        $('#detailTable tbody').append($tr);
        initPiLineSelect($tr.find('.select2-pi-line'));
        $tr.find('input.packing-tab').first().focus();
    });

    $('body').on('click', '.btn-remove-row', function() {
        var $tr = $(this).closest('tr');
        var $sel = $tr.find('.select2-pi-line');
        if ($sel.hasClass('select2-hidden-accessible')) {
            $sel.select2('destroy');
        }
        $tr.remove();
    });

    $('body').on('change', '#detailTable input.part-no-input', function() {
        clearPiSelect($(this).closest('tr'));
    });

    $('body').on('keydown', '#form-packing-edit input.packing-tab', function(e) {
        if (e.key === 'Tab' && !e.shiftKey) {
            e.preventDefault();
            focusNextField($(this));
        } else if (e.key === 'Tab' && e.shiftKey) {
            e.preventDefault();
            focusPrevField($(this));
        }
    });

    $('#form-packing-edit').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var btn = form.find('button[type=submit]');
        loadingButton(btn);
        $.ajax({
            method: "POST",
            url: url_gb + "/admin/{{ $admin_lang_slash }}PackingForm/{{ $packingForm->id }}",
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
</script>
@endpush
