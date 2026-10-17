<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Auth;
use App\Http\Controllers\Controller;
use App\Models\Menu;
class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data['currentMenu'] = Menu::where('url','Dashboard')->first();
        return view('admin.home',$data);
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
        //
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

    public function permissionDenined()
    {

        $data['currentMenu'] = Menu::where('url','Dashboard')->first();
        return view('admin.permission_denined',$data);
    }

    /**
     * ต่ออายุ session และคืน CSRF token ชุดใหม่ ให้หน้าที่เปิดค้างไว้นาน
     * (เช่น ฟอร์ม Quotation หลายแถว) ping มาเป็นระยะ กัน session/token หมดอายุ
     * แล้ว save ไม่ผ่าน (419 CSRF token mismatch)
     */
    public function keepAlive()
    {
        return response()->json(['status' => 1, 'token' => csrf_token()]);
    }

}
