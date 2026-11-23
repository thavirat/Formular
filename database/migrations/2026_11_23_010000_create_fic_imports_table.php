<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * FIC/SHORT import — batch header (1 ไฟล์ = 1 batch)
 * บัญชี upload ไฟล์ FIC (.xlsx) → สร้าง batch สถานะ pending (พรีวิว) → apply
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('fic_imports')) {
            return;
        }
        Schema::create('fic_imports', function (Blueprint $table) {
            $table->id();
            $table->string('source_filename')->nullable();
            $table->string('file_sha256', 64)->nullable();
            $table->string('status', 20)->default('pending'); // pending | applied | cancelled
            $table->integer('total_rows')->default(0);        // แถวข้อมูลทั้งไฟล์
            $table->integer('relevant_rows')->default(0);     // แถวที่แตะ PI ในระบบ (เก็บเป็น lines)
            $table->integer('update_rows')->default(0);
            $table->integer('ambiguous_rows')->default(0);
            $table->integer('credit_rows')->default(0);
            $table->integer('skipped_no_packing_rows')->default(0);
            $table->integer('skipped_no_pi_rows')->default(0); // นับรวม ไม่เก็บ line
            $table->unsignedBigInteger('imported_by')->nullable();
            $table->timestamp('applied_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fic_imports');
    }
};
