<?php

namespace App\Http\Controllers\Admin;

use App\Models\Customer;
use App\Models\Menu;
use App\Models\ProformaInvoice;
use DataTables;
use Help;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * รายงานลูกหนี้การค้า (AR) — เฟส 3
 * คำนวณจาก PI (ยอดที่ต้องชำระ) เทียบกับ customer_payments (ยอดที่รับแล้ว)
 * ครบกำหนด = doc_date + credit_day ; aging ตามจำนวนวันเกินกำหนด
 */
class AccountReceivableController extends AdminController
{
    public $current_menu = 'AccountReceivable';

    public function index()
    {
        if (!Help::CheckPermissionMenu($this->current_menu, 'r')) {
            return redirect('/admin/PermissionDenined');
        }
        $data['currentMenu'] = Menu::where('url', $this->current_menu)->first();
        $data['SidebarMenus'] = Menu::Active()->get();
        $data['route_locale'] = config('app.locale');
        $data['Customers'] = Customer::orderBy('company_name')->get();
        $data['Regions'] = ['ASIA', 'MIDDLEAST', 'EUROPE', 'CHAMP', 'OTHER'];
        $data['summary'] = $this->summary($this->baseQuery(new Request()));

        return view('admin.AccountReceivable.account_receivable', $data);
    }

    private function baseQuery(Request $request)
    {
        $pfx = DB::getTablePrefix();
        $paid = "(SELECT COALESCE(SUM(cp.amount),0) FROM {$pfx}customer_payments cp WHERE cp.pi_id = {$pfx}proforma_invoices.id)";

        $q = ProformaInvoice::query()
            ->leftJoin('customers', 'proforma_invoices.customer_id', '=', 'customers.id')
            ->leftJoin('currencies', 'proforma_invoices.currency_id', '=', 'currencies.id')
            ->select(
                'proforma_invoices.id', 'proforma_invoices.doc_no', 'proforma_invoices.doc_date',
                'proforma_invoices.total', 'proforma_invoices.customer_po', 'proforma_invoices.company_name',
                'customers.company_name as cust_name', 'customers.country', 'customers.region', 'customers.credit_day',
                'currencies.symbol as cur_symbol'
            )
            ->selectRaw("$paid as paid_amount")
            ->where('proforma_invoices.total', '>', 0);

        if ($request->filled('customer_id') && $request->customer_id !== 'all') {
            $q->where('proforma_invoices.customer_id', $request->customer_id);
        }
        if ($request->filled('region') && $request->region !== 'all') {
            $q->where('customers.region', $request->region);
        }
        if ($request->filled('start_date')) {
            $q->where('proforma_invoices.doc_date', '>=', date('Y-m-d', strtotime($request->start_date)));
        }
        if ($request->filled('end_date')) {
            $q->where('proforma_invoices.doc_date', '<=', date('Y-m-d', strtotime($request->end_date)));
        }
        // ค่าเริ่มต้น: แสดงเฉพาะที่ยังค้างชำระ
        if ($request->input('status', 'outstanding') === 'outstanding') {
            $q->whereRaw("{$pfx}proforma_invoices.total - $paid > 0.009");
        } elseif ($request->input('status') === 'overdue') {
            $q->whereRaw("{$pfx}proforma_invoices.total - $paid > 0.009")
              ->whereRaw("DATE_ADD({$pfx}proforma_invoices.doc_date, INTERVAL COALESCE({$pfx}customers.credit_day,0) DAY) < CURDATE()");
        }

        return $q;
    }

    /** ข้อมูลสรุป (ยอดค้าง/เกินกำหนด/ตาม aging) */
    private function summary($query)
    {
        $rows = (clone $query)->get();
        $sum = ['count' => 0, 'outstanding' => 0.0, 'overdue' => 0.0,
                'b_current' => 0.0, 'b30' => 0.0, 'b60' => 0.0, 'b90' => 0.0, 'b90p' => 0.0];
        foreach ($rows as $r) {
            $c = $this->calc($r);
            $sum['count']++;
            $sum['outstanding'] += $c['outstanding'];
            if ($c['days'] > 0) {
                $sum['overdue'] += $c['outstanding'];
            }
            $sum[$c['bucket_key']] += $c['outstanding'];
        }

        return $sum;
    }

