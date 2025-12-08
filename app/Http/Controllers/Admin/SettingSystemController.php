<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Setting;
use Help;
use Validator;
use Storage;
use DB;

class SettingSystemController extends Controller
{

    public $current_menu;

    public function __construct() {
        $this->current_menu = 'SettingSystem';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        shell_exec('mysqldump');
        $permission = Help::CheckPermissionMenu($this->current_menu , 'r');
        if(!$permission){
            return redirect('/admin/PermissionDenined');
        }
        $data['SidebarMenus'] = Menu::Active()->get();
        $data['currentMenu'] = Menu::where('url',$this->current_menu)->first();
        $Settings = Setting::all();

        foreach ($Settings as $Setting) {

            $setting = json_decode($Setting['meta_value']);
            if ( $setting && is_array($setting) ) {
                $this->moveFileToTempOrak($setting , $this->current_menu);
            }

        }

        return view('admin.SettingSystem.setting_system',$data);
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
        ]);

        if (!$validator->fails()) {

            // Remove File Orak
            $Settings = Setting::whereIn('meta',['logo', 'bg_login'])->get();
            foreach ($Settings as $Setting) {
                $this->removeFileOrak(json_decode($Setting->meta_value), 'SettingSystem');
            }

            $bg_logins = $request->input('bg_login',[]);
            $logos = $request->input('logo',[]);


            foreach ($bg_logins as $bg_login) {
                if (Storage::disk("uploads")->exists("temp/" . $bg_login) && !Storage::disk("uploads")->exists("SettingSystem/" . $bg_login)) {
                    Storage::disk("uploads")->copy("temp/" . $bg_login, "SettingSystem/". $bg_login);
                    Storage::disk("uploads")->delete("temp/" . $bg_login);
                }
            }

            foreach ($logos as $logo) {
                if (Storage::disk("uploads")->exists("temp/" . $logo) && !Storage::disk("uploads")->exists("SettingSystem/" . $logo)) {
                    Storage::disk("uploads")->copy("temp/" . $logo, "SettingSystem/". $logo);
                    Storage::disk("uploads")->delete("temp/" . $logo);
                }
            }


            $bg_logins = json_encode($bg_logins);
            $logos = json_encode($logos);
            $company_name = $request->input('company_name');
            $program_name = $request->input('program_name');

            DB::beginTransaction();

            try {

                Setting::where('meta','bg_login')->update(['meta_value' => $bg_logins]);
                Setting::where('meta','logo')->update(['meta_value' => $logos]);
                Setting::where('meta','company_name')->update(['meta_value' => $company_name]);
                Setting::where('meta','program_name')->update(['meta_value' => $program_name]);

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
