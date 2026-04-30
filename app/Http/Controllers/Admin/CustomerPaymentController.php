<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\CustomerPayment;
use DataTables;
use Help;
use DB;
use Validator;
use Storage;
use App\Models\BankAccount;
use App\Models\Currency;
use App\Models\PaymentMethod;
class CustomerPaymentController extends AdminController
{
    public $current_menu;

    public function __construct() {
        $this->current_menu = 'CustomerPayment';
    }

    /**
     * รวมค่าวันที่จาก datepicker (รูปแบบไทยหรือ Y-m-d) กับชั่วโมง/นาทีจาก dropdown เป็น datetime สำหรับคอลัมน์ payment_date
     */
    private function composePaymentDateTime(Request $request): ?string
    {
        $raw = $request->input('payment_date');
        if ($raw === null || $raw === '') {
            return null;
        }
        $raw = trim((string) $raw);
        $datePart = Help::convertDateThaiToDbFormat($raw, '/');
        if (!$datePart && preg_match('/^\d{4}-\d{2}-\d{2}/', $raw)) {
            $datePart = substr($raw, 0, 10);
        }
        if (!$datePart) {
            return null;
        }
        $h = max(0, min(23, (int) $request->input('payment_time_hour', 0)));
        $m = max(0, min(59, (int) $request->input('payment_time_minute', 0)));

        return $datePart.sprintf(' %02d:%02d:00', $h, $m);
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
        $data['SidebarMenus'] = Menu::Active()->get();
        $data['BankAccounts'] = BankAccount::orderBy('account_no')->get();
        $data['PaymentMethods'] = PaymentMethod::orderBy('name_en')->get();
        $data['Currencies'] = Currency::orderBy('name')->get();
        return view('admin.CustomerPayment.customer_payment',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['SidebarMenus'] = Menu::Active()->get();
        $data['currentMenu'] = Menu::where('url',$this->current_menu)->first();
        $data['BankAccounts'] = BankAccount::orderBy('account_no')->get();
        $data['PaymentMethods'] = PaymentMethod::orderBy('name_en')->get();
        $data['Currencies'] = Currency::orderBy('name')->get();
        return view('admin.CustomerPayment.customer_payment_create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fromReceipt = $request->filled('pi_id') && Help::CheckPermissionMenu('CustomerReceipt', 'c');
        $permission = Help::CheckPermissionMenu($this->current_menu, 'c') || $fromReceipt;
        if (!$permission) {
            return ['status' => 0, 'title' => __('messages.error'), 'content' => 'ไม่มีสิทธิ์บันทึกข้อมูลการชำระเงิน'];
        }
        $validator = Validator::make($request->all(), [
        ]);
        if (!$validator->fails()) {
            $pi_id = $request->input('pi_id');
            $bank_account_id = $request->input('bank_account_id');
            $payment_method_id = $request->input('payment_method_id');
            $currency_id = $request->input('currency_id');
            $reference_no = $request->input('reference_no');
            $payment_date = $this->composePaymentDateTime($request);
            $payment_note = $request->input('payment_note');
            $amount = $request->input('amount');
            $amount_bath = $request->input('amount_bath');
            $exchange_rate = $request->input('exchange_rate');
            $photo = $request->input('photo');
                if(!empty($request->input('photo'))){
                $photo = json_encode($photo);
                foreach ($request->input('photo') as $key => $val) {
                    if (Storage::disk("uploads")->exists("temp/" . $val) && !Storage::disk("uploads")->exists("CustomerPayment/" . $val)) {
                        Storage::disk("uploads")->copy("temp/" . $val, "CustomerPayment/" . $val);
                        Storage::disk("uploads")->delete("temp/" . $val);
                    }
                }
            }

            DB::beginTransaction();
            try {
                $CustomerPayment = new CustomerPayment;
                $CustomerPayment->pi_id = $pi_id;
                $CustomerPayment->bank_account_id = $bank_account_id;
                $CustomerPayment->payment_method_id = $payment_method_id;
                $CustomerPayment->currency_id = $currency_id;
                $CustomerPayment->reference_no = $reference_no;
                $CustomerPayment->payment_date = $payment_date;
                $CustomerPayment->payment_note = $payment_note;
                $CustomerPayment->amount = $amount;
                $CustomerPayment->amount_bath = $amount_bath;
                $CustomerPayment->exchange_rate = $exchange_rate;
                $CustomerPayment->photo = $photo;
                $CustomerPayment->save();
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
        }else{
            $failedRules = $validator->failed();
            $return['content'] = '';

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
        $CustomerPayment = CustomerPayment::find($id);
                             if($CustomerPayment->photo){
            $this->moveFileToTempOrak(json_decode($CustomerPayment->photo) , $this->current_menu);
        }
  
                $return['status'] = 1;
                $return['title'] = 'Get CustomerPayment';
                $return['content'] = $CustomerPayment;
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
        $data['SidebarMenus'] = Menu::Active()->get();
        $data['currentMenu'] = Menu::where('url',$this->current_menu)->first();
        $data['BankAccounts'] = BankAccount::orderBy('account_no')->get();
        $data['PaymentMethods'] = PaymentMethod::orderBy('name_en')->get();
        $data['Currencies'] = Currency::orderBy('name')->get();
        return view('admin.CustomerPayment.customer_payment_edit',$data);
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
            $pi_id = $request->input('pi_id');
            $bank_account_id = $request->input('bank_account_id');
            $payment_method_id = $request->input('payment_method_id');
            $currency_id = $request->input('currency_id');
            $reference_no = $request->input('reference_no');
            $payment_date = $this->composePaymentDateTime($request);
            $payment_note = $request->input('payment_note');
            $amount = $request->input('amount');
            $amount_bath = $request->input('amount_bath');
            $exchange_rate = $request->input('exchange_rate');
            $data = CustomerPayment::find($id);
            if($data->photo){
                $this->removeFileOrak(json_decode($data->photo) , 'CustomerPayment');
            }
            $photo = $request->input('photo');
            if(!empty($request->input('photo'))){
                $photo = json_encode($photo);
                foreach ($request->input('photo') as $key => $val) {
                    if (Storage::disk("uploads")->exists("temp/" . $val) && !Storage::disk("uploads")->exists("CustomerPayment/" . $val)) {
                        Storage::disk("uploads")->copy("temp/" . $val, "CustomerPayment/" . $val);
                        Storage::disk("uploads")->delete("temp/" . $val);
                    }
                }                        
            }
            DB::beginTransaction();
            try {
                $CustomerPayment = CustomerPayment::find($id);
                if ($pi_id !== null && $pi_id !== '') {
                    $CustomerPayment->pi_id = $pi_id;
                }
                $CustomerPayment->bank_account_id = $bank_account_id;
                $CustomerPayment->payment_method_id = $payment_method_id;
                $CustomerPayment->currency_id = $currency_id;
                $CustomerPayment->reference_no = $reference_no;
                $CustomerPayment->payment_date = $payment_date;
                $CustomerPayment->payment_note = $payment_note;
                $CustomerPayment->amount = $amount;
                $CustomerPayment->amount_bath = $amount_bath;
                $CustomerPayment->exchange_rate = $exchange_rate;
                $CustomerPayment->photo = $photo;
                $CustomerPayment->save();
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
            $CustomerPayment = CustomerPayment::find($id);
            if($CustomerPayment->photo){
                $this->removeFileOrak(json_decode($CustomerPayment->photo), $CustomerPayment);
            }
            CustomerPayment::where('id' , $id)->delete();

            DB::commit();
            $return['status'] = 1;
            $return['content'] = __('messages.success');
        }catch (\Exception $e) {
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
    public function report(Request $request = null)
    {
        $request = $request ?? request();
        $q = CustomerPayment::query()
            ->leftJoin('bank_accounts', 'bank_accounts.id', '=', 'customer_payments.bank_account_id')
            ->leftJoin('banks', 'banks.id', '=', 'bank_accounts.bank_id')
            ->leftJoin('payment_methods', 'payment_methods.id', '=', 'customer_payments.payment_method_id')
            ->leftJoin('currencies', 'currencies.id', '=', 'customer_payments.currency_id')
            ->leftJoin('proforma_invoices', 'proforma_invoices.id', '=', 'customer_payments.pi_id')
            ->select(
                'customer_payments.*',
                'proforma_invoices.doc_no as pi_doc_no',
                DB::raw('COALESCE(`tb_banks`.`name_th`, `tb_banks`.`name_en`, `tb_bank_accounts`.`account_no`) as bank_display'),
                DB::raw('COALESCE(`tb_payment_methods`.`name_th`, `tb_payment_methods`.`name_en`) as payment_method_label'),
                'currencies.name as currency_label'
            )
            ->orderByDesc('customer_payments.id');
        if ($request->filled('pi_id')) {
            $q->where('customer_payments.pi_id', (int) $request->input('pi_id'));
        }

        return $q;
    }

    public function lists(Request $request)
    {
        $result = $this->report($request);

        return DataTables::of($result)
        ->editColumn('photo', function ($rec) {
            if (empty($rec->photo)) {
                return '<span class="text-grey-m1">—</span>';
            }
            $files = json_decode($rec->photo, true);
            if (!is_array($files) || count($files) === 0) {
                return '<span class="text-grey-m1">—</span>';
            }
            $html = '<div class="d-flex flex-wrap align-items-center justify-content-start">';
            foreach ($files as $file) {
                if (!is_string($file) || $file === '') {
                    continue;
                }
                $safe = basename($file);
                $url = asset('uploads/CustomerPayment/' . $safe);
                $html .= '<a href="' . e($url) . '" target="_blank" rel="noopener" class="mr-1 mb-1 d-inline-block">'
                    . '<img src="' . e($url) . '" alt="" class="radius-1 border-1 brc-grey-l2" style="max-height:44px;max-width:56px;object-fit:cover;">'
                    . '</a>';
            }
            $html .= '</div>';

            return $html === '<div class="d-flex flex-wrap align-items-center justify-content-start"></div>'
                ? '<span class="text-grey-m1">—</span>'
                : $html;
        })
        ->addColumn('action', function($rec){
            $btnEdit = '<button class="btn btn-xs btn-warning btn-edit" data-id="'.$rec->id.'" data-toggle="tooltip" data-placement="top" title="แก้ไข">
            <i class="fa fa-edit"></i>
            </button> ';
            $btnDelete = '<button class="btn btn-xs btn-danger btn-delete" data-id="'.$rec->id.'" data-toggle="tooltip" data-placement="top" title="ลบ">
            <i class="fa fa-trash"></i>
            </button> ';
            $update = Help::CheckPermissionMenu($this->current_menu , 'u');
            $str = '';
            if($update){
                $str.=$btnEdit;
            }
            $delete = Help::CheckPermissionMenu($this->current_menu , 'd');
            if($delete){
                $str.=$btnDelete;
            }

            return $str;
        })
        ->addIndexColumn()
        ->rawColumns(['photo', 'action'])
        ->make(true);
    }

    public function export_excel(Request $request){
        $result = $this->report($request);
        $data['result'] = $result->get();

        \Excel::create('รายงาน CustomerPayment ', function ($excel) use ($data) {
            $excel->sheet('รายงาน CustomerPayment', function ($sheet) use ($data) {
                $sheet->loadView('admin.CustomerPayment.customer_payment_export_excel', $data);
            });
        })->export('xlsx');
    }

    public function export_print(Request $request){
        $result = $this->report($request);
        $data['result'] = $result->get();


        $pdf = \PDF::loadView('admin.CustomerPayment.customer_payment_export_print', $data);
        return $pdf->stream('CustomerPayment.pdf');
    }

    public function export_pdf(Request $request){
        $result = $this->report($request);
        $data['result'] = $result->get();

        return view('admin.CustomerPayment.customer_payment_export_pdf', $data);
    }

}
