@extends('admin.layouts.default')

@section('title', $pageTitle)

@section('body')
<div class="page-content container-fluid container-plus">
    <div class="page-header mb-2 pb-2 flex-column flex-sm-row align-items-start align-items-sm-center py-25 px-1">
        <h1 class="page-title text-primary-d2 text-140">{{ $pageTitle }}</h1>
        <div class="ml-sm-auto mt-2 mt-sm-0">
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalFicImport">
                <i class="fa fa-file-import mr-1"></i> Import FIC
            </button>
        </div>
    </div>

    @if (session('fic_success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session('fic_success') }}
        </div>
    @endif
    @if (session('fic_error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session('fic_error') }}
        </div>
    @endif

    <div class="row mt-3">
        <div class="col-12">
            <div class="card dcard">
                <div class="card-body p-0">
                    <table id="tableInvoice" class="table table-striped-primary table-borderless border-0 mb-0 w-100 table-hover">
                        <thead>
                            <tr class="bgc-primary-d1 text-white">
                                <th class="text-center" width="5%">#</th>
                                <th>เลขที่ Invoice / วันที่</th>
                                <th>ลูกค้า</th>
                                <th class="text-center">แถว</th>
                                <th class="text-center" width="200">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Import FIC -->
<div class="modal fade" id="modalFicImport" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ url('admin/'.$route_locale.'/Invoice/FicImport/Upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-file-import mr-1"></i> นำเข้าไฟล์ FIC / SHORT</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted text-95 mb-2">
                        ไฟล์ Excel (.xlsx) จากบัญชี — ระบบจะจับคู่กับ PI ผ่านคอลัมน์ <b>Po No</b> และ <b>item (part_no)</b>
                        แล้วให้ตรวจก่อน apply
                    </p>
                    <div class="form-group mb-0">
                        <label class="d-block">เลือกไฟล์ FIC</label>
                        <input type="file" name="fic_file" accept=".xlsx,.xls" class="form-control-file" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-upload mr-1"></i> อัปโหลด &amp; ตรวจ</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('#tableInvoice').DataTable({
        ajax: {
            url: url_gb + "/admin/{{ $route_locale }}/Invoice/Lists",
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: function(d) {
                d._token = $('meta[name="csrf-token"]').attr('content');
            }
        },
        columns: [
            { data: "DT_RowIndex", searchable: false, orderable: false, className: "text-center" },
            { data: "doc_info", name: "invoice_no", className: "align-middle" },
            { data: "customer_info", name: "customer_name", className: "align-middle" },
            { data: "lines", name: "details_count", className: "text-center align-middle" },
            { data: "action_btns", searchable: false, orderable: false, className: "text-center align-middle" }
        ],
        order: [[1, "desc"]]
    });
</script>
@endpush
