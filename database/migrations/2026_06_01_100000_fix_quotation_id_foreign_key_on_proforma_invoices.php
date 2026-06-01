<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * แก้บั๊ก: proforma_invoices.quotation_id เดิมผูก foreign key ไปที่ตาราง admin_users
 * (copy-paste ผิดใน migration เดิม) แก้ให้ชี้ไปที่ตาราง quotations ตามที่ควรเป็น
 */
return new class extends Migration
{
    public function up(): void
    {
        // เคลียร์ค่า quotation_id ที่ไม่ตรงกับ quotations.id ออกก่อน (กัน FK ใหม่สร้างไม่ได้)
        DB::table('proforma_invoices')
            ->whereNotNull('quotation_id')
            ->whereNotIn('quotation_id', function ($q) {
                $q->select('id')->from('quotations');
            })
            ->update(['quotation_id' => null]);

        Schema::table('proforma_invoices', function (Blueprint $table) {
            $table->dropForeign(['quotation_id']);
            $table->foreign('quotation_id')->references('id')->on('quotations')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('proforma_invoices', function (Blueprint $table) {
            $table->dropForeign(['quotation_id']);
            // คืนสภาพเดิม (ผูกไป admin_users) ตาม migration ต้นทาง
            $table->foreign('quotation_id')->references('id')->on('admin_users')->onDelete('set null');
        });
    }
};
