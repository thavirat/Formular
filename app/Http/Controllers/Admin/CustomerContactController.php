<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\CustomerContact;
use DataTables;
use Help;
use DB;
use Validator;
use Storage;
use App\Models\Customer;
class CustomerContactController extends AdminController
{
    public $current_menu;

    public function __construct() {
        $this->current_menu = 'CustomerContact';
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
        return view('admin.CustomerContact.customer_contact',$data);
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
        return view('admin.CustomerContact.customer_contact_create',$data);
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
            $name = $request->input('name');
            $mobile = $request->input('mobile');
            $email = $request->input('email');
            $customer_id = $request->input('customer_id');

            DB::beginTransaction();
            try {
                $CustomerContact = new CustomerContact;
                $CustomerContact->name = $name;
                $CustomerContact->mobile = $mobile;
                $CustomerContact->email = $email;
                $CustomerContact->customer_id = $customer_id;
                $CustomerContact->save();
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
        $CustomerContact = CustomerContact::find($id);

                $return['status'] = 1;
                $return['title'] = 'Get CustomerContact';
                $return['content'] = $CustomerContact;
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

        $data['currentMenu'] = Menu::where('url',$this->current_menu)->first();
        $data['Customers'] = Customer::orderBy('company_name')->get();
        return view('admin.CustomerContact.customer_contact_edit',$data);
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
            $name = $request->input('name');
            $mobile = $request->input('mobile');
            $email = $request->input('email');
            $customer_id = $request->input('customer_id');

            DB::beginTransaction();
            try {
                $CustomerContact = CustomerContact::find($id);
                $CustomerContact->name = $name;
                $CustomerContact->mobile = $mobile;
                $CustomerContact->email = $email;
                $CustomerContact->customer_id = $customer_id;
                $CustomerContact->save();
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
            $CustomerContact = CustomerContact::find($id);

            CustomerContact::where('id' , $id)->delete();

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
    public function report(Request $request){
        $customer_id = $request->input('customer_id');
        $result = CustomerContact::leftJoin('customers' , 'customers.id' , 'customer_contacts.customer_id')
        ->select(
            'customer_contacts.*'
            ,'customers.company_name'
        );
        if($customer_id){
            $result->where('customer_contacts.customer_id' , $customer_id);
        }
        return $result;
    }

    public function lists(Request $request)
    {
        $result = $this->report($request);

        // ใน Controller ส่วน lists()
        return DataTables::of($result)
            ->editColumn('company_name', function($rec) {
                return '<div class="text-primary-d1 font-bolder">' . $rec->company_name . '</div>';
            })
            ->editColumn('name', function($rec) {
                return '<div><i class="far fa-user text-grey-m1 mr-1"></i> ' . $rec->name . '</div>';
            })
            ->addColumn('contact_info', function($rec) {
                return '<div>
                            <div class="text-90"><i class="fa fa-phone-alt text-success-m2 mr-1 w-2"></i>' . $rec->mobile . '</div>
                            <div class="text-85 text-grey-m1"><i class="fa fa-envelope text-info-m2 mr-1 w-2"></i>' . $rec->email . '</div>
                        </div>';
            })
            ->addColumn('action', function($rec){
                $update = Help::CheckPermissionMenu($this->current_menu , 'u');
                $delete = Help::CheckPermissionMenu($this->current_menu , 'd');

                $str = '<div class="btn-group">';
                if($update){
                    $str .= '<button class="btn btn-outline-warning btn-h-light-warning btn-a-light-warning btn-edit" data-id="'.$rec->id.'" title="แก้ไข">
                                <i class="fa fa-pencil-alt"></i>
                            </button>';
                }
                if($delete){
                    $str .= '<button class="btn btn-outline-danger btn-h-light-danger btn-a-light-danger ml-1 btn-delete" data-id="'.$rec->id.'" title="ลบ">
                                <i class="fa fa-trash-alt"></i>
                            </button>';
                }
                $str .= '</div>';
                return $str;
            })
            ->rawColumns(['company_name', 'name', 'contact_info', 'action'])
            ->addIndexColumn()
            ->make(true);
    }

    public function export_excel(Request $request){
        $result = $this->report($request);
        $data['result'] = $result->get();

        \Excel::create('รายงาน CustomerContact ', function ($excel) use ($data) {
            $excel->sheet('รายงาน CustomerContact', function ($sheet) use ($data) {
                $sheet->loadView('admin.CustomerContact.customer_contact_export_excel', $data);
            });
        })->export('xlsx');
    }

    public function export_print(Request $request){
        $result = $this->report($request);
        $data['result'] = $result->get();


        $pdf = \PDF::loadView('admin.CustomerContact.customer_contact_export_print', $data);
        return $pdf->stream('CustomerContact.pdf');
    }

    public function export_pdf(Request $request){
        $result = $this->report($request);
        $data['result'] = $result->get();

        return view('admin.CustomerContact.customer_contact_export_pdf', $data);
    }

}
