<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Models\ContactChannel;
use App\Models\ProformaInvoice;
use DataTables;
use Help;
use Illuminate\Http\Request;
trait ApprovedProformaInvoiceListTrait
{
    protected function approvedProformaInvoiceReport(Request $request)
    {
        $result = ProformaInvoice::with(['Comments' => function ($q) {
            $q->leftJoin('admin_users', 'admin_users.id', 'comments.created_by');
            $q->leftJoin('contact_channels', 'contact_channels.id', 'comments.channel_id');
            $q->select(
                'comments.*',
                'admin_users.nickname as created_by_name',
                'contact_channels.name as channel_name'
            )->orderBy('comments.created_at', 'desc');
        }])
            ->leftJoin('admin_users', 'admin_users.id', '=', 'proforma_invoices.created_by')
            ->leftJoin('admin_users as send_approve', 'proforma_invoices.send_approve_by', '=', 'send_approve.id')
            ->leftJoin('admin_users as approve', 'proforma_invoices.approve_by', '=', 'approve.id')
            ->leftJoin('proforma_invoice_statuses', 'proforma_invoices.status_id', '=', 'proforma_invoice_statuses.id')
            ->select(
                'proforma_invoices.*',
                'admin_users.nickname as created_by_name',
                'send_approve.nickname as send_approve_name',
                'approve.nickname as approve_name',
                'proforma_invoice_statuses.name as status_name'
            )
            ->where('proforma_invoices.status_id', 3); // 3 = อนุมัติ

        if ($request->has('start_date') && $request->start_date != '') {
            $result->where('proforma_invoices.doc_date', '>=', date('Y-m-d', strtotime($request->start_date)));
        }
        if ($request->has('end_date') && $request->end_date != '') {
            $result->where('proforma_invoices.doc_date', '<=', date('Y-m-d', strtotime($request->end_date)));
        }
        if ($request->has('admin_id') && $request->admin_id != 'all' && $request->admin_id !== null && $request->admin_id !== '') {
            $result->where('proforma_invoices.created_by', '=', $request->admin_id);
        }
        if ($request->has('customer_id') && $request->customer_id != 'all' && $request->customer_id !== null && $request->customer_id !== '') {
            $result->where('proforma_invoices.customer_id', '=', $request->customer_id);
        }

        return $result->orderByDesc('proforma_invoices.id');
    }

