<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('credit_notes')) {
            Schema::create('credit_notes', function (Blueprint $table) {
                $table->id();
                $table->string('doc_no', 50)->unique();       // C/N-004/2026 หรือ D/N-001/2026
                $table->integer('run_no')->default(0);          // 4
                $table->smallInteger('doc_year')->nullable();   // 2026 (รันแยกรายปี)
                $table->enum('doc_type', ['credit', 'debit'])->default('credit');
                $table->date('doc_date')->nullable();
                $table->unsignedBigInteger('customer_id')->nullable()->index();
                // snapshot ผู้ซื้อ (ดึงจากลูกค้าอัตโนมัติ แก้ได้)
                $table->string('company_name', 191)->nullable();
                $table->string('address', 255)->nullable();
                $table->string('address2', 255)->nullable();
                $table->string('country', 191)->nullable();
                $table->string('phone', 100)->nullable();
                $table->string('tax_id', 100)->nullable();
                $table->string('contact_name', 191)->nullable();
                $table->string('refer', 191)->nullable();       // อ้างอิงใบกำกับ (พิมพ์เอง)
                $table->unsignedBigInteger('currency_id')->nullable(); // ตามสกุลของ PI ที่เลือก
                $table->decimal('total', 12, 2)->default(0);
                $table->text('reason')->nullable();             // Reason for
                $table->unsignedBigInteger('authorized_by')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('credit_note_items')) {
            Schema::create('credit_note_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('credit_note_id')->index();
                $table->integer('seq')->default(0);
                $table->unsignedBigInteger('pi_id')->nullable();          // Invoice (PI) ที่เลือก
                $table->unsignedBigInteger('pi_product_id')->nullable();  // สินค้าใน PI นั้น
                $table->unsignedBigInteger('product_id')->nullable();
                $table->string('invoice_no', 191)->nullable();  // snapshot โชว์คอลัมน์ Invoice #
                $table->string('part_no', 191)->nullable();
                $table->text('description')->nullable();
                $table->decimal('qty', 10, 2)->default(0);
                $table->decimal('unit_price', 12, 2)->default(0);
                $table->decimal('amount', 12, 2)->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('credit_note_items');
        Schema::dropIfExists('credit_notes');
    }
};
