<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('position_id')->nullable()->comment('ตำแหน่ง');
            $table->unsignedBigInteger('department_id')->nullable()->comment('แผนก');
            $table->string('office_id')->nullable()->comment('รหัสพนักงาน');
            $table->unsignedBigInteger('prefix_id')->nullable()->comment('คำนำหน้าชื่อ');
            $table->string('firstname')->nullable()->comment('ชื่อจริง');
            $table->string('lastname')->nullable()->comment('นามสกุล');
            $table->string('nickname')->nullable()->comment('ชื่อเล่น');
            $table->string('email')->nullable()->comment('อีเมล์');
            $table->string('mobile')->nullable()->comment('เบอร์โทร');
            $table->date('birthday')->nullable()->comment('วันเกิด');
            $table->enum('active', ['T', 'F' ])->default('F')->nullable()->comment('สิทธิเข้าใช้งาน');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('remark')->nullable()->comment('หมายเหตุ');
            $table->string('photo')->nullable();
            $table->string('api_token', 80)->unique()->nullable()->default(null);
            $table->rememberToken();
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
        Schema::dropIfExists('admin_users');
    }
}
