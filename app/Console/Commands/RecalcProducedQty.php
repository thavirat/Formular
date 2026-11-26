<?php

namespace App\Console\Commands;

use App\Models\ProformaInvoiceProduct;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Resync produced_qty ของ proforma_invoice_products ทั้งระบบ จาก packing
 * นับเฉพาะ packing "ใบล่าสุด" ต่อ pi_product (กันนับซ้ำเมื่อแพ็คหลายใบ)
 *
 *   php artisan produced:recalc            # คำนวณใหม่ + บันทึก
 *   php artisan produced:recalc --dry-run  # แสดงรายการที่จะเปลี่ยน โดยไม่บันทึก
 */
class RecalcProducedQty extends Command
{
    protected $signature = 'produced:recalc {--dry-run : แสดงผลที่จะเปลี่ยนโดยไม่บันทึก}';

    protected $description = 'คำนวณ produced_qty ใหม่ทั้งระบบจาก packing (นับเฉพาะใบล่าสุดต่อ pi_product)';

    public function handle(): int
    {
        DB::disableQueryLog();
        $dry = (bool) $this->option('dry-run');

        $total = 0;
        $changed = 0;
        $examples = [];

        ProformaInvoiceProduct::select('id', 'produced_qty')
            ->orderBy('id')
            ->chunkById(500, function ($rows) use (&$total, &$changed, &$examples, $dry) {
                foreach ($rows as $pip) {
                    $total++;
                    $old = (int) $pip->produced_qty;
                    $new = ProformaInvoiceProduct::producedQtyFor((int) $pip->id);
                    if ($old === $new) {
                        continue;
                    }
                    $changed++;
                    if (count($examples) < 15) {
                        $examples[] = "  pip#{$pip->id}: {$old} -> {$new}";
                    }
                    if (!$dry) {
                        ProformaInvoiceProduct::where('id', $pip->id)->update(['produced_qty' => $new]);
                    }
                }
            });

        $this->info(($dry ? '[DRY-RUN] ' : '').'ตรวจ pi_product ทั้งหมด: '.$total.' รายการ');
        $this->info('ที่ค่าต่างจากเดิม: '.$changed.' รายการ'.($dry ? ' (ยังไม่บันทึก)' : ' (บันทึกแล้ว)'));
        if ($examples) {
            $this->line('ตัวอย่างการเปลี่ยน:');
            foreach ($examples as $e) {
                $this->line($e);
            }
        }

        return self::SUCCESS;
    }
}
