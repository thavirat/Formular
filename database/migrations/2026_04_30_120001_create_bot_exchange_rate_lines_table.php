<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * รายการสกุลเงินละเอียดจาก BOT (data.data_detail[])
     */
    public function up(): void
    {
        Schema::create('bot_exchange_rate_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bot_exchange_rate_snapshot_id')
                ->constrained('bot_exchange_rate_snapshots')
                ->cascadeOnDelete();
            $table->string('period', 32)->nullable()->comment('รอบรายงานจากแถว (อาจว่าง)');
            $table->string('currency_code', 16)->comment('เดิม currency_id เช่น USD, JPY');
            $table->text('currency_name_th')->nullable();
            $table->text('currency_name_eng')->nullable();
            $table->decimal('buying_sight', 20, 10)->nullable();
            $table->decimal('buying_transfer', 20, 10)->nullable();
            $table->decimal('selling', 20, 10)->nullable();
            $table->decimal('mid_rate', 20, 10)->nullable();
            $table->timestamps();

            $table->unique(['bot_exchange_rate_snapshot_id', 'currency_code'], 'bot_exrate_snap_curr_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_exchange_rate_lines');
    }
};