    /** คำนวณ due date / วันเกิน / aging bucket ต่อแถว */
    private function calc($r): array
    {
        $total = (float) $r->total;
        $paid = (float) $r->paid_amount;
        $outstanding = round($total - $paid, 2);
        $credit = (int) ($r->credit_day ?? 0);
        $due = $r->doc_date ? Carbon::parse($r->doc_date)->addDays($credit) : null;
        $days = $due ? Carbon::today()->diffInDays($due, false) * -1 : 0; // >0 = เกินกำหนดกี่วัน
        if ($days <= 0) {
            $bucket = 'ยังไม่ครบกำหนด'; $key = 'b_current';
        } elseif ($days <= 30) {
            $bucket = '1-30 วัน'; $key = 'b30';
        } elseif ($days <= 60) {
            $bucket = '31-60 วัน'; $key = 'b60';
        } elseif ($days <= 90) {
            $bucket = '61-90 วัน'; $key = 'b90';
        } else {
            $bucket = 'เกิน 90 วัน'; $key = 'b90p';
        }

        return compact('total', 'paid', 'outstanding', 'due', 'days', 'bucket') + ['bucket_key' => $key];
    }

    public function lists(Request $request)
    {
        $q = $this->baseQuery($request)
            ->orderBy('proforma_invoices.doc_date', 'asc')
            ->orderBy('proforma_invoices.doc_no', 'asc');

        return DataTables::of($q)
            ->addIndexColumn()
            ->addColumn('customer', fn ($r) => e($r->cust_name ?: $r->company_name ?: '-')
                . ($r->country ? '<div class="text-80 text-muted">'.e($r->country).($r->region ? ' · '.e($r->region) : '').'</div>' : ''))
            ->addColumn('invoice', fn ($r) => '<div class="font-bolder">'.e($r->doc_no).'</div>'
                . '<div class="text-80 text-muted">'.($r->doc_date ? \Carbon\Carbon::parse($r->doc_date)->format('d/m/Y') : '-').'</div>')
            ->addColumn('credit_term', fn ($r) => ((int) $r->credit_day).' วัน')
            ->addColumn('due_date', function ($r) {
                $c = $this->calc($r);
                return $c['due'] ? $c['due']->format('d/m/Y') : '-';
            })
            ->addColumn('days_overdue', function ($r) {
                $c = $this->calc($r);
                if ($c['days'] > 0) {
                    return '<span class="text-danger font-bolder">'.$c['days'].'</span>';
                }
                return '<span class="text-success">'.abs($c['days']).' วัน</span>';
            })
            ->addColumn('aging', function ($r) {
                $c = $this->calc($r);
                $cls = $c['days'] <= 0 ? 'badge-success' : ($c['days'] <= 30 ? 'badge-warning' : 'badge-danger');
                return '<span class="badge '.$cls.'">'.$c['bucket'].'</span>';
            })
            ->addColumn('total_amt', fn ($r) => e($r->cur_symbol).' '.number_format((float) $r->total, 2))
            ->addColumn('paid_amt', fn ($r) => e($r->cur_symbol).' '.number_format((float) $r->paid_amount, 2))
            ->addColumn('outstanding_amt', function ($r) {
                $c = $this->calc($r);
                return '<span class="font-bolder text-danger-d1">'.e($r->cur_symbol).' '.number_format($c['outstanding'], 2).'</span>';
            })
            ->rawColumns(['customer', 'invoice', 'days_overdue', 'aging', 'outstanding_amt'])
            ->make(true);
    }

    public function summaryData(Request $request)
    {
        return response()->json($this->summary($this->baseQuery($request)));
    }
}
