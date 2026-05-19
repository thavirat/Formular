<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu;
use App\Models\PackingForm;
use App\Models\PackingFormDetail;
use App\Services\PackingListImportService;
use DataTables;
use Help;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Validator;

class PackingFormController extends AdminController
{
    public $current_menu = 'PackingForm';

    public function index()
    {
        $permission = Help::CheckPermissionMenu($this->current_menu, 'r');
        if (!$permission) {
            return redirect('/admin/PermissionDenined');
        }
        $data['currentMenu'] = Menu::where('url', $this->current_menu)->first();
        $data['SidebarMenus'] = Menu::Active()->get();
        $data['route_locale'] = config('app.locale');

        return view('admin.PackingForm.packing_form', $data);
    }

    public function report()
    {
        return PackingForm::query()
            ->withCount('details')
            ->orderByDesc('id');
    }

    public function lists(Request $request)
    {
        $result = $this->report();
        $lang = config('app.locale');

        return DataTables::of($result)
            ->addIndexColumn()
            ->addColumn('doc_info', function ($rec) {
                return '<div class="text-600">'.$rec->doc_no.'</div>'
                    .'<div class="text-90 text-muted">'.($rec->doc_date ? $rec->doc_date->format('Y-m-d') : '-').'</div>';
            })
            ->addColumn('customer_info', function ($rec) {
                return e($rec->customer_name ?: '-');
            })
            ->addColumn('file_info', function ($rec) {
                return e($rec->source_filename ?: '-');
            })
            ->addColumn('lines', function ($rec) {
                return (int) ($rec->details_count ?? 0);
            })
            ->addColumn('action_btns', function ($rec) use ($lang) {
                $str = '<div class="btn-group btn-group-sm">';
                $u = Help::CheckPermissionMenu($this->current_menu, 'u');
                $d = Help::CheckPermissionMenu($this->current_menu, 'd');
                $r = Help::CheckPermissionMenu($this->current_menu, 'r');
                if ($r) {
                    $str .= '<a href="'.url('admin/'.$lang.'/PackingForm/'.$rec->id.'/pdf/customer').'" class="btn btn-xs btn-info" title="PL ของลูกค้า" target="_blank"><i class="fa fa-file-pdf"></i></a> ';
                    $str .= '<a href="'.url('admin/'.$lang.'/PackingForm/'.$rec->id.'/pdf/accounting').'" class="btn btn-xs btn-secondary" title="PL ของบัญชี" target="_blank"><i class="fa fa-file-pdf"></i></a> ';
                }
                if ($u) {
                    $str .= '<a href="'.url('admin/'.$lang.'/PackingForm/'.$rec->id.'/edit').'" class="btn btn-xs btn-warning" title="แก้ไข"><i class="fa fa-edit"></i></a> ';
                }
                if ($d) {
                    $str .= '<button type="button" class="btn btn-xs btn-danger btn-delete" data-id="'.$rec->id.'" title="ลบ"><i class="fa fa-trash"></i></button>';
                }
                $str .= '</div>';

                return $str;
            })
            ->rawColumns(['doc_info', 'action_btns'])
            ->make(true);
    }

