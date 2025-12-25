<?php

namespace App\Http\Controllers\Install;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\CrudMenu;
use DB;
use Help;
use Storage;


class InstallController extends Controller
{
    public $current_menu;

    public function __construct() {
        $this->current_menu = 'Install';
    }

    public function index(){
        $permission = Help::CheckPermissionMenu($this->current_menu , 'r');
        if(!$permission){
            return redirect('/admin/PermissionDenined');
        }
        $data['SidebarMenus'] = Menu::Active()->get();
        $data['currentMenu'] = Menu::where('url',$this->current_menu)->first();
        $data['Menus'] = Menu::all();
        $data['Tables'] = DB::select('SHOW TABLES');
        return view('install.index', $data);
    }

    public function getField(Request $request){
        $name = $request->input('name');
        return DB::select('SHOW COLUMNS FROM '.$name);
    }

    public function store(Request $request){
        DB::beginTransaction();
        try {
            $this->genView($request->all());
            $this->genModel($request->all());
            $this->genRoute($request->all());
            $this->genController($request->all());
            $this->createMenu($request->all());
            $this->genExportExcel($request->all());
            $this->genExportPdf($request->all());
            $this->genExportPrint($request->all());

            DB::commit();
            $return['status'] = 1;
            $return['message'] = 'Success';
        } catch (Exception $e) {
            DB::rollBack();
            $return['status'] = 0;
            $return['message'] = $e->getMessage();
        }
        return $return;
    }

    public function genView($input){
        $default_view = view('Install.render.default' , $input);
        $view_name = strtolower($input['view_name']);
        $model_name = $input['model_name'];
        $path = "admin/".$model_name."/".$view_name.".blade.php";
        Storage::disk('views')->put($path, $default_view);
        return $default_view;
    }

    public function getFieldFromModel(Request $request){
        $model = '\\App\Models\\'.$request->input('model');
        $m = new $model;
        $table = $m->getTable();
        return DB::select('SHOW COLUMNS FROM '.env('DB_PREFIX').$table);
    }

    public function genModel($input){
        $table = $input['table_name'];
        $input['fields'] = DB::select('SHOW COLUMNS FROM '.$table);
        $default_view = view('Install.render.default_model', $input);
        $default_view = str_replace('<#?php' , '<?php' , $default_view);
        $model_name = $input['model_name'];
        $path = $model_name.".php";
        if(!Storage::disk('models')->exists($path)){
            Storage::disk('models')->put($path, $default_view);
        }

        return $default_view;
    }

    public function getModel(){
        return Storage::disk('models')->allFiles('');
    }

    public function genController($input){
        $default_view = view('Install.render.default_controller' , $input);
        $controller_name = $input['controller_name'];
        $default_view = str_replace('<#?php' , '<?php' , $default_view);
        $path = "Admin/".$controller_name.".php";
        Storage::disk('controllers')->put($path, $default_view);
        return $default_view;
    }

    public function genRoute($input){
        $model_name = $input['model_name'];
        $controller_name = $input['controller_name'];
        $str = "Route::post('/".$model_name."/Lists', 'Admin\\$controller_name@lists');\n";
        $data = Storage::disk('routes')->get('admin.php');
        if(!strpos($data, $str)){
            $str.= "    Route::get('/".$model_name."/ExportPDF', 'Admin\\$controller_name@export_pdf');\n";
            $str.= "    Route::get('/".$model_name."/ExportExcel', 'Admin\\$controller_name@export_excel');\n";
            $str.= "    Route::get('/".$model_name."/ExportPrint', 'Admin\\$controller_name@export_print');\n";
            $str.= "    Route::resource('/{$model_name}', Admin\\{$controller_name}::class);\n";
            $str.= "\n    ##FOR##REPLACE##INSTALL##";
            $data = str_replace('##FOR##REPLACE##INSTALL##', $str , $data);
            $path = "admin.php";
            Storage::disk('routes')->put($path, $data);
        }
    }


    public function createMenu($input){
        $model_name = $input['model_name'];
        $menu_name = $input['menu_name'];
        $result = Menu::where('url' , $model_name)->first();
        if(!$result){
            $menu = new Menu;
            $menu->icon = 'fa fa-th-large';
            $menu->url = $model_name;
            $menu->title_th = $menu_name;
            $menu->title_en = $menu_name;
            $menu->show = 'T';
            $menu->sort_id = 1;
            $menu->save();

            $crudMenu = new CrudMenu;
            $crudMenu->user_id = 1;
            $crudMenu->menu_id = $menu->id;
            $crudMenu->created = 'T';
            $crudMenu->readed = 'T';
            $crudMenu->updated = 'T';
            $crudMenu->deleted = 'T';
            $crudMenu->printed = 'T';
            $crudMenu->export_excel = 'T';
            $crudMenu->export_pdf = 'T';
            $crudMenu->save();
        }
    }

    public function genExportExcel($input){
        $default_view = view('Install.render.default_export_excel' , $input);
        $view_name = strtolower($input['view_name']);
        $model_name = $input['model_name'];
        $path = "admin/".$model_name."/".$view_name."_export_excel.blade.php";
        Storage::disk('views')->put($path, $default_view);
        return $default_view;
    }

    public function genExportPdf($input){
        $default_view = view('Install.render.default_export_pdf' , $input);
        $view_name = strtolower($input['view_name']);
        $model_name = $input['model_name'];
        $path = "admin/".$model_name."/".$view_name."_export_pdf.blade.php";
        Storage::disk('views')->put($path, $default_view);
        return $default_view;
    }

    public function genExportPrint($input){
        $default_view = view('Install.render.default_export_print' , $input);
        $view_name = strtolower($input['view_name']);
        $model_name = $input['model_name'];
        $path = "admin/".$model_name."/".$view_name."_export_print.blade.php";
        Storage::disk('views')->put($path, $default_view);
        return $default_view;
    }
}
