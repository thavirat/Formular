<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ApprovedProformaInvoiceListTrait;
use App\Models\AdminUser;
use App\Models\Customer;
use App\Models\Menu;
use Help;
use Illuminate\Http\Request;

class FactoryPaymentController extends AdminController
{
    use ApprovedProformaInvoiceListTrait;

    public function __construct()
    {
        $this->current_menu = 'FactoryPayment';
    }

    public function index()
    {
        $permission = Help::CheckPermissionMenu($this->current_menu, 'r');
        if (!$permission) {
            return redirect('/admin/PermissionDenined');
        }
        $data['currentMenu'] = Menu::where('url', $this->current_menu)->first();
        if (!$data['currentMenu']) {
            abort(404, 'Menu FactoryPayment not found. Add menu with url FactoryPayment.');
        }
        $data['SidebarMenus'] = Menu::Active()->get();
        $data['admins'] = AdminUser::orderBy('nickname')->get();
        $data['Customers'] = Customer::orderBy('company_name')->get();
        $data['listTableId'] = 'tableFactoryPayment';
        $data['listsPath'] = 'FactoryPayment/Lists';

        return view('admin.ApprovedProformaInvoice.list', $data);
    }

    public function lists(Request $request)
    {
        return $this->approvedProformaInvoiceLists($request);
    }
}
