<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('admin_id')->nullable()->comment('ผู้ใช้งาน');
            $table->unsignedBigInteger('permission_id')->nullable()->comment('สิทธิใช้งาน');
            $table->enum('permission', ['T', 'F' ])->default('F')->nullable()->comment('สิทธิ');
            $table->timestamps();
        });
        \DB::statement("ALTER TABLE `".env('DB_PREFIX')."admin_permissions` comment 'สิทธิของ Admin'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_permissions');
    }
}