    public function import(Request $request, PackingListImportService $importer)
    {
        $permission = Help::CheckPermissionMenu($this->current_menu, 'c');
        if (!$permission) {
            return response()->json(['status' => 0, 'title' => 'ไม่มีสิทธิ์', 'content' => 'Permission denied'], 403);
        }

        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'title' => 'ข้อมูลไม่ถูกต้อง',
                'content' => $validator->errors()->first(),
            ], 422);
        }

        $result = $importer->import(
            $request->file('file'),
            $request->filled('replace_id') ? (int) $request->input('replace_id') : null,
            $request->boolean('force_new')
        );

        if (!empty($result['warnings']) && ($result['status'] ?? 0) === 1) {
            $result['content'] .= "\n\nหมายเหตุ:\n".implode("\n", $result['warnings']);
        }

        return response()->json($result);
    }

    /**
     * Select2: ค้นหาใบ PI จาก doc_no (q) โดยต้องมีบรรทัดสินค้าที่ตรง part_no กับแถวปัจจุบัน
     */
    public function searchPiProduct(Request $request)
    {
        $read = Help::CheckPermissionMenu($this->current_menu, 'r');
        $update = Help::CheckPermissionMenu($this->current_menu, 'u');
        if (!$read && !$update) {
            return response()->json(['items' => []], 403);
        }

        $partNo = trim((string) $request->input('part_no', ''));
        $q = trim((string) $request->input('q', ''));

        if ($partNo === '') {
            return response()->json(['items' => []]);
        }

        $query = DB::table('proforma_invoice_products as pip')
            ->join('proforma_invoices as pi', 'pi.id', '=', 'pip.pi_id')
            ->leftJoin('products as pr', 'pr.id', '=', 'pip.product_id')
            ->where(function ($w) use ($partNo) {
                $w->whereRaw('TRIM(pip.part_no) = ?', [$partNo])
                    ->orWhereRaw('TRIM(pr.code) = ?', [$partNo]);
            });

        if ($q !== '') {
            $query->where('pi.doc_no', 'like', '%'.$q.'%');
        }

        $rows = $query
            ->orderByDesc('pi.id')
            ->orderBy('pip.id')
            ->limit(30)
            ->select(
                'pip.id',
                'pi.doc_no',
                'pip.part_no',
                'pr.code as product_code',
                'pip.detail_eng'
            )
            ->get();

        $items = [];
        foreach ($rows as $row) {
            $displayPart = trim((string) ($row->part_no ?: $row->product_code));
            $desc = $row->detail_eng ? Str::limit((string) $row->detail_eng, 48) : '';
            $text = $row->doc_no.' — '.$displayPart.($desc !== '' ? ' — '.$desc : '');
            $items[] = [
                'id' => (int) $row->id,
                'text' => $text,
            ];
        }

        return response()->json(['items' => $items]);
    }

    public function pdf(Request $request, $id)
    {
        return $this->streamPlPdf($id, 'customer');
    }

    public function pdfCustomer(Request $request, $id)
    {
        return $this->streamPlPdf($id, 'customer');
    }

    public function pdfAccounting(Request $request, $id)
    {
        return $this->streamPlPdf($id, 'accounting');
    }

    private function streamPlPdf($id, string $variant)
    {
        $permission = Help::CheckPermissionMenu($this->current_menu, 'r');
        if (!$permission) {
            return redirect('/admin/PermissionDenined');
        }

        $packingForm = PackingForm::with([
            'details' => function ($q) {
                $q->orderBy('excel_row')->orderBy('id');
            },
            'details.piProduct.pi',
        ])->findOrFail($id);

        $view = $variant === 'accounting'
            ? 'admin.PackingForm.packing_form_pdf_accounting'
            : 'admin.PackingForm.packing_form_pdf_customer';

        $pdf = \PDF::loadView($view, [
            'packingForm' => $packingForm,
        ], [], [
            'format' => 'A4',
            'margin_left' => 8,
            'margin_right' => 8,
            'margin_top' => 8,
            'margin_bottom' => 10,
        ]);

        $suffix = $variant === 'accounting' ? '_accounting' : '_customer';
        $filename = ($packingForm->doc_no ?: 'PackingList').$suffix.'_'.date('Ymd').'.pdf';

        return $pdf->stream($filename);
    }

    public function edit(Request $request, $id)
    {
        $permission = Help::CheckPermissionMenu($this->current_menu, 'u');
        if (!$permission) {
            return redirect('/admin/PermissionDenined');
        }
        $data['SidebarMenus'] = Menu::Active()->get();
        $data['currentMenu'] = Menu::where('url', $this->current_menu)->first();
        $data['packingForm'] = PackingForm::with([
            'details' => function ($q) {
                $q->orderBy('excel_row')->orderBy('id');
            },
            'details.piProduct.pi',
            'details.piProduct.product',
        ])->findOrFail($id);
        $data['admin_lang_slash'] = in_array($request->segment(2), ['th', 'en', 'ch'], true)
            ? $request->segment(2).'/'
            : '';

        return view('admin.PackingForm.packing_form_edit', $data);
    }

    public function update(Request $request, $id)
    {
        $permission = Help::CheckPermissionMenu($this->current_menu, 'u');
        if (!$permission) {
            return response()->json(['status' => 0, 'title' => 'ไม่มีสิทธิ์', 'content' => 'Permission denied'], 403);
        }

        $validator = Validator::make($request->all(), [
            'doc_date' => 'nullable|date',
            'customer_name' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'to' => 'nullable|string|max:255',
            'invoice_no' => 'nullable|string|max:100',
            'place_of_issue' => 'nullable|string|max:100',
            'customer_address' => 'nullable|string',
            'customer_phone' => 'nullable|string|max:100',
            'sailing_date' => 'nullable|date',
            'shipped_from' => 'nullable|string|max:255',
            'per_vessel' => 'nullable|string|max:255',
            'lc_no' => 'nullable|string|max:100',
            'issued_by' => 'nullable|string|max:255',
            'pkg' => 'nullable|integer|min:0',
            'qty' => 'nullable|integer|min:0',
            'cubic_meter' => 'nullable|numeric',
            'weight_nw' => 'nullable|numeric',
            'weight_gw' => 'nullable|numeric',
            'weight_nt' => 'nullable|numeric',
            'weight_gt' => 'nullable|numeric',
            'detail_id' => 'nullable|array',
            'detail_id.*' => 'nullable|integer',
            'part_no' => 'nullable|array',
            'part_no.*' => 'nullable|string|max:255',
            'detail_qty' => 'required|array',
            'detail_qty.*' => 'nullable|integer|min:0',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'title' => 'ข้อมูลไม่ถูกต้อง',
                'content' => $validator->errors()->first(),
            ], 422);
        }

        DB::beginTransaction();
        try {
            $form = PackingForm::findOrFail($id);
            $form->doc_date = $request->input('doc_date') ?: $form->doc_date;
            $form->customer_name = $request->input('customer_name');
            $form->country = $request->input('country');
            $form->to = $request->input('to');
            $form->invoice_no = $request->input('invoice_no');
            $form->place_of_issue = $request->input('place_of_issue');
            $form->customer_address = $request->input('customer_address');
            $form->customer_phone = $request->input('customer_phone');
            $form->sailing_date = $request->input('sailing_date') ?: null;
            $form->shipped_from = $request->input('shipped_from');
            $form->per_vessel = $request->input('per_vessel');
            $form->lc_no = $request->input('lc_no');
            $form->issued_by = $request->input('issued_by');
            $form->pkg = $request->filled('pkg') ? (int) $request->input('pkg') : null;
            $form->qty = (int) ($request->input('qty') ?? 0);
            $form->cubic_meter = $this->nullableDecimalInput($request->input('cubic_meter'));
            $form->weight_nw = $this->nullableDecimalInput($request->input('weight_nw'));
            $form->weight_gw = $this->nullableDecimalInput($request->input('weight_gw'));
            $form->weight_nt = $this->nullableDecimalInput($request->input('weight_nt'));
            $form->weight_gt = $this->nullableDecimalInput($request->input('weight_gt'));
            $form->save();

            $keepIds = [];
            $detailIds = $request->input('detail_id', []);
            $partNos = $request->input('part_no', []);
            $qtys = $request->input('detail_qty', []);
            $descriptions = $request->input('description', []);
            $cusParts = $request->input('cus_part_no', []);
            $piProductIds = $request->input('pi_product_id', []);
            $froms = $request->input('from', []);
            $tos = $request->input('line_to', []);
            $formulars = $request->input('formular_number', []);
            $widths = $request->input('width', []);
            $lengths = $request->input('lenght', []);
            $heights = $request->input('height', []);
            $cbms = $request->input('detail_cubic_meter', []);
            $nws = $request->input('detail_weight_nw', []);
            $gws = $request->input('detail_weight_gw', []);
            $nts = $request->input('detail_weight_nt', []);
            $gts = $request->input('detail_weight_gt', []);
            $uoms = $request->input('uom', []);
            $fromCos = $request->input('from_co', []);

            foreach ($partNos as $idx => $partNo) {
                $partNo = trim((string) $partNo);
                if ($partNo === '' && empty($detailIds[$idx])) {
                    continue;
                }
                $qty = (int) ($qtys[$idx] ?? 0);
                $did = isset($detailIds[$idx]) && $detailIds[$idx] !== '' && $detailIds[$idx] !== null
                    ? (int) $detailIds[$idx]
                    : null;

                if ($did) {
                    $detail = PackingFormDetail::where('packing_form_id', $form->id)->where('id', $did)->first();
                    if (!$detail) {
                        continue;
                    }
                } else {
                    $detail = new PackingFormDetail();
                    $detail->packing_form_id = $form->id;
                    $detail->excel_row = null;
                }

                $detail->from = $this->nullableIntInput($froms[$idx] ?? null);
                $detail->to = $this->nullableIntInput($tos[$idx] ?? null);
                $detail->part_no = $partNo ?: null;
                $detail->qty = $qty;
                $detail->description = $descriptions[$idx] ?? null;
                $detail->cus_part_no = $cusParts[$idx] ?? null;
                $detail->formular_number = $formulars[$idx] ?? null;
                $detail->width = $this->nullableDecimalInput($widths[$idx] ?? null);
                $detail->lenght = $this->nullableDecimalInput($lengths[$idx] ?? null);
                $detail->height = $this->nullableDecimalInput($heights[$idx] ?? null);
                $detail->cubic_meter = $this->nullableDecimalInput($cbms[$idx] ?? null);
                $detail->weight_nw = $this->nullableDecimalInput($nws[$idx] ?? null);
                $detail->weight_gw = $this->nullableDecimalInput($gws[$idx] ?? null);
                $detail->weight_nt = $this->nullableDecimalInput($nts[$idx] ?? null);
                $detail->weight_gt = $this->nullableDecimalInput($gts[$idx] ?? null);
                $detail->uom = isset($uoms[$idx]) && trim((string) $uoms[$idx]) !== '' ? trim((string) $uoms[$idx]) : null;
                $detail->from_co = isset($fromCos[$idx]) && trim((string) $fromCos[$idx]) !== '' ? trim((string) $fromCos[$idx]) : null;
                $pid = $piProductIds[$idx] ?? null;
                $detail->pi_product_id = $pid !== '' && $pid !== null ? (int) $pid : null;
                $detail->save();
                $keepIds[] = $detail->id;
            }

            PackingFormDetail::where('packing_form_id', $form->id)
                ->whereNotIn('id', $keepIds)
                ->delete();

            DB::commit();

            return response()->json(['status' => 1, 'title' => 'สำเร็จ', 'content' => 'บันทึกแล้ว']);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json(['status' => 0, 'title' => 'ผิดพลาด', 'content' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $permission = Help::CheckPermissionMenu($this->current_menu, 'd');
        if (!$permission) {
            return response()->json(['status' => 0, 'content' => 'Permission denied'], 403);
        }
        DB::beginTransaction();
        try {
            $form = PackingForm::findOrFail($id);
            PackingFormDetail::where('packing_form_id', $form->id)->delete();
            $form->delete();
            DB::commit();

            return response()->json(['status' => 1, 'title' => 'สำเร็จ', 'content' => 'ลบแล้ว']);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json(['status' => 0, 'title' => 'ผิดพลาด', 'content' => $e->getMessage()]);
        }
    }

    private function nullableDecimalInput(mixed $value): ?string
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

    private function nullableIntInput(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }
        $s = trim((string) $value);
        if ($s === '' || !is_numeric($s)) {
            return null;
        }

        return (int) $s;
    }
}
