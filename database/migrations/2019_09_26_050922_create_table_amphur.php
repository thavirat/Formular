<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAmphur extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amphurs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('province_id')->nullable()->comment('จังหวัด');
            $table->string('name')->nullable()->comment('ชื่ออำเภอ');
            $table->string('zipcode')->nullable()->comment('รหัสไปรษณีย์');
            $table->timestamps();
        });
        \DB::statement("ALTER TABLE `tb_amphurs` comment 'อำเภอ'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('amphurs');
    }
}
