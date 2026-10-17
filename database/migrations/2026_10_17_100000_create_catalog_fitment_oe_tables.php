<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| Catalog: Vehicle fitment + OE cross-reference
|--------------------------------------------------------------------------
|
| ตารางรองรับฟีเจอร์หน้าบ้าน catalog (ดู CATALOG_PLAN.md):
|   - ค้นหาตามรุ่นรถ: ยี่ห้อ → รุ่น → ปี  (vehicle_makes / vehicle_models / product_fitments)
|   - ค้นหาตามเลข OE                      (product_oe_numbers)
|
| สร้างไว้ "รอกรอก/import ภายหลัง" — additive ล้วน ไม่แตะตารางเดิมของ CRM
| product_id อ้างตาราง legacy `tb_products` แบบ index เฉย ๆ (ไม่ผูก FK constraint)
| ตามแนวทางเดียวกับ migration price_setting_* ที่อ้าง currency_id/level_id
|
*/

return new class extends Migration
{
    public function up(): void
    {
        // ยี่ห้อรถ (vehicle make) — แยกจาก brand_products ที่ปนยี่ห้ออะไหล่
        if (!Schema::hasTable('vehicle_makes')) {
            Schema::create('vehicle_makes', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();   // TOYOTA, HONDA, ISUZU ...
                $table->timestamps();
            });
        }

        // รุ่นรถ (vehicle model) ต่อยี่ห้อ
        if (!Schema::hasTable('vehicle_models')) {
            Schema::create('vehicle_models', function (Blueprint $table) {
                $table->id();
                $table->foreignId('vehicle_make_id')->constrained('vehicle_makes')->onDelete('cascade');
                $table->string('name');             // Hilux Revo, Civic, D-Max ...
                $table->timestamps();
                $table->unique(['vehicle_make_id', 'name']);
            });
        }

        // mapping สินค้า ↔ รถที่รองรับ (product อยู่กับหลายรถ, รถอยู่กับหลายสินค้า)
        if (!Schema::hasTable('product_fitments')) {
            Schema::create('product_fitments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_id');                 // -> tb_products.id (ไม่ผูก FK, legacy)
                $table->unsignedBigInteger('vehicle_make_id');            // ต้องมียี่ห้อเสมอ
                $table->unsignedBigInteger('vehicle_model_id')->nullable(); // รุ่น (ว่างได้ = ระดับยี่ห้อ)
                $table->smallInteger('year_start')->nullable();           // ปี ค.ศ. เริ่ม
                $table->smallInteger('year_end')->nullable();             // ปี ค.ศ. สิ้นสุด
                $table->string('note')->nullable();                       // รุ่นย่อย/เครื่องยนต์/หมายเหตุ
                $table->timestamps();

                $table->index('product_id');
                $table->index('vehicle_make_id');
                $table->index('vehicle_model_id');
            });
        }

        // เลข OE อ้างอิง (1 สินค้า : หลายเลข)
        if (!Schema::hasTable('product_oe_numbers')) {
            Schema::create('product_oe_numbers', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_id');   // -> tb_products.id (ไม่ผูก FK, legacy)
                $table->string('oe_number');                // เลข OE
                $table->string('maker')->nullable();        // เจ้าของเลข OE เช่น DENSO, TOYOTA
                $table->timestamps();

                $table->index('product_id');
                $table->index('oe_number');                 // ใช้ค้นหา
                $table->unique(['product_id', 'oe_number']); // กันซ้ำต่อสินค้า
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('product_oe_numbers');
        Schema::dropIfExists('product_fitments');
        Schema::dropIfExists('vehicle_models');
        Schema::dropIfExists('vehicle_makes');
    }
};
