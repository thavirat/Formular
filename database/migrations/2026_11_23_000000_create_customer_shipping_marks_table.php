<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Shipping Mark หลายชุดต่อลูกค้า (per customer)
 * - จัดการในหน้าแก้ไขลูกค้า (เพิ่ม/ลบได้หลายชุด, ตั้ง default ได้)
 * - หน้า PI ปุ่ม "โหลด Shipping Mark" ดึงรายการชุดของลูกค้ามาเลือกเติม ship_remark
 * - Backfill: ย้ายค่า customers.shipping_mark เดิมเป็นชุด default อัตโนมัติ
 *   (คอลัมน์ customers.shipping_mark เดิมคงไว้ ไม่ลบ)
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('customer_shipping_marks')) {
            Schema::create('customer_shipping_marks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
                $table->string('name')->nullable();
                $table->text('content')->nullable();
                $table->boolean('is_default')->default(false);
                $table->boolean('active')->default(true);
                $table->timestamps();
            });
        }

        // ย้าย shipping_mark เดิมของแต่ละลูกค้าเป็นชุด default (ทำครั้งเดียว, ข้ามลูกค้าที่มีชุดอยู่แล้ว)
        if (Schema::hasColumn('customers', 'shipping_mark')) {
            $rows = DB::table('customers')
                ->whereNotNull('shipping_mark')
                ->where('shipping_mark', '!=', '')
                ->get(['id', 'shipping_mark']);
            foreach ($rows as $c) {
                $hasMark = DB::table('customer_shipping_marks')->where('customer_id', $c->id)->exists();
                if ($hasMark) {
                    continue;
                }
                DB::table('customer_shipping_marks')->insert([
                    'customer_id' => $c->id,
                    'name'        => 'ปกติ',
                    'content'     => $c->shipping_mark,
                    'is_default'  => true,
                    'active'      => true,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_shipping_marks');
    }
};
