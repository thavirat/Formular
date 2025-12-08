<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \Auth;
use \Hash;
use \App\Models\Setting;
use \App\Models\AdminUser;
class LoginController extends Controller
{
    public function index(Request $request)
    {
        Auth::guard('admin')->logout();
        $data['url_redirect'] = $request->input('url_redirect');
        $setting_from_db = Setting::get();
        $settings = [];
        foreach($setting_from_db as $s){
            $settings[$s->meta] = $s->meta_value;
        }
        $data['settings'] = $settings;
        return view('admin.login',$data);
    }


    public function checkLogin(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');
        $remember = $request->input('remember_me','off');
        $remember = ( $remember == 'on' ? true : false );
        if (Auth::guard('admin')->attempt(['username' => $username, 'password' => $password, 'active' => 'T'] , $remember)) {
            $user = Auth::guard('admin')->user();
            if(!$user->api_token){
                $token = Hash::make('60');
                AdminUser::where('id' , $user->id)->update(['api_token' => $token]);
            }
            $return['status'] = 1;
            $return['content'] = __('messages.success');
        }else{
            $return['status'] = 0;
            $return['title'] = __('messages.login_unsuccess');
            $return['content'] = __('messages.username_password_denined');
        }
        return $return;
    }

    public function logout(){
        Auth::logout();
        return redirect('/admin');
    }

}
