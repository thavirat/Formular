<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrudMenus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crud_menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->comment('ไอดีแอดทิร');
            $table->unsignedBigInteger('menu_id')->nullable()->comment('เมนู');
            $table->enum('created', ['T', 'F' ])->default('T')->nullable()->comment('สิทธิการสร้าง');
            $table->enum('readed', ['T', 'F' ])->default('T')->nullable()->comment('สิทธการอ่านหรือเปิดดู');
            $table->enum('updated', ['T', 'F' ])->default('T')->nullable()->comment('สิทธิการแก้ไข อัพเดต');
            $table->enum('deleted', ['T', 'F' ])->default('T')->nullable()->comment('สิทธิการลบ');
            $table->enum('printed', ['T', 'F' ])->default('T')->nullable()->comment('สิทธิการปริ้น');
            $table->enum('export_excel', ['T', 'F' ])->default('T')->nullable()->comment('สิทธิการ Export Excel');
            $table->enum('export_pdf', ['T', 'F' ])->default('T')->nullable()->comment('สิทธิการ Export PDF');
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
        Schema::dropIfExists('crud_menus');
    }
}
