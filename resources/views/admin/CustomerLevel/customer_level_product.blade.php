@extends('admin.layouts.default')

@section('title', $currentMenu->title)

@section('css')
<style>
    /* 1. Layout & Table Setup */
    .table-price-setting thead th {
        background-color: #f4f7f9;
        text-align: center;
        vertical-align: middle;
        white-space: nowrap;
        border-bottom: 2px solid #dee2e6;
        color: #495057;
        font-size: 0.85rem;
    }

    /* ตรึงคอลัมน์ชื่อสินค้า */
    .sticky-col {
        position: sticky;
        left: 0;
        background-color: #fff !important;
        z-index: 10;
        box-shadow: 2px 0 5px -2px rgba(0,0,0,0.1);
        min-width: 250px;
    }

    /* 2. Input Styling */
    .price-input {
        text-align: right;
        font-weight: 600;
        color: #2d3748;
        min-width: 120px;
        border-color: #d1d5db;
    }

    .price-input:focus {
        border-color: #3182ce;
        box-shadow: 0 0 0 1px #3182ce;
    }

    /* 3. Status Indicators */
    .status-indicator {
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }
    .status-saving { color: #f6ad55; }   /* สีส้ม: กำลังบันทึก */
    .status-success { color: #48bb78; }  /* สีเขียว: สำเร็จ */
    .status-error { color: #f56565; }    /* สีแดง: พลาด */

    /* 4. Utility */
    .table-hover tbody tr:hover,
    .table-hover tbody tr:hover .sticky-col {
        background-color: #f0f7ff !important;
    }
    .input-group-text {
        font-size: 0.7rem;
        min-width: 40px;
        justify-content: center;
        background-color: #f8fafc;
    }
</style>
@endsection

@section('body')
<div class="page-content container container-plus">
    <div class="page-header mb-2 pb-2 flex-column flex-sm-row align-items-start align-items-sm-center py-25 px-1">
        <h1 class="page-title text-primary-d2 text-140">
            {{__('Setting Price Customer Level')}} {{$level->name}}

        </h1>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card dcard overflow-hidden">
                <div class="card-header bgc-primary-d1 d-flex justify-content-between">
                    <h5 class="card-title text-white">
                        <i class="fa fa-sync-alt mr-2 text-white-tp2"></i>
                        {{__('Manage Product Prices')}}
                    </h5>
                    <span class="badge badge-yellow text-80 px-3 text-white">
                        <i class="fa fa-info-circle mr-1 "></i> {{__('Changes are saved automatically per row')}}
                    </span>
                </div>

                <div class="card-body p-0 table-responsive">
                    <table class="table table-bordered table-hover mb-0 table-price-setting" id="table_product">
                        <thead>
                            <tr>
                                <th width="50">#</th>
                                <th class="sticky-col">{{__('Product Name')}}</th>
                                @foreach($Currencies as $Currency)
                                    <th>{{ $Currency->name }} ({{$Currency->symbol}})</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
$(document).ready(function() {
    // 1. เปิดใช้งาน DataTables พร้อม Pagination (สำคัญมากสำหรับ 2,000 รายการ)
    var table_product = $('#table_product').dataTable({

        "ajax": {
            "url": url_gb+"/admin/CustomerLevel/Product/Lists",
            "type": "POST",
            "data": function ( d ) {
                d.level_id = {{$level_id}};
                // d.custom = $('#myInput').val();
                // etc
            }
        },
        "drawCallback": function( settings ) {
            $('[data-toggle="tooltip"]').tooltip();
        },
        "responsive": false,
        "columns": [
            {"data": "DT_RowIndex", 'searchable': false, 'orderable': false, "class": "text-center"},
            {"data": "name_en", "name": 'name_en'},
            @foreach($Currencies as $Currency)
                {"data": "currency_{{$Currency->symbol}}", "name": 'currency_{{$Currency->symbol}}' , 'searchable': false, 'orderable': false,},
            @endforeach
        ]
    });

    function saveSingleInput(inputElement) {
        const row = inputElement.closest('tr');
        const productId = row.attr('data-product-id');
        const currencyId = inputElement.data('currency-id');
        const levelId = inputElement.data('level-id');
        const value = inputElement.val().replace(/,/g, '');

        // ชี้เป้าไปที่จุดแสดงสถานะในช่องนั้นๆ (ในที่นี้คือ span ที่มี symbol)
        const statusBox = $(`#status-${productId}-${currencyId}`);
        const originalContent = statusBox.html(); // เก็บตัวย่อสกุลเงินไว้ (เช่น $)

        // 1. แสดงสถานะกำลังบันทึก (เปลี่ยนพื้นหลังหรือใส่ Spinner เล็กๆ)
        statusBox.html('<i class="fa fa-spinner fa-spin text-warning"></i>');
        statusBox.addClass('bgc-yellow-l4');

        $.ajax({
            method: "POST",
            url: url_gb + "/admin/CustomerLevel/ProductPrice/QuickSave",
            data: {
                product_id: productId,
                currency_id: currencyId,
                price: value,
                level_id : levelId
            },
            dataType: "json"
        }).done(function(res) {
            if (res.status == 1) {
                // 2. บันทึกสำเร็จ: แสดงสีเขียวชั่วคราว
                statusBox.html('<i class="fa fa-check text-success"></i>');
                statusBox.removeClass('bgc-yellow-l4').addClass('bgc-success-l4');

                setTimeout(() => {
                    statusBox.html(originalContent);
                    statusBox.removeClass('bgc-success-l4');
                }, 2000);
            } else {
                statusBox.html('<i class="fa fa-exclamation-triangle text-danger"></i>');
            }
        }).fail(function() {
            statusBox.html('<i class="fa fa-times text-danger"></i>');
        });
    }

    // เปลี่ยน Event จากจับทั้งแถว เป็นจับเฉพาะช่องที่มีการเปลี่ยนแปลง
    $('body').on('change', '.auto-save-input', function() {
        saveSingleInput($(this));
    });

    // 4. Event: เมื่อออกจากช่อง (blur) ให้ใส่ Comma
    $('body').on('blur', '.price-input', function() {
        let val = $(this).val().replace(/,/g, '');
        if ($.isNumeric(val)) {
            $(this).val(parseFloat(val).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));
        } else {
            $(this).val('0.00');
        }
    });

    // 5. Bonus:Navigation ปุ่มลูกศร ขึ้น/ลง
    $('body').on('keydown', '.price-input', function(e) {
        let currentInput = $(this);
        let currentTd = currentInput.closest('td');
        let currentTr = currentInput.closest('tr');
        let colIndex = currentTr.find('td').index(currentTd);

        if (e.which == 40) { // Down Arrow
            e.preventDefault();
            currentTr.next().find('td').eq(colIndex).find('input').focus();
        } else if (e.which == 38) { // Up Arrow
            e.preventDefault();
            currentTr.prev().find('td').eq(colIndex).find('input').focus();
        }
    });
});
</script>
@endpush
