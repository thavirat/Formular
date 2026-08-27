<?php

namespace App\Http\Controllers\Admin;

use App\Models\AdminUser;
use App\Models\CreditNote;
use App\Models\CreditNoteItem;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Menu;
use App\Models\ProformaInvoice;
use App\Models\ProformaInvoiceProduct;
use DataTables;
use Help;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

class CreditNoteController extends AdminController
{
    public $current_menu = 'CreditNote';

    /* ===================== หน้า List ===================== */
    public function index()
    {
        if (!Help::CheckPermissionMenu($this->current_menu, 'r')) {
            return redirect('/admin/PermissionDenined');
        }
        $data['currentMenu'] = Menu::where('url', $this->current_menu)->first();
        $data['SidebarMenus'] = Menu::Active()->get();
        $data['route_locale'] = config('app.locale');

        return view('admin.CreditNote.credit_note', $data);
    }

    public function lists(Request $request)
    {
        $q = CreditNote::query()->leftJoin('customers', 'credit_notes.customer_id', '=', 'customers.id')
            ->select('credit_notes.*', 'customers.company_name as cust_company');

        $kw = trim((string) $request->input('keyword', ''));
        if ($kw !== '') {
            $q->where(function ($w) use ($kw) {
                $w->where('credit_notes.company_name', 'like', "%{$kw}%")
                    ->orWhere('customers.company_name', 'like', "%{$kw}%")
                    ->orWhere('credit_notes.doc_no', 'like', "%{$kw}%")
                    ->orWhere('credit_notes.refer', 'like', "%{$kw}%")
                    ->orWhereExists(function ($sub) use ($kw) {
                        $sub->select(DB::raw(1))->from('credit_note_items')
                            ->whereColumn('credit_note_items.credit_note_id', 'credit_notes.id')
                            ->where('credit_note_items.invoice_no', 'like', "%{$kw}%");
                    });
            });
        }
        $q->orderByDesc('credit_notes.id');
        $lang = config('app.locale');

        return DataTables::of($q)
            ->addIndexColumn()
            ->addColumn('doc_info', function ($r) {
                $badge = $r->doc_type === 'debit'
                    ? '<span class="badge badge-warning">DEBIT</span>'
                    : '<span class="badge badge-info">CREDIT</span>';
                return '<div class="font-bolder">'.e($r->doc_no).'</div>'
                    .'<div class="text-90 text-muted">'.($r->doc_date ? $r->doc_date->format('Y-m-d') : '-').' '.$badge.'</div>';
            })
            ->addColumn('customer', fn ($r) => e($r->company_name ?: $r->cust_company ?: '-'))
            ->addColumn('refer', fn ($r) => e($r->refer ?: '-'))
            ->addColumn('total', fn ($r) => number_format((float) $r->total, 2))
            ->addColumn('action_btns', function ($r) use ($lang) {
                $s = '<div class="btn-group btn-group-sm">';
                if (Help::CheckPermissionMenu($this->current_menu, 'r')) {
                    $s .= '<a href="'.url('admin/'.$lang.'/CreditNote/'.$r->id.'/pdf').'" target="_blank" class="btn btn-xs btn-danger" title="PDF"><i class="fa fa-file-pdf"></i></a> ';
                }
                if (Help::CheckPermissionMenu($this->current_menu, 'u')) {
                    $s .= '<a href="'.url('admin/'.$lang.'/CreditNote/'.$r->id.'/edit').'" class="btn btn-xs btn-warning" title="แก้ไข"><i class="fa fa-edit"></i></a> ';
                }
                if (Help::CheckPermissionMenu($this->current_menu, 'd')) {
                    $s .= '<button type="button" class="btn btn-xs btn-danger btn-delete" data-id="'.$r->id.'" title="ลบ"><i class="fa fa-trash"></i></button>';
                }
                return $s.'</div>';
            })
            ->rawColumns(['doc_info', 'action_btns'])
            ->make(true);
    }

    /* ===================== Create / Edit ===================== */
    public function create()
    {
        if (!Help::CheckPermissionMenu($this->current_menu, 'c')) {
            return redirect('/admin/PermissionDenined');
        }
        $data['currentMenu'] = Menu::where('url', $this->current_menu)->first();
        $data['SidebarMenus'] = Menu::Active()->get();
        $data['Customers'] = Customer::orderBy('company_name')->get();
        $data['Admins'] = AdminUser::orderBy('nickname')->get();
        $data['creditNote'] = new CreditNote(['doc_type' => 'credit', 'doc_date' => date('Y-m-d')]);
        $data['suggested_doc_no'] = $this->suggestDocNo('credit', (int) date('Y'));
        $data['isEdit'] = false;

        return view('admin.CreditNote.credit_note_form', $data);
    }

