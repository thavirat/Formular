<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('key_permission')->nullable()->comment('สิทธิเพื่อเอาไว้เช็คในโค้ด');
            $table->string('detail')->nullable()->comment('รายละเอียดของสิทธิ เช่น สิทธิการดูรายงาน นู่นนี่นั่น');
            $table->timestamps();
        });
        \DB::statement("ALTER TABLE `".env('DB_PREFIX')."permissions` comment 'สิทธิการใช้งานเพิ่มเติมนอกจาก CRUD'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
    }
}
