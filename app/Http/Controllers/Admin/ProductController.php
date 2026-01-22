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
use App\Models\SubCategory;
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
        $data['SidebarMenus'] = Menu::Active()->get();
        $data['ProductCategories'] = ProductCategory::orderBy('name_th')->get();
        $data['SubCategories'] = SubCategory::orderBy('name_th')->get();
        return view('admin.Product.product',$data);
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
        $data['ProductCategories'] = ProductCategory::orderBy('name_th')->get();
        $data['SubCategories'] = SubCategory::orderBy('name_th')->get();
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
            $code = $request->input('code');
            $name_th = $request->input('name_th');
            $name_en = $request->input('name_en');
            $drawing = $request->input('drawing');
            $width = $request->input('width');
            $height = $request->input('height');
            $length = $request->input('length');
            $weight = $request->input('weight');
            $sub_category_id = $request->input('sub_category_id');

            DB::beginTransaction();
            try {
                $Product = new Product;
                $Product->category_id = $category_id;
                $Product->code = $code;
                $Product->name_th = $name_th;
                $Product->name_en = $name_en;
                $Product->drawing = $drawing;
                $Product->width = $width;
                $Product->height = $height;
                $Product->length = $length;
                $Product->weight = $weight;
                $Product->sub_category_id = $sub_category_id;
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
        $data['SidebarMenus'] = Menu::Active()->get();
        $data['currentMenu'] = Menu::where('url',$this->current_menu)->first();
        $data['ProductCategories'] = ProductCategory::orderBy('name_th')->get();
        $data['SubCategories'] = SubCategory::orderBy('name_th')->get();
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
            $code = $request->input('code');
            $name_th = $request->input('name_th');
            $name_en = $request->input('name_en');
            $drawing = $request->input('drawing');
            $width = $request->input('width');
            $height = $request->input('height');
            $length = $request->input('length');
            $weight = $request->input('weight');
            $sub_category_id = $request->input('sub_category_id');

            DB::beginTransaction();
            try {
                $Product = Product::find($id);
                $Product->category_id = $category_id;
                $Product->code = $code;
                $Product->name_th = $name_th;
                $Product->name_en = $name_en;
                $Product->drawing = $drawing;
                $Product->width = $width;
                $Product->height = $height;
                $Product->length = $length;
                $Product->weight = $weight;
                $Product->sub_category_id = $sub_category_id;
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
        $result = Product::select()->orderByDesc('id');
        return $result;
    }

    public function lists(Request $request)
    {
        $result = $this->report($request);

        return DataTables::of($result)
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


    public function import_product(Request $request)
    {
        try {
            if (!$request->hasFile('file')) {
                return response()->json(['status' => 0, 'title' => 'Error', 'content' => 'กรุณาเลือกไฟล์']);
            }

            \Excel::import(new \App\Imports\ProductImport, $request->file('file'));

            return response()->json([
                'status' => 1,
                'title' => 'สำเร็จ',
                'content' => 'นำเข้าข้อมูลสินค้าเรียบร้อยแล้ว'
            ]);

        } catch (\Exception $e) {
            // ใช้ฟังก์ชัน ajaxFail ของเตี้ยมเพื่อ Debug
            return response()->json([
                'message' => $e->getMessage(),
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

}
