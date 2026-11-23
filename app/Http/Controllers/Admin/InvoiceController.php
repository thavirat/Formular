<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu;
use App\Models\PackingForm;
use App\Models\PackingFormDetail;
use DataTables;
use Help;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

/**
 * หน้า Invoice — แสดงรายการแบบเดียวกับหน้า Packing
 * แต่ปุ่มแก้ไข = แก้ราคา (unit_price) และดูได้เฉพาะใบ Invoice
 * ใช้สิทธิ์ร่วมกับเมนู PackingForm
 */
class InvoiceController extends AdminController
{
    public $current_menu = 'PackingForm';

    public function index()
    {
        if (!Help::CheckPermissionMenu($this->current_menu, 'r')) {
            return redirect('/admin/PermissionDenined');
        }
        $data['SidebarMenus'] = Menu::Active()->get();
        $data['route_locale'] = config('app.locale');
        $data['currentMenu'] = Menu::where('url', 'Invoice')->first() ?: Menu::where('url', 'PackingForm')->first();
        $data['pageTitle'] = 'Invoice';

        return view('admin.Invoice.invoice', $data);
    }

    public function lists(Request $request)
    {
        $result = PackingForm::query()
            ->withCount('details')
            ->withCount(['details as fic_count' => function ($q) {
                $q->whereNotNull('fic_snapshot');
            }])
            ->orderByDesc('id');
        $lang = config('app.locale');

        return DataTables::of($result)
            ->addIndexColumn()
            ->addColumn('doc_info', function ($rec) {
                return '<div class="text-600">'.e($rec->invoice_no ?: $rec->doc_no).'</div>'
                    .'<div class="text-90 text-muted">'.($rec->doc_date ? $rec->doc_date->format('Y-m-d') : '-').'</div>';
            })
            ->addColumn('customer_info', function ($rec) {
                return e($rec->customer_name ?: '-');
            })
            ->addColumn('lines', function ($rec) {
                return (int) ($rec->details_count ?? 0);
            })
            ->addColumn('action_btns', function ($rec) use ($lang) {
                $str = '<div class="btn-group btn-group-sm">';
                $r = Help::CheckPermissionMenu($this->current_menu, 'r');
                $u = Help::CheckPermissionMenu($this->current_menu, 'u');
                if ($r) {
                    $str .= '<a href="'.url('admin/'.$lang.'/Invoice/'.$rec->id.'/pdf').'" class="btn btn-xs btn-success" title="ดู Invoice" target="_blank"><i class="fa fa-file-invoice-dollar"></i> Invoice</a> ';
                }
                if ($u) {
                    $str .= '<a href="'.url('admin/'.$lang.'/Invoice/'.$rec->id.'/EditPrice').'" class="btn btn-xs btn-warning" title="แก้ไขราคา"><i class="fa fa-dollar-sign"></i> แก้ราคา</a> ';
                }
                if ($r && (int) ($rec->fic_count ?? 0) > 0) {
                    $str .= '<a href="'.url('admin/'.$lang.'/Invoice/'.$rec->id.'/pdf?original=1').'" class="btn btn-xs btn-outline-secondary" title="ดูตัวเลขก่อน FIC (snapshot เดิม)" target="_blank"><i class="fa fa-history"></i> ก่อน FIC</a> ';
                }
                $str .= '</div>';

                return $str;
            })
            ->rawColumns(['doc_info', 'action_btns'])
            ->make(true);
    }

    public function editPrice($id)
    {
        if (!Help::CheckPermissionMenu($this->current_menu, 'u')) {
            return redirect('/admin/PermissionDenined');
        }

        $packingForm = PackingForm::with([
            'details' => function ($q) {
                $q->orderBy('excel_row')->orderBy('id');
            },
            'details.piProduct.pi.currency',
        ])->findOrFail($id);

        $data['packingForm'] = $packingForm;
        $data['SidebarMenus'] = Menu::Active()->get();
        $data['route_locale'] = config('app.locale');
        $data['currentMenu'] = Menu::where('url', 'Invoice')->first() ?: Menu::where('url', 'PackingForm')->first();
        $data['pageTitle'] = 'แก้ไขราคา Invoice';

        return view('admin.Invoice.invoice_edit_price', $data);
    }

