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
            box-shadow: 2px 0 5px -2px rgba(0, 0, 0, 0.1);
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

        .status-saving {
            color: #f6ad55;
        }

        /* สีส้ม: กำลังบันทึก */
        .status-success {
            color: #48bb78;
        }

        /* สีเขียว: สำเร็จ */
        .status-error {
            color: #f56565;
        }

        /* สีแดง: พลาด */

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
                {{ __('Setting Price Customer Level') }} {{ $level->name }}
            </h1>
            <div class="page-tools mt-3 mt-sm-0 mb-sm-n1">
                <button type="button" class="btn btn-light-warning btn-h-warning btn-a-warning border-0 radius-3 py-2 text-600 text-90" data-toggle="modal" data-target="#ModalImport">
                    <span class="d-none d-sm-inline mr-1">
                        Upload Price
                    </span>
                    <i class="fa fa-upload text-110 w-2 h-2"></i>
                </button>

                <a href="{{url('admin/CustomerLevel/ExportProduct?id='.$level->id)}}" type="button" class="btn btn-light-green btn-h-green btn-a-green border-0 radius-3 py-2 text-600 text-90">
                    <span class="d-none d-sm-inline mr-1">
                        ดาวโหลดเทมเพลต
                    </span>
                    <i class="fa fa-download text-110 w-2 h-2"></i>
                </a>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <div class="card dcard overflow-hidden">
                    <div class="card-header bgc-primary-d1 d-flex justify-content-between">
                        <h5 class="card-title text-white">
                            <i class="fa fa-sync-alt mr-2 text-white-tp2"></i>
                            {{ __('Manage Product Prices') }}
                        </h5>
                        <span class="badge badge-yellow text-80 px-3 text-white">
                            <i class="fa fa-info-circle mr-1 "></i> {{ __('Changes are saved automatically per row') }}
                        </span>
                    </div>

                    <div class="card-body p-0 table-responsive">
                        <table class="table table-bordered table-hover mb-0 table-price-setting" id="table_product">
                            <thead>
                                <tr>
                                    <th width="50">#</th>
                                    <th class="sticky-col">{{ __('Product Name') }}</th>
                                    @foreach ($Currencies as $Currency)
                                        <th>{{ $Currency->name }} ({{ $Currency->symbol }})</th>
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


<div class="modal fade" id="ModalImport" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow radius-1">
            <form id="FormImport" enctype="multipart/form-data">
                <div class="modal-header bgc-warning-d1">
                    <h5 class="modal-title text-white">
                        <i class="fa fa-upload mr-2"></i>Upload Product Prices
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body p-4 bgc-grey-l5">
                    <div class="text-center mb-3">
                        <i class="fa fa-file-excel fa-3x text-success-m2 shadow-sm p-3 bgc-white radius-round"></i>
                    </div>
                    <div class="form-group">
                        <label class="text-80 text-grey-m1 uppercase font-bolder">เลือกไฟล์ Excel (.xlsx)</label>
                        <input type="file" name="file" class="form-control" accept=".xlsx, .xls" required>
                    </div>
                    <div class="alert bgc-yellow-l3 border-none border-l-4 brc-warning-m1 text-dark-tp2 text-90">
                        <i class="fa fa-exclamation-triangle mr-1 text-orange-d1"></i>
                        คำแนะนำ: โปรดใช้ไฟล์ที่ <strong>Export</strong> จากระบบเพื่อป้องกันรหัสสินค้าผิดพลาด
                    </div>
                </div>

                <div class="modal-footer bgc-white">
                    <button type="button" class="btn btn-outline-grey btn-bold px-4" data-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-warning btn-bold px-4 text-white">
                        <i class="fa fa-save mr-1"></i> เริ่มนำเข้าข้อมูล
                    </button>
                </div>
            </form>
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
                    "url": url_gb + "/admin/CustomerLevel/Product/Lists",
                    "type": "POST",
                    "data": function(d) {
                        d.level_id = {{ $level_id }};
                        // d.custom = $('#myInput').val();
                        // etc
                    }
                },
                "drawCallback": function(settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                },
                "responsive": false,
                "columns": [{
                        "data": "DT_RowIndex",
                        'searchable': false,
                        'orderable': false,
                        "class": "text-center"
                    },
                    {
                        "data": "name_en",
                        "name": 'name_en'
                    },
                    @foreach ($Currencies as $Currency)
                        {
                            "data": "currency_{{ $Currency->symbol }}",
                            "name": 'currency_{{ $Currency->symbol }}',
                            'searchable': false,
                            'orderable': false,
                        },
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
                        level_id: levelId
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


        // JavaScript สำหรับส่งไฟล์
        $('#FormImport').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            loadingButton($(this).find('button[type=submit]'));

            $.ajax({
                url: url_gb + "/admin/CustomerLevel/ImportProduct",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
            }).done(function(res) {
                resetButton($('#FormImport').find('button[type=submit]'));
                if (res.status == 1) {
                    Swal.fire('สำเร็จ', res.content, 'success');
                    $('#ModalImport').modal('hide');
                    tableCustomerCodeProduct.api().ajax.reload();
                } else {
                    Swal.fire('ผิดพลาด', res.content, 'error');
                }
            });
        });
    </script>
@endpush
