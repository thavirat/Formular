<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\CustomerCodeProduct;
use App\Models\Customer;
use App\Models\Product;
use DataTables;
use Help;
use DB;
use Validator;

class CustomerCodeProductController extends AdminController
{
    public $current_menu;

    public function __construct() {
        $this->current_menu = 'CustomerCodeProduct';
    }

    public function index()
    {
        if(!Help::CheckPermissionMenu($this->current_menu , 'r')) return redirect('/admin/PermissionDenined');

        $data['currentMenu'] = Menu::where('url',$this->current_menu)->first();


        $data['Customers'] = Customer::orderBy('company_name')->get();
        $data['Products'] = Product::orderBy('name_en')->get();
        return view('admin.CustomerCodeProduct.customer_code_product',$data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required',
            'product_id' => 'required',
            'code' => 'required'
        ]);

        if ($validator->fails()) return ['status' => 0, 'title' => 'ผิดพลาด', 'content' => 'กรุณากรอกข้อมูลให้ครบถ้วน'];

        DB::beginTransaction();
        try {
            $model = new CustomerCodeProduct;
            $model->customer_id = $request->customer_id;
            $model->product_id = $request->product_id;
            $model->code = $request->code;
            $model->save();
            DB::commit();
            return ['status' => 1, 'title' => 'สำเร็จ', 'content' => 'บันทึกข้อมูลเรียบร้อย'];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['status' => 0, 'title' => 'ผิดพลาด', 'content' => $e->getMessage()];
        }
    }

    public function show($id)
    {
        return ['status' => 1, 'content' => CustomerCodeProduct::find($id)];
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $model = CustomerCodeProduct::find($id);
            $model->customer_id = $request->customer_id;
            $model->product_id = $request->product_id;
            $model->code = $request->code;
            $model->save();
            DB::commit();
            return ['status' => 1, 'title' => 'สำเร็จ', 'content' => 'อัปเดตข้อมูลเรียบร้อย'];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['status' => 0, 'title' => 'ผิดพลาด', 'content' => $e->getMessage()];
        }
    }

    public function destroy($id)
    {
        try {
            CustomerCodeProduct::destroy($id);
            return ['status' => 1, 'title' => 'ลบข้อมูล', 'content' => 'ลบข้อมูลสำเร็จ'];
        } catch (\Exception $e) {
            return ['status' => 0, 'title' => 'ผิดพลาด', 'content' => $e->getMessage()];
        }
    }

    public function report(Request $request) {
        $customer_id = $request->input('customer_id');
        $result = CustomerCodeProduct::leftJoin('customers', 'customers.id', '=', 'customer_code_products.customer_id')
            ->leftJoin('products', 'products.id', '=', 'customer_code_products.product_id')
            ->select('customer_code_products.*', 'customers.company_name', 'products.name_en', 'products.name_th')
            ;
        if($customer_id){
            $result->where('customer_code_products.customer_id' , $customer_id);
        }

        return $result;
    }

    public function lists(Request $request)
    {
        return DataTables::of($this->report($request))
            ->addColumn('customer_display', function($rec) {
                return '<span class="text-primary-d1 font-bolder">'.$rec->company_name.'</span>';
            })
            ->addColumn('product_display', function($rec) {
                return '<div><strong>'.$rec->name_en.'</strong></div><small class="text-grey">'.$rec->name_th.'</small>';
            })
            ->addColumn('code_display', function($rec) {
                return '<span class="badge badge-lg bgc-success-l3 text-success-d2 border-1 brc-success-m3 px-3">'.$rec->code.'</span>';
            })
            ->addColumn('action', function($rec){
                $str = '<div class="btn-group">';
                if(Help::CheckPermissionMenu($this->current_menu , 'u')) {
                    $str .= '<button class="btn btn-outline-warning btn-h-light-warning btn-a-light-warning btn-edit" data-id="'.$rec->id.'"><i class="fa fa-pencil-alt"></i></button>';
                }
                if(Help::CheckPermissionMenu($this->current_menu , 'd')) {
                    $str .= '<button class="btn btn-outline-danger btn-h-light-danger btn-a-light-danger btn-delete" data-id="'.$rec->id.'"><i class="fa fa-trash-alt"></i></button>';
                }
                $str .= '</div>';
                return $str;
            })
            ->rawColumns(['customer_display', 'product_display', 'code_display', 'action'])
            ->addIndexColumn()
            ->make(true);
    }


    public function create()
    {
        $data['currentMenu'] = Menu::where('url',$this->current_menu)->first();


        $data['Customers'] = Customer::orderBy('company_name')->get();
        $data['Products'] = Product::orderBy('name_en')->get();
        return view('admin.CustomerCodeProduct.customer_code_product_create',$data);
    }

    public function edit($id)
    {
        $data['currentMenu'] = Menu::where('url',$this->current_menu)->first();


        $data['Customers'] = Customer::orderBy('company_name')->get();
        $data['Products'] = Product::orderBy('name_en')->get();
        return view('admin.CustomerCodeProduct.customer_code_product_edit',$data);
    }

}
