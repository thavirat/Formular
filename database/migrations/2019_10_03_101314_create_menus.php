<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('main_menu_id')->nullable()->comment('เมนูหลัก');
            $table->string('icon')->nullable()->comment('ไอค่อนที่จะแสดง ตัวอย่าง fa fa-user');
            $table->string('img')->nullable()->comment('ใช้รูปแทนโลโก้ขนาด 130 * 130');
            $table->string('title')->nullable()->comment('ชื่อเมนูที่แสดงบนไซด์บาร์');
            $table->string('url')->nullable()->comment('ลิงค์');
            $table->string('badge')->nullable()->comment('ตัวเลขแจ้งเตือน');
            $table->string('label')->nullable()->comment('เลเบล');
            $table->string('highlight')->nullable()->comment('ไฮไลท์');
            $table->enum('show' , ['T', 'F'])->default('T')->nullable()->comment('สถานะการแสดง');
            $table->integer('sort_id')->default(99)->nullable()->comment('ลำดับ');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
