<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\AccountCredit;
use DataTables;
use Help;
use DB;
use Validator;
use Storage;
use App\Models\BankAccount;
use App\Models\Currency;
class AccountCreditController extends AdminController
{
    public $current_menu;

    public function __construct() {
        $this->current_menu = 'AccountCredit';
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
        $data['BankAccounts'] = $this->bankAccountOptions();
        $data['Currencies'] = Currency::orderBy('name')->get();
        return view('admin.AccountCredit.account_credit',$data);
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
        $data['BankAccounts'] = $this->bankAccountOptions();
        $data['Currencies'] = Currency::orderBy('name')->get();
        return view('admin.AccountCredit.account_credit_create',$data);
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
        ]);
        if (!$validator->fails()) {
            $bank_account_id = $request->input('bank_account_id');
            $currency_id = $request->input('currency_id');
            $credit_amount = $request->input('credit_amount');
            $credit_balance = $request->input('credit_balance');
            $date_start = $request->input('date_start');
            if($date_start){
                $date_start = Help::convertDateThaiToDbFormat($date_start , '/');
            }
            $date_end = $request->input('date_end');
            if($date_end){
                $date_end = Help::convertDateThaiToDbFormat($date_end , '/');
            }
            $remark = $request->input('remark');

            DB::beginTransaction();
            try {
                $AccountCredit = new AccountCredit;
                $AccountCredit->bank_account_id = $bank_account_id;
                $AccountCredit->currency_id = $currency_id;
                $AccountCredit->credit_amount = $credit_amount;
                $AccountCredit->credit_balance = $credit_balance;
                $AccountCredit->date_start = $date_start;
                $AccountCredit->date_end = $date_end;
                $AccountCredit->remark = $remark;
                $AccountCredit->save();
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $AccountCredit = AccountCredit::find($id);

                $return['status'] = 1;
                $return['title'] = 'Get AccountCredit';
                $return['content'] = $AccountCredit;
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
        $data['BankAccounts'] = $this->bankAccountOptions();
        $data['Currencies'] = Currency::orderBy('name')->get();
        return view('admin.AccountCredit.account_credit_edit',$data);
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
            $bank_account_id = $request->input('bank_account_id');
            $currency_id = $request->input('currency_id');
            $credit_amount = $request->input('credit_amount');
            $credit_balance = $request->input('credit_balance');
            $date_start = $request->input('date_start');
            if($date_start){
                $date_start = Help::convertDateThaiToDbFormat($date_start , '/');
            }
            $date_end = $request->input('date_end');
            if($date_end){
                $date_end = Help::convertDateThaiToDbFormat($date_end , '/');
            }
            $remark = $request->input('remark');

            DB::beginTransaction();
            try {
                $AccountCredit = AccountCredit::find($id);
                $AccountCredit->bank_account_id = $bank_account_id;
                $AccountCredit->currency_id = $currency_id;
                $AccountCredit->credit_amount = $credit_amount;
                $AccountCredit->credit_balance = $credit_balance;
                $AccountCredit->date_start = $date_start;
                $AccountCredit->date_end = $date_end;
                $AccountCredit->remark = $remark;
                $AccountCredit->save();
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
            $AccountCredit = AccountCredit::find($id);

            AccountCredit::where('id' , $id)->delete();

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
    public function report()
    {
        return AccountCredit::query()
            ->leftJoin('bank_accounts', 'account_credits.bank_account_id', '=', 'bank_accounts.id')
            ->leftJoin('banks', 'bank_accounts.bank_id', '=', 'banks.id')
            ->leftJoin('currencies', 'account_credits.currency_id', '=', 'currencies.id')
            ->select(
                'account_credits.*',
                'banks.name_en as bank_name',
                'bank_accounts.account_no',
                'currencies.name as currency_name',
                'currencies.symbol as currency_symbol'
            )
            ->orderByDesc('account_credits.id');
    }

    private function bankAccountOptions()
    {
        return BankAccount::leftJoin('banks', 'banks.id', '=', 'bank_accounts.bank_id')
            ->select(
                'bank_accounts.*',
                'banks.name_en as bank_name'
            )
            ->orderBy('banks.name_en')
            ->get();
    }

    public function lists(Request $request)
    {
        $result = $this->report($request);

        return DataTables::of($result)
        ->addColumn('bank_display', function ($rec) {
            $name = trim(($rec->bank_name ?? '').' '.($rec->account_no ?? ''));

            return $name !== '' ? e($name) : '-';
        })
        ->addColumn('currency_display', function ($rec) {
            if (!$rec->currency_name) {
                return '-';
            }
            $label = $rec->currency_name;
            if ($rec->currency_symbol) {
                $label .= ' ('.$rec->currency_symbol.')';
            }

            return e($label);
        })
        ->filterColumn('bank_display', function ($query, $keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('banks.name_en', 'like', '%'.$keyword.'%')
                    ->orWhere('bank_accounts.account_no', 'like', '%'.$keyword.'%');
            });
        })
        ->filterColumn('currency_display', function ($query, $keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('currencies.name', 'like', '%'.$keyword.'%')
                    ->orWhere('currencies.symbol', 'like', '%'.$keyword.'%');
            });
        })
        ->addColumn('action', function ($rec) {
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
        ->rawColumns(['action'])
        ->make(true);
    }

    public function export_excel(Request $request){
        $result = $this->report($request);
        $data['result'] = $result->get();

        \Excel::create('รายงาน AccountCredit ', function ($excel) use ($data) {
            $excel->sheet('รายงาน AccountCredit', function ($sheet) use ($data) {
                $sheet->loadView('admin.AccountCredit.account_credit_export_excel', $data);
            });
        })->export('xlsx');
    }

    public function export_print(Request $request){
        $result = $this->report($request);
        $data['result'] = $result->get();


        $pdf = \PDF::loadView('admin.AccountCredit.account_credit_export_print', $data);
        return $pdf->stream('AccountCredit.pdf');
    }

    public function export_pdf(Request $request){
        $result = $this->report($request);
        $data['result'] = $result->get();

        return view('admin.AccountCredit.account_credit_export_pdf', $data);
    }

}
