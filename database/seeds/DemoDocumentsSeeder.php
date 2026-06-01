<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * สร้างข้อมูลจำลอง: ใบเสนอราคา 10 ใบ + Proforma Invoice 10 ใบ
 * ใบละ 50–100 รายการสินค้าแบบสุ่ม
 *
 * วิธีรัน:  php artisan db:seed --class=DemoDocumentsSeeder
 *
 * - ใช้ลูกค้า/สินค้า/สกุลเงิน/หมวดที่มีอยู่จริงในระบบ (สุ่มหยิบ)
 * - ถ้ามีน้อยเกินไป จะสร้างข้อมูล demo เติมให้พอใช้งาน
 * - รันซ้ำได้ (doc_no มี timestamp กันชนกัน)
 */
class DemoDocumentsSeeder extends Seeder
{
    public function run()
    {
        $now = now();

        /* ---------- เตรียม master data ---------- */
        $currencyId = DB::table('currencies')->where('symbol', 'USD')->value('id')
            ?? DB::table('currencies')->value('id');
        if (!$currencyId) {
            $currencyId = DB::table('currencies')->insertGetId([
                'name' => 'USD', 'symbol' => 'USD',
                'buying_sight' => 33, 'buying_transfer' => 33, 'selling' => 34, 'mid_rate' => 33.5,
                'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        $incotermId = DB::table('incoterms')->value('id')
            ?? DB::table('incoterms')->insertGetId([
                'code' => 'C&F', 'description' => 'Cost and Freight',
                'created_at' => $now, 'updated_at' => $now,
            ]);

        $creditPaymentId = DB::table('credit_payments')->value('id')
            ?? DB::table('credit_payments')->insertGetId([
                'name' => 'BY T/T', 'created_at' => $now, 'updated_at' => $now,
            ]);

        $adminId = DB::table('admin_users')->value('id');

        // สถานะ "อนุมัติแล้ว" = id มากสุด (ปกติ 3) เพื่อให้เอกสารพร้อมใช้งานต่อ
        $qStatusId  = DB::table('quotation_statuses')->orderByDesc('id')->value('id');
        $piStatusId = DB::table('proforma_invoice_statuses')->orderByDesc('id')->value('id');

        /* ---------- หมวดสินค้า ---------- */
        $categoryIds = DB::table('product_categories')->pluck('id')->all();
        if (empty($categoryIds)) {
            foreach (['AUTO AIR-CONDITIONING PARTS', 'RUBBER & HOSE PARTS', 'ELECTRONIC PARTS'] as $cn) {
                $categoryIds[] = DB::table('product_categories')->insertGetId([
                    'name_en' => $cn, 'name_th' => $cn, 'created_at' => $now, 'updated_at' => $now,
                ]);
            }
        }

        /* ---------- สินค้า (ถ้ามีน้อยกว่า 50 สร้าง demo เติม) ---------- */
        if (DB::table('products')->count() < 50) {
            $units = ['PCS', 'UNITS', 'SET'];
            $namesEn = ['ALU.FITTING W/OUT FERRULE', 'STRAIGHT SPLICER', 'SOLENOID VALVE',
                'RUBBER HOSE', 'O-RING SEAL', 'BLOCK VALVE KNOB', 'EVAPORATOR UNIT',
                'CONDENSER COIL', 'COMPRESSOR MOUNT', 'RECEIVER DRIER'];
            $namesTh = ['ข้อต่ออลูมิเนียมไม่มีปลอก', 'ข้อต่อตรง', 'โซลินอยด์วาล์ว',
                'สายยาง', 'โอริงซีล', 'บล็อกวาล์วน็อบ', 'คอยล์เย็น',
                'คอยล์ร้อน', 'ขายึดคอมเพรสเซอร์', 'ตัวกรองน้ำยา'];
            $sizes = ['3/8"X5/8', '1/2"X5/8', '5/8" [O-RING]', '13MM x 50M', 'R-12', 'R-134A'];
            $need = 60 - DB::table('products')->count();
            $batch = [];
            for ($k = 1; $k <= $need; $k++) {
                $idx = array_rand($namesEn);
                $batch[] = [
                    'category_id' => $categoryIds[array_rand($categoryIds)],
                    'code' => '80' . rand(10, 99) . '-' . rand(1000, 9999) . '-00',
                    'part_no' => '80' . rand(10, 99) . '-' . rand(1000, 9999) . '-00',
                    'name_th' => $namesTh[$idx] . ' ' . $sizes[array_rand($sizes)],
                    'name_en' => $namesEn[$idx] . ' ' . $sizes[array_rand($sizes)],
                    'drawing' => 'FHF-' . rand(10, 999),
                    'unit' => $units[array_rand($units)],
                    'active' => 'T',
                    'created_at' => $now, 'updated_at' => $now,
                ];
            }
            foreach (array_chunk($batch, 100) as $chunk) {
                DB::table('products')->insert($chunk);
            }
        }
        $products = DB::table('products')->inRandomOrder()->limit(300)->get();

        /* ---------- ลูกค้า (ถ้าไม่มี สร้าง demo) ---------- */
        if (DB::table('customers')->count() == 0) {
            $demoCustomers = [
                ['SUCCESS INTERNATIONAL (PTE) LTD.', '59 JALAN PEMIMPIN, SINGAPORE'],
                ['THANSING TRADING CO., LTD.', '88 SUKHUMVIT ROAD, BANGKOK, THAILAND'],
                ['ASIA AUTO PARTS SDN BHD', '12 JALAN INDUSTRI, KUALA LUMPUR, MALAYSIA'],
                ['PACIFIC COOLING LTD.', '230 GEORGE STREET, SYDNEY, AUSTRALIA'],
                ['NIPPON KUExCHANGE CO.', '5-1 GINZA, TOKYO, JAPAN'],
            ];
            foreach ($demoCustomers as $i => $c) {
                DB::table('customers')->insert([
                    'contact_name' => 'Mr. Demo ' . ($i + 1),
                    'company_name' => $c[0],
                    'address' => $c[1],
                    'tax_id' => (string) rand(1000000000000, 9999999999999),
                    'phone' => '0' . rand(20000000, 99999999),
                    'mobile' => '08' . rand(10000000, 99999999),
                    'fax' => '0' . rand(20000000, 99999999),
                    'created_at' => $now, 'updated_at' => $now,
                ]);
            }
        }
        $customers = DB::table('customers')->inRandomOrder()->limit(50)->get();

        $lineFromProduct = function ($p) {
            $qty = rand(10, 500);
            $price = round(($p->price ?? 0) > 0 ? $p->price : (rand(50, 200000) / 100), 2);
            return [$qty, $price, round($qty * $price, 2)];
        };

        /* ---------- สร้างเอกสาร ---------- */
        DB::transaction(function () use (
            $now, $products, $customers, $currencyId, $incotermId, $creditPaymentId,
            $adminId, $qStatusId, $piStatusId, $lineFromProduct
        ) {
            $stamp = $now->format('ymdHis');

            // 10 ใบเสนอราคา
            for ($i = 1; $i <= 10; $i++) {
                $cust = $customers->random();
                $qid = DB::table('quotations')->insertGetId([
                    'status_id' => $qStatusId, 'customer_id' => $cust->id,
                    'doc_no' => 'QO-DEMO-' . $stamp . '-' . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'doc_date' => $now->toDateString(), 'run_no' => $i,
                    'contact_name' => $cust->contact_name, 'company_name' => $cust->company_name,
                    'tax_id' => $cust->tax_id, 'address' => $cust->address,
                    'phone' => $cust->phone, 'mobile' => $cust->mobile, 'fax_no' => $cust->fax,
                    'incoterm_id' => $incotermId, 'currency_id' => $currencyId,
                    'credit_payment_id' => $creditPaymentId, 'created_by' => $adminId,
                    'subtotal' => 0, 'total' => 0, 'created_at' => $now, 'updated_at' => $now,
                ]);
                $rows = []; $subtotal = 0;
                $count = rand(50, 100);
                for ($n = 1; $n <= $count; $n++) {
                    $p = $products->random();
                    [$qty, $price, $line] = $lineFromProduct($p);
                    $subtotal += $line;
                    $rows[] = [
                        'quotation_id' => $qid, 'product_id' => $p->id,
                        'part_no' => $p->code ?: $p->part_no, 'drawing' => $p->drawing,
                        'cus_code' => 'CUS' . rand(1000, 9999),
                        'detail_thai' => $p->name_th, 'detail_eng' => $p->name_en ?: $p->name_th,
                        'qty' => $qty, 'price_per_item' => $price, 'total_price' => $line,
                        'discount_percents' => 0, 'discount_amount' => 0, 'proforma_qty' => 0,
                        'created_at' => $now, 'updated_at' => $now,
                    ];
                }
                foreach (array_chunk($rows, 200) as $chunk) {
                    DB::table('quotation_products')->insert($chunk);
                }
                DB::table('quotations')->where('id', $qid)
                    ->update(['subtotal' => $subtotal, 'total' => $subtotal]);
            }

            // 10 Proforma Invoice
            for ($i = 1; $i <= 10; $i++) {
                $cust = $customers->random();
                $piid = DB::table('proforma_invoices')->insertGetId([
                    'quotation_id' => null, // FK ใน schema ผูกไป admin_users (บั๊กเดิม) จึงเว้น null
                    'status_id' => $piStatusId, 'customer_id' => $cust->id,
                    'incoterm_id' => $incotermId, 'currency_id' => $currencyId,
                    'credit_payment_id' => $creditPaymentId,
                    'doc_no' => 'PI-DEMO-' . $stamp . '-' . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'doc_date' => $now->toDateString(), 'run_no' => $i,
                    'contact_name' => $cust->contact_name, 'company_name' => $cust->company_name,
                    'tax_id' => $cust->tax_id, 'address' => $cust->address,
                    'phone' => $cust->phone, 'mobile' => $cust->mobile, 'fax_no' => $cust->fax,
                    'subtotal' => 0, 'total' => 0, 'created_by' => $adminId,
                    'ship_date' => $now->toDateString(), 'ship_to_code' => null,
                    'customer_po' => str_pad((string) rand(1, 99999), 5, '0', STR_PAD_LEFT),
                    'ship_remark' => 'ON/AROUND',
                    'created_at' => $now, 'updated_at' => $now,
                ]);
                $rows = []; $subtotal = 0;
                $count = rand(50, 100);
                for ($n = 1; $n <= $count; $n++) {
                    $p = $products->random();
                    [$qty, $price, $line] = $lineFromProduct($p);
                    $subtotal += $line;
                    $rows[] = [
                        'pi_id' => $piid, 'product_id' => $p->id,
                        'part_no' => $p->code ?: $p->part_no, 'drawing' => $p->drawing,
                        'cus_code' => 'CUS' . rand(1000, 9999),
                        'detail_thai' => $p->name_th, 'detail_eng' => $p->name_en ?: $p->name_th,
                        'qty' => $qty, 'produced_qty' => 0, 'price_per_item' => $price,
                        'total_price' => $line, 'seq' => $n,
                        'created_at' => $now, 'updated_at' => $now,
                    ];
                }
                foreach (array_chunk($rows, 200) as $chunk) {
                    DB::table('proforma_invoice_products')->insert($chunk);
                }
                DB::table('proforma_invoices')->where('id', $piid)
                    ->update(['subtotal' => $subtotal, 'total' => $subtotal]);
            }
        });

        $this->command->info('สร้างข้อมูลจำลองเรียบร้อย: ใบเสนอราคา 10 ใบ + PI 10 ใบ (ใบละ 50–100 รายการ)');
    }
}
