<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\ShipmentMethod;
use DataTables;
use Help;
use DB;
use Validator;

class ShipmentMethodController extends AdminController
{
    public $current_menu;

    public function __construct()
    {
        $this->current_menu = 'ShipmentMethod';
    }

    public function index()
    {
        if (!Help::CheckPermissionMenu($this->current_menu, 'r')) {
            return redirect('/admin/PermissionDenined');
        }
        $data['currentMenu'] = Menu::where('url', $this->current_menu)->first();
        return view('admin.ShipmentMethod.shipment_method', $data);
    }

    public function store(Request $request)
    {
        if (!Help::CheckPermissionMenu($this->current_menu, 'c')) {
            return redirect('/admin/PermissionDenined');
        }
        DB::beginTransaction();
        try {
            $m = new ShipmentMethod;
            $m->name   = $request->input('name');
            $m->seq    = (int) ($request->input('seq') ?: 0);
            $m->active = $request->input('active') === 'F' ? 'F' : 'T';
            $m->save();
            DB::commit();
            return ['status' => 1, 'title' => __('messages.save'), 'content' => __('messages.success')];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['status' => 0, 'title' => __('messages.error'), 'content' => $e->getMessage()];
        }
    }

    public function show($id)
    {
        return ['status' => 1, 'title' => 'Get ShipmentMethod', 'content' => ShipmentMethod::find($id)];
    }

    public function update(Request $request, $id)
    {
        if (!Help::CheckPermissionMenu($this->current_menu, 'u')) {
            return redirect('/admin/PermissionDenined');
        }
        DB::beginTransaction();
        try {
            $m = ShipmentMethod::find($id);
            $m->name   = $request->input('name');
            $m->seq    = (int) ($request->input('seq') ?: 0);
            $m->active = $request->input('active') === 'F' ? 'F' : 'T';
            $m->save();
            DB::commit();
            return ['status' => 1, 'title' => __('messages.save'), 'content' => __('messages.success')];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['status' => 0, 'title' => __('messages.error'), 'content' => $e->getMessage()];
        }
    }

    public function destroy($id)
    {
        if (!Help::CheckPermissionMenu($this->current_menu, 'd')) {
            return redirect('/admin/PermissionDenined');
        }
        DB::beginTransaction();
        try {
            ShipmentMethod::where('id', $id)->delete();
            DB::commit();
            return ['status' => 1, 'title' => __('messages.delete_data'), 'content' => __('messages.success')];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['status' => 0, 'title' => __('messages.error'), 'content' => $e->getMessage()];
        }
    }

    public function lists(Request $request)
    {
        $result = ShipmentMethod::select()->orderBy('seq')->orderBy('id');
        return DataTables::of($result)
            ->addColumn('active_label', function ($rec) {
                return $rec->active === 'T'
                    ? '<span class="badge badge-success">ใช้งาน</span>'
                    : '<span class="badge badge-secondary">ปิด</span>';
            })
            ->addColumn('action', function ($rec) {
                $str = '';
                if (Help::CheckPermissionMenu($this->current_menu, 'u')) {
                    $str .= '<button class="btn btn-xs btn-warning btn-edit" data-id="' . $rec->id . '" title="แก้ไข"><i class="fa fa-edit"></i></button> ';
                }
                if (Help::CheckPermissionMenu($this->current_menu, 'd')) {
                    $str .= '<button class="btn btn-xs btn-danger btn-delete" data-id="' . $rec->id . '" title="ลบ"><i class="fa fa-trash"></i></button> ';
                }
                return $str;
            })
            ->rawColumns(['active_label', 'action'])
            ->addIndexColumn()
            ->make(true);
    }
}
