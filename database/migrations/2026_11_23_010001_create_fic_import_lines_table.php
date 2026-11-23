<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * FIC/SHORT import — รายบรรทัด (เก็บเฉพาะแถวที่แมตช์ PI ในระบบ)
 * action: update | ambiguous | credit_note | skipped_no_packing
 * - update          : แมตช์ packing detail ตรง 1 แถว → ทับ qty/price + scale น้ำหนัก
 * - ambiguous       : เจอ packing detail หลายแถว → ให้เลือกตอนพรีวิว (candidates)
 * - credit_note     : qty ติดลบ → ออก Credit Note (ไม่แตะ packing)
 * - skipped_no_packing : เจอ PI แต่ไม่มี packing detail ของ part_no นี้
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('fic_import_lines')) {
            return;
        }
        Schema::create('fic_import_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fic_import_id')->constrained('fic_imports')->onDelete('cascade');
            $table->integer('row_no')->nullable();            // แถวใน Excel (1-based)

            // ข้อมูลดิบจาก FIC
            $table->string('inv_num')->nullable();
            $table->date('inv_date')->nullable();
            $table->string('cust_num')->nullable();
            $table->string('item')->nullable();               // = part_no
            $table->text('fic_desc')->nullable();
            $table->decimal('qty', 12, 2)->default(0);        // qty_invoiced (ติดลบได้)
            $table->decimal('price', 12, 2)->default(0);
            $table->decimal('total_price', 14, 2)->nullable();
            $table->string('po_no')->nullable();              // FA-xxxxxxxx (ดิบ)
            $table->string('po_digits')->nullable();          // normalize เลขล้วน
            $table->string('poline')->nullable();
            $table->string('product_code')->nullable();

            // ผลการแมตช์
            $table->unsignedBigInteger('matched_pi_id')->nullable();
            $table->string('matched_pi_doc_no')->nullable();
            $table->unsignedBigInteger('matched_detail_id')->nullable(); // packing_form_detail ที่จะทับ
            $table->json('candidates')->nullable();           // [{detail_id, packing_doc_no, qty, from, to}]
            $table->string('action', 30)->default('skipped_no_packing');
            $table->string('note')->nullable();

            $table->unsignedBigInteger('credit_note_id')->nullable();
            $table->boolean('applied')->default(false);
            $table->timestamps();

            $table->index(['fic_import_id', 'action']);
            $table->index('matched_pi_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fic_import_lines');
    }
};
