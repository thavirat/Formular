<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Prefix;
use App\Models\AdminUser;
use Auth;
use Help;
use DataTables;
use Validator;
use Storage;
use DB;
use Hash;
use Rule;

class ProfileController extends Controller
{
    public $current_menu;

    public function __construct() {
        $this->current_menu = 'Profile';
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
         $data['Prefixs'] = Prefix::get();
         $data['AdminUser'] = Auth::user();

         if ($data['AdminUser']->birthday) {
             $data['AdminUser']->birthday = date('d/m/Y', strtotime($data['AdminUser']->birthday.'+543 years'));
         }
         if ( $data['AdminUser']->photo ) {
             $this->moveFileToTempOrak( [$data['AdminUser']->photo], 'AdminUser');
         }

         return view('admin.Profile.profile',$data);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
             'username'=>'required',
             'email'=>'required',
             'username'=>[ 'required', Rule::unique('admin_users')->ignore($id) ],
         ]);
         if (!$validator->fails()) {

             $photo = $request->input('photo', []);
             if (sizeof($photo) > 0) {
                 $photo = $photo[0];
             }else{
                 $photo = null;
             }

             foreach ($request->input('photo', []) as $photo) {
                 if (Storage::disk("uploads")->exists("temp/" . $photo) && !Storage::disk("uploads")->exists("AdminUser/" . $photo)) {
                     Storage::disk("uploads")->copy("temp/" . $photo, "AdminUser/". $photo);
                     Storage::disk("uploads")->delete("temp/" . $photo);
                 }
             }

             $AdminUser = AdminUser::find($id);
             $AdminUser->photo = $photo;
             $AdminUser->prefix_id = $request->input('prefix_id');
             $AdminUser->firstname = $request->input('firstname');
             $AdminUser->lastname = $request->input('lastname');
             $AdminUser->nickname = $request->input('nickname');
             $AdminUser->email = $request->input('email');
             $AdminUser->mobile = $request->input('mobile');
             $AdminUser->username = $request->input('username');

             if ( $request->input('password', null) ) {
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
             if(isset($failedRules['email']['Required'])) {
                 $return['status'] = 2;
                 $return['title'] = 'แก้ไขข้อมูล';
                 $return['content'].= 'จำเป็นต้องระบุฟิล email <br>';
             }
             if(isset($failedRules['username']['Unique'])) {
                 $return['status'] = 2;
                 $return['title'] = 'แก้ไขข้อมูล';
                 $return['content'].= 'มีชื่อสำหรับล็อกอิน '.$request->input('username').' อยู่นระบบแล้ว <br>';
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
        //
    }
}