    public function approvedProformaInvoiceLists(Request $request)
    {
        $result = $this->approvedProformaInvoiceReport($request);
        $lang = config('app.locale');
        $channels = ContactChannel::get();
        $piMenu = 'ProformaInvoice';

        return DataTables::of($result)
            ->addColumn('doc_info', function ($rec) {
                return '<div class="text-primary-d2 font-bolder text-95">' . e($rec->doc_no) . '</div>
                        <div class="text-80 text-grey-m2"><i class="far fa-calendar-alt mr-1"></i>' . e($rec->doc_date) . '</div>';
            })
            ->addColumn('customer_info', function ($rec) {
                return '<div class="text-dark-m3 font-bold">' . e($rec->company_name) . '</div>
                        <div class="text-80 text-blue-m2"><i class="far fa-user mr-1"></i>' . e($rec->created_by_name ?? '-') . '</div>';
            })
            ->editColumn('total', function ($rec) {
                return '<span class="text-110 font-bolder text-success-d1">' . number_format($rec->total, 2) . '</span>';
            })
            ->addColumn('status_name', function ($rec) {
                $statusColor = 'success';
                $str = '<div class="text-center">
                            <span class="badge badge-lg bgc-' . $statusColor . '-l3 text-' . $statusColor . '-d2 border-1 brc-' . $statusColor . '-m3 mb-1">' . e($rec->status_name) . '</span>';
                if ((int) $rec->status_id >= 2 && !empty($rec->send_approve_date)) {
                    $str .= '<div class="text-75 text-grey-m1 mt-1" title="วันที่ส่งขออนุมัติ">
                                <i class="fa fa-paper-plane mr-1 text-purple-m2"></i>' . e($rec->send_approve_name ?? '-') . '
                                <br>' . date('d/m/Y H:i', strtotime($rec->send_approve_date)) . '
                            </div>';
                }
                if ((int) $rec->status_id == 3 && !empty($rec->approve_date)) {
                    $str .= '<div class="text-75 text-success-m1 mt-1 border-t-1 brc-grey-l4 pt-1" title="วันที่อนุมัติ">
                                <i class="fa fa-check-circle mr-1"></i>' . e($rec->approve_name ?? '-') . '
                                <br>' . date('d/m/Y H:i', strtotime($rec->approve_date)) . '
                            </div>';
                }
                $str .= '</div>';

                return $str;
            })
            ->editColumn('created_by', function ($rec) {
                return $rec->created_by_name ?? '-';
            })
            ->addColumn('comment_box', function ($rec) use ($channels) {
                $items = '';
                foreach ($rec->Comments as $comment) {
                    $items .= '
                <div class="mb-2 p-1 border-l-3 brc-success-m2 bgc-grey-l5 radius-1 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-600 text-blue-d1 text-80">' . ($comment->created_by_name ?? '-') . '</span>
                        <span class="text-70 text-grey-m1"><i class="far fa-clock mr-1 text-75"></i>' . date('d/m/Y H:i', strtotime($comment->created_at)) . '</span>
                    </div>
                    <div class="text-85 text-dark-m3 line-height-125 px-1">' . $comment->detail . '</div>
                    <div class="text-70 text-right mt-1">
                        <span class="badge badge-sm bgc-grey-l3 text-grey-d2 border-0">' . ($comment->channel_name ?? '-') . '</span>
                    </div>
                </div>';
                }

                $options = '';
                foreach ($channels as $channel) {
                    $options .= '<option value="' . $channel->id . '">' . $channel->name . '</option>';
                }

                $history = '<div class="comment-history mb-3 pr-1" style="max-height: 180px; overflow-y: auto; scrollbar-width: thin;">' . $items . '</div>';

                $inputSection = '
            <div class="comment-input-group bgc-white p-2 radius-1 border-1 brc-grey-l2 shadow-sm">
                <textarea class="form-control text-85 border-0 bgc-transparent no-resize comment-' . $rec->id . '"
                        rows="2" placeholder="พิมพ์บันทึกติดตามงาน..."></textarea>

                <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-t-1 brc-grey-l4">
                    <div class="flex-grow-1 mr-2">
                        <select class="custom-select custom-select-sm border-0 bgc-grey-l4 text-80 text-600 channel-' . $rec->id . '">
                            ' . $options . '
                        </select>
                    </div>
                    <button type="button" class="btn btn-sm btn-success px-3 btn-save-comment shadow-sm radius-2px"
                            data-id="' . $rec->id . '" data-customer-id="' . $rec->customer_id . '">
                        <i class="fa fa-paper-plane mr-1 text-90"></i> บันทึก
                    </button>
                </div>
            </div>';

                return '<div class="pi-comment-container p-1" style="min-width: 250px;">' . $history . $inputSection . '</div>';
            })
            ->addColumn('action_btns', function ($rec) use ($lang, $piMenu) {
                $tEdit = __('Edit') . ' — แก้ไข';
                $tDel = __('Delete') . ' — ลบ';
                $tPdf = 'PDF — ออกใบ PI ส่งโรงงาน';
                $tPay = 'ชำระเงิน';
                $tFa = __('FA') . ' — ออกใบ PI ส่งโรงงาน (Factory Accept)';
                $tExPo = __('EX PO') . ' — ออกใบ PI ส่งโรงงาน (Export PO)';
                $tPoProduct = __('PO Product') . ' — ออกใบ Export Product';
                $tFic2Fi = __('Fic 2 Fi') . ' — ออกใบ Fic 2 Fi';

                $isCustomerReceipt = ($this->current_menu === 'CustomerReceipt');

                $str = '<div class="btn-group btn-group-sm">';
                $str .= '<a href="' . url('admin/' . $lang . '/ProformaInvoice/pdfFactory?pi_id=' . $rec->id) . '"
                    class="btn btn-outline-info btn-h-light-info btn-a-light-info border-b-2"
                    title="' . e($tPdf) . '" target="_blank">
                    <i class="fa fa-file-pdf"></i>
                </a>';
                if ($isCustomerReceipt) {
                    $str .= '<a href="' . url('admin/' . $lang . '/CustomerReceipt/RecordPayment/' . $rec->id) . '"
                        class="btn btn-outline-success btn-h-light-success btn-a-light-success border-b-2"
                        title="' . e($tPay) . '">
                        <i class="fa fa-money-bill-wave"></i>
                    </a>';
                } else {
                    $update = Help::CheckPermissionMenu($piMenu, 'u');
                    if ($update) {
                        $str .= '<a href="' . url('admin/' . $lang . '/ProformaInvoice/' . $rec->id . '/edit') . '"
                            class="btn btn-outline-warning btn-h-light-warning btn-a-light-warning border-b-2"
                            title="' . e($tEdit) . '">
                            <i class="fa fa-edit"></i>
                        </a>';
                    }
                    $delete = Help::CheckPermissionMenu($piMenu, 'd');
                    if ($delete) {
                        $str .= '<button class="btn btn-outline-danger btn-h-light-danger btn-a-light-danger border-b-2 btn-delete-pi"
                            data-id="' . $rec->id . '" title="' . e($tDel) . '">
                            <i class="fa fa-trash-alt"></i>
                        </button>';
                    }
                }
                $str .= '<a href="' . url('admin/' . $lang . '/ProformaInvoice/' . $rec->id . '/FA') . '"
                    class="btn btn-outline-orange btn-h-light-orange btn-a-light-orange border-b-2"
                    title="' . e($tFa) . '">
                    <i class="fa fa-industry"></i>
                </a>';
                $str .= '<a href="' . url('admin/' . $lang . '/ProformaInvoice/' . $rec->id . '/ExportPo') . '"
                    class="btn btn-outline-blue btn-h-light-blue btn-a-light-blue border-b-2"
                    title="' . e($tExPo) . '">
                    <i class="fa fa-shipping-fast"></i>
                </a>';
                $str .= '<a href="' . url('admin/' . $lang . '/ProformaInvoice/' . $rec->id . '/ExportProduct') . '"
                    class="btn btn-outline-secondary btn-h-light-secondary btn-a-light-secondary border-b-2"
                    title="' . e($tPoProduct) . '">
                    <i class="fa fa-boxes"></i>
                </a>';
                $str .= '<a href="' . url('admin/' . $lang . '/ProformaInvoice/' . $rec->id . '/Fic2Fi') . '"
                    class="btn btn-outline-purple btn-h-light-purple btn-a-light-purple border-b-2"
                    title="' . e($tFic2Fi) . '">
                    <i class="fa fa-random"></i>
                </a>';
                $str .= '</div>';

                return $str;
            })
            ->addIndexColumn()
            ->rawColumns(['doc_info', 'customer_info', 'total', 'status_name', 'comment_box', 'action_btns'])
            ->make(true);
    }
}
