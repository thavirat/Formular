<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Product;
use DataTables;
use Help;
use DB;
use Validator;
use Storage;
use App\Models\ProductCategory;
use App\Models\BrandProduct;
use App\Models\DesignProduct;
use App\Models\UnitProduct;
use App\Models\Customer;
class ProductController extends AdminController
{
    public $current_menu;

    public function __construct() {
        $this->current_menu = 'Product';
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

        $data['ProductCategories'] = ProductCategory::orderBy('name')->get();
        $data['BrandProducts'] = BrandProduct::orderBy('name')->get();
        $data['DesignProducts'] = DesignProduct::orderBy('name')->get();
        $data['UnitProducts'] = UnitProduct::orderBy('name')->get();
        return view('admin.Product.product',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $data['currentMenu'] = Menu::where('url',$this->current_menu)->first();
        $data['ProductCategories'] = ProductCategory::orderBy('name')->get();
        $data['BrandProducts'] = BrandProduct::orderBy('name')->get();
        $data['DesignProducts'] = DesignProduct::orderBy('name')->get();
        $data['UnitProducts'] = UnitProduct::orderBy('name')->get();
        return view('admin.Product.product_create',$data);
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
            $category_id = $request->input('category_id');
            $brand_id = $request->input('brand_id');
            $design_id = $request->input('design_id');
            $unit_id = $request->input('unit_id');
            $code = $request->input('code');
            $part_no = $request->input('part_no');
            $name_th = $request->input('name_th');
            $name_en = $request->input('name_en');
            $name_cn = $request->input('name_cn');
            $drawing = $request->input('drawing');
            $width = $request->input('width');
            $height = $request->input('height');
            $length = $request->input('length');
            $weight = $request->input('weight');
            $cube = $request->input('cube');
            $active = $request->input('active' , 'F');
            $user = Auth::user();
            DB::beginTransaction();
            try {
                $Product = new Product;
                $Product->category_id = $category_id;
                $Product->brand_id = $brand_id;
                $Product->design_id = $design_id;
                $Product->unit_id = $unit_id;
                $Product->code = $code;
                $Product->part_no = $part_no;
                $Product->name_th = $name_th;
                $Product->name_en = $name_en;
                $Product->name_cn = $name_cn;
                $Product->drawing = $drawing;
                $Product->width = $width;
                $Product->height = $height;
                $Product->length = $length;
                $Product->weight = $weight;
                $Product->cube = $cube;
                $Product->active = $active;
                $Product->status_id = 1;
                $Product->save();
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
        $Product = Product::find($id);

                $return['status'] = 1;
                $return['title'] = 'Get Product';
                $return['content'] = $Product;
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
        $data['ProductCategories'] = ProductCategory::orderBy('name')->get();
        $data['BrandProducts'] = BrandProduct::orderBy('name')->get();
        $data['DesignProducts'] = DesignProduct::orderBy('name')->get();
        $data['UnitProducts'] = UnitProduct::orderBy('name')->get();
        return view('admin.Product.product_edit',$data);
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
            $category_id = $request->input('category_id');
            $brand_id = $request->input('brand_id');
            $design_id = $request->input('design_id');
            $unit_id = $request->input('unit_id');
            $code = $request->input('code');
            $part_no = $request->input('part_no');
            $name_th = $request->input('name_th');
            $name_en = $request->input('name_en');
            $name_cn = $request->input('name_cn');
            $drawing = $request->input('drawing');
            $width = $request->input('width');
            $height = $request->input('height');
            $length = $request->input('length');
            $weight = $request->input('weight');
            $cube = $request->input('cube');
            $active = $request->input('active' , 'F');

            DB::beginTransaction();
            try {
                $Product = Product::find($id);
                $Product->category_id = $category_id;
                $Product->brand_id = $brand_id;
                $Product->design_id = $design_id;
                $Product->unit_id = $unit_id;
                $Product->code = $code;
                $Product->part_no = $part_no;
                $Product->name_th = $name_th;
                $Product->name_en = $name_en;
                $Product->name_cn = $name_cn;
                $Product->drawing = $drawing;
                $Product->width = $width;
                $Product->height = $height;
                $Product->length = $length;
                $Product->weight = $weight;
                $Product->cube = $cube;
                $Product->active = $active;
                $Product->save();
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
            $Product = Product::find($id);

            Product::where('id' , $id)->delete();

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
        $result = Product::leftJoin('product_categories' , 'product_categories.id' , 'products.category_id')
        ->leftJoin('design_products' , 'design_products.id' , 'products.design_id')
        ->leftJoin('brand_products' , 'brand_products.id' , 'products.brand_id')
        ->leftJoin('unit_products' , 'unit_products.id' , 'products.unit_id')
        ->select(
            'products.*'
            ,'product_categories.name as category_name'
            ,'design_products.name as design_name'
            ,'brand_products.name as brand_name'
            ,'unit_products.name as unit_name'
        )
        ;
        return $result;
    }

    public function lists(Request $request)
    {
        $result = $this->report($request);

        return DataTables::of($result)
        ->addColumn('active', function($rec){
            if($rec->active=='T'){
                return 'เปิดใช้งาน';
            }else{
                return 'ปิดใช้งาน';
            }
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
        ->make(true);
    }

    public function export_excel(Request $request){
        $result = $this->report($request);
        $data['result'] = $result->get();

        \Excel::create('รายงาน Product ', function ($excel) use ($data) {
            $excel->sheet('รายงาน Product', function ($sheet) use ($data) {
                $sheet->loadView('admin.Product.product_export_excel', $data);
            });
        })->export('xlsx');
    }

    public function export_print(Request $request){
        $result = $this->report($request);
        $data['result'] = $result->get();


        $pdf = \PDF::loadView('admin.Product.product_export_print', $data);
        return $pdf->stream('Product.pdf');
    }

    public function export_pdf(Request $request){
        $result = $this->report($request);
        $data['result'] = $result->get();

        return view('admin.Product.product_export_pdf', $data);
    }

    public function Search(Request $request) {
        $q = $request->input('q');
        $customer_id = $request->input('customer_id');
        $currency_id = $request->input('currency_id');
        $customer = Customer::find($customer_id);


        $products = Product::leftJoin('customer_level_discouts' , function($join) use($customer , $currency_id){
            $join->on('customer_level_discouts.product_id' , 'products.id')
                 ->where('customer_level_discouts.level_id' , $customer->level_id)
                 ->where('customer_level_discouts.currency_id' , $currency_id);
        })
        ->leftJoin('currencies' , 'currencies.id' , 'customer_level_discouts.currency_id')
        ->leftJoin('customer_code_products' , function($q) use($customer_id){
            $q->on('customer_code_products.product_id' , 'products.id');
            $q->where('customer_code_products.customer_id' , $customer_id);
        })
        ->where(function($query) use ($q) {
            $query->where('products.code', 'LIKE', '%' . $q . '%')
                  ->orWhere('products.name_th', 'LIKE', '%' . $q . '%')
                  ->orWhere('products.name_en', 'LIKE', '%' . $q . '%')
                  ->orWhere('products.name_cn', 'LIKE', '%' . $q . '%');
        })

        ->limit(20)
        ->select(
            'products.*',
            'customer_level_discouts.price',
            'currencies.symbol',
            'customer_code_products.code as cus_code'
        )
        // ->groupBy('products.id')
        ->get();



        $items = [];
        foreach ($products as $product) {
            $items[] = [
                'id' => $product->id,
                'text' => $product->code . ' : ' . $product->name_en,
                'drawing' => $product->code,
                'description' => $product->name_en,
                'symbol' => $product->symbol,
                'cus_code' => $product->cus_code,
                'price' => $product->price ?? 0
            ];
        }

        // ส่งกลับตามรูปแบบที่ Select2 และสคริปต์หน้าบ้านต้องการ
        return response()->json([
            'items' => $items
        ]);
    }

}
