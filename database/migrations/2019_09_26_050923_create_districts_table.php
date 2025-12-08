<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('districts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('province_id')->nullable()->comment('จังหวัด');
            $table->unsignedBigInteger('amphur_id')->nullable()->comment('อำเภอ');
            $table->string('name')->nullable()->comment('ชื่อตำบล');
            $table->timestamps();
        });

        \DB::statement("ALTER TABLE `".env('DB_PREFIX')."districts` comment 'เวลางาน'");

        Schema::table('districts', function (Blueprint $table) {
            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('amphur_id')->references('id')->on('amphurs')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('districts', function (Blueprint $table) {
            $table->dropForeign(env('DB_PREFIX').'districts_province_id_foreign');
            $table->dropForeign(env('DB_PREFIX').'districts_amphur_id_foreign');
        });
        Schema::dropIfExists('districts');
    }
}