    public function edit($id)
    {
        if (!Help::CheckPermissionMenu($this->current_menu, 'u')) {
            return redirect('/admin/PermissionDenined');
        }
        $data['currentMenu'] = Menu::where('url', $this->current_menu)->first();
        $data['SidebarMenus'] = Menu::Active()->get();
        $data['Customers'] = Customer::orderBy('company_name')->get();
        $data['Admins'] = AdminUser::orderBy('nickname')->get();
        $data['creditNote'] = CreditNote::with('items')->findOrFail($id);
        $data['suggested_doc_no'] = $data['creditNote']->doc_no;
        $data['isEdit'] = true;

        return view('admin.CreditNote.credit_note_form', $data);
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
        $validator = Validator::make($request->all(), [
            'doc_type' => 'required|in:credit,debit',
            'doc_date' => 'required|date',
            'customer_id' => 'nullable',
            'company_name' => 'required|string|max:191',
        ], [], ['company_name' => 'ชื่อบริษัท', 'doc_date' => 'วันที่']);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'title' => 'ข้อมูลไม่ถูกต้อง', 'content' => $validator->errors()->first()]);
        }

        DB::beginTransaction();
        try {
            $cn = $id ? CreditNote::findOrFail($id) : new CreditNote();

            // เลขเอกสาร + สกุลเงิน (ตาม PI แถวแรกที่เลือก)
            $year = (int) date('Y', strtotime($request->doc_date));
            $type = $request->doc_type === 'debit' ? 'debit' : 'credit';
            if (!$id || $cn->doc_type !== $type || (int) $cn->doc_year !== $year) {
                // สร้างเลขใหม่เมื่อสร้างใหม่ หรือเปลี่ยนชนิด/ปี
                [$docNo, $run] = $this->genDocNo($type, $year);
                $cn->doc_no = $docNo;
                $cn->run_no = $run;
                $cn->doc_year = $year;
            }

            $currencyId = $this->resolveCurrencyFromItems($request->input('pi_id', []));

            $cn->doc_type      = $type;
            $cn->doc_date      = $request->doc_date;
            $cn->customer_id   = $request->customer_id ?: null;
            $cn->company_name  = $request->company_name;
            $cn->address       = $request->address;
            $cn->address2      = $request->address2;
            $cn->country       = $request->country;
            $cn->phone         = $request->phone;
            $cn->tax_id        = $request->tax_id;
            $cn->contact_name  = $request->contact_name;
            $cn->refer         = $request->refer;
            $cn->currency_id   = $currencyId;
            $cn->reason        = $request->reason;
            $cn->authorized_by = $request->authorized_by ?: null;
            if (!$id) {
                $cn->created_by = optional(Auth::guard('admin')->user())->id;
            }
            $cn->save();

            // รายการ
            CreditNoteItem::where('credit_note_id', $cn->id)->delete();
            $piProductIds = (array) $request->input('pi_product_id', []);
            $piIds        = (array) $request->input('pi_id', []);
            $invoiceNos   = (array) $request->input('invoice_no', []);
            $descs        = (array) $request->input('description', []);
            $partNos      = (array) $request->input('part_no', []);
            $qtys         = (array) $request->input('qty', []);
            $prices       = (array) $request->input('unit_price', []);
            $total = 0.0; $seq = 1;
            foreach ($piProductIds as $i => $pipId) {
                $qty   = (float) str_replace(',', '', (string) ($qtys[$i] ?? 0));
                $price = (float) str_replace(',', '', (string) ($prices[$i] ?? 0));
                $desc  = trim((string) ($descs[$i] ?? ''));
                if ($desc === '' && !$pipId) {
                    continue;
                }
                $amount = $qty * $price;
                $total += $amount;
                CreditNoteItem::create([
                    'credit_note_id' => $cn->id,
                    'seq'            => $seq++,
                    'pi_id'          => $piIds[$i] ?? null,
                    'pi_product_id'  => $pipId ?: null,
                    'invoice_no'     => $invoiceNos[$i] ?? null,
                    'part_no'        => $partNos[$i] ?? null,
                    'description'    => $desc ?: null,
                    'qty'            => $qty,
                    'unit_price'     => $price,
                    'amount'         => $amount,
                ]);
            }
            $cn->total = $total;
            $cn->save();

            DB::commit();
            return response()->json([
                'status' => 1, 'title' => 'สำเร็จ',
                'content' => 'บันทึก '.$cn->doc_no.' เรียบร้อย', 'id' => $cn->id,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 0, 'title' => 'เกิดข้อผิดพลาด', 'content' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        if (!Help::CheckPermissionMenu($this->current_menu, 'd')) {
            return response()->json(['status' => 0, 'content' => 'Permission denied'], 403);
        }
        DB::beginTransaction();
        try {
            CreditNoteItem::where('credit_note_id', $id)->delete();
            CreditNote::where('id', $id)->delete();
            DB::commit();
            return response()->json(['status' => 1, 'title' => 'สำเร็จ', 'content' => 'ลบแล้ว']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 0, 'title' => 'ผิดพลาด', 'content' => $e->getMessage()]);
        }
    }

    /* ===================== AJAX ===================== */
    /** Invoice (PI) ของลูกค้าที่เลือก */
    public function customerInvoices(Request $request)
    {
        $cid = (int) $request->query('customer_id');
        $items = ProformaInvoice::where('customer_id', $cid)
            ->leftJoin('currencies', 'proforma_invoices.currency_id', '=', 'currencies.id')
            ->orderByDesc('proforma_invoices.id')
            ->get([
                'proforma_invoices.id', 'proforma_invoices.doc_no', 'proforma_invoices.customer_po',
                'proforma_invoices.currency_id', 'currencies.symbol as currency_symbol',
            ]);

        return response()->json(['items' => $items]);
    }

    /** สินค้าใน PI ที่เลือก (ดึงราคา unit price อัตโนมัติ) */
    public function invoiceProducts(Request $request)
    {
        $piId = (int) $request->query('pi_id');
        $pi = ProformaInvoice::with('currency')->find($piId);
        $symbol = optional(optional($pi)->currency)->symbol ?? '';
        $rows = ProformaInvoiceProduct::where('pi_id', $piId)
            ->leftJoin('products', 'proforma_invoice_products.product_id', '=', 'products.id')
            ->orderBy('proforma_invoice_products.seq')
            ->get([
                'proforma_invoice_products.id as pi_product_id',
                'proforma_invoice_products.product_id',
                'proforma_invoice_products.part_no',
                'proforma_invoice_products.detail_eng',
                'proforma_invoice_products.detail_thai',
                'proforma_invoice_products.qty',
                'proforma_invoice_products.price_per_item',
                'products.code as product_code',
                'products.name_en',
            ]);

        return response()->json(['currency_symbol' => $symbol, 'items' => $rows]);
    }

    public function suggestDocNoAjax(Request $request)
    {
        $type = $request->query('doc_type') === 'debit' ? 'debit' : 'credit';
        $year = (int) ($request->query('year') ?: date('Y'));
        return response()->json(['doc_no' => $this->suggestDocNo($type, $year)]);
    }

    /* ===================== PDF ===================== */
    public function pdf($id)
    {
        if (!Help::CheckPermissionMenu($this->current_menu, 'r')) {
            return redirect('/admin/PermissionDenined');
        }
        $cn = CreditNote::with(['items', 'currency', 'customer', 'authorizedBy'])->findOrFail($id);
        // $settings (logo) ถูกแชร์ global ผ่าน AppServiceProvider แล้ว
        $pdf = \PDF::loadView('admin.CreditNote.credit_note_pdf', ['cn' => $cn], [], [
            'format' => 'A4', 'margin_top' => 8, 'margin_bottom' => 8, 'margin_left' => 8, 'margin_right' => 8,
        ]);

        return $pdf->stream('CreditNote_'.str_replace(['/', '\\'], '-', $cn->doc_no).'.pdf');
    }

    /* ===================== Helpers ===================== */
    private function suggestDocNo(string $type, int $year): string
    {
        [$docNo] = $this->genDocNo($type, $year);
        return $docNo;
    }

    /** สร้างเลข C/N-XXX/YYYY หรือ D/N-XXX/YYYY (รันแยกตามชนิด+ปี) */
    private function genDocNo(string $type, int $year): array
    {
        $prefix = $type === 'debit' ? 'D/N' : 'C/N';
        $last = CreditNote::where('doc_type', $type)->where('doc_year', $year)->max('run_no');
        $run = ((int) $last) + 1;
        $docNo = $prefix.'-'.str_pad((string) $run, 3, '0', STR_PAD_LEFT).'/'.$year;

        return [$docNo, $run];
    }

    private function resolveCurrencyFromItems(array $piIds): ?int
    {
        foreach ($piIds as $pid) {
            if ($pid) {
                $cur = ProformaInvoice::where('id', $pid)->value('currency_id');
                if ($cur) {
                    return (int) $cur;
                }
            }
        }
        return null;
    }
}
