<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Province;
use DataTables;
use Help;
use DB;
use Validator;
use Storage;
class ProvinceController extends AdminController
{
    public $current_menu;

    public function __construct() {
        $this->current_menu = 'Province';
    }

    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
        $permission = Help::CheckPermissionMenu($this->current_menu , 'r');
        if(!$permission){
            return redirect('/admin/PermissionDenined');
        }
        $data['currentMenu'] = Menu::where('url',$this->current_menu)->first();

    return view('admin.Province.province',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {

        $data['currentMenu'] = Menu::where('url',$this->current_menu)->first();
    return view('admin.Province.province_create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $permission = Help::CheckPermissionMenu($this->current_menu , 'c');
        if(!$permission){
            return redirect('/admin/PermissionDenined');
        }
        $validator = Validator::make($request->all(), [
            'id'=>'required',
            'name'=>'required',
            'created_at'=>'required',
            'updated_at'=>'required',
         ]);
        if (!$validator->fails()) {
            $id = $request->input('id');
            $name = $request->input('name');
            $created_at = $request->input('created_at');
            $updated_at = $request->input('updated_at');

            DB::beginTransaction();
            try {
                $Province = new Province;
                $Province->id = $id;
                $Province->name = $name;
                $Province->created_at = $created_at;
                $Province->updated_at = $updated_at;
                $Province->save();
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
            if(isset($failedRules['id']['Required'])) {
                $return['status'] = 2;
                $return['title'] = __('messages.error');
                $return['content'].= 'จำเป็นต้องระบุฟิลid<br>';
            }

            if(isset($failedRules['name']['Required'])) {
                $return['status'] = 2;
                $return['title'] = __('messages.error');
                $return['content'].= 'จำเป็นต้องระบุฟิลname<br>';
            }

            if(isset($failedRules['created_at']['Required'])) {
                $return['status'] = 2;
                $return['title'] = __('messages.error');
                $return['content'].= 'จำเป็นต้องระบุฟิลcreated_at<br>';
            }

            if(isset($failedRules['updated_at']['Required'])) {
                $return['status'] = 2;
                $return['title'] = __('messages.error');
                $return['content'].= 'จำเป็นต้องระบุฟิลupdated_at<br>';
            }



        }
        return $return;
    }

    /**
     * Display the specified resource.
     *
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function show($id)
    {
        $content = Province::find($id);
        $return['status'] = 1;
        $return['title'] = 'Get Province';
        $return['content'] = $content;
        return $return;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $data['currentMenu'] = Menu::where('url',$this->current_menu)->first();
    return view('admin.Province.province_edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $permission = Help::CheckPermissionMenu($this->current_menu , 'u');
        if(!$permission){
            return redirect('/admin/PermissionDenined');
        }
        $validator = Validator::make($request->all(), [
            'id'=>'required',
            'name'=>'required',
            'created_at'=>'required',
            'updated_at'=>'required',
         ]);
        if (!$validator->fails()) {
            $id = $request->input('id');
            $name = $request->input('name');
            $created_at = $request->input('created_at');
            $updated_at = $request->input('updated_at');

            DB::beginTransaction();
            try {
                $Province = Province::find($id);
                $Province->id = $id;
                $Province->name = $name;
                $Province->created_at = $created_at;
                $Province->updated_at = $updated_at;
                $Province->save();
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
            if(isset($failedRules['id']['Required'])) {
                $return['status'] = 2;
                $return['title'] = __('messages.error');
                $return['content'].= 'จำเป็นต้องระบุฟิลid<br>';
            }

            if(isset($failedRules['name']['Required'])) {
                $return['status'] = 2;
                $return['title'] = __('messages.error');
                $return['content'].= 'จำเป็นต้องระบุฟิลname<br>';
            }

            if(isset($failedRules['created_at']['Required'])) {
                $return['status'] = 2;
                $return['title'] = __('messages.error');
                $return['content'].= 'จำเป็นต้องระบุฟิลcreated_at<br>';
            }

            if(isset($failedRules['updated_at']['Required'])) {
                $return['status'] = 2;
                $return['title'] = __('messages.error');
                $return['content'].= 'จำเป็นต้องระบุฟิลupdated_at<br>';
            }



        }
        return $return;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $Province = Province::find($id);

            Province::where('id' , $id)->delete();

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
     * @param      \Illuminate\Http\Request  $request
     * @return    \Illuminate\Http\Response
     */
    public function lists(Request  $request)
    {
        $result = Province::query();
        return DataTables::of($result)
        ->addColumn('action' , function($rec){
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
}
