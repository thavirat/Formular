<#?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\{{$model_name}};
use DataTables;
use Help;
use DB;
use Validator;
use Storage;
@php
$model = [];
@endphp
@if(isset($select)&&(sizeof($select)>0))
@foreach($select as $k=>$v)
@if(!in_array($v, $model))
use App\Models\{{$v}};
@php
$model[] = $v;
@endphp
@endif
@endforeach
@endif
class {{$controller_name}} extends AdminController
{
    public $current_menu;

    public function __construct() {
        $this->current_menu = '{{$model_name}}';
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
        $data['SidebarMenus'] = Menu::Active()->get();
@if(isset($select)&&(sizeof($select)>0))
@php
$model = [];
@endphp
@foreach($select as $k=>$v)
    @if(!in_array($v, $model))
    $data['{{ucfirst(\Str::plural($v))}}'] = {{$v}}::orderBy('{{$select_field[$k]}}')->get();
@php
$model[] = $v;
@endphp
@endif
@endforeach
@endif
        return view('admin.{{$model_name}}.{{strtolower($view_name)}}',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['SidebarMenus'] = Menu::Active()->get();
        $data['currentMenu'] = Menu::where('url',$this->current_menu)->first();
@if(isset($select)&&(sizeof($select)>0))
@php
$model = [];
@endphp
@foreach($select as $k=>$v)
    @if(!in_array($v, $model))
    $data['{{ucfirst(\Str::plural($v))}}'] = {{$v}}::orderBy('{{$select_field[$k]}}')->get();
@php
$model[] = $v;
@endphp
@endif
@endforeach
@endif
        return view('admin.{{$model_name}}.{{strtolower($view_name)}}_create',$data);
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
            return redirect('/admin/PermissionDenined');
        }
        $validator = Validator::make($request->all(), [
@if(isset($required_field))@foreach ($required_field as $key=>$require)
    @if($require=="T")
        '{{$key}}'=>'required{{(isset($unique_field[$key])&&$unique_field[$key]=='T')? '|unique:'.substr($table_install , strlen(env('DB_PREFIX'))):''}}',
@endif
@endforeach @endif
        ]);
        if (!$validator->fails()) {
@foreach($name_in_form as $key=>$name)
@if($name)
            @include('install.skeleton.request_input_'.$input_in_form[$key] , ['name'=>$key , 'checkbox'=>(isset($checkbox[$key])? $checkbox[$key] : []) , 'model_name'=>$model_name])
@endif
@endforeach

            DB::beginTransaction();
            try {
                ${{$model_name}} = new {{$model_name}};
@foreach($name_in_form as $key=>$name)
@if($name)
                ${{$model_name}}->{{$key}} = ${{$key}};
@endif
@endforeach
                ${{$model_name}}->save();
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
@if(isset($required_field))@foreach ($required_field as $key=>$require)
@if($require=="T")
            if(isset($failedRules['{{$key}}']['Required'])) {
                $return['status'] = 2;
                $return['title'] = __('messages.error');
                $return['content'].= 'จำเป็นต้องระบุฟิล{{$key}}<br>';
            }
@endif
@endforeach @endif
@if(isset($unique_field))
    @foreach ($unique_field as $key=>$unique)
        @if($unique=="T")
if(isset($failedRules['{{$key}}']['Unique'])) {
                $return['status'] = 2;
                $return['title'] = __('messages.error');
                $return['content'].= 'ข้อมูลฟิล{{$key}}ซ้ำ ';
            }
    @endif
@endforeach
@endif

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
        ${{$model_name}} = {{$model_name}}::find($id);
            @foreach($name_in_form as $key=>$name)
@if($name)
@if($input_in_form[$key]=='OrakSingle')
        if(${{$model_name}}->{{$key}}){
            $this->moveFileToTempOrak(json_decode(${{$model_name}}->{{$key}}) , $this->current_menu);
        }
 @endif
 @if($input_in_form[$key]=='file')
 if(${{$model_name}}->{{$key}}){
                    $photos = json_decode(${{$model_name}}->{{$key}});
                    foreach($photos as $photo){
                        if(!Storage::disk('uploads')->exists('files/temp/'.$photo)){
                            Storage::disk('uploads')->copy('files/{{$model_name}}/'.$photo, 'files/temp/'.$photo);
                        }
                    }
                }
@endif
@endif
@endforeach

                $return['status'] = 1;
                $return['title'] = 'Get {{$model_name}}';
                $return['content'] = ${{$model_name}};
                return $return;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['SidebarMenus'] = Menu::Active()->get();
        $data['currentMenu'] = Menu::where('url',$this->current_menu)->first();
@if(isset($select)&&(sizeof($select)>0))
@php
$model = [];
@endphp
@foreach($select as $k=>$v)
    @if(!in_array($v, $model))
    $data['{{ucfirst(\Str::plural($v))}}'] = {{$v}}::orderBy('{{$select_field[$k]}}')->get();
@php
$model[] = $v;
@endphp
@endif
@endforeach
@endif
        return view('admin.{{$model_name}}.{{strtolower($view_name)}}_edit',$data);
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
            return redirect('/admin/PermissionDenined');
        }
        $validator = Validator::make($request->all(), [
@if(isset($required_field))@foreach ($required_field as $key=>$require)
    @if($require=="T")
        '{{$key}}'=>'required{{(isset($unique_field[$key])&&$unique_field[$key]=='T')? '|unique:'.substr($table_install , strlen(env('DB_PREFIX'))):''}}',
@endif
@endforeach @endif
        ]);
        if (!$validator->fails()) {
@foreach($name_in_form as $key=>$name)
@if($name)
            @include('install.skeleton.request_edit_input_'.$input_in_form[$key] , ['name'=>$key , 'checkbox'=>(isset($checkbox[$key])? $checkbox[$key] : []) , 'model_name'=>$model_name])
@endif
@endforeach

            DB::beginTransaction();
            try {
                ${{$model_name}} = {{$model_name}}::find($id);
@foreach($name_in_form as $key=>$name)
@if($name)
                ${{$model_name}}->{{$key}} = ${{$key}};
@endif
@endforeach
                ${{$model_name}}->save();
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
@if(isset($required_field))@foreach ($required_field as $key=>$require)
@if($require=="T")
            if(isset($failedRules['{{$key}}']['Required'])) {
                $return['status'] = 2;
                $return['title'] = __('messages.error');
                $return['content'].= 'จำเป็นต้องระบุฟิล{{$key}}<br>';
            }
@endif
@endforeach @endif
@if(isset($unique_field))
    @foreach ($unique_field as $key=>$unique)
        @if($unique=="T")
if(isset($failedRules['{{$key}}']['Unique'])) {
                $return['status'] = 2;
                $return['title'] = __('messages.error');
                $return['content'].= 'ข้อมูลฟิล{{$key}}ซ้ำ ';
            }
    @endif
@endforeach
@endif

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
        DB::beginTransaction();
        try {
            ${{$model_name}} = {{$model_name}}::find($id);
            @foreach($name_in_form as $key=>$name)
@if($name)
@include('install.skeleton.destroy_input_'.$input_in_form[$key] , ['name'=>$key , 'model_name'=>$model_name , 'data' => $model_name])
@endif
@endforeach

            {{$model_name}}::where('id' , $id)->delete();

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
     * @param   \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\Response
     */
    public function report(){
        $result = {{ $model_name }}::select()->orderByDesc('id');
        return $result;
    }

    public function lists(Request $request)
    {
        $result = $this->report($request);

        return DataTables::of($result)
        ->addColumn('action', function($rec){
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

    public function export_excel(Request $request){
        $result = $this->report($request);
        $data['result'] = $result->get();

        \Excel::create('รายงาน {{ $model_name }} ', function ($excel) use ($data) {
            $excel->sheet('รายงาน {{ $model_name }}', function ($sheet) use ($data) {
                $sheet->loadView('admin.{{ $model_name }}.{{strtolower($view_name)}}_export_excel', $data);
            });
        })->export('xlsx');
    }

    public function export_print(Request $request){
        $result = $this->report($request);
        $data['result'] = $result->get();


        $pdf = \PDF::loadView('admin.{{ $model_name }}.{{strtolower($view_name)}}_export_print', $data);
        return $pdf->stream('{{ $model_name }}.pdf');
    }

    public function export_pdf(Request $request){
        $result = $this->report($request);
        $data['result'] = $result->get();

        return view('admin.{{ $model_name }}.{{strtolower($view_name)}}_export_pdf', $data);
    }

}
