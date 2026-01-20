<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Permission;
use Help;
use DataTables;
use Validator;
use Storage;
use DB;

class PermissionController extends Controller
{
    public $current_menu;

    public function __construct() {
        $this->current_menu = 'Permission';
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
         return view('admin.Permission.permission',$data);
     }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
             $return['title'] = 'เพิ่มข้อมูล';
             $return['content'] = 'คุณไม่มีสิทธิ์เข้าถึง';
             $return['status'] = 0;
             return json_encode($return);
         }
         $validator = Validator::make($request->all(), [
             'key_permission'=>'required',
         ]);
         if (!$validator->fails()) {

             $permission = new Permission;
             $permission->key_permission = $request->input('key_permission');
             $permission->detail = $request->input('detail');

             DB::beginTransaction();
             try {

                $permission->save();

                DB::commit();
                $return['status'] = 1;
                $return['title'] = 'เพิ่มข้อมูล';
                $return['content'] = 'สำเร็จ';
             } catch (Exception $e) {
                DB::rollBack();
                $return['status'] = 0;
                $return['title'] = 'เพิ่มข้อมูล';
                $return['content'] = $e->getMessage();
            }
         }else{
             $failedRules = $validator->failed();
             $return['content'] = '';
             if(isset($failedRules['key_permission']['Required'])) {
                 $return['status'] = 2;
                 $return['title'] = 'เพิ่มข้อมูล';
                 $return['content'].= 'จำเป็นต้องระบุฟิล key_permission <br>';
             }
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
        $result = Permission::find($id);
        return $result;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
             $return['title'] = 'แก้ไขข้อมูล';
             $return['content'] = 'คุณไม่มีสิทธิ์เข้าถึง';
             $return['status'] = 0;
             return json_encode($return);
         }
         $validator = Validator::make($request->all(), [
             'key_permission'=>'required',
         ]);
          if (!$validator->fails()) {
                $permission = Permission::find($id);
                $permission->key_permission = $request->input('key_permission');
                $permission->detail = $request->input('detail');

                DB::beginTransaction();
                try {

                   $permission->save();

                   DB::commit();
                   $return['status'] = 1;
                   $return['title'] = 'เพิ่มข้อมูล';
                   $return['content'] = 'สำเร็จ';
                } catch (Exception $e) {
                   DB::rollBack();
                   $return['status'] = 0;
                   $return['title'] = 'เพิ่มข้อมูล';
                   $return['content'] = $e->getMessage();
               }

          }else{
              $failedRules = $validator->failed();
              $return['content'] = '';
              if(isset($failedRules['key_permission']['Required'])) {
                  $return['status'] = 2;
                  $return['title'] = 'เพิ่มข้อมูล';
                  $return['content'].= 'จำเป็นต้องระบุฟิล key_permission <br>';
              }
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
        $permission = Help::CheckPermissionMenu($this->current_menu , 'd');
         if(!$permission){
             $return['title'] = 'ลบข้อมูล';
             $return['content'] = 'คุณไม่มีสิทธิ์เข้าถึง';
             $return['status'] = 0;
             return json_encode($return);
         }

        \DB::beginTransaction();
        try {
            Permission::where('id',$id)->delete();
            \DB::commit();
            $return['status'] = 1;
            $return['content'] = 'สำเร็จ';
        } catch (Exception $e) {
            \DB::rollBack();
            $return['status'] = 0;
            $return['content'] = 'ไม่สำเร็จ'.$e->getMessage();
        }
        $return['title'] = 'ลบข้อมูล';
        return $return;
    }

    public function lists(Request $request)
    {
        $result = Permission::select('permissions.*');

        return DataTables::of($result)
        ->addColumn('collapse' , function($rec){
            $str = '
                    <div class="position-lc h-95 ml-1px border-l-3 brc-purple-m1">
                    </div>
                    ';
            return $str;
        })
        ->addColumn('action' , function($rec){
            $btnEdit = '<button class="btn btn-xs btn-warning btn-edit" style="color:white;" data-id="'.$rec->id.'" data-toggle="tooltip" data-placement="top" title="แก้ไข">
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
        ->rawColumns(['action','collapse'])
        ->make(true);
    }
}
