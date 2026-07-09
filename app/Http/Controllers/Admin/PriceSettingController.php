<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\PriceSetting;
use App\Models\PriceSettingRate;
use App\Models\PriceSettingLevel;
use App\Models\PriceSettingQty;
use App\Models\Currency;
use App\Models\CustomerLevel;
use DataTables;
use Help;
use DB;

class PriceSettingController extends AdminController
{
    public $current_menu;

    public function __construct()
    {
        $this->current_menu = 'PriceSetting';
    }

    public function index()
    {
        if (!Help::CheckPermissionMenu($this->current_menu, 'r')) {
            return redirect('/admin/PermissionDenined');
        }
        $data['currentMenu'] = Menu::where('url', $this->current_menu)->first();
        return view('admin.PriceSetting.price_setting', $data);
    }

    public function create()
    {
        if (!Help::CheckPermissionMenu($this->current_menu, 'c')) {
            return redirect('/admin/PermissionDenined');
        }
        $data['currentMenu']    = Menu::where('url', $this->current_menu)->first();
        $data['Currencies']     = Currency::orderBy('id')->get();
        $data['CustomerLevels'] = CustomerLevel::orderBy('id')->get();
        $data['PriceSetting']   = null;
        return view('admin.PriceSetting.price_setting_form', $data);
    }

    public function edit($id)
    {
        if (!Help::CheckPermissionMenu($this->current_menu, 'u')) {
            return redirect('/admin/PermissionDenined');
        }
        $data['currentMenu']    = Menu::where('url', $this->current_menu)->first();
        $data['Currencies']     = Currency::orderBy('id')->get();
        $data['CustomerLevels'] = CustomerLevel::orderBy('id')->get();
        $data['PriceSetting']   = PriceSetting::with(['rates', 'levels', 'qtys'])->findOrFail($id);
        return view('admin.PriceSetting.price_setting_form', $data);
    }

    public function store(Request $request)
    {
        if (!Help::CheckPermissionMenu($this->current_menu, 'c')) {
            return response()->json(['status' => 0, 'title' => 'ไม่มีสิทธิ์', 'content' => 'Permission denied'], 403);
        }
        return $this->save($request, null);
    }

    public function update(Request $request, $id)
    {
        if (!Help::CheckPermissionMenu($this->current_menu, 'u')) {
            return response()->json(['status' => 0, 'title' => 'ไม่มีสิทธิ์', 'content' => 'Permission denied'], 403);
        }
        return $this->save($request, $id);
    }

    private function save(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $ps = $id ? PriceSetting::findOrFail($id) : new PriceSetting();
            $ps->start_date = $request->input('start_date') ?: null;
            $ps->end_date   = $request->input('end_date') ?: null;
            $ps->active     = $request->input('active') === 'F' ? 'F' : 'T';
            $ps->save();

            // เรตต่อสกุลเงิน
            PriceSettingRate::where('price_setting_id', $ps->id)->delete();
            foreach ((array) $request->input('rate', []) as $currencyId => $rate) {
                PriceSettingRate::create([
                    'price_setting_id' => $ps->id,
                    'currency_id'      => $currencyId,
                    'rate'             => (float) str_replace(',', '', $rate),
                ]);
            }

            // ตัวคูณต่อระดับลูกค้า + ระบุระดับฐาน (BIG)
            $qtyBase = $request->input('qty_base');
            PriceSettingLevel::where('price_setting_id', $ps->id)->delete();
            foreach ((array) $request->input('multiplier', []) as $levelId => $mul) {
                PriceSettingLevel::create([
                    'price_setting_id' => $ps->id,
                    'level_id'         => $levelId,
                    'multiplier'       => (float) str_replace(',', '', $mul),
                    'is_qty_base'      => ((string) $qtyBase === (string) $levelId),
                ]);
            }

            // แถม (giveaway) ต่อระดับจำนวน (บังคับมี min_qty=1)
            PriceSettingQty::where('price_setting_id', $ps->id)->delete();
            $mins = (array) $request->input('min_qty', []);
            $gives = (array) $request->input('giveaway', []);
            $hasOne = false;
            foreach ($mins as $i => $m) {
                $m = (int) $m;
                if ($m <= 0) continue;
                if ($m === 1) $hasOne = true;
                PriceSettingQty::create([
                    'price_setting_id' => $ps->id,
                    'min_qty'          => $m,
                    'giveaway'         => (float) str_replace(',', '', $gives[$i] ?? 0),
                ]);
            }
            if (!$hasOne) {
                // บังคับมีขั้น 1 เสมอ (ไม่แถม)
                PriceSettingQty::create(['price_setting_id' => $ps->id, 'min_qty' => 1, 'giveaway' => 0]);
            }

            DB::commit();
            return response()->json(['status' => 1, 'title' => 'สำเร็จ', 'content' => 'บันทึกเรียบร้อย', 'id' => $ps->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 0, 'title' => 'ผิดพลาด', 'content' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        if (!Help::CheckPermissionMenu($this->current_menu, 'd')) {
            return ['status' => 0, 'title' => 'ไม่มีสิทธิ์', 'content' => 'Permission denied'];
        }
        DB::beginTransaction();
        try {
            PriceSetting::where('id', $id)->delete(); // cascade ลบ detail
            DB::commit();
            return ['status' => 1, 'title' => 'ลบข้อมูล', 'content' => 'สำเร็จ'];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['status' => 0, 'title' => 'ผิดพลาด', 'content' => $e->getMessage()];
        }
    }

    public function lists(Request $request)
    {
        $result = PriceSetting::select('price_settings.*')->orderByDesc('id');
        return DataTables::of($result)
            ->addColumn('period', function ($rec) {
                $s = $rec->start_date ? \Carbon\Carbon::parse($rec->start_date)->format('d/m/Y') : '-';
                $e = $rec->end_date ? \Carbon\Carbon::parse($rec->end_date)->format('d/m/Y') : '-';
                return $s . ' – ' . $e;
            })
            ->addColumn('status', function ($rec) {
                return $rec->active === 'T'
                    ? '<span class="badge badge-success">ใช้งาน</span>'
                    : '<span class="badge badge-secondary">ปิด</span>';
            })
            ->addColumn('action', function ($rec) {
                $lang = config('app.locale');
                $str = '';
                if (Help::CheckPermissionMenu($this->current_menu, 'u')) {
                    $str .= '<a href="' . url('admin/' . $lang . '/PriceSetting/' . $rec->id . '/edit') . '" class="btn btn-xs btn-warning" title="แก้ไข"><i class="fa fa-edit"></i></a> ';
                }
                if (Help::CheckPermissionMenu($this->current_menu, 'd')) {
                    $str .= '<button class="btn btn-xs btn-danger btn-delete" data-id="' . $rec->id . '" title="ลบ"><i class="fa fa-trash"></i></button>';
                }
                return $str;
            })
            ->rawColumns(['status', 'action'])
            ->addIndexColumn()
            ->make(true);
    }
}