    public function updatePrice(Request $request, $id)
    {
        if (!Help::CheckPermissionMenu($this->current_menu, 'u')) {
            return response()->json(['status' => 0, 'title' => 'ไม่มีสิทธิ์', 'content' => 'Permission denied'], 403);
        }

        $validator = Validator::make($request->all(), [
            'detail_id' => 'required|array',
            'unit_price' => 'required|array',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'title' => 'ผิดพลาด', 'content' => $validator->errors()->first()]);
        }

        DB::beginTransaction();
        try {
            $form = PackingForm::findOrFail($id);
            $ids = $request->input('detail_id', []);
            $prices = $request->input('unit_price', []);
            $underValues = $request->input('under_value_price', []);
            $freights = $request->input('freight_price', []);

            foreach ($ids as $i => $detailId) {
                PackingFormDetail::where('id', (int) $detailId)
                    ->where('packing_form_id', $form->id)
                    ->update([
                        'unit_price' => $this->nullablePrice($prices[$i] ?? null),
                        'under_value_price' => $this->nullablePrice($underValues[$i] ?? null),
                        'freight_price' => $this->nullablePrice($freights[$i] ?? null),
                    ]);
            }

            // เก็บ % Under Value รวมไว้ที่หัวเอกสาร (ไว้ re-calc ตอนเปิดแก้อีกครั้ง)
            $form->under_value_percent = $this->nullablePrice($request->input('under_value_percent'));
            $form->save();

            DB::commit();

            return response()->json(['status' => 1, 'title' => 'สำเร็จ', 'content' => 'บันทึกราคาเรียบร้อย']);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json(['status' => 0, 'title' => 'ผิดพลาด', 'content' => $e->getMessage()]);
        }
    }

    /** ดูเฉพาะใบ Invoice (variant accounting) */
    public function pdf(Request $request, $id)
    {
        if (!Help::CheckPermissionMenu($this->current_menu, 'r')) {
            return redirect('/admin/PermissionDenined');
        }

        $priceType = in_array($request->query('price'), ['under_value', 'freight'], true)
            ? $request->query('price')
            : 'normal';

        $packingForm = PackingForm::with([
            'details' => function ($q) {
                $q->orderBy('excel_row')->orderBy('id');
            },
            'details.piProduct.pi.currency',
            'details.piProduct.pi.remarks',
            'details.piProduct.pi.incoterm',
            'details.piProduct.product.unitProduct',
            'details.piProduct.product.category',
        ])->findOrFail($id);

        // ดูตัวเลขก่อน FIC: สลับค่าจาก snapshot ลงในโมเดลชั่วคราว (ไม่ save) แล้ว render ด้วย template เดิม
        $original = filter_var($request->query('original'), FILTER_VALIDATE_BOOLEAN);
        if ($original) {
            foreach ($packingForm->details as $d) {
                $snap = $d->fic_snapshot;
                if (is_array($snap)) {
                    foreach (['qty', 'unit_price', 'weight_nw', 'weight_gw', 'weight_nt', 'weight_gt', 'cubic_meter'] as $f) {
                        if (array_key_exists($f, $snap)) {
                            $d->{$f} = $snap[$f];
                        }
                    }
                }
            }
        }

        $pdf = \PDF::loadView('admin.PackingForm.packing_form_pdf_accounting', [
            'packingForm' => $packingForm,
            'priceType' => $priceType,
        ], [], [
            'format' => 'A4',
            'margin_left' => 8,
            'margin_right' => 8,
            'margin_top' => 68,
            'margin_header' => 5,
            'margin_bottom' => 10,
        ]);

        $filename = ($packingForm->doc_no ?: 'Doc').'_Invoice'.($original ? '_beforeFIC' : '').'_'.date('Ymd').'.pdf';

        return $pdf->stream($filename);
    }

    private function nullablePrice(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }
        $s = str_replace([',', ' '], '', trim((string) $value));
        if ($s === '' || !is_numeric($s)) {
            return null;
        }

        return $s;
    }
}
