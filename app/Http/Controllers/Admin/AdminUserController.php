<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\AdminUser;
use App\Models\CrudMenu;
use App\Models\AdminPermission;
use App\Models\Permission;
use App\Models\Prefix;
use Auth;
use Help;
use DataTables;
use DB;
use Validator;
use Hash;
use Storage;
use Rule;
use PDF;
use Excel;

class AdminUserController extends Controller
{

    public $current_menu;

    public function __construct() {
        $this->current_menu = 'AdminUser';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index()
     {
         $this->createUploadsFolderIfNotExist();
         $permission = Help::CheckPermissionMenu($this->current_menu , 'r');
         if(!$permission){
             return redirect('/admin/PermissionDenined');
         }
         $data['SidebarMenus'] = Menu::Active()->get();
         $data['currentMenu'] = Menu::where('url',$this->current_menu)->first();
         $data['Prefixs'] = Prefix::get();
         return view('admin.AdminUser.admin_user',$data);
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
             'password'=>'required',
             'username'=>'required|unique:admin_users',
         ]);
         if (!$validator->fails()) {


             foreach ($request->input('photo', []) as $photo) {
                 if (Storage::disk("uploads")->exists("temp/" . $photo) && !Storage::disk("uploads")->exists("AdminUser/" . $photo)) {
                     Storage::disk("uploads")->copy("temp/" . $photo, "AdminUser/". $photo);
                     Storage::disk("uploads")->delete("temp/" . $photo);
                 }
             }

             $AdminUser = new AdminUser;
             $AdminUser->prefix_id = $request->input('prefix_id');
             $AdminUser->firstname = $request->input('firstname');
             $AdminUser->lastname = $request->input('lastname');
             $AdminUser->nickname = $request->input('nickname');
             $AdminUser->email = $request->input('email');
             $AdminUser->mobile = $request->input('mobile');
             $AdminUser->active = $request->input('active','F');
             $AdminUser->username = $request->input('username');
             $AdminUser->remark = $request->input('remark');
             $AdminUser->photo = json_encode($request->input('photo', []));

             if ( $request->input('password') ) {
                 $AdminUser->password = Hash::make($request->input('password'));
             }

             if ( $request->input('birthday') ) {
                 $AdminUser->birthday = Help::convertDateThaiToDbFormat($request->input('birthday'), '/');
             }


             DB::beginTransaction();

             try {
                $AdminUser->save();
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
             if(isset($failedRules['password']['Required'])) {
                 $return['status'] = 2;
                 $return['title'] = 'เพิ่มข้อมูล';
                 $return['content'].= 'จำเป็นต้องระบุฟิล password <br>';
             }
             if(isset($failedRules['username']['Required'])) {
                 $return['status'] = 2;
                 $return['title'] = 'เพิ่มข้อมูล';
                 $return['content'].= 'จำเป็นต้องระบุฟิล username <br>';
             }
             if(isset($failedRules['username']['Unique'])) {
                 $return['status'] = 2;
                 $return['title'] = 'เพิ่มข้อมูล';
                 $return['content'].= 'มีชื่อสำหรับล็อกอิน '.$request->input('username').' อยู่ในระบบแล้ว <br>';
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
        $result = AdminUser::find($id);
        if ($result->birthday) {
            $result->birthday = date('d/m/Y', strtotime($result->birthday.'+543 years'));
        }
        if ( $result->photo ) {
            $this->moveFileToTempOrak( json_decode($result->photo), 'AdminUser');
        }else{
            $result->photo = json_encode([]);
            $result->save();
        }
        return $result;
    }


    public function showPermission($id)
    {
        $permission = Help::CheckPermissionMenu($this->current_menu , 'r');
        if(!$permission){
            return redirect('/admin/PermissionDenined');
        }

        $data['SidebarMenus'] = Menu::Active()->get();
        $data['currentMenu'] = Menu::where('url',$this->current_menu)->first();
        $db_menus = Menu::where('main_menu_id', null)->get();
        $data['Menus'] = Menu::get();

        $this->genCrudMenu($id);
        $this->getPermission($id);
        $permission = Help::CheckPermissionMenu($this->current_menu , 'u');

        if( (Help::CheckGeneralPermission('admin_permission' , 'T') === true || Help::CheckGeneralPermission('general_permission' , 'T') === true ) && $permission ){
            $data['Permissions'] = Permission::select('permissions.*','admin_permissions.permission')
            ->leftJoin('admin_permissions' , function($q) use ($id){
                $q->on('admin_permissions.permission_id' ,'=', 'permissions.id');
                $q->on('admin_permissions.admin_id' ,'=', DB::raw($id));
            })->get();
            $data['CrudMenus'] = CrudMenu::where('user_id', $id)->get();
            $data['AdminUser'] = AdminUser::find($id);
            return view('admin.AdminUser.admin_user_permission',$data);
        }else{
            return redirect('/PermissionDenined');
        }
    }

    public function updatePermission(Request $request, $id)
    {
        $permission = Help::CheckGeneralPermission('admin_permission' , 'T');
        if($permission !== true){
            $return['title'] = 'แก้ไขข้อมูล';
            $return['content'] = 'คุณไม่มีสิทธิ์เข้าถึง';
            $return['status'] = 0;
            return json_encode($return);
        }

        $input_menus = $request->input('menu',[]);
        $input_admin_permissions = $request->input('admin_permission',[]);
        DB::beginTransaction();

        try {

            CrudMenu::where('user_id', $id)->update([
                'readed' => 'F',
                'created' => 'F',
                'updated' => 'F',
                'deleted' => 'F',
                'printed' => 'F',
                'export_pdf' => 'F',
                'export_excel' => 'F'
            ]);

            foreach ($input_menus as $input_menu_id => $input_menu) {
                CrudMenu::updateOrCreate(
                    ['user_id' => $id, 'menu_id' => $input_menu_id],
                    $input_menu
                );
            }

            AdminPermission::where('admin_id', $id)->update([
                'permission' => 'F'
            ]);

            foreach ($input_admin_permissions as $input_admin_permission_id => $input_admin_permission) {
                AdminPermission::updateOrCreate(
                    ['admin_id' => $id, 'permission_id' => $input_admin_permission_id],
                    [
                        'admin_id' => $id,
                        'permission_id' => $input_admin_permission_id,
                        'permission' => $input_admin_permission
                    ]
                );
            }

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

        return $return;
    }

    public function genCrudMenu($admin_user_id)
    {
        $menus = Menu::get();
        foreach($menus as $menu){
            $check = CrudMenu::where('user_id' , $admin_user_id)->where('menu_id' , $menu->id)->first();
            if(!$check){
                $menu_crud = new CrudMenu;
                $menu_crud->user_id = $admin_user_id;
                $menu_crud->menu_id = $menu->id;
                $menu_crud->created = 'F';
                $menu_crud->readed = 'F';
                $menu_crud->updated = 'F';
                $menu_crud->deleted = 'F';
                $menu_crud->printed = 'F';
                $menu_crud->export_excel = 'F';
                $menu_crud->export_pdf = 'F';
                $menu_crud->save();
            }
        }
    }

    public function getPermission($admin_user_id)
    {
        $permissions = Permission::get();
        foreach($permissions as $permission){
            $check = AdminPermission::where([
                                                'admin_id'=>$admin_user_id,
                                                'permission_id'=>$permission->id
                                            ])->first();
            if(!$check){
                $admin_user_permission = new AdminPermission;
                $admin_user_permission->admin_id = $admin_user_id;
                $admin_user_permission->permission_id = $permission->id;
                $admin_user_permission->permission = 'F';
                $admin_user_permission->save();
            }
        }
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
             'username'=>[ 'required', Rule::unique('admin_users')->ignore($id) ],
         ]);
         if (!$validator->fails()) {

             $AdminUser = AdminUser::find($id);
             $AdminUser->prefix_id = $request->input('prefix_id');
             $AdminUser->firstname = $request->input('firstname');
             $AdminUser->lastname = $request->input('lastname');
             $AdminUser->nickname = $request->input('nickname');
             $AdminUser->email = $request->input('email');
             $AdminUser->mobile = $request->input('mobile');
             $AdminUser->active = $request->input('active','F');
             $AdminUser->username = $request->input('username');
             $AdminUser->remark = $request->input('remark');
             $this->removeFileOrak(json_decode($AdminUser->photo), 'AdminUser');
             foreach ($request->input('photo', []) as $photo) {
                 if (Storage::disk("uploads")->exists("temp/" . $photo) && !Storage::disk("uploads")->exists("AdminUser/" . $photo)) {
                     Storage::disk("uploads")->copy("temp/" . $photo, "AdminUser/". $photo);
                     Storage::disk("uploads")->delete("temp/" . $photo);
                 }
             }
             $AdminUser->photo = json_encode($request->input('photo', []));

             if ( $request->input('birthday') ) {
                 $AdminUser->birthday = Help::convertDateThaiToDbFormat($request->input('birthday'), '/');
             }

             DB::beginTransaction();

             try {
                 $AdminUser->save();
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
             if(isset($failedRules['username']['Unique'])) {
                 $return['status'] = 2;
                 $return['title'] = 'แก้ไขข้อมูล';
                 $return['content'].= 'มีชื่อสำหรับล็อกอิน '.$request->input('username').' อยู่ในระบบแล้ว <br>';
             }
             if(isset($failedRules['username']['Required'])) {
                 $return['status'] = 2;
                 $return['title'] = 'แก้ไขข้อมูล';
                 $return['content'].= 'จำเป็นต้องระบุฟิล username <br>';
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
            $AdminUser = AdminUser::find($id);
            $this->removeFileOrak(json_decode($AdminUser->photo), 'AdminUser');

            AdminUser::where('id',$id)->delete();
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

    public function password(Request $request, $id)
    {
        $permission = Help::CheckPermissionMenu($this->current_menu , 'u');
        if(!$permission){
            $return['title'] = 'แก้ไขข้อมูล';
            $return['content'] = 'คุณไม่มีสิทธิ์เข้าถึง';
            $return['status'] = 0;
            return json_encode($return);
        }
        $validator = Validator::make($request->all(), [
            'password'=>'required',
        ]);

        if (!$validator->fails()) {

            $AdminUser = AdminUser::find($id);
            $AdminUser->password = Hash::make($request->input('password'));

            DB::beginTransaction();
            try {
                $AdminUser->save();
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

        } else {
            $failedRules = $validator->failed();
            $return['content'] = '';
            if(isset($failedRules['password']['Required'])) {
                $return['status'] = 2;
                $return['title'] = 'แก้ไขข้อมูล';
                $return['content'].= 'จำเป็นต้องระบุฟิล password <br>';
            }
        }

        return $return;
    }

    public function lists(Request $request)
    {
        $result = AdminUser::select('admin_users.*', 'prefixes.name as prefix_name')
                           ->leftJoin('prefixes','prefixes.id','admin_users.prefix_id');

        return DataTables::of($result)
        ->editColumn('active' , function($rec){
            if ( $rec->active == 'T' ) {
                return 'เปิดใช้งาน';
            }else{
                return 'ปิดใช้งาน';
            }
        })
        ->addColumn('action' , function($rec){
            $btnEdit = '<button class="btn btn-xs btn-warning btn-edit" style="color:white;" data-id="'.$rec->id.'" data-toggle="tooltip" data-placement="top" title="แก้ไข">
            <i class="fa fa-edit"></i>
            </button> ';
            $btnPassword = '<button class="btn btn-xs btn-info btn-password" data-id="'.$rec->id.'" data-toggle="tooltip" data-placement="top" title="เปลี่ยนรหัสผ่าน">
            <i class="fa fa-key bigger-120"></i>
            </button> ';
            $btnPermission = '<a href="'.url("admin/AdminUser/{$rec->id}/permission").'" class="btn btn-xs btn-success mr-1" data-id="'.$rec->id.'"data-toggle="tooltip" data-placement="top" title="จัดการสิทธิ์" >
            <i class="fa fa-lock"></i>
            </a>';
            $btnDelete = '<button class="btn btn-xs btn-danger btn-delete" data-id="'.$rec->id.'" data-toggle="tooltip" data-placement="top" title="ลบ">
            <i class="fa fa-trash"></i>
            </button> ';
            $update = Help::CheckPermissionMenu($this->current_menu , 'u');
            $str = '';
            if($update){
                $str.=$btnEdit;
                $str.=$btnPassword;
                $str.=$btnPermission;
            }
            $delete = Help::CheckPermissionMenu($this->current_menu , 'd');
            if($delete){
                $str.=$btnDelete;
            }
            return $str;
        })
        ->addIndexColumn()
        ->rawColumns(['action'])
        ->make(true);
    }

    public function exportPDF(Request $request)
    {
        $data['AdminUsers'] = AdminUser::get();
        $pdf = PDF::loadView('admin.AdminUser.pdf',$data);
        return $pdf->stream('admin.AdminUser.pdf');

    }

    public function exportExcel(Request $request)
    {
        \Excel::create('excel', function ($excel) use ($request) {
            $excel->sheet('excel', function ($sheet) use ($request) {
                $data['AdminUsers'] = AdminUser::get();
                $data['request'] = $request->all();
                $sheet->loadView('admin.AdminUser.excel',$data);
            });
        })->export('xlsx');
    }

}
