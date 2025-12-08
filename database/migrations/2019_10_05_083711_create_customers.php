<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('prefix_id')->nullable()->comment('คำนำหน้าชื่อ');
            $table->string('firstname')->nullable()->comment('ชื่อจริง');
            $table->string('lastname')->nullable()->comment('นามสกุล');
            $table->string('nickname')->nullable()->comment('ชื่อเล่น');
            $table->string('email')->nullable()->comment('อีเมล์');
            $table->string('mobile')->nullable()->comment('เบอร์โทร');
            $table->string('address')->nullable()->comment('ที่อยู่');
            $table->unsignedBigInteger('province_id')->nullable()->comment('จังหวัด');
            $table->unsignedBigInteger('amphur_id')->nullable()->comment('อำเภอ');
            $table->integer('zipcode')->nullable()->comment('รหัสไปรษณีย์');
            $table->date('birthday')->nullable()->comment('วันเกิด');
            $table->enum('active', ['T', 'F' ])->default('F')->nullable()->comment('สิทธิเข้าใช้งาน');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('remark')->nullable()->comment('หมายเหตุ');
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
        Schema::dropIfExists('customers');
    }
}
