<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ExportOrderReportExport;
use App\Models\Menu;
use Carbon\Carbon;
use Help;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

/**
 * เมนูรายงาน (Reports) — เฟส 2
 * ยอดออก (Export/Shipped) สร้างจาก PackingForm (= shipment record) + ยอดจาก PI ที่ผูก
 */
class ReportController extends AdminController
{
    public $current_menu = 'Report';

    public function index()
    {
        if (!Help::CheckPermissionMenu($this->current_menu, 'r')) {
            return redirect('/admin/PermissionDenined');
        }
        $data['currentMenu'] = Menu::where('url', $this->current_menu)->first();
        $data['SidebarMenus'] = Menu::Active()->get();
        $data['route_locale'] = config('app.locale');

        return view('admin.Report.report_index', $data);
    }

    public function exportOrder()
    {
        if (!Help::CheckPermissionMenu($this->current_menu, 'r')) {
            return redirect('/admin/PermissionDenined');
        }
        $data['currentMenu'] = Menu::where('url', $this->current_menu)->first();
        $data['SidebarMenus'] = Menu::Active()->get();
        $data['route_locale'] = config('app.locale');
        $data['Regions'] = ['ASIA', 'MIDDLEAST', 'EUROPE', 'CHAMP', 'OTHER'];
        $data['years'] = range((int) date('Y'), (int) date('Y') - 4);

        return view('admin.Report.export_order', $data);
    }

    /** ดึงแถวยอดออก (PackingForm + amount/currency/region จาก PI ที่ผูก) ตามฟิลเตอร์ */
    private function fetchRows(Request $request)
    {
        $pfx = DB::getTablePrefix();
        $amt = "(SELECT COALESCE(SUM(pip.total_price),0) FROM {$pfx}packing_form_details pfd
                 JOIN {$pfx}proforma_invoice_products pip ON pip.id = pfd.pi_product_id
                 WHERE pfd.packing_form_id = {$pfx}packing_forms.id)";
        $cur = "(SELECT c.symbol FROM {$pfx}packing_form_details pfd
                 JOIN {$pfx}proforma_invoice_products pip ON pip.id = pfd.pi_product_id
                 JOIN {$pfx}proforma_invoices pi ON pi.id = pip.pi_id
                 JOIN {$pfx}currencies c ON c.id = pi.currency_id
                 WHERE pfd.packing_form_id = {$pfx}packing_forms.id LIMIT 1)";
        $reg = "(SELECT cu.region FROM {$pfx}packing_form_details pfd
                 JOIN {$pfx}proforma_invoice_products pip ON pip.id = pfd.pi_product_id
                 JOIN {$pfx}proforma_invoices pi ON pi.id = pip.pi_id
                 JOIN {$pfx}customers cu ON cu.id = pi.customer_id
                 WHERE pfd.packing_form_id = {$pfx}packing_forms.id AND cu.region IS NOT NULL AND cu.region <> '' LIMIT 1)";

        $q = DB::table('packing_forms')
            ->select('id', 'doc_no', 'invoice_no', 'doc_date', 'sailing_date', 'customer_name', 'country',
                'cubic_meter', 'weight_nw', 'weight_gw', 'qty', 'pkg')
            ->selectRaw("$amt as ship_amount")
            ->selectRaw("$cur as ship_currency")
            ->selectRaw("$reg as region");

        if ($request->filled('year')) {
            $q->whereYear('doc_date', (int) $request->year);
        }
        if ($request->filled('start_date')) {
            $q->where('doc_date', '>=', date('Y-m-d', strtotime($request->start_date)));
        }
        if ($request->filled('end_date')) {
            $q->where('doc_date', '<=', date('Y-m-d', strtotime($request->end_date)));
        }
        if ($request->filled('customer')) {
            $q->where('customer_name', 'like', '%'.$request->customer.'%');
        }

        $rows = $q->orderBy('doc_date', 'asc')->orderBy('id', 'asc')->get();

        // ฟิลเตอร์ภูมิภาค (post-fetch) — region มาจาก subquery
        if ($request->filled('region') && $request->region !== 'all') {
            $rows = $rows->filter(fn ($r) => strtoupper((string) $r->region) === strtoupper($request->region))->values();
        }

        return $rows;
    }

    public function exportOrderData(Request $request)
    {
        $rows = $this->fetchRows($request);

        // สรุป
        $summary = [
            'count' => $rows->count(),
            'cbm' => round($rows->sum('cubic_meter'), 2),
            'weight' => round($rows->sum('weight_gw'), 2),
            'by_currency' => [],
        ];
        foreach ($rows->groupBy(fn ($r) => $r->ship_currency ?: '-') as $sym => $g) {
            $summary['by_currency'][] = ['currency' => $sym, 'amount' => round($g->sum('ship_amount'), 2)];
        }

        // รายเดือน (CBM + จำนวน)
        $monthly = [];
        foreach ($rows->groupBy(fn ($r) => $r->doc_date ? Carbon::parse($r->doc_date)->format('Y-m') : '-') as $ym => $g) {
            $monthly[] = ['month' => $ym, 'cbm' => round($g->sum('cubic_meter'), 2), 'count' => $g->count()];
        }
        usort($monthly, fn ($a, $b) => strcmp($a['month'], $b['month']));

        // ตามภูมิภาค (CBM + จำนวน)
        $byRegion = [];
        foreach ($rows->groupBy(fn ($r) => $r->region ?: 'ไม่ระบุ') as $rg => $g) {
            $byRegion[] = ['region' => $rg, 'cbm' => round($g->sum('cubic_meter'), 2), 'count' => $g->count()];
        }

        // แถวตาราง
        $list = $rows->map(function ($r) {
            return [
                'invoice_no' => $r->invoice_no ?: $r->doc_no,
                'doc_date' => $r->doc_date ? Carbon::parse($r->doc_date)->format('d/m/Y') : '',
                'etd' => $r->sailing_date ? Carbon::parse($r->sailing_date)->format('d/m/Y') : '',
                'customer' => $r->customer_name,
                'country' => $r->country,
                'region' => $r->region ?: '-',
                'cbm' => (float) $r->cubic_meter,
                'weight' => (float) $r->weight_gw,
                'amount' => (float) $r->ship_amount,
                'currency' => $r->ship_currency ?: '',
            ];
        })->values();

        return response()->json(['summary' => $summary, 'monthly' => $monthly, 'byRegion' => $byRegion, 'rows' => $list]);
    }

    public function exportOrderExcel(Request $request)
    {
        $rows = $this->fetchRows($request);
        $filename = 'ExportOrder_'.date('Ymd_His').'.xlsx';

        return Excel::download(new ExportOrderReportExport($rows), $filename);
    }
}
