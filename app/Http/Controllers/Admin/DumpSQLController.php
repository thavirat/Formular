<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Setting;
use Help;
use Hash;
use DataTables;
use Validator;
use Storage;
use DB;

class DumpSQLController extends Controller
{

    public $current_menu;

    public function __construct() {
        $this->current_menu = 'DumpSQL';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data['currentMenu'] = Menu::where('url',$this->current_menu)->first();
        $data['Menus'] = Menu::all();
        return view('admin.DumpSQL.dump_sql',$data);
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
        $dump_password = $request->input('dump_password');
        $db_dump_password = Setting::where('meta','dump_password')->first();

        if ( $dump_password && $db_dump_password && Hash::check($dump_password, $db_dump_password->meta_value) ) {
            $file_name = env('DB_DATABASE').'_'.date('Y-m-d_H-i-s').'.sql';
            try {
                include ( base_path().'/vendor/ifsnop/mysqldump-php/src/Ifsnop/Mysqldump/Mysqldump.php');
                $dump = new \Ifsnop\Mysqldump\Mysqldump('mysql:host='.env('DB_HOST').';dbname='.env('DB_DATABASE'), env('DB_USERNAME'), env('DB_PASSWORD'),['add-drop-table' => true]);
                $dump->start(public_path('uploads/temp/'.$file_name));
            } catch (\Exception $e) {
                echo 'mysqldump-php error: ' . $e->getMessage();
                dd($e);
            }
            return Storage::disk('uploads')->download('temp/'.$file_name);
        }else{
            return redirect('admin/DumpSQL')->with('error','Password Dump SQL ไม่ถูกต้อง');
        }

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
}
