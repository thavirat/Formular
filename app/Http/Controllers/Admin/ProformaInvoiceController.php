<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Fic2FiExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\ProformaInvoice;
use App\Models\ProformaInvoiceProduct;
use App\Models\Customer;
use App\Models\Incoterm;
use App\Models\CreditPayment;
use App\Models\Quotation;
use App\Models\Currency;
use App\Models\QuotationProduct;
use App\Models\ProformaInvoiceStatus;
use App\Models\AdminUser;
use App\Models\ContactChannel;
use App\Models\Comment;
use App\Services\PackingListExcelExportService;
use Maatwebsite\Excel\Facades\Excel;
use Auth;
use DataTables;
use Help;
use UserPermission;
use DB;
use Validator;
use Storage;

class ProformaInvoiceController extends AdminController
{
    public $current_menu;

    public function __construct()
    {
        $this->current_menu = 'ProformaInvoice';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $permission = Help::CheckPermissionMenu($this->current_menu, 'r');
        if (!$permission) {
            return redirect('/admin/PermissionDenined');
        }
        $data['currentMenu'] = Menu::where('url', $this->current_menu)->first();
        $data['SidebarMenus'] = Menu::Active()->get();
        $data['proforma_invoice_statuses'] = ProformaInvoiceStatus::orderBy('name')->get();
        $data['admins'] = AdminUser::orderBy('nickname')->get();
        $data['Customers'] = Customer::orderBy('company_name')->get();
        $data['url_pi_create'] = $this->proformaInvoiceCreateUrl($request);
        $data['app_debug'] = (bool) config('app.debug');
        if ($data['app_debug']) {
            $slash = $this->adminLangSlash($request);
            $data['packing_export_template_url'] = url('admin/'.$slash.'ProformaInvoice/ExportPackingListTemplate');
        }

        return view('admin.ProformaInvoice.proforma_invoice', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $permission = Help::CheckPermissionMenu($this->current_menu, 'c');
        if (!$permission) {
            return redirect('/admin/PermissionDenined');
        }

        $data['currentMenu'] = Menu::where('url', $this->current_menu)->first();
        $data['Customers'] = Customer::orderBy('company_name')->get();
        $data['Incoterms'] = Incoterm::orderBy('code')->get();
        $data['Currencies'] = Currency::orderBy('name')->get();
        $data['CreditPayments'] = CreditPayment::orderBy('name')->get();
        $data['admin_lang_slash'] = $this->adminLangSlash($request);
        $data['default_currency_symbol'] = Currency::orderBy('name')->value('symbol') ?? '';
        $data['suggested_doc_no'] = $this->suggestProformaInvoiceDocNo(date('Y-m-d'))['doc_no'];

        $id = $request->query('quotation_id');
        $data['Quotation'] = null;

        if ($id !== null && $id !== '') {
            $data['Quotation'] = Quotation::with(['products' => function ($q) {
                $q->leftJoin('products', 'quotation_products.product_id', '=', 'products.id');
                $q->select(
                    'quotation_products.*',
                    'products.code as part_no'
                );
            }])
                ->leftJoin('currencies', 'quotations.currency_id', '=', 'currencies.id')
                ->leftJoin('credit_payments', 'quotations.credit_payment_id', '=', 'credit_payments.id')
                ->select(
                    'quotations.*',
                    'currencies.name as currency_name',
                    'currencies.symbol',
                    'credit_payments.name as credit_payment_name'
                )
                ->find($id);

            if (!$data['Quotation']) {
                abort(404, 'Quotation not found');
            }
        }

        return view('admin.ProformaInvoice.proforma_invoice_create', $data);
    }

    public function suggestDocNo(Request $request)
    {
        $permission = Help::CheckPermissionMenu($this->current_menu, 'c');
        if (!$permission) {
            return response()->json(['status' => 0, 'title' => 'ไม่มีสิทธิ์', 'content' => 'Permission denied'], 403);
        }

        $suggested = $this->suggestProformaInvoiceDocNo($request->query('doc_date'));

        return response()->json([
            'status' => 1,
            'doc_no' => $suggested['doc_no'],
            'run_no' => $suggested['run_no'],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required',
            'doc_no' => 'required|string|max:50|unique:proforma_invoices,doc_no',
            'doc_date' => 'required',
            'product' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'title' => 'เกิดข้อผิดพลาด',
                'content' => $validator->errors()->first(),
            ]);
        }

        DB::beginTransaction();

        try {
            $doc_no = trim((string) $request->input('doc_no'));
            $suggested = $this->suggestProformaInvoiceDocNo($request->input('doc_date'));
            $run_no = $suggested['run_no'];

            $quotationId = $request->input('quotation_id');
            $quotationId = ($quotationId === '' || $quotationId === null) ? null : (int) $quotationId;

            $pi = new ProformaInvoice();
            $pi->quotation_id      = $quotationId;
            $pi->status_id         = 1;
            $pi->customer_id       = $request->customer_id;
            $pi->incoterm_id       = $request->incoterm_id;
            $pi->currency_id       = $request->currency_id;
            $pi->credit_payment_id = $request->credit_payment_id;
            $pi->doc_no            = $doc_no;
            $pi->doc_date          = $request->doc_date;
            $pi->run_no            = $run_no;
            $pi->contact_name      = $request->contact_name;
            $pi->company_name      = $request->company_name;
            $pi->tax_id            = $request->tax_id;
            $pi->address           = $request->address;
            $pi->fax_no            = $request->fax_no;
            $pi->ship_date            = $request->ship_date;
            $pi->ship_to_code            = $request->ship_to_code;
            $pi->customer_po            = $request->customer_po;
            $pi->ship_remark            = $request->ship_remark;
            $pi->subtotal          = str_replace(',', '', $request->grand_total);
            $pi->total             = str_replace(',', '', $request->grand_total);
            $pi->created_by        = Auth::guard('admin')->user()->id;
            $pi->save();

            if ($request->product && is_array($request->product)) {
                foreach ($request->product as $key => $product_id) {
                    if (empty($product_id)) {
                        continue;
                    }

                    $request_qty = $request->qty[$key] ?? 0;

                    if ($quotationId) {
                        $quotationProduct = QuotationProduct::where('quotation_id', $quotationId)
                            ->where('product_id', $product_id)
                            ->first();

                        if ($quotationProduct) {
                            QuotationProduct::where('id', $quotationProduct->id)
                                ->update(['proforma_qty' => $quotationProduct->proforma_qty + $request_qty]);
                        }
                    }

                    $item = new ProformaInvoiceProduct();
                    $item->pi_id          = $pi->id;
                    $item->product_id     = $product_id;
                    $item->seq            = $request->seq[$key] ?? ($key + 1); // บันทึกลำดับจาก Drag & Drop
                    $item->drawing        = $request->drawing[$key] ?? null;
                    $item->cus_code       = $request->customer_code[$key] ?? null;
                    $item->detail_eng     = $request->description[$key] ?? null;
                    $item->qty            = $request_qty;
                    $item->price_per_item = $request->unit_price[$key] ?? 0;
                    $item->total_price    = $request->amount[$key] ?? 0;
                    $item->save();
                }
            }

            DB::commit();

            return response()->json([
                'status' => 1,
                'title'  => 'สำเร็จ',
                'content' => 'บันทึกใบ Proforma Invoice เลขที่ ' . $doc_no . ' เรียบร้อยแล้ว',
                'id'     => $pi->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 0,
                'title'   => 'เกิดข้อผิดพลาด',
                'content' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ProformaInvoice = ProformaInvoice::find($id);

        $return['status'] = 1;
        $return['title'] = 'Get ProformaInvoice';
        $return['content'] = $ProformaInvoice;
        return $return;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $data['SidebarMenus'] = Menu::Active()->get();
        $data['currentMenu']  = Menu::where('url', $this->current_menu)->first();

        // ดึงข้อมูล Master Data สำหรับ Dropdown
        $data['Customers']      = Customer::orderBy('company_name')->get();
        $data['Incoterms']      = Incoterm::orderBy('code')->get();
        $data['Currencies']     = Currency::orderBy('name')->get();
        $data['CreditPayments'] = CreditPayment::orderBy('name')->get();

        // ดึงข้อมูล Proforma Invoice พร้อม Products ที่เรียงตาม seq
        $data['ProformaInvoice'] = ProformaInvoice::with(['products' => function ($q) {
            $q->leftJoin('products', 'proforma_invoice_products.product_id', '=', 'products.id')
                ->select(
                    'proforma_invoice_products.*',
                    'products.code as part_no'
                )
                ->orderBy('proforma_invoice_products.seq', 'asc'); // เรียงตามลำดับที่บันทึกไว้
        }])->findOrFail($id);

        $data['admin_lang_slash'] = $this->adminLangSlash($request);

        return view('admin.ProformaInvoice.proforma_invoice_edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $pi = ProformaInvoice::findOrFail($id);

            $newQuotationId = $request->input('quotation_id');
            $newQuotationId = ($newQuotationId === '' || $newQuotationId === null) ? null : (int) $newQuotationId;

            // --- 1. คืนค่ายอดโควต้าเดิมกลับไปให้ Quotation ก่อน (เฉพาะ PI ที่ผูกใบเสนอราคา) ---
            $old_pi_products = ProformaInvoiceProduct::where('pi_id', $pi->id)->get();
            if ($pi->quotation_id) {
                foreach ($old_pi_products as $old_item) {
                    QuotationProduct::where('quotation_id', $pi->quotation_id)
                        ->where('product_id', $old_item->product_id)
                        ->decrement('proforma_qty', $old_item->qty); // ลดค่ายอดที่เคยดึงไป
                }
            }

            // ลบรายการสินค้า PI เดิมทิ้งทั้งหมดเพื่อเตรียม Insert ใหม่
            ProformaInvoiceProduct::where('pi_id', $pi->id)->delete();

            $pi->quotation_id      = $newQuotationId;
            $pi->status_id         = 1;
            $pi->customer_id       = $request->customer_id;
            $pi->incoterm_id       = $request->incoterm_id;
            $pi->currency_id       = $request->currency_id;
            $pi->credit_payment_id = $request->credit_payment_id;
            $pi->doc_date          = $request->doc_date;
            $pi->contact_name      = $request->contact_name;
            $pi->company_name      = $request->company_name;
            $pi->tax_id            = $request->tax_id;
            $pi->address           = $request->address;
            $pi->fax_no            = $request->fax_no;
            $pi->ship_date            = $request->ship_date;
            $pi->ship_to_code            = $request->ship_to_code;
            $pi->customer_po            = $request->customer_po;
            $pi->ship_remark            = $request->ship_remark;
            $pi->subtotal          = str_replace(',', '', $request->grand_total);
            $pi->total             = str_replace(',', '', $request->grand_total);
            $pi->save();

            // --- 3. ตรวจสอบโควต้าและ Insert รอบใหม่ ---
            if ($request->product && is_array($request->product)) {
                foreach ($request->product as $key => $product_id) {
                    if (empty($product_id)) {
                        continue;
                    }

                    $request_qty = $request->qty[$key] ?? 0;

                    if ($newQuotationId) {
                        $quotationProduct = QuotationProduct::where('quotation_id', $newQuotationId)
                            ->where('product_id', $product_id)
                            ->first();

                        if ($quotationProduct) {
                            QuotationProduct::where('id', $quotationProduct->id)
                                ->increment('proforma_qty', $request_qty);
                        }
                    }

                    // Insert รายการ PI
                    $item = new ProformaInvoiceProduct();
                    $item->pi_id          = $pi->id;
                    $item->product_id     = $product_id;
                    $item->seq            = $request->seq[$key] ?? ($key + 1); // บันทึกลำดับจาก Drag & Drop
                    $item->drawing        = $request->drawing[$key] ?? null;
                    $item->cus_code       = $request->customer_code[$key] ?? null;
                    $item->detail_eng     = $request->description[$key] ?? null;
                    $item->qty            = $request_qty;
                    $item->price_per_item = $request->unit_price[$key] ?? 0;
                    $item->total_price    = $request->amount[$key] ?? 0;
                    $item->save();
                }
            }

            DB::commit();
            return response()->json(['status' => 1, 'title' => 'สำเร็จ', 'content' => 'อัปเดตข้อมูลเรียบร้อย']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 0, 'title' => 'เกิดข้อผิดพลาด', 'content' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $pi = ProformaInvoice::findOrFail($id);

            // 1. ดึงรายการสินค้าใน PI ที่กำลังจะลบ
            $pi_products = ProformaInvoiceProduct::where('pi_id', $pi->id)->get();

            // 2. คืนยอด proforma_qty กลับไปให้ Quotation (เฉพาะ PI ที่ผูกใบเสนอราคา)
            if ($pi->quotation_id) {
                foreach ($pi_products as $item) {
                    DB::table('quotation_products')
                        ->where('quotation_id', $pi->quotation_id)
                        ->where('product_id', $item->product_id)
                        ->decrement('proforma_qty', $item->qty); // ลบยอดที่เคยจองไว้ออก
                }
            }

            // 3. ลบรายการสินค้า PI และ ตัว PI
            ProformaInvoiceProduct::where('pi_id', $pi->id)->delete();
            $pi->delete();

            DB::commit();
            return response()->json(['status' => 1, 'title' => 'สำเร็จ', 'content' => 'ลบข้อมูลเรียบร้อยแล้ว']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 0, 'title' => 'เกิดข้อผิดพลาด', 'content' => $e->getMessage()]);
        }
    }

    /**
     * Show Data With Datatable from storage.
     *
     * @param   \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\Response
     */
    public function report(Request $request)
    {
        $result = ProformaInvoice::with(['Comments' => function ($q) {
            $q->leftJoin('admin_users', 'admin_users.id', 'comments.created_by');
            $q->leftJoin('contact_channels', 'contact_channels.id', 'comments.channel_id');
            $q->select(
                'comments.*',
                'admin_users.nickname as created_by_name',
                'contact_channels.name as channel_name'
            )->orderBy('comments.created_at', 'desc');
        }])
            ->leftJoin('admin_users', 'admin_users.id', '=', 'proforma_invoices.created_by')
            ->leftJoin('admin_users as send_approve', 'proforma_invoices.send_approve_by', '=', 'send_approve.id')
            ->leftJoin('admin_users as approve', 'proforma_invoices.approve_by', '=', 'approve.id')
            ->leftJoin('proforma_invoice_statuses', 'proforma_invoices.status_id', '=', 'proforma_invoice_statuses.id')
            ->select(
                'proforma_invoices.*',
                'admin_users.nickname as created_by_name',
                'send_approve.nickname as send_approve_name',
                'approve.nickname as approve_name',
                'proforma_invoice_statuses.name as status_name'
            );

        $pfx = DB::getTablePrefix();
        $pipTbl = $pfx . (new ProformaInvoiceProduct)->getTable();
        $piTbl  = $pfx . (new ProformaInvoice)->getTable();

        $result
            ->selectRaw("(SELECT COALESCE(SUM(pip.qty), 0) FROM {$pipTbl} pip WHERE pip.pi_id = {$piTbl}.id) as total_qty")
            ->selectRaw("(SELECT COALESCE(SUM(pip.produced_qty), 0) FROM {$pipTbl} pip WHERE pip.pi_id = {$piTbl}.id) as total_produced_qty");

        if ($request->has('start_date') && $request->start_date != '') {
            $result->where('proforma_invoices.doc_date', '>=', date('Y-m-d', strtotime($request->start_date)));
        }
        if ($request->has('end_date') && $request->end_date != '') {
            $result->where('proforma_invoices.doc_date', '<=', date('Y-m-d', strtotime($request->end_date)));
        }
        if ($request->has('status_id') && $request->status_id != 'all' && $request->status_id !== null && $request->status_id !== '') {
            $result->where('proforma_invoices.status_id', '=', $request->status_id);
        }
        if ($request->has('admin_id') && $request->admin_id != 'all' && $request->admin_id !== null && $request->admin_id !== '') {
            $result->where('proforma_invoices.created_by', '=', $request->admin_id);
        }
        if ($request->has('customer_id') && $request->customer_id != 'all' && $request->customer_id !== null && $request->customer_id !== '') {
            $result->where('proforma_invoices.customer_id', '=', $request->customer_id);
        }

        $production = $request->input('production_status');
        if ($production === 'completed') {
            $result->whereRaw("(SELECT COALESCE(SUM(pip.qty), 0) FROM {$pipTbl} pip WHERE pip.pi_id = {$piTbl}.id) > 0")
                   ->whereRaw("(SELECT COALESCE(SUM(pip.produced_qty), 0) FROM {$pipTbl} pip WHERE pip.pi_id = {$piTbl}.id) >= (SELECT COALESCE(SUM(pip.qty), 0) FROM {$pipTbl} pip WHERE pip.pi_id = {$piTbl}.id)");
        } elseif ($production === 'incomplete') {
            $result->whereRaw("(SELECT COALESCE(SUM(pip.produced_qty), 0) FROM {$pipTbl} pip WHERE pip.pi_id = {$piTbl}.id) < (SELECT COALESCE(SUM(pip.qty), 0) FROM {$pipTbl} pip WHERE pip.pi_id = {$piTbl}.id)");
        }

        return $result->orderByDesc('proforma_invoices.id');
    }

    public function lists(Request $request)
    {
        $result = $this->report($request);
        $lang = config('app.locale');
        $channels = ContactChannel::get();
        $all_permission = UserPermission::getMyPermissions();

        return DataTables::of($result)
            ->addColumn('doc_info', function ($rec) {
                return '<div class="text-primary-d2 font-bolder text-95">' . e($rec->doc_no) . '</div>
                        <div class="text-80 text-grey-m2"><i class="far fa-calendar-alt mr-1"></i>' . e($rec->doc_date) . '</div>';
            })
            ->addColumn('customer_info', function ($rec) {
                return '<div class="text-dark-m3 font-bold">' . e($rec->company_name) . '</div>
                        <div class="text-80 text-blue-m2"><i class="far fa-user mr-1"></i>' . e($rec->created_by_name ?? '-') . '</div>';
            })
            ->editColumn('total', function ($rec) {
                return '<span class="text-110 font-bolder text-success-d1">' . number_format($rec->total, 2) . '</span>';
            })
            ->addColumn('produced_progress', function ($rec) {
                $totalQty = (float) ($rec->total_qty ?? 0);
                $producedQty = (float) ($rec->total_produced_qty ?? 0);

                if ($totalQty <= 0) {
                    return '<span class="text-muted text-80">-</span>';
                }

                $pct = min(100, round(($producedQty / $totalQty) * 100));
                $color = $pct >= 100 ? 'success' : ($pct >= 50 ? 'warning' : 'danger');
                $label = number_format($producedQty, 0) . ' / ' . number_format($totalQty, 0);

                return '<div class="text-center">
                    <div class="text-90 text-600 mb-1">' . $label . '</div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bgc-' . $color . '" style="width: ' . $pct . '%"></div>
                    </div>
                    <div class="text-75 text-' . $color . '-d1 mt-1">' . $pct . '%</div>
                </div>';
            })
            ->addColumn('status_name', function ($rec) {
                $statusColor = 'secondary';
                if ((int) $rec->status_id === 1) $statusColor = 'light-grey';
                if ((int) $rec->status_id === 2) $statusColor = 'warning';
                if ((int) $rec->status_id === 3) $statusColor = 'success';
                if ((int) $rec->status_id === 4) $statusColor = 'danger';
                $str = '<div class="text-center">
                            <span class="badge badge-lg bgc-' . $statusColor . '-l3 text-' . $statusColor . '-d2 border-1 brc-' . $statusColor . '-m3 mb-1">' . e($rec->status_name) . '</span>';
                if ((int) $rec->status_id >= 2 && !empty($rec->send_approve_date)) {
                    $str .= '<div class="text-75 text-grey-m1 mt-1" title="วันที่ส่งขออนุมัติ">
                                <i class="fa fa-paper-plane mr-1 text-purple-m2"></i>' . e($rec->send_approve_name ?? '-') . '
                                <br>' . date('d/m/Y H:i', strtotime($rec->send_approve_date)) . '
                            </div>';
                }
                if ((int) $rec->status_id == 3 && !empty($rec->approve_date)) {
                    $str .= '<div class="text-75 text-success-m1 mt-1 border-t-1 brc-grey-l4 pt-1" title="วันที่อนุมัติ">
                                <i class="fa fa-check-circle mr-1"></i>' . e($rec->approve_name ?? '-') . '
                                <br>' . date('d/m/Y H:i', strtotime($rec->approve_date)) . '
                            </div>';
                }
                $str .= '</div>';
                return $str;
            })
            ->editColumn('created_by', function ($rec) {
                return $rec->created_by_name ?? '-';
            })
            ->addColumn('comment_box', function ($rec) use ($channels) {
                $items = '';
                foreach ($rec->Comments as $comment) {
                    $items .= '
                <div class="mb-2 p-1 border-l-3 brc-success-m2 bgc-grey-l5 radius-1 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-600 text-blue-d1 text-80">' . ($comment->created_by_name ?? '-') . '</span>
                        <span class="text-70 text-grey-m1"><i class="far fa-clock mr-1 text-75"></i>' . date('d/m/Y H:i', strtotime($comment->created_at)) . '</span>
                    </div>
                    <div class="text-85 text-dark-m3 line-height-125 px-1">' . $comment->detail . '</div>
                    <div class="text-70 text-right mt-1">
                        <span class="badge badge-sm bgc-grey-l3 text-grey-d2 border-0">' . ($comment->channel_name ?? '-') . '</span>
                    </div>
                </div>';
                }

                $options = '';
                foreach ($channels as $channel) {
                    $options .= '<option value="' . $channel->id . '">' . $channel->name . '</option>';
                }

                $history = '<div class="comment-history mb-3 pr-1" style="max-height: 180px; overflow-y: auto; scrollbar-width: thin;">' . $items . '</div>';

                $inputSection = '
            <div class="comment-input-group bgc-white p-2 radius-1 border-1 brc-grey-l2 shadow-sm">
                <textarea class="form-control text-85 border-0 bgc-transparent no-resize comment-' . $rec->id . '"
                        rows="2" placeholder="พิมพ์บันทึกติดตามงาน..."></textarea>

                <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-t-1 brc-grey-l4">
                    <div class="flex-grow-1 mr-2">
                        <select class="custom-select custom-select-sm border-0 bgc-grey-l4 text-80 text-600 channel-' . $rec->id . '">
                            ' . $options . '
                        </select>
                    </div>
                    <button type="button" class="btn btn-sm btn-success px-3 btn-save-comment shadow-sm radius-2px"
                            data-id="' . $rec->id . '" data-customer-id="' . $rec->customer_id . '">
                        <i class="fa fa-paper-plane mr-1 text-90"></i> บันทึก
                    </button>
                </div>
            </div>';

                return '<div class="pi-comment-container p-1" style="min-width: 250px;">' . $history . $inputSection . '</div>';
            })
            ->addColumn('action_btns', function ($rec) use ($lang, $all_permission) {
                $tEdit = __('Edit') . ' — แก้ไข';
                $tDel = __('Delete') . ' — ลบ';
                $tPdf = 'PDF — ออกใบ PI ส่งโรงงาน';
                $tFa = __('FA') . ' — ออกใบ PI ส่งโรงงาน (Factory Accept)';
                $tExPo = __('EX PO') . ' — ออกใบ PI ส่งโรงงาน (Export PO)';
                $tPoProduct = __('PO Product') . ' — ออกใบ Export Product';
                $tFic2Fi = __('Fic 2 Fi') . ' — ออกใบ Fic 2 Fi';

                $update = Help::CheckPermissionMenu($this->current_menu, 'u');
                $str = '<div class="btn-group btn-group-sm">';
                $str .= '<a href="' . url('admin/' . $lang . '/ProformaInvoice/pdfFactory?pi_id=' . $rec->id) . '"
                    class="btn btn-outline-info btn-h-light-info btn-a-light-info border-b-2"
                    title="' . e($tPdf) . '" target="_blank">
                    <i class="fa fa-file-pdf"></i>
                </a>';
                if ($update) {
                    $str .= '<a href="' . url('admin/' . $lang . '/ProformaInvoice/' . $rec->id . '/edit') . '"
                        class="btn btn-outline-warning btn-h-light-warning btn-a-light-warning border-b-2"
                        title="' . e($tEdit) . '">
                        <i class="fa fa-edit"></i>
                    </a>';
                }
                $delete = Help::CheckPermissionMenu($this->current_menu, 'd');
                if ($delete) {
                    $str .= '<button class="btn btn-outline-danger btn-h-light-danger btn-a-light-danger border-b-2 btn-delete"
                        data-id="' . $rec->id . '" title="' . e($tDel) . '">
                        <i class="fa fa-trash-alt"></i>
                    </button>';
                }
                if ((int) $rec->status_id === 1) {
                    $str .= '<button class="btn btn-outline-purple btn-h-light-purple btn-a-light-purple border-b-2 btn-request-approval"
                        data-id="' . $rec->id . '" title="ส่งขออนุมัติ">
                        <i class="fa fa-paper-plane"></i>
                    </button>';
                }
                $approvePiPermission = isset($all_permission['approve_pi']) ? $all_permission['approve_pi'] : 'F';
                if ((int) $rec->status_id === 2 && $approvePiPermission === 'T') {
                    $str .= '<button class="btn btn-outline-success btn-h-light-success btn-a-light-success border-b-2 btn-approve"
                        data-id="' . $rec->id . '" title="อนุมัติ">
                        <i class="fa fa-check-circle"></i>
                    </button>';
                }
                if ((int) $rec->status_id === 3) {
                    $str .= '<a href="' . url('admin/' . $lang . '/ProformaInvoice/' . $rec->id . '/FA') . '"
                        class="btn btn-outline-orange btn-h-light-orange btn-a-light-orange border-b-2"
                        title="' . e($tFa) . '">
                        <i class="fa fa-industry"></i>
                    </a>';
                    $str .= '<a href="' . url('admin/' . $lang . '/ProformaInvoice/' . $rec->id . '/ExportPo') . '"
                        class="btn btn-outline-blue btn-h-light-blue btn-a-light-blue border-b-2"
                        title="' . e($tExPo) . '">
                        <i class="fa fa-shipping-fast"></i>
                    </a>';
                    $str .= '<a href="' . url('admin/' . $lang . '/ProformaInvoice/' . $rec->id . '/ExportProduct') . '"
                        class="btn btn-outline-secondary btn-h-light-secondary btn-a-light-secondary border-b-2"
                        title="' . e($tPoProduct) . '">
                        <i class="fa fa-boxes"></i>
                    </a>';
                    $str .= '<a href="' . url('admin/' . $lang . '/ProformaInvoice/' . $rec->id . '/Fic2Fi') . '"
                        class="btn btn-outline-purple btn-h-light-purple btn-a-light-purple border-b-2"
                        title="' . e($tFic2Fi) . '">
                        <i class="fa fa-random"></i>
                    </a>';
                }
                if (config('app.debug')) {
                    $str .= '<a href="' . url('admin/' . $lang . '/ProformaInvoice/' . $rec->id . '/ExportPackingList') . '"
                        class="btn btn-outline-primary btn-h-light-primary btn-a-light-primary border-b-2"
                        title="Packing List Excel (Debug) — ดาวน์โหลดเพื่อนำเข้า Packing">
                        <i class="fa fa-file-excel"></i>
                    </a>';
                }
                $str .= '</div>';
                return $str;
            })
            ->addIndexColumn()
            ->rawColumns(['doc_info', 'customer_info', 'total', 'produced_progress', 'status_name', 'comment_box', 'action_btns'])
            ->make(true);
    }

    public function RequestApproval(Request $request)
    {
        try {
            if (empty($request->id)) {
                return response()->json(['status' => 0, 'title' => 'ผิดพลาด', 'content' => 'กรุณากรอกรายละเอียด']);
            }
            $affected = ProformaInvoice::where('id', $request->id)
                ->where('status_id', 1) // 1 = Draft
                ->update([
                'status_id' => 2,
                'send_approve_by' => Auth::guard('admin')->user()->id,
                'send_approve_date' => date('Y-m-d H:i:s')
            ]);
            if ($affected === 0) {
                return response()->json([
                    'status' => 0,
                    'title' => 'ผิดพลาด',
                    'content' => 'ส่งขออนุมัติได้เฉพาะเอกสารสถานะ Draft เท่านั้น'
                ]);
            }
            return response()->json([
                'status' => 1,
                'title' => 'สำเร็จ',
                'content' => 'ส่งอนุมัติเรียบร้อย'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    public function Approve(Request $request)
    {
        try {
            if (empty($request->id)) {
                return response()->json(['status' => 0, 'title' => 'ผิดพลาด', 'content' => 'กรุณากรอกรายละเอียด']);
            }
            $affected = ProformaInvoice::where('id', $request->id)
                ->where('status_id', 2) // 2 = รออนุมัติ
                ->update([
                'status_id' => 3,
                'approve_by' => Auth::guard('admin')->user()->id,
                'approve_date' => date('Y-m-d H:i:s')
            ]);
            if ($affected === 0) {
                return response()->json([
                    'status' => 0,
                    'title' => 'ผิดพลาด',
                    'content' => 'อนุมัติได้เฉพาะเอกสารสถานะรออนุมัติเท่านั้น'
                ]);
            }
            return response()->json([
                'status' => 1,
                'title' => 'สำเร็จ',
                'content' => 'ส่งอนุมัติเรียบร้อย'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    public function save_comment(Request $request)
    {
        try {
            if (empty($request->detail)) {
                return response()->json(['status' => 0, 'title' => 'ผิดพลาด', 'content' => 'กรุณากรอกรายละเอียด']);
            }

            $comment = new Comment();
            $comment->channel_id  = $request->contact_channel_id;
            $comment->customer_id = $request->customer_id;
            $comment->pi_id       = $request->pi_id;
            $comment->detail      = $request->detail;
            $comment->created_by  = Auth::guard('admin')->user()->id;
            $comment->save();

            return response()->json([
                'status' => 1,
                'title' => 'สำเร็จ',
                'content' => 'บันทึกคอมเมนต์เรียบร้อยแล้ว'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    public function export_excel(Request $request)
    {
        $result = $this->report($request);
        $data['result'] = $result->get();

        \Excel::create('รายงาน ProformaInvoice ', function ($excel) use ($data) {
            $excel->sheet('รายงาน ProformaInvoice', function ($sheet) use ($data) {
                $sheet->loadView('admin.ProformaInvoice.proforma_invoice_export_excel', $data);
            });
        })->export('xlsx');
    }

    public function export_print(Request $request)
    {
        $result = $this->report($request);
        $data['result'] = $result->get();


        $pdf = \PDF::loadView('admin.ProformaInvoice.proforma_invoice_export_print', $data);
        return $pdf->stream('ProformaInvoice.pdf');
    }

    public function export_pdf(Request $request)
    {
        $result = $this->report($request);
        $data['result'] = $result->get();

        return view('admin.ProformaInvoice.proforma_invoice_export_pdf', $data);
    }

    public function pdfFactory(Request $request)
    {
        $id = $request->input('pi_id');
        $pi = ProformaInvoice::with([
            'Products' => function ($q) {
                $q->orderBy('seq');
            },
            'Products.product.category',
            'Products.product.unitProduct',
            'currency',
            'incoterm',
            'creditPayment',
            'createdBy',
        ])->findOrFail($id);

        $pdf = \PDF::loadView('admin.ProformaInvoice.pdf_factory', ['pi' => $pi]);

        return $pdf->stream($pi->doc_no . '.pdf');
    }

    public function factory_accept($id)
    {
        // ดึงข้อมูล PI แบบเดียวกับหน้า Show
        $data['ProformaInvoice'] = ProformaInvoice::with(['products' => function ($q) {
            $q->leftJoin('products', 'proforma_invoice_products.product_id', '=', 'products.id')
                ->leftJoin('factories', 'products.factory_id', '=', 'factories.id')
                ->leftJoin('unit_products', 'products.unit_id', '=', 'unit_products.id')
                ->select(
                    'proforma_invoice_products.*',
                    'products.code as part_no',
                    'products.name_en',
                    'products.drawing',
                    'products.width',
                    'products.length',
                    'products.height',
                    'products.cube',
                    'factories.code as fac_no',
                    'unit_products.name as unit_name'
                )
                ->orderBy('proforma_invoice_products.seq', 'asc');
        }, 'customer', 'createdBy', 'creditPayment', 'currency'])
            ->leftJoin('customers', 'proforma_invoices.customer_id', '=', 'customers.id')
            ->select('proforma_invoices.*', 'customers.company_name as customer_name')
            ->findOrFail($id);



        // โหลด View สำหรับ PDF พร้อมส่งตัวแปรไป
        $pdf = \PDF::loadView('admin.ProformaInvoice.proforma_invoice_pdf', $data);

        // สั่งให้เปิด PDF ใน Browser (ถ้าอยากให้โหลดลงเครื่องเลย เปลี่ยน stream เป็น download)
        return $pdf->stream('EXPORT_FA_' . $data['ProformaInvoice']->doc_no . '.pdf');
    }

    public function export_po($id)
    {
        // ดึงข้อมูล PI แบบเดียวกับหน้า Show
        $data['ProformaInvoice'] = ProformaInvoice::with(['products' => function ($q) {
            $q->leftJoin('products', 'proforma_invoice_products.product_id', '=', 'products.id')
                ->leftJoin('factories', 'products.factory_id', '=', 'factories.id')
                ->leftJoin('unit_products', 'products.unit_id', '=', 'unit_products.id')
                ->select(
                    'proforma_invoice_products.*',
                    'products.code as part_no',
                    'products.name_en',
                    'products.drawing',
                    'factories.code as fac_no',
                    'unit_products.name as unit_name'
                )
                ->orderBy('proforma_invoice_products.seq', 'asc');
        }, 'customer'])
            ->leftJoin('customers', 'proforma_invoices.customer_id', '=', 'customers.id')
            ->leftJoin('admin_users', 'proforma_invoices.created_by', '=', 'admin_users.id')
            ->leftJoin('prefixes', 'admin_users.prefix_id', '=', 'prefixes.id')
            ->select(
                'proforma_invoices.*',
                'customers.company_name as customer_name',
                'admin_users.firstname as sale_firstname',
                'admin_users.lastname as sale_lastname',
                'prefixes.name as sale_prefix'
            )
            ->findOrFail($id);



        // โหลด View สำหรับ PDF พร้อมส่งตัวแปรไป
        $pdf = \PDF::loadView('admin.ProformaInvoice.proforma_invoice_export_po', $data);

        // สั่งให้เปิด PDF ใน Browser (ถ้าอยากให้โหลดลงเครื่องเลย เปลี่ยน stream เป็น download)
        return $pdf->stream('EXPORT_PO_' . $data['ProformaInvoice']->doc_no . '.pdf');
    }


    public function export_product($id)
    {
        // ดึงข้อมูล PI แบบเดียวกับหน้า Show
        $data['ProformaInvoice'] = ProformaInvoice::with(['products' => function ($q) {
            $q->leftJoin('products', 'proforma_invoice_products.product_id', '=', 'products.id')
                ->leftJoin('factories', 'products.factory_id', '=', 'factories.id')
                ->leftJoin('unit_products', 'products.unit_id', '=', 'unit_products.id')
                ->select(
                    'proforma_invoice_products.*',
                    'products.code as part_no',
                    'products.name_en',
                    'products.name_th',
                    'products.drawing',
                    'factories.code as fac_no',
                    'unit_products.name as unit_name'
                )
                ->orderBy('proforma_invoice_products.seq', 'asc');
        }, 'customer'])
            ->leftJoin('customers', 'proforma_invoices.customer_id', '=', 'customers.id')
            ->leftJoin('admin_users', 'proforma_invoices.created_by', '=', 'admin_users.id')
            ->leftJoin('prefixes', 'admin_users.prefix_id', '=', 'prefixes.id')
            ->select(
                'proforma_invoices.*',
                'customers.company_name as customer_name',
                'admin_users.firstname as sale_firstname',
                'admin_users.lastname as sale_lastname',
                'prefixes.name as sale_prefix'
            )
            ->findOrFail($id);



        // โหลด View สำหรับ PDF พร้อมส่งตัวแปรไป
        $pdf = \PDF::loadView('admin.ProformaInvoice.proforma_invoice_export_product', $data);

        // สั่งให้เปิด PDF ใน Browser (ถ้าอยากให้โหลดลงเครื่องเลย เปลี่ยน stream เป็น download)
        return $pdf->stream('EXPORT_PRODUCT_' . $data['ProformaInvoice']->doc_no . '.pdf');
    }

    public function fic_2_fi(Request $request, $id)
    {
        $pi = ProformaInvoice::with(['products' => function ($q) {
            $q->leftJoin('products', 'proforma_invoice_products.product_id', '=', 'products.id')
                ->leftJoin('factories', 'products.factory_id', '=', 'factories.id')
                ->leftJoin('unit_products', 'products.unit_id', '=', 'unit_products.id')
                ->select(
                    'proforma_invoice_products.*',
                    'products.code as part_no',
                    'products.name_en',
                    'products.name_th',
                    'products.drawing',
                    'factories.code as fac_no',
                    'unit_products.name as unit_name'
                )
                ->orderBy('proforma_invoice_products.seq', 'asc');
        }, 'createdBy' , 'customer'])->findOrFail($id);

        return Excel::download(new Fic2FiExport($pi), 'FIC2FI_' . $pi->doc_no . '_' . date('YmdHis') . '.xlsx');
    }

    public function export_packing_list_template(PackingListExcelExportService $exporter)
    {
        $this->abortUnlessAppDebug();

        return $exporter->downloadBlankTemplate();
    }

    public function export_packing_list($id, PackingListExcelExportService $exporter)
    {
        $this->abortUnlessAppDebug();

        $pi = ProformaInvoice::with('customer')->findOrFail($id);

        return $exporter->downloadForProformaInvoice($pi);
    }

    private function abortUnlessAppDebug(): void
    {
        if (!config('app.debug')) {
            abort(404);
        }
    }

    /** URL prefix segment สำหรับ route แบบ admin/{th|en|ch}/... */
    private function adminLangSlash(Request $request): string
    {
        $locales = ['th', 'en', 'ch'];
        $seg2 = $request->segment(2);

        return in_array($seg2, $locales, true) ? $seg2.'/' : '';
    }

    private function proformaInvoiceCreateUrl(Request $request): string
    {
        $slash = $this->adminLangSlash($request);

        return url('admin/'.$slash.'ProformaInvoice/create');
    }

    public function outstandingReport(Request $request)
    {
        $data['currentMenu'] = Menu::where('url', $this->current_menu)->first();
        $data['SidebarMenus'] = Menu::Active()->get();
        $data['Customers'] = Customer::orderBy('company_name')->get();

        return view('admin.ProformaInvoice.outstanding_report', $data);
    }

    public function outstandingReportLists(Request $request)
    {
        $pfx = DB::getTablePrefix();
        $pipTbl = $pfx . (new ProformaInvoiceProduct)->getTable();
        $piTbl  = $pfx . (new ProformaInvoice)->getTable();

        $result = ProformaInvoiceProduct::query()
            ->join('proforma_invoices', 'proforma_invoices.id', '=', 'proforma_invoice_products.pi_id')
            ->leftJoin('products', 'products.id', '=', 'proforma_invoice_products.product_id')
            ->select(
                'proforma_invoice_products.*',
                'proforma_invoices.doc_no',
                'proforma_invoices.doc_date',
                'proforma_invoices.company_name as customer_name',
                'products.code as product_code'
            );

        if ($request->filled('customer_id') && $request->customer_id !== 'all') {
            $result->where('proforma_invoices.customer_id', $request->customer_id);
        }
        if ($request->filled('start_date')) {
            $result->where('proforma_invoices.doc_date', '>=', date('Y-m-d', strtotime($request->start_date)));
        }
        if ($request->filled('end_date')) {
            $result->where('proforma_invoices.doc_date', '<=', date('Y-m-d', strtotime($request->end_date)));
        }

        $showMode = $request->input('show_mode', 'incomplete');
        if ($showMode === 'incomplete') {
            $result->whereColumn('proforma_invoice_products.produced_qty', '<', 'proforma_invoice_products.qty');
        } elseif ($showMode === 'completed') {
            $result->whereColumn('proforma_invoice_products.produced_qty', '>=', 'proforma_invoice_products.qty')
                   ->where('proforma_invoice_products.qty', '>', 0);
        }

        $result->orderBy('proforma_invoices.doc_date', 'desc')
               ->orderBy('proforma_invoices.doc_no', 'desc');

        return DataTables::of($result)
            ->addColumn('customer_name', function ($rec) {
                return e($rec->customer_name ?? '-');
            })
            ->addColumn('doc_no', function ($rec) {
                return e($rec->doc_no);
            })
            ->addColumn('doc_date', function ($rec) {
                return e($rec->doc_date);
            })
            ->addColumn('part_no_display', function ($rec) {
                return e($rec->product_code ?? $rec->part_no ?? '-');
            })
            ->addColumn('qty_display', function ($rec) {
                return number_format($rec->qty, 0);
            })
            ->addColumn('produced_qty_display', function ($rec) {
                return number_format($rec->produced_qty, 0);
            })
            ->addColumn('remaining', function ($rec) {
                $remain = max(0, $rec->qty - $rec->produced_qty);
                return '<span class="text-danger-d1 text-600">' . number_format($remain, 0) . '</span>';
            })
            ->addColumn('progress', function ($rec) {
                $qty = (float) $rec->qty;
                $produced = (float) $rec->produced_qty;
                if ($qty <= 0) return '<span class="text-muted">-</span>';
                $pct = min(100, round(($produced / $qty) * 100));
                $color = $pct >= 100 ? 'success' : ($pct >= 50 ? 'warning' : 'danger');
                return '<div class="progress" style="height:6px;min-width:60px;">
                    <div class="progress-bar bgc-' . $color . '" style="width:' . $pct . '%"></div>
                </div>
                <div class="text-center text-75 text-' . $color . '-d1 mt-1">' . $pct . '%</div>';
            })
            ->addIndexColumn()
            ->rawColumns(['remaining', 'progress'])
            ->make(true);
    }

    /**
     * @return array{run_no: int, doc_no: string}
     */
    private function suggestProformaInvoiceDocNo(?string $docDate): array
    {
        $docDate = ($docDate && strtotime($docDate)) ? $docDate : date('Y-m-d');
        $year_month = date('ym', strtotime($docDate));
        $last_run = ProformaInvoice::where('doc_no', 'like', "PI{$year_month}%")
            ->orderBy('run_no', 'desc')
            ->first();
        $run_no = $last_run ? ((int) $last_run->run_no) + 1 : 1;
        $doc_no = 'PI'.$year_month.str_pad((string) $run_no, 4, '0', STR_PAD_LEFT);

        return ['run_no' => $run_no, 'doc_no' => $doc_no];
    }
}
