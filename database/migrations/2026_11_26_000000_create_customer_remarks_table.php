<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * หมายเหตุ (Remark) ตั้งค่าได้หลายชุดต่อลูกค้า (per customer) + เปิด/ปิด (active)
 * - จัดการในหน้าแก้ไขลูกค้า (เพิ่ม/ลบ/ปิด-เปิดได้)
 * - หน้า PI ปุ่ม "โหลดหมายเหตุของลูกค้า" ดึงเฉพาะชุดที่ active มาทับตาราง Remark
 * - เริ่มจากว่าง (ไม่ backfill ข้อมูลเดิม)
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('customer_remarks')) {
            return;
        }
        Schema::create('customer_remarks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->text('content')->nullable();
            $table->boolean('active')->default(true);
            $table->integer('seq')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_remarks');
    }
};
