<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ApprovedProformaInvoiceListTrait;
use App\Models\AdminUser;
use App\Models\BankAccount;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Menu;
use App\Models\CustomerPayment;
use App\Models\PaymentMethod;
use App\Models\ProformaInvoice;
use Help;
use Illuminate\Http\Request;

class CustomerReceiptController extends AdminController
{
    use ApprovedProformaInvoiceListTrait;

    public function __construct()
    {
        $this->current_menu = 'CustomerReceipt';
    }

    public function index()
    {
        $permission = Help::CheckPermissionMenu($this->current_menu, 'r');
        if (!$permission) {
            return redirect('/admin/PermissionDenined');
        }
        $data['currentMenu'] = Menu::where('url', $this->current_menu)->first();
        if (!$data['currentMenu']) {
            abort(404, 'Menu CustomerReceipt not found. Add menu with url CustomerReceipt.');
        }
        $data['SidebarMenus'] = Menu::Active()->get();
        $data['admins'] = AdminUser::orderBy('nickname')->get();
        $data['Customers'] = Customer::orderBy('company_name')->get();
        $data['listTableId'] = 'tableCustomerReceipt';
        $data['listsPath'] = 'CustomerReceipt/Lists';

        return view('admin.ApprovedProformaInvoice.list', $data);
    }

    public function lists(Request $request)
    {
        return $this->approvedProformaInvoiceLists($request);
    }

    /**
     * หน้าบันทึกชำระเงินลูกค้า (จาก PI ที่อนุมัติแล้ว)
     */
    public function recordPayment($id)
    {
        $permission = Help::CheckPermissionMenu($this->current_menu, 'r');
        if (!$permission) {
            return redirect('/admin/PermissionDenined');
        }
        $pi = ProformaInvoice::with(['currency', 'customer'])
            ->where('id', $id)
            ->where('status_id', 3)
            ->first();
        if (!$pi) {
            abort(404);
        }
        $data['currentMenu'] = Menu::where('url', $this->current_menu)->first();
        $data['SidebarMenus'] = Menu::Active()->get();
        $data['pi'] = $pi;
        $data['BankAccounts'] = BankAccount::orderBy('account_no')->get();
        $data['PaymentMethods'] = PaymentMethod::orderBy('name_en')->get();
        $data['Currencies'] = Currency::orderBy('name')->get();
        $data['piPaidTotal'] = (float) CustomerPayment::where('pi_id', $pi->id)->sum('amount');
        $data['piPaidTotalBath'] = (float) CustomerPayment::where('pi_id', $pi->id)->sum('amount_bath');
        $data['canAddCustomerPayment'] = Help::CheckPermissionMenu('CustomerPayment', 'c')
            || Help::CheckPermissionMenu('CustomerReceipt', 'c');

        return view('admin.CustomerReceipt.record_payment', $data);
    }

    /**
     * ยอดชำระรวมของ PI (สำหรับรีเฟรชหัวข้อหลังบันทึก)
     */
    public function paymentTotals($id)
    {
        if (!Help::CheckPermissionMenu($this->current_menu, 'r')) {
            return response()->json(['ok' => false], 403);
        }
        if (!ProformaInvoice::where('id', $id)->where('status_id', 3)->exists()) {
            return response()->json(['ok' => false], 404);
        }
        $paidAmount = (float) CustomerPayment::where('pi_id', $id)->sum('amount');
        $paidBath = (float) CustomerPayment::where('pi_id', $id)->sum('amount_bath');

        return response()->json([
            'ok' => true,
            'paid_amount' => $paidAmount,
            'paid_bath' => $paidBath,
        ]);
    }
}
