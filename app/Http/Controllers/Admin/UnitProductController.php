<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\UnitProduct;
use DataTables;
use Help;
use DB;
use Validator;
use Storage;
class UnitProductController extends AdminController
{
    public $current_menu;

    public function __construct() {
        $this->current_menu = 'UnitProduct';
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

        return view('admin.UnitProduct.unit_product',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $data['currentMenu'] = Menu::where('url',$this->current_menu)->first();
        return view('admin.UnitProduct.unit_product_create',$data);
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
                $UnitProduct = new UnitProduct;
                $UnitProduct->name = $name;
                $UnitProduct->save();
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
        $UnitProduct = UnitProduct::find($id);

                $return['status'] = 1;
                $return['title'] = 'Get UnitProduct';
                $return['content'] = $UnitProduct;
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
        return view('admin.UnitProduct.unit_product_edit',$data);
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
                $UnitProduct = UnitProduct::find($id);
                $UnitProduct->name = $name;
                $UnitProduct->save();
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
            $UnitProduct = UnitProduct::find($id);

            UnitProduct::where('id' , $id)->delete();

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
        $result = UnitProduct::select()->orderByDesc('id');
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

        \Excel::create('รายงาน UnitProduct ', function ($excel) use ($data) {
            $excel->sheet('รายงาน UnitProduct', function ($sheet) use ($data) {
                $sheet->loadView('admin.UnitProduct.unit_product_export_excel', $data);
            });
        })->export('xlsx');
    }

    public function export_print(Request $request){
        $result = $this->report($request);
        $data['result'] = $result->get();


        $pdf = \PDF::loadView('admin.UnitProduct.unit_product_export_print', $data);
        return $pdf->stream('UnitProduct.pdf');
    }

    public function export_pdf(Request $request){
        $result = $this->report($request);
        $data['result'] = $result->get();

        return view('admin.UnitProduct.unit_product_export_pdf', $data);
    }

}
