<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class ProformaInvoiceProduct extends Model
{
    public function pi(): BelongsTo
    {
        return $this->belongsTo(ProformaInvoice::class, 'pi_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * คำนวณ produced_qty ใหม่ของ pi_product ที่ระบุ จาก packing
     * นับ "เฉพาะ packing ใบล่าสุด" ที่มี detail ของ pi_product นั้น (กันนับซ้ำเมื่อแพ็คหลายใบ)
     * ใบล่าสุด = packing_form ที่ doc_date มากสุด (เท่ากันใช้ id มากสุด)
     *
     * @param  array<int>  $piProductIds
     */
    public static function syncProducedQty(array $piProductIds): void
    {
        $ids = array_filter(array_unique(array_map('intval', $piProductIds)));
        if (empty($ids)) {
            return;
        }

        foreach ($ids as $pipId) {
            static::where('id', $pipId)->update(['produced_qty' => static::producedQtyFor($pipId)]);
        }
    }

    /**
     * ผลรวม qty ของ pi_product จาก packing "ใบล่าสุด" ใบเดียว (0 ถ้าไม่มี packing)
     */
    public static function producedQtyFor(int $pipId): int
    {
        $latestFormId = DB::table('packing_form_details as d')
            ->join('packing_forms as pf', 'pf.id', '=', 'd.packing_form_id')
            ->where('d.pi_product_id', $pipId)
            ->orderByDesc('pf.doc_date')
            ->orderByDesc('pf.id')
            ->value('d.packing_form_id');

        if ($latestFormId === null) {
            return 0;
        }

        return (int) PackingFormDetail::where('pi_product_id', $pipId)
            ->where('packing_form_id', $latestFormId)
            ->sum('qty');
    }
}
