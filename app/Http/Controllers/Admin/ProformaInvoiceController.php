<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\ProformaInvoice;
use App\Models\ProformaInvoiceProduct;
use App\Models\Customer;
use App\Models\Incoterm;
use App\Models\CreditPayment;
use App\Models\Quotation;
use App\Models\Currency;
use Auth;
use DataTables;
use Help;
use DB;
use Validator;
use Storage;
class ProformaInvoiceController extends AdminController
{
    public $current_menu;

    public function __construct() {
        $this->current_menu = 'ProformaInvoice';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permission = Help::CheckPermissionMenu($this->current_menu , 'r');
        if(!$permission){
            return redirect('/admin/PermissionDenined');
        }
        $data['currentMenu'] = Menu::where('url',$this->current_menu)->first();
        $data['SidebarMenus'] = Menu::Active()->get();
        return view('admin.ProformaInvoice.proforma_invoice',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $id = $request->quotation_id;
        $data['currentMenu'] = Menu::where('url', $this->current_menu)->first();
        $data['Customers'] = Customer::orderBy('company_name')->get();
        $data['Incoterms'] = Incoterm::orderBy('code')->get();
        $data['Currencies'] = Currency::orderBy('name')->get();
        $data['CreditPayments'] = CreditPayment::orderBy('name')->get();
        $data['Quotation'] = Quotation::with(['products'=>function($q){
            $q->leftJoin('products' , 'quotation_products.product_id', '=', 'products.id');
            $q->select(
                'quotation_products.*'
                , 'products.code as part_no'
            );
        }])
        ->leftJoin('currencies' , 'quotations.currency_id', '=', 'currencies.id')
        ->leftJoin('credit_payments' , 'quotations.credit_payment_id', '=', 'credit_payments.id')
        ->select(
            'quotations.*'
            , 'currencies.name as currency_name'
            , 'currencies.symbol'
            , 'credit_payments.name as credit_payment_name'
        )
        ->find($id);

        return view('admin.ProformaInvoice.proforma_invoice_create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $date_now = date('Y-m-d');
            $year_month = date('ym');
            $last_run = ProformaInvoice::where('doc_no', 'like', "PI$year_month%")
                                        ->orderBy('run_no', 'desc')
                                        ->first();
            $run_no = $last_run ? $last_run->run_no + 1 : 1;
            $doc_no = "PI" . $year_month . str_pad($run_no, 4, '0', STR_PAD_LEFT);

            $pi = new ProformaInvoice();
            $pi->quotation_id      = $request->quotation_id;
            $pi->status_id         = 1;
            $pi->customer_id       = $request->customer_id;
            $pi->incoterm_id       = $request->incoterm_id;
            $pi->currency_id       = $request->currency_id;
            $pi->credit_payment_id = $request->credit_payment_id;
            $pi->doc_no            = $doc_no;
            $pi->doc_date          = $request->doc_date;
            $pi->run_no            = $run_no;
            $pi->contact_name      = $request->contact_name;
            $pi->company_name      = $request->company_name;
            $pi->tax_id            = $request->tax_id;
            $pi->address           = $request->address;
            $pi->fax_no            = $request->fax_no;
            $pi->subtotal          = str_replace(',', '', $request->grand_total);
            $pi->total             = str_replace(',', '', $request->grand_total);
            $pi->created_by        = Auth::guard('admin')->user()->id;
            $pi->save();

            if ($request->product && is_array($request->product)) {
                foreach ($request->product as $key => $product_id) {
                    if (empty($product_id)) continue;

                    $item = new ProformaInvoiceProduct();
                    $item->pi_id          = $pi->id;
                    $item->product_id     = $product_id;
                    $item->drawing        = $request->drawing[$key] ?? null;
                    $item->cus_code       = $request->customer_code[$key] ?? null;
                    $item->detail_eng     = $request->description[$key] ?? null;
                    $item->qty            = $request->qty[$key] ?? 0;
                    $item->price_per_item = $request->unit_price[$key] ?? 0;
                    $item->total_price    = $request->amount[$key] ?? 0;
                    $item->save();
                }
            }

            DB::commit();

            return response()->json([
                'status' => 1,
                'title'  => 'สำเร็จ',
                'content' => 'บันทึกใบ Proforma Invoice เลขที่ ' . $doc_no . ' เรียบร้อยแล้ว',
                'id'     => $pi->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 0,
                'title'   => 'เกิดข้อผิดพลาด',
                'content' => $e->getMessage() . ' Line: ' . $e->getLine()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ProformaInvoice = ProformaInvoice::find($id);

                $return['status'] = 1;
                $return['title'] = 'Get ProformaInvoice';
                $return['content'] = $ProformaInvoice;
                return $return;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['SidebarMenus'] = Menu::Active()->get();
        $data['currentMenu'] = Menu::where('url',$this->current_menu)->first();
        return view('admin.ProformaInvoice.proforma_invoice_edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $permission = Help::CheckPermissionMenu($this->current_menu , 'u');
        if(!$permission){
            return redirect('/admin/PermissionDenined');
        }
        $validator = Validator::make($request->all(), [
        ]);
        if (!$validator->fails()) {
            $doc_no = $request->input('doc_no');

            DB::beginTransaction();
            try {
                $ProformaInvoice = ProformaInvoice::find($id);
                $ProformaInvoice->doc_no = $doc_no;
                $ProformaInvoice->save();
                DB::commit();
                $return['status'] = 1;
                $return['title'] = __('messages.save');
                $return['content'] = __('messages.success');
            } catch (Exception $e) {
                DB::rollBack();
                $return['status'] = 0;
                $return['title'] = __('messages.error');
                $return['content'] = $e->getMessage();
            }
        }else{
            $failedRules = $validator->failed();
            $return['content'] = '';

        }
        return $return;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $ProformaInvoice = ProformaInvoice::find($id);

            ProformaInvoice::where('id' , $id)->delete();

            DB::commit();
            $return['status'] = 1;
            $return['content'] = __('messages.success');
        }catch (Exception $e) {
            DB::rollBack();
            $return['status'] = 0;
            $return['title'] = __('messages.error');
            $return['content'] = $e->getMessage();
        }
        $return['title'] = __('messages.delete_data');
        return $return;
    }

    /**
     * Show Data With Datatable from storage.
     *
     * @param   \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\Response
     */
    public function report(){
        $result = ProformaInvoice::select()->orderByDesc('id');
        return $result;
    }

    public function lists(Request $request)
    {
        $result = $this->report($request);
        $lang = config('app.locale');

        return DataTables::of($result)
        ->addColumn('action', function($rec) use ($lang){
            $btnEdit = '<button class="btn btn-xs btn-warning btn-edit" data-id="'.$rec->id.'" data-toggle="tooltip" data-placement="top" title="แก้ไข">
            <i class="fa fa-edit"></i>
            </button> ';
            $btnDelete = '<button class="btn btn-xs btn-danger btn-delete" data-id="'.$rec->id.'" data-toggle="tooltip" data-placement="top" title="ลบ">
            <i class="fa fa-trash"></i>
            </button> ';
            $update = Help::CheckPermissionMenu($this->current_menu , 'u');
            $str = '';
            if($update){
                $str.=$btnEdit;
            }
            $delete = Help::CheckPermissionMenu($this->current_menu , 'd');
            if($delete){
                $str.=$btnDelete;
            }

            $str .= '<a href="'.url('admin/'.$lang.'/ProformaInvoice/pdfFactory?pi_id='.$rec->id).'"
                class="btn btn-outline-orange btn-h-light-orange btn-a-light-orange border-b-2"
                title="ออกใบ PI ส่งโรงงาน">
                <i class="fa fa-factory"></i>
             </a>';

            return $str;
        })
        ->addIndexColumn()
        ->make(true);
    }

    public function export_excel(Request $request){
        $result = $this->report($request);
        $data['result'] = $result->get();

        \Excel::create('รายงาน ProformaInvoice ', function ($excel) use ($data) {
            $excel->sheet('รายงาน ProformaInvoice', function ($sheet) use ($data) {
                $sheet->loadView('admin.ProformaInvoice.proforma_invoice_export_excel', $data);
            });
        })->export('xlsx');
    }

    public function export_print(Request $request){
        $result = $this->report($request);
        $data['result'] = $result->get();


        $pdf = \PDF::loadView('admin.ProformaInvoice.proforma_invoice_export_print', $data);
        return $pdf->stream('ProformaInvoice.pdf');
    }

    public function export_pdf(Request $request){
        $result = $this->report($request);
        $data['result'] = $result->get();

        return view('admin.ProformaInvoice.proforma_invoice_export_pdf', $data);
    }

    public function pdfFactory(Request $request)
    {
        $id = $request->input('pi_id');
        $pi = ProformaInvoice::with(['Products'=>function($q){

        }])->findOrFail($id);

        $data = [
            'pi' => $pi,
            'doc_no' => $pi->doc_no,
            'shipping_marks' => [
                'customer' => $pi->company_name,
                'destination' => 'AUSTRALIA',
                'container' => 'C/NO.1-UP',
            ],
            'remarks' => [
                '1. CDS,PFC, CO,LM บรรจุกล่องแบบ "A" และไม่ต้องติดสติ๊กเกอร์ FORMULA',
                '2. CDS,PFC ทุกรายการ พิมพ์ JAY AIR ตามแบบที่วางไว้บนกล่อง',
                '3. ทุกรายการให้ติดสติ๊กเกอร์ JAY AIR พิมพ์ PART NO. และบาร์โค้ดลูกค้า',
                '4. PFC ทุกรุ่นที่เป็นสี BRONZE ไม่ต้องทำการพ่นสี',
                '5. สินค้าตู้แอร์ใช้กล่องแบบ "N"',
            ],
            'sale_rep' => $pi->creator->name ?? 'PATRAPRON YARACH'
        ];


        $pdf = \PDF::loadView('admin.ProformaInvoice.pdf_factory', $data);

        return $pdf->stream($pi->doc_no . '_factory.pdf');
    }

}
