<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\CustomerLevel;
use DataTables;
use Help;
use DB;
use Validator;
use Storage;
use App\Models\Product;
use App\Models\Currency;
use App\Models\CustomerLevelDiscout;
class CustomerLevelController extends AdminController
{
    public $current_menu;

    public function __construct() {
        $this->current_menu = 'CustomerLevel';
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
        return view('admin.CustomerLevel.customer_level',$data);
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
        return view('admin.CustomerLevel.customer_level_create',$data);
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

            DB::beginTransaction();
            try {
                $CustomerLevel = new CustomerLevel;
                $CustomerLevel->name = $name;
                $CustomerLevel->save();
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
        $CustomerLevel = CustomerLevel::find($id);

                $return['status'] = 1;
                $return['title'] = 'Get CustomerLevel';
                $return['content'] = $CustomerLevel;
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
        return view('admin.CustomerLevel.customer_level_edit',$data);
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

            DB::beginTransaction();
            try {
                $CustomerLevel = CustomerLevel::find($id);
                $CustomerLevel->name = $name;
                $CustomerLevel->save();
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
            $CustomerLevel = CustomerLevel::find($id);

            CustomerLevel::where('id' , $id)->delete();

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
        $result = CustomerLevel::select()->orderByDesc('id');
        return $result;
    }

    public function lists(Request $request)
    {
        $lang = config('app.locale');
        $result = $this->report($request);

        return DataTables::of($result)
        ->addColumn('btn-product', function($rec) use ($lang){
            $str = '<a href="'.url('admin/'.$lang.'/CustomerLevel/Product?id='.$rec->id).'" class="btn btn-xs btn-info" data-id="'.$rec->id.'" data-toggle="tooltip" data-placement="top" title="'.__('Price Setting').'">
            <i class="fa fa-list"></i>
            </a> ';
            return $str;
        })
        ->addColumn('btn-edit', function($rec){
            $btnEdit = '<button class="btn btn-xs btn-warning btn-edit" data-id="'.$rec->id.'" data-toggle="tooltip" data-placement="top" title="'.__('Edit').'">
            <i class="fa fa-edit"></i>
            </button> ';
            $update = Help::CheckPermissionMenu($this->current_menu , 'u');
            $str = '';
            if($update){
                $str.=$btnEdit;
            }
            return $str;
        })
        ->addColumn('btn-delete', function($rec){

            $btnDelete = '<button class="btn btn-xs btn-danger btn-delete" data-id="'.$rec->id.'" data-toggle="tooltip" data-placement="top" title="'.__('Delete').'">
            <i class="fa fa-trash"></i>
            </button> ';

            $str = '';

            $delete = Help::CheckPermissionMenu($this->current_menu , 'd');
            if($delete){
                $str.=$btnDelete;
            }

            return $str;
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
        ->rawColumns(['action' , 'btn-edit' ,'btn-delete' , 'btn-product'])
        ->addIndexColumn()
        ->make(true);
    }

    public function export_excel(Request $request){
        $result = $this->report($request);
        $data['result'] = $result->get();

        \Excel::create('รายงาน CustomerLevel ', function ($excel) use ($data) {
            $excel->sheet('รายงาน CustomerLevel', function ($sheet) use ($data) {
                $sheet->loadView('admin.CustomerLevel.customer_level_export_excel', $data);
            });
        })->export('xlsx');
    }

    public function export_print(Request $request){
        $result = $this->report($request);
        $data['result'] = $result->get();


        $pdf = \PDF::loadView('admin.CustomerLevel.customer_level_export_print', $data);
        return $pdf->stream('CustomerLevel.pdf');
    }

    public function export_pdf(Request $request){
        $result = $this->report($request);
        $data['result'] = $result->get();

        return view('admin.CustomerLevel.customer_level_export_pdf', $data);
    }

    public function product(Request $request){
        $permission = Help::CheckPermissionMenu($this->current_menu , 'r');
        if(!$permission){
            return redirect('/admin/PermissionDenined');
        }
        $data['currentMenu'] = Menu::where('url',$this->current_menu)->first();
        $data['SidebarMenus'] = Menu::Active()->get();
        $data['Products'] = Product::get();
        $data['Currencies'] = Currency::get();
        $data['level_id'] = $request->input('id');
        $data['level'] = CustomerLevel::find($request->input('id'));
        return view('admin.CustomerLevel.customer_level_product',$data);
    }

    public function product_list(Request $request) {
        $level_id = $request->input('level_id');
        $Currencies = Currency::get();

        $result = Product::with(['CustomerLevelDiscouts' => function($q) use ($level_id) {
            $q->where('level_id', $level_id);
        }]);

        $table = DataTables::of($result);

        $table->editColumn('name_en', function($rec) {
            return '<div><strong>'.$rec->name_en.'</strong></div>
                    <small class="text-grey">'.$rec->name_th.'</small>';
        });

        $table->editColumn('action', function($rec) {
            return '<div class="text-center">
                        <span class="status-indicator" id="status-'.$rec->id.'">
                            <i class="fa fa-check-circle text-grey-l3" style="font-size: 1.2rem;"></i>
                        </span>
                    </div>';
        });

        foreach($Currencies as $Currency) {
            $table->addColumn('currency_'.$Currency->symbol, function($rec) use ($Currency , $level_id) {
                $priceData = $rec->CustomerLevelDiscouts
                    ->where('currency_id', $Currency->id)
                    ->first();

                $currentPrice = $priceData ? $priceData->price : 0;
                $formattedPrice = number_format($currentPrice, 2);

                return '
                <div class="input-group" style="width:150px">
                    <input type="text"
                        class="form-control price-input auto-save-input"
                        data-currency-id="'.$Currency->id.'"
                        data-level-id="'.$level_id.'"
                        value="'.$formattedPrice.'"
                        placeholder="0.00">
                    <div class="input-group-append">
                        <span class="input-group-text" id="status-'.$rec->id.'-'.$Currency->id.'">
                            '.$Currency->symbol.'
                        </span>
                    </div>
                </div>';
            });
        }

        $rawColumns = ['action', 'name_en'];
        foreach($Currencies as $Currency) {
            $rawColumns[] = 'currency_'.$Currency->symbol;
        }

        return $table->rawColumns($rawColumns)
            ->setRowAttr([
                'data-product-id' => function($rec) {
                    return $rec->id;
                },
            ])
            ->addIndexColumn()
            ->make(true);
    }

    public function QuickSave(Request $request){
        $product_id = $request->input('product_id');
        $currency_id = $request->input('currency_id');
        $price = $request->input('price');
        $level_id = $request->input('level_id');

        DB::beginTransaction();
        try {
            CustomerLevelDiscout::updateOrCreate([
                'product_id'=>$product_id
                ,'currency_id'=>$currency_id
                ,'price'=>$price
                ,'level_id'=>$level_id
            ]);
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
        return $return;
    }

}
