{{-- หัวเอกสาร Packing List สำหรับ PDF --}}
<h5 class="text-primary-d2 mb-2">หัวเอกสาร (PDF PL ลูกค้า / PL บัญชี)</h5>
<div class="border-1 brc-grey-l2 radius-1 p-3 mb-3 bgc-grey-l4">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group mb-2">
                <label class="text-90">เลขที่ Packing List</label>
                <input type="text" class="form-control form-control-sm" value="{{ $packingForm->doc_no }}" readonly tabindex="-1">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group mb-2">
                <label class="text-90">วันที่เอกสาร</label>
                <div class="input-group input-group-sm">
                    <input type="text" name="doc_date" id="packing_doc_date" class="form-control form-control-sm init-date packing-tab" value="{{ $packingForm->doc_date ? $packingForm->doc_date->format('Y-m-d') : '' }}" readonly>
                    <div class="input-group-append remove_date_time">
                        <div class="input-group-text"><i class="fa fa-times"></i></div>
                    </div>
                    <div class="input-group-append">
                        <div class="input-group-text"><i class="far fa-calendar"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group mb-2">
                <label class="text-90">Invoice No.</label>
                <input type="text" name="invoice_no" class="form-control form-control-sm packing-tab" value="{{ $packingForm->invoice_no }}" placeholder="FT/8854/2025">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group mb-2">
                <label class="text-90">สถานที่ (Place)</label>
                <input type="text" name="place_of_issue" class="form-control form-control-sm packing-tab" value="{{ $packingForm->place_of_issue }}" placeholder="Bangkok">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group mb-2">
                <label class="text-90">ชื่อลูกค้า</label>
                <input type="text" name="customer_name" class="form-control form-control-sm packing-tab" value="{{ $packingForm->customer_name }}">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group mb-2">
                <label class="text-90">To</label>
                <input type="text" name="to" class="form-control form-control-sm packing-tab" value="{{ $packingForm->to }}">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group mb-2">
                <label class="text-90">ประเทศ</label>
                <input type="text" name="country" class="form-control form-control-sm packing-tab" value="{{ $packingForm->country }}">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mb-2">
                <label class="text-90">โทรศัพท์ลูกค้า</label>
                <input type="text" name="customer_phone" class="form-control form-control-sm packing-tab" value="{{ $packingForm->customer_phone }}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group mb-2">
                <label class="text-90">ที่อยู่ลูกค้า</label>
                <textarea name="customer_address" class="form-control form-control-sm packing-tab" rows="2">{{ $packingForm->customer_address }}</textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group mb-2">
                <label class="text-90">Sailing on/about</label>
                <div class="input-group input-group-sm">
                    <input type="text" name="sailing_date" id="packing_sailing_date" class="form-control form-control-sm init-date packing-tab" value="{{ $packingForm->sailing_date ? $packingForm->sailing_date->format('Y-m-d') : '' }}" readonly>
                    <div class="input-group-append remove_date_time">
                        <div class="input-group-text"><i class="fa fa-times"></i></div>
                    </div>
                    <div class="input-group-append">
                        <div class="input-group-text"><i class="far fa-calendar"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group mb-2">
                <label class="text-90">Shipped from</label>
                <input type="text" name="shipped_from" class="form-control form-control-sm packing-tab" value="{{ $packingForm->shipped_from }}" placeholder="LAEM CHABANG, THAILAND to AJMAN, U.A.E.">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mb-2">
                <label class="text-90">Per (เรือ/ขนส่ง)</label>
                <input type="text" name="per_vessel" class="form-control form-control-sm packing-tab" value="{{ $packingForm->per_vessel }}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group mb-0">
                <label class="text-90">L/C No.</label>
                <input type="text" name="lc_no" class="form-control form-control-sm packing-tab" value="{{ $packingForm->lc_no }}">
            </div>
        </div>
        <div class="col-md-8">
            <div class="form-group mb-0">
                <label class="text-90">Issued by</label>
                <input type="text" name="issued_by" class="form-control form-control-sm packing-tab" value="{{ $packingForm->issued_by }}">
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-md-12">
            <div class="form-group mb-0">
                <label class="text-90">Marks &amp; No. (พิมพ์ได้หลายบรรทัด แสดงหัวตาราง Invoice/Packing List)</label>
                <textarea name="marks" class="form-control form-control-sm packing-tab" rows="2" placeholder="DHANYA EX-477  2,147 CARTONS&#10;EX-478  804 CARTONS">{{ $packingForm->marks }}</textarea>
            </div>
        </div>
    </div>
</div>

<h6 class="text-secondary mb-2">ยอดรวมท้ายเอกสาร</h6>
<div class="row mb-2">
    <div class="col-md-2">
        <div class="form-group">
            <label class="text-90">Pkg</label>
            <input type="number" name="pkg" class="form-control form-control-sm packing-tab" value="{{ $packingForm->pkg }}" min="0" step="1">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label class="text-90">Qty รวม</label>
            <input type="number" name="qty" class="form-control form-control-sm packing-tab" value="{{ $packingForm->qty }}" min="0" step="1">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label class="text-90">CBM รวม</label>
            <input type="number" name="cubic_meter" class="form-control form-control-sm packing-tab" value="{{ $packingForm->cubic_meter }}" min="0" step="0.01">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label class="text-90">N.W. รวม</label>
            <input type="number" name="weight_nw" class="form-control form-control-sm packing-tab" value="{{ $packingForm->weight_nw }}" min="0" step="0.01">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label class="text-90">G.W. รวม</label>
            <input type="number" name="weight_gw" class="form-control form-control-sm packing-tab" value="{{ $packingForm->weight_gw }}" min="0" step="0.01">
        </div>
    </div>
    <div class="col-md-1">
        <div class="form-group">
            <label class="text-90">N.T.</label>
            <input type="number" name="weight_nt" class="form-control form-control-sm packing-tab" value="{{ $packingForm->weight_nt }}" min="0" step="0.01">
        </div>
    </div>
    <div class="col-md-1">
        <div class="form-group">
            <label class="text-90">G.T.</label>
            <input type="number" name="weight_gt" class="form-control form-control-sm packing-tab" value="{{ $packingForm->weight_gt }}" min="0" step="0.01">
        </div>
    </div>
</div>
