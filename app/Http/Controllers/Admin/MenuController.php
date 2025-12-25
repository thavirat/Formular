<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use Help;
use DataTables;
use Validator;
use Storage;
use DB;
class MenuController extends Controller
{
    public $current_menu;

    public function __construct() {
        $this->current_menu = 'Menu';
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
        $data['SidebarMenus'] = Menu::Active()->get();
        $data['currentMenu'] = Menu::where('url',$this->current_menu)->first();
        $data['Menus'] = Menu::all();
        return view('admin.Menu.menu',$data);
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
             'url'=>'required',
         ]);
         if (!$validator->fails()) {
             $menu = new Menu;
             $menu->main_menu_id = $request->input('main_menu_id');
             $menu->icon = $request->input('icon');
             $menu->title_th = $request->input('title_th');
             $menu->title_en = $request->input('title_en');
             $menu->url = $request->input('url');
             $menu->show = $request->input('show');
             $menu->sort_id = $request->input('sort_id');
             DB::beginTransaction();
             try {
                $menu->save();
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
             if(isset($failedRules['title']['Required'])) {
                 $return['status'] = 2;
                 $return['title'] = 'เพิ่มข้อมูล';
                 $return['content'].= 'จำเป็นต้องระบุฟิล title <br>';
             }
             if(isset($failedRules['url']['Required'])) {
                 $return['status'] = 2;
                 $return['title'] = 'เพิ่มข้อมูล';
                 $return['content'].= 'จำเป็นต้องระบุฟิล url <br>';
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
        $result = Menu::find($id);
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
             'url'=>'required',
         ]);
         if (!$validator->fails()) {
             $menu = Menu::find($id);
             $menu->main_menu_id = $request->input('main_menu_id');
             $menu->icon = $request->input('icon');
             $menu->title_th = $request->input('title_th');
             $menu->title_en = $request->input('title_en');
             $menu->url = $request->input('url');
             $menu->show = $request->input('show');
             $menu->sort_id = $request->input('sort_id');
             DB::beginTransaction();
             try {
                $menu->save();
                DB::commit();
                $return['status'] = 1;
                $return['title'] = 'แก้ไขข้อมูล';
                $return['content'] = 'สำเร็จ';
             } catch (Exception $e) {
                DB::rollBack();
                $return['status'] = 0;
                $return['title'] = 'แก้ไขข้อมูล';
                $return['content'] = $e->getMessage();
            }
         }else{
             $failedRules = $validator->failed();
             $return['content'] = '';
             if(isset($failedRules['title']['Required'])) {
                 $return['status'] = 2;
                 $return['title'] = 'แก้ไขข้อมูล';
                 $return['content'].= 'จำเป็นต้องระบุฟิล title <br>';
             }
             if(isset($failedRules['url']['Required'])) {
                 $return['status'] = 2;
                 $return['title'] = 'แก้ไขข้อมูล';
                 $return['content'].= 'จำเป็นต้องระบุฟิล url <br>';
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
            Menu::where('id',$id)->delete();
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
        $result = Menu::select('menus.*','main_menus.title_th as main_menu_title')
                      ->leftJoin('menus as main_menus','main_menus.id','menus.main_menu_id');

        return DataTables::of($result)
        ->editColumn('show' , function($rec){
            return ( $rec->show == 'T' ? 'เปิดการแสดงผล' : 'ปิดการแสดงผล' );
        })
        ->addColumn('action' , function($rec){
            $btnEdit = '<button class="btn btn-xs btn-warning btn-edit" style="color:white;" data-id="'.$rec->id.'" data-toggle="tooltip" data-placement="top" title="แก้ไข">
            <i class="fa fa-edit"></i>
            </button> ';
            $btnDelete = '<button class="btn btn-xs btn-danger btn-delete" data-id="'.$rec->id.'"data-toggle="tooltip" data-placement="top" title="ลบ">
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
        ->rawColumns(['action'])
        ->make(true);
    }

}
