<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * หัวชุดข้อมูลจาก BOT Stat-ExchangeRate v2 (result + data.data_header)
     */
    public function up(): void
    {
        Schema::create('bot_exchange_rate_snapshots', function (Blueprint $table) {
            $table->id();
            $table->dateTime('result_timestamp')->nullable()->comment('result.timestamp');
            $table->string('api_name', 255)->nullable()->comment('result.api');
            $table->date('query_start_period')->nullable()->comment('พารามิเตอร์ start_period ตอนดึง');
            $table->date('query_end_period')->nullable()->comment('พารามิเตอร์ end_period ตอนดึง');
            $table->date('header_last_updated')->nullable()->comment('data.data_header.last_updated');
            $table->text('report_name_eng')->nullable();
            $table->text('report_name_th')->nullable();
            $table->text('report_uoq_name_eng')->nullable();
            $table->text('report_uoq_name_th')->nullable();
            $table->json('report_source_of_data')->nullable()->comment('data.data_header.report_source_of_data');
            $table->json('report_remark')->nullable()->comment('data.data_header.report_remark');
            $table->json('raw_result_json')->nullable()->comment('เก็บ result ทั้งก้อนจาก API สำหรับ audit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_exchange_rate_snapshots');
    }
};
