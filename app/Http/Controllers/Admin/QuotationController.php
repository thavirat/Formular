<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Quotation;
use DataTables;
use Help;
use DB;
use Validator;
use Storage;
use Auth;
use UserPermission;
use App\Models\Customer;
use App\Models\Incoterm;
use App\Models\Currency;
use App\Models\CreditPayment;
use App\Models\QuotationProduct;
use App\Models\ContactChannel;
class QuotationController extends AdminController
{
    public $current_menu;

    public function __construct() {
        $this->current_menu = 'Quotation';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permission = Help::CheckPermissionMenu($this->current_menu , 'r');
        if(!$permission){
            return redirect('/admin/PermissionDenined');
        }
        $data['currentMenu'] = Menu::where('url',$this->current_menu)->first();

        $data['Customers'] = Customer::orderBy('company_name')->get();
        $data['Incoterms'] = Incoterm::orderBy('code')->get();
        $data['Currencies'] = Currency::orderBy('name')->get();
        $data['CreditPayments'] = CreditPayment::orderBy('name')->get();
        return view('admin.Quotation.quotation',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $data['currentMenu'] = Menu::where('url',$this->current_menu)->first();
        $data['Customers'] = Customer::orderBy('company_name')->get();
        $data['Incoterms'] = Incoterm::orderBy('code')->get();
        $data['Currencies'] = Currency::orderBy('name')->get();
        $data['CreditPayments'] = CreditPayment::orderBy('name')->get();
        return view('admin.Quotation.quotation_create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $permission = Help::CheckPermissionMenu($this->current_menu , 'c');
        if(!$permission){
            return redirect('/admin/PermissionDenined');
        }

        $validator = Validator::make($request->all(), [
            'customer_id' => 'required',
            'product' => 'required|array',
        ]);

        if (!$validator->fails()) {
            // --- ข้อมูลทั่วไป ---
            $customer_id = $request->input('customer_id');
            $contact_name = $request->input('contact_name');
            $company_name = $request->input('company_name');
            $tax_id = $request->input('tax_id');
            $address = $request->input('address');
            $phone = $request->input('phone');
            $mobile = $request->input('mobile');
            $fax_no = $request->input('fax_no');
            $currency_id = $request->input('currency_id');
            $credit_payment_id = $request->input('credit_payment_id');
            $incoterm_id = $request->input('incoterm_id');
            $grand_total = str_replace(',' , '' , $request->input('grand_total'));
            $doc_date = $request->input('doc_date');
            $user = Auth::user();

            // --- ข้อมูลสินค้า (Array) ---
            $product = $request->input('product');
            $drawing = $request->input('drawing');
            $customer_code = $request->input('customer_code');
            $description = $request->input('description');
            $qty = $request->input('qty');
            $unit_price = $request->input('unit_price');
            $amount = $request->input('amount');

            // เพิ่มการรับค่าส่วนลดจาก Request
            $discount_percents = $request->input('disc_percent'); // ชื่อเดียวกับที่ตั้งในหน้า Blade
            $discount_amounts = $request->input('disc_amount');   // ชื่อเดียวกับที่ตั้งในหน้า Blade

            // --- จัดการเลขที่เอกสาร ---
            $check = Quotation::where('doc_date' , '>=' , date("Y-m-01",strtotime($doc_date)))->where('doc_date' , '<=' , date("Y-m-t",strtotime($doc_date)))->orderBy('run_no' , 'desc')->first();
            $run_no = $check ? $check->run_no + 1 : 1;
            $doc_no = 'QT-'.date('ym', strtotime($doc_date)).sprintf('%03d' , $run_no);

            DB::beginTransaction();
            try {
                // 1. บันทึกหัวเอกสาร (Header)
                $Quotation = new Quotation;
                $Quotation->customer_id = $customer_id;
                $Quotation->contact_name = $contact_name;
                $Quotation->company_name = $company_name;
                $Quotation->tax_id = $tax_id;
                $Quotation->address = $address;
                $Quotation->phone = $phone;
                $Quotation->mobile = $mobile;
                $Quotation->fax_no = $fax_no;
                $Quotation->currency_id = $currency_id;
                $Quotation->credit_payment_id = $credit_payment_id;
                $Quotation->incoterm_id = $incoterm_id;
                $Quotation->doc_no = $doc_no;
                $Quotation->run_no = $run_no;
                $Quotation->doc_date = $doc_date;
                $Quotation->subtotal = $grand_total;
                $Quotation->total = $grand_total;
                $Quotation->status_id = 1;
                $Quotation->created_by = $user->id;
                $Quotation->save();

                // 2. เตรียมข้อมูลสินค้า (Detail)
                $quotation_details = [];
                foreach($product as $key => $value) {
                    if($value){
                        $quotation_details[] = [
                            'quotation_id' => $Quotation->id,
                            'product_id' => $value,
                            'drawing' => $drawing[$key] ?? null,
                            'cus_code' => $customer_code[$key] ?? null,
                            'detail_eng' => $description[$key] ?? null,
                            'qty' => $qty[$key] ?? 0,
                            'price_per_item' => $unit_price[$key] ?? 0,
                            // บันทึกส่วนลดลงในแต่ละบรรทัด
                            'discount_percents' => $discount_percents[$key] ?? 0,
                            'discount_amount' => $discount_amounts[$key] ?? 0,
                            'total_price' => $amount[$key] ?? 0,
                        ];
                    }
                }

                // 3. บันทึกข้อมูลสินค้าทั้งหมดทีเดียว
                if(count($quotation_details) > 0){
                    QuotationProduct::insert($quotation_details);
                }

                DB::commit();
                $return['status'] = 1;
                $return['title'] = __('messages.save');
                $return['content'] = __('messages.success');

            } catch (\Exception $e) {
                DB::rollBack();
                $return['status'] = 0;
                $return['title'] = __('messages.error');
                $return['content'] = $e->getMessage();
            }
        } else {
            $return['status'] = 0;
            $return['title'] = __('messages.error');
            $return['content'] = 'กรุณากรอกข้อมูลที่จำเป็นให้ครบถ้วน';
        }

        return $return;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Quotation = Quotation::find($id);

                $return['status'] = 1;
                $return['title'] = 'Get Quotation';
                $return['content'] = $Quotation;
                return $return;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $data['currentMenu'] = Menu::where('url', $this->current_menu)->first();
        $data['Customers'] = Customer::orderBy('company_name')->get();
        $data['Incoterms'] = Incoterm::orderBy('code')->get();
        $data['Currencies'] = Currency::orderBy('name')->get();
        $data['CreditPayments'] = CreditPayment::orderBy('name')->get();
        $data['Quotation'] = Quotation::with(['products'=>function($q){
            $q->leftJoin('products' , 'quotation_products.product_id', '=', 'products.id');
            $q->select(
                'quotation_products.*'
                , 'products.code as part_no'
            );
        }])
        ->leftJoin('currencies' , 'quotations.currency_id', '=', 'currencies.id')
        ->leftJoin('credit_payments' , 'quotations.credit_payment_id', '=', 'credit_payments.id')
        ->select(
            'quotations.*'
            , 'currencies.name as currency_name'
            , 'currencies.symbol'
            , 'credit_payments.name as credit_payment_name'
        )
        ->find($id);
        return view('admin.Quotation.quotation_edit', $data);
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
        $permission = Help::CheckPermissionMenu($this->current_menu , 'u');
        if(!$permission){
            return redirect('/admin/PermissionDenined');
        }
        $validator = Validator::make($request->all(), [
        ]);
        if (!$validator->fails()) {
            $customer_id = $request->input('customer_id');
            $contact_name = $request->input('contact_name');
            $company_name = $request->input('company_name');
            $tax_id = $request->input('tax_id');
            $address = $request->input('address');
            $phone = $request->input('phone');
            $mobile = $request->input('mobile');
            $fax_no = $request->input('fax_no');
            $currency_id = $request->input('currency_id');
            $credit_payment_id = $request->input('credit_payment_id');
            $incoterm_id = $request->input('incoterm_id');
            $grand_total = str_replace(',' , '' , $request->input('grand_total'));
            $product = $request->input('product');
            $drawing = $request->input('drawing');
            $customer_code = $request->input('customer_code');
            $description = $request->input('description');
            $qty = $request->input('qty');
            $unit_price = $request->input('unit_price');
            $amount = str_replace(',' , '' , $request->input('amount'));
            $doc_date = $request->input('doc_date');

            DB::beginTransaction();
            try {
                $Quotation = Quotation::find($id);
                $Quotation->customer_id = $customer_id;
                $Quotation->contact_name = $contact_name;
                $Quotation->company_name = $company_name;
                $Quotation->tax_id = $tax_id;
                $Quotation->address = $address;
                $Quotation->phone = $phone;
                $Quotation->mobile = $mobile;
                $Quotation->fax_no = $fax_no;
                $Quotation->currency_id = $currency_id;
                $Quotation->credit_payment_id = $credit_payment_id;
                $Quotation->incoterm_id = $incoterm_id;
                $Quotation->doc_date = $doc_date;
                $Quotation->subtotal = $grand_total;
                $Quotation->total = $grand_total;
                $Quotation->save();
                QuotationProduct::where('quotation_id' , $id)->delete();
                $quotation_detail = [];
                foreach($product as $key => $value) {
                    if($value){
                        $quotation_detail[] =[
                            'quotation_id' => $Quotation->id,
                            'product_id' => $value,
                            'drawing' => $drawing[$key],
                            'cus_code' => $customer_code[$key],
                            'detail_eng' => $description[$key],
                            'qty' => $qty[$key],
                            'price_per_item' => $unit_price[$key],
                            'total_price' => $amount[$key],
                        ];
                    }
                }

                QuotationProduct::insert($quotation_detail);

                DB::commit();
                $return['status'] = 1;
                $return['title'] = __('messages.save');
                $return['content'] = __('messages.success');
            } catch (Exception $e) {
                DB::rollBack();
                $return['status'] = 0;
                $return['title'] = __('messages.error');
                $return['content'] = $e->getMessage();
            }
        }else{
            $failedRules = $validator->failed();
            $return['content'] = '';

        }
        return $return;
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
            $Quotation = Quotation::find($id);

            Quotation::where('id' , $id)->delete();

            DB::commit();
            $return['status'] = 1;
            $return['content'] = __('messages.success');
        }catch (Exception $e) {
            DB::rollBack();
            $return['status'] = 0;
            $return['title'] = __('messages.error');
            $return['content'] = $e->getMessage();
        }
        $return['title'] = __('messages.delete_data');
        return $return;
    }

    /**
     * Show Data With Datatable from storage.
     *
     * @param   \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\Response
     */
    public function report(){
        $result = Quotation::with(['Comments'=>function($q){
            $q->leftJoin('admin_users' , 'admin_users.id' , 'comments.created_by');
            $q->leftJoin('contact_channels' , 'contact_channels.id' , 'comments.channel_id');
            $q->select(
                'comments.*'
                ,'admin_users.nickname'
                ,'contact_channels.name as channel_name'
            );

        }])->leftJoin('admin_users' , 'quotations.created_by', '=', 'admin_users.id')
        ->leftJoin('admin_users as send_approve' , 'quotations.send_approve_by', '=', 'send_approve.id')
        ->leftJoin('admin_users as approve' , 'quotations.approve_by', '=', 'approve.id')
        ->leftJoin('quotation_statuses' , 'quotations.status_id', '=', 'quotation_statuses.id')
        ->select(
            'quotations.*'
            , 'admin_users.firstname as created_by_name'
            , 'admin_users.lastname as created_by_lastname'
            , 'send_approve.firstname as send_approve_name'
            , 'send_approve.lastname as send_approve_lastname'
            , 'approve.firstname as approve_name'
            , 'approve.lastname as approve_lastname'
            , 'quotation_statuses.name as status_name'
        );
        return $result;
    }

    public function lists(Request $request)
    {
        $result = $this->report($request);
        $lang = config('app.locale');
        $all_permission = UserPermission::getMyPermissions();
        $channels = ContactChannel::get();
        $view_quotation_permission = isset($all_permission['view_all_quotation']) ?  $all_permission['view_all_quotation']:'F';
        $user = Auth::guard('admin')->user();
        if($view_quotation_permission=='F'){
            $result->where('quotations.created_by' , $user->id);
        }

        return DataTables::of($result)
        ->addColumn('status_name', function($rec) use($lang) {
            $status_color = 'secondary';
            if ($rec->status_id == 1) $status_color = 'light-grey'; // Draft
            if ($rec->status_id == 2) $status_color = 'warning';    // Pending
            if ($rec->status_id == 3) $status_color = 'success';    // Approved


            $str = '<div class="text-center">';
            $str .= '<span class="badge badge-lg bgc-'.$status_color.'-l3 text-'.$status_color.'-d2 border-1 brc-'.$status_color.'-m3 mb-1">' . $rec->status_name . '</span>';

            if ($rec->status_id >= 2 && $rec->send_approve_date) {
                $str .= '<div class="text-75 text-grey-m1 mt-1" title="วันที่ส่งขออนุมัติ">
                            <i class="fa fa-paper-plane mr-1 text-purple-m2"></i>' . $rec->send_approve_name.' '.$rec->send_approve_lastname.'
                            <br>' . date('d/m/Y H:i', strtotime($rec->send_approve_date)) . '
                        </div>';
            }

            if ($rec->status_id == 3 && $rec->approve_date) {
                $str .= '<div class="text-75 text-success-m1 mt-1 border-t-1 brc-grey-l4 pt-1" title="วันที่อนุมัติ">
                            <i class="fa fa-check-circle mr-1"></i>' . $rec->approve_name.' '.$rec->approve_lastname.'
                            <br>' . date('d/m/Y H:i', strtotime($rec->approve_date)) . '
                        </div>';
            }



            $str .= '</div>';
            return $str;
        })

        ->addColumn('doc_info', function($rec) {
            return '<div class="text-primary-d2 font-bolder text-95">'.$rec->doc_no.'</div>
                    <div class="text-80 text-grey-m2"><i class="far fa-calendar-alt mr-1"></i>'.$rec->doc_date.'</div>';
        })
        ->addColumn('customer_info', function($rec) {
            return '<div class="text-dark-m3 font-bold">'.$rec->company_name.'</div>
                    <div class="text-80 text-blue-m2"><i class="far fa-user mr-1"></i>'.$rec->created_by_name.' '.$rec->created_by_lastname.'</div>';
        })
        ->editColumn('total', function($rec) {
            return '<span class="text-110 font-bolder text-success-d1">'.number_format($rec->total, 2).'</span>';
        })
        ->addColumn('comment_box', function($rec) use($channels){
            $items = '';
            foreach($rec->Comments as $Comment){
                $items .= '<li class="text-80 text-grey-d1 border-b-1 brc-grey-l4 pb-1 mb-1">'.$Comment->detail.' '.$Comment->channel_name.' '.date('Ymd H:i' , strtotime($Comment->created_at)).' '.$Comment->created_by_name.'</li>';
            }

            $options = '';
            foreach($channels as $channel){
                $options .= '<option value="'.$channel->id.'">'.$channel->name.'</option>';
            }

            return '<ul class="list-unstyled mb-2 max-h-100 overflow-auto">'.$items.'</ul>
                    <div class="input-group">
                        <textarea class="form-control text-85 brc-on-focus brc-success-m2 comment-'.$rec->id.'" placeholder="บันทึกเพิ่มเติม..."></textarea>
                        <div class="input-group-append flex-column">
                            <select class="custom-select custom-select-sm border-0 bgc-grey-l4 text-80 channel-'.$rec->id.'">'.$options.'</select>
                            <button class="btn btn-xs btn-success btn-block btn-save-comment" data-id="'.$rec->id.'" data-customer-id="'.$rec->customer_id.'">
                                <i class="fa fa-save"></i>
                            </button>
                        </div>
                    </div>';
        })
        ->addColumn('action_btns', function($rec) use ($lang , $all_permission){
            $update = Help::CheckPermissionMenu($this->current_menu , 'u');
            $delete = Help::CheckPermissionMenu($this->current_menu , 'd');

            $str = '<div class="btn-group btn-group-sm">';
            $str .= '<a href="'.url('admin/'.$lang.'/Quotation/'.$rec->id.'/pdf').'" target="_blank" class="btn btn-outline-info btn-h-light-info btn-a-light-info border-b-2" title="PDF"><i class="fa fa-file-pdf"></i></a>';
            if($update){
                $str .= '<a href="'.url('admin/'.$lang.'/Quotation/'.$rec->id.'/edit').'" class="btn btn-outline-warning btn-h-light-warning btn-a-light-warning border-b-2" title="แก้ไข"><i class="fa fa-edit"></i></a>';
            }
            if($delete){
                $str .= '<button class="btn btn-outline-danger btn-h-light-danger btn-a-light-danger border-b-2 btn-delete" data-id="'.$rec->id.'" title="ลบ"><i class="fa fa-trash-alt"></i></button>';
            }

            if($rec->status_id==1){
                $str .= '<button class="btn btn-outline-purple btn-h-light-purple btn-a-light-purple border-b-2 btn-request-approval" data-id="'.$rec->id.'" title="ส่งขออนุมัติ"><i class="fa fa-paper-plane"></i></button>';
            }

            $approve_quotation_permission = isset($all_permission['approve_quotation']) ?  $all_permission['approve_quotation']:'F';
            if($rec->status_id==2 && $approve_quotation_permission=='T'){
                $str .= '<button class="btn btn-outline-success btn-h-light-success btn-a-light-success border-b-2 btn-approve" data-id="'.$rec->id.'" title="อนุมัติ"><i class="fa fa-check-circle"></i></button>';
            }

            if($rec->status_id == 3){
                $str .= '<a href="'.url('admin/'.$lang.'/ProformaInvoice/create?quotation_id='.$rec->id).'"
                            class="btn btn-outline-indigo btn-h-light-indigo btn-a-light-indigo border-b-2"
                            title="สร้างใบ PI">
                            <i class="fa fa-file-invoice-dollar"></i>
                        </a>';
            }


            $str .= '</div>';
            return $str;
        })
        ->addIndexColumn()
        ->rawColumns(['doc_info', 'customer_info', 'total', 'comment_box', 'action_btns' , 'status_name'])
        ->make(true);
    }

    public function export_excel(Request $request){
        $result = $this->report($request);
        $data['result'] = $result->get();

        \Excel::create('รายงาน Quotation ', function ($excel) use ($data) {
            $excel->sheet('รายงาน Quotation', function ($sheet) use ($data) {
                $sheet->loadView('admin.Quotation.quotation_export_excel', $data);
            });
        })->export('xlsx');
    }

    public function export_print(Request $request){
        $result = $this->report($request);
        $data['result'] = $result->get();


        $pdf = \PDF::loadView('admin.Quotation.quotation_export_print', $data);
        return $pdf->stream('Quotation.pdf');
    }

    public function export_pdf(Request $request){
        $result = $this->report($request);
        $data['result'] = $result->get();

        return view('admin.Quotation.quotation_export_pdf', $data);
    }

    public function view_pdf($id){
        $data['Quotation'] = Quotation::with(['products'=>function($q){
            $q->leftJoin('products' , 'quotation_products.product_id', '=', 'products.id');
            $q->select(
                'quotation_products.*'
                , 'products.code as part_no'
            );
        }])
        ->leftJoin('currencies' , 'quotations.currency_id', '=', 'currencies.id')
        ->leftJoin('credit_payments' , 'quotations.credit_payment_id', '=', 'credit_payments.id')
        ->leftJoin('admin_users' , 'quotations.created_by', '=', 'admin_users.id')
        ->leftJoin('admin_departments' , 'admin_users.department_id', '=', 'admin_departments.id')
        ->select(
            'quotations.*'
            , 'currencies.name as currency_name'
            , 'currencies.symbol'
            , 'credit_payments.name as credit_payment_name'
            , 'admin_users.firstname'
            , 'admin_users.lastname'
            , 'admin_users.mobile'
            , 'admin_users.email'
            , 'admin_departments.name as department_name'
        )
        ->find($id);
        $quotation = $data['Quotation'];
        $pdf = \PDF::loadView('admin.Quotation.quotation_pdf', $data);
        return $pdf->stream($quotation->doc_no.'_'.date('Ymd_Hi').'.pdf');
    }

    public function save_comment(Request $request)
    {
        try {
            // Validation เบื้องต้น
            if (empty($request->detail)) {
                return response()->json(['status' => 0, 'title' => 'ผิดพลาด', 'content' => 'กรุณากรอกรายละเอียด']);
            }

            $comment = new \App\Models\Comment(); // ตรวจสอบชื่อ Model ของคุณด้วยครับ
            $comment->channel_id   = $request->channel_id;
            $comment->customer_id  = $request->customer_id;
            $comment->quotation_id = $request->quotation_id;
            $comment->detail       = $request->detail;
            $comment->created_by   = Auth::guard('admin')->user()->id;
            $comment->save();

            return response()->json([
                'status' => 1,
                'title' => 'สำเร็จ',
                'content' => 'บันทึกคอมเมนต์เรียบร้อยแล้ว'
            ]);
        } catch (\Exception $e) {
            // ใช้ข้อมูลจาก Exception ส่งกลับไปให้ ajaxFail ช่วยจัดการ
            return response()->json([
                'message' => $e->getMessage(),
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    public function RequestApproval(Request $request){
        try {
            // Validation เบื้องต้น
            if (empty($request->id)) {
                return response()->json(['status' => 0, 'title' => 'ผิดพลาด', 'content' => 'กรุณากรอกรายละเอียด']);
            }

            Quotation::where('id' , $request->id)->update([
                'status_id'=>2
                ,'send_approve_by'=>Auth::guard('admin')->user()->id
                ,'send_approve_date'=>date('Y-m-d H:i:s')
            ]);



            return response()->json([
                'status' => 1,
                'title' => 'สำเร็จ',
                'content' => 'ส่งอนุมัติเรียบร้อย'
            ]);
        } catch (\Exception $e) {
            // ใช้ข้อมูลจาก Exception ส่งกลับไปให้ ajaxFail ช่วยจัดการ
            return response()->json([
                'message' => $e->getMessage(),
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    public function Approve(Request $request){
        try {
            if (empty($request->id)) {
                return response()->json(['status' => 0, 'title' => 'ผิดพลาด', 'content' => 'กรุณากรอกรายละเอียด']);
            }

            Quotation::where('id' , $request->id)->update([
                'status_id'=>3
                ,'approve_by'=>Auth::guard('admin')->user()->id
                ,'approve_date'=>date('Y-m-d H:i:s')
            ]);



            return response()->json([
                'status' => 1,
                'title' => 'สำเร็จ',
                'content' => 'ส่งอนุมัติเรียบร้อย'
            ]);
        } catch (\Exception $e) {
            // ใช้ข้อมูลจาก Exception ส่งกลับไปให้ ajaxFail ช่วยจัดการ
            return response()->json([
                'message' => $e->getMessage(),
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

}
