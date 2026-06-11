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
use App\Models\Customer;
use App\Models\Factory;
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
        $data['Factories'] = Factory::orderBy('name')->get();
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
            $cost = $request->input('cost');
            $width = $request->input('width');
            $height = $request->input('height');
            $length = $request->input('length');
            $weight = $request->input('weight');
            $factory_id = $request->input('factory_id');
            $sub_category_id = $request->input('sub_category_id');

            DB::beginTransaction();
            try {
                $Product = new Product;
                $Product->category_id = $category_id;
                $Product->code = $code;
                $Product->name_th = $name_th;
                $Product->name_en = $name_en;
                $Product->drawing = $drawing;
                $Product->cost = $cost;
                $Product->width = $width;
                $Product->height = $height;
                $Product->length = $length;
                $Product->weight = $weight;
                $Product->factory_id = $factory_id;
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
            $cost = $request->input('cost');
            $factory_id = $request->input('factory_id');
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
                $Product->cost = $cost;
                $Product->factory_id = $factory_id;
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
        $result = Product::leftJoin('product_categories', 'product_categories.id', 'products.category_id')
        ->leftJoin('sub_categories', 'sub_categories.id', 'products.sub_category_id')
        ->leftJoin('product_groups', 'product_groups.id', 'products.group_id')
        ->select([
            'products.*',
            'product_categories.name_th as category_name',
            'sub_categories.name_th as sub_category_name',
            'product_groups.name_th as group_name'
        ]);
        return $result;
    }

    public function lists(Request $request)
{
    $result = $this->report();

    return DataTables::of($result)
            ->editColumn('name_en', function($rec) {
                return '<div class="text-95 text-primary-d2 font-bolder">' . ($rec->name_en ?: '-') . '</div>
                        <small class="text-grey-m1">' . ($rec->name_th ?: '-') . '</small>';
            })
            ->editColumn('code', function($rec) {
                return '<span class="badge badge-lg bgc-light-blue-l2 text-blue-d2 border-1 brc-blue-m4 px-2">' . $rec->code . '</span>';
            })
            ->addColumn('dimensions', function($rec) {
                return '<small class="text-80 text-grey">W:' . (float)$rec->width . ' H:' . (float)$rec->height . ' L:' . (float)$rec->length . '</small>';
            })
            ->addColumn('action', function($rec) {
                $update = Help::CheckPermissionMenu($this->current_menu, 'u');
                $delete = Help::CheckPermissionMenu($this->current_menu, 'd');

                $str = '<div class="btn-group">';
                if($update) {
                    $str .= '<button class="btn btn-sm btn-outline-warning btn-h-light-warning btn-a-light-warning btn-edit" data-id="'.$rec->id.'" title="แก้ไข"><i class="fa fa-pencil-alt"></i></button>';
                }
                if($delete) {
                    $str .= '<button class="btn btn-sm btn-outline-danger btn-h-light-danger btn-a-light-danger btn-delete" data-id="'.$rec->id.'" title="ลบ"><i class="fa fa-trash-alt"></i></button>';
                }
                $str .= '</div>';
                return $str;
            })
            ->rawColumns(['action', 'name_en', 'code', 'dimensions'])
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

    /**
     * นำเข้า มิติ (ProdWide/ProdLong/ProdHigh/ProdWeight) จากไฟล์ Excel แล้วคำนวณ cube
     * - อ่านไฟล์จาก: storage/app/imports/product_dimensions.xlsx (ชีท "All in")
     * - จับคู่ด้วย code = ProdID (เช็คซ้ำจาก ProdID), อัปเดตเฉพาะแถวที่มี W/L/H ครบ
     * - cube = (width * length * height) / 1,000,000,000 (มม. -> ลบ.ม. ตาม convention ของระบบ)
     * URL: /admin/{lang}/Product/ImportDimensions
     */
    public function import_dimensions(Request $request)
    {
        // เช็คสิทธิ์: ต้องมีสิทธิ์ "แก้ไข (u)" ของเมนู Product เพราะเป็นการอัปเดตข้อมูลสินค้า
        if (!Help::CheckPermissionMenu($this->current_menu, 'u')) {
            return redirect('/admin/PermissionDenined');
        }

        @set_time_limit(0);
        @ini_set('memory_limit', '1024M');

        $dir  = storage_path('app/imports');
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true); // สร้างโฟลเดอร์ให้อัตโนมัติ จะได้นำไฟล์มาวางได้
        }
        $path = $dir . DIRECTORY_SEPARATOR . 'product_dimensions.xlsx';
        if (!is_file($path)) {
            return response()->json([
                'status'  => 0,
                'title'   => 'ไม่พบไฟล์',
                'content' => 'กรุณาวางไฟล์ไว้ที่ storage/app/imports/product_dimensions.xlsx แล้วเรียกลิงก์นี้อีกครั้ง',
            ], 404);
        }

        try {
            // ขยายความละเอียดคอลัมน์ cube ครั้งเดียว (decimal(10,2) จะปัดค่าเล็ก ๆ เป็น 0.00)
            // ใช้ raw SQL เพื่อเลี่ยง table prefix (tb_) ที่จะถูกเติมให้ information_schema.columns โดยอัตโนมัติ
            $col = DB::select(
                'SELECT NUMERIC_SCALE AS s FROM information_schema.columns
                 WHERE table_schema = ? AND table_name = ? AND column_name = ? LIMIT 1',
                [DB::getDatabaseName(), 'tb_products', 'cube']
            );
            $scale = $col[0]->s ?? 0;
            if ((int) $scale < 6) {
                DB::statement('ALTER TABLE tb_products MODIFY cube decimal(12,6) NULL DEFAULT 0.000000');
            }

            // อ่าน Excel (อ่านเฉพาะข้อมูล ไม่อ่านสูตร/สไตล์ เพื่อความเร็ว)
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($path);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($path);
            $sheet = $spreadsheet->getSheetByName('All in') ?: $spreadsheet->getActiveSheet();
            $rows  = $sheet->toArray(null, true, false, false); // 0-indexed arrays

            $updated = 0;
            $skipped = 0;
            $notFound = 0;

            DB::beginTransaction();
            foreach ($rows as $i => $r) {
                if ($i === 0) {
                    continue; // header
                }
                $code = isset($r[9]) ? trim((string) $r[9]) : '';
                if ($code === '' || strtoupper($code) === 'NULL') {
                    continue;
                }

                $w = is_numeric($r[14] ?? null) ? (float) $r[14] : null; // ProdWide
                $l = is_numeric($r[15] ?? null) ? (float) $r[15] : null; // ProdLong
                $h = is_numeric($r[16] ?? null) ? (float) $r[16] : null; // ProdHigh
                $wt = is_numeric($r[17] ?? null) ? (float) $r[17] : 0;   // ProdWeight

                // อัปเดตเฉพาะแถวที่มีมิติครบ (>0) เพื่อไม่ทับข้อมูลเดิมด้วย 0
                if ($w === null || $l === null || $h === null || $w <= 0 || $l <= 0 || $h <= 0) {
                    $skipped++;
                    continue;
                }

                $cube = round($w * $l * $h / 1000000000, 6);

                $affected = DB::table('products')
                    ->where('code', $code)
                    ->update([
                        'width'  => $w,
                        'length' => $l,
                        'height' => $h,
                        'weight' => $wt,
                        'cube'   => $cube,
                    ]);

                if ($affected > 0) {
                    $updated++;
                } else {
                    $notFound++;
                }
            }
            DB::commit();

            return response()->json([
                'status'  => 1,
                'title'   => 'สำเร็จ',
                'content' => "อัปเดต {$updated} รายการ | ข้าม (ไม่มีมิติ) {$skipped} | ไม่พบ code ตรงกัน {$notFound}",
                'summary' => compact('updated', 'skipped', 'notFound'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'    => 0,
                'title'     => 'เกิดข้อผิดพลาด',
                'message'   => $e->getMessage(),
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
            ], 500);
        }
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
                  ->orWhere('products.drawing', 'LIKE', '%' . $q . '%');
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
                'code' => $product->code,
                'drawing' => $product->drawing,
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
