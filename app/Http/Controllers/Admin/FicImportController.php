<?php

namespace App\Http\Controllers\Admin;

use App\Models\FicImport;
use App\Models\Menu;
use App\Services\FicImportService;
use Help;
use Illuminate\Http\Request;
use Validator;

/**
 * นำเข้าไฟล์ FIC/SHORT (เอกสารบัญชี จำนวน/ราคาจริง) เข้าหน้า Invoice
 * ใช้สิทธิ์ร่วมกับเมนู PackingForm (เหมือน InvoiceController)
 */
class FicImportController extends AdminController
{
    public $current_menu = 'PackingForm';

    /** รับไฟล์ -> parse/match -> ไปหน้าพรีวิว */
    public function upload(Request $request, FicImportService $service)
    {
        if (!Help::CheckPermissionMenu($this->current_menu, 'u')) {
            return redirect('/admin/PermissionDenined');
        }

        $validator = Validator::make($request->all(), [
            'fic_file' => 'required|file|mimes:xlsx,xls|max:20480',
        ], [], ['fic_file' => 'ไฟล์ FIC']);
        if ($validator->fails()) {
            return back()->with('fic_error', $validator->errors()->first());
        }

        $res = $service->createFromFile($request->file('fic_file'));
        if ($res['status'] !== 1) {
            return back()->with('fic_error', $res['message'] ?? 'นำเข้าไม่สำเร็จ');
        }

        $lang = config('app.locale');

        return redirect(url('admin/'.$lang.'/Invoice/FicImport/'.$res['import']->id.'/Preview'));
    }

    /** หน้าพรีวิวก่อน apply */
    public function preview($id)
    {
        if (!Help::CheckPermissionMenu($this->current_menu, 'u')) {
            return redirect('/admin/PermissionDenined');
        }

        $import = FicImport::with('lines')->findOrFail($id);

        $data['import'] = $import;
        $data['lines'] = $import->lines;
        $data['SidebarMenus'] = Menu::Active()->get();
        $data['route_locale'] = config('app.locale');
        $data['currentMenu'] = Menu::where('url', 'Invoice')->first() ?: Menu::where('url', 'PackingForm')->first();
        $data['pageTitle'] = 'พรีวิว FIC Import';

        return view('admin.Invoice.fic_import_preview', $data);
    }

    /** ยืนยัน apply */
    public function apply(Request $request, $id, FicImportService $service)
    {
        if (!Help::CheckPermissionMenu($this->current_menu, 'u')) {
            return redirect('/admin/PermissionDenined');
        }

        $import = FicImport::findOrFail($id);
        $choices = [];
        foreach ((array) $request->input('detail_choice', []) as $lineId => $detailId) {
            $choices[(int) $lineId] = (int) $detailId;
        }

        $res = $service->apply($import, $choices);
        $lang = config('app.locale');
        if ($res['status'] !== 1) {
            return redirect(url('admin/'.$lang.'/Invoice/FicImport/'.$import->id.'/Preview'))
                ->with('fic_error', $res['message'] ?? 'apply ไม่สำเร็จ');
        }

        $s = $res['summary'];
        $msg = sprintf('apply สำเร็จ — อัปเดต packing %d รายการ, Credit Note %d ใบ (%d รายการ), ข้าม %d',
            $s['updated'], $s['credit_notes'], $s['credit_items'], $s['skipped']);

        return redirect(url('admin/'.$lang.'/Invoice'))->with('fic_success', $msg);
    }

    /** ยกเลิก batch pending (ลบทิ้ง) */
    public function cancel($id)
    {
        if (!Help::CheckPermissionMenu($this->current_menu, 'u')) {
            return redirect('/admin/PermissionDenined');
        }
        $import = FicImport::findOrFail($id);
        $lang = config('app.locale');
        if ($import->status === 'pending') {
            $import->delete(); // cascade ลบ lines
        }

        return redirect(url('admin/'.$lang.'/Invoice'))->with('fic_success', 'ยกเลิกการนำเข้าแล้ว');
    }
}
