<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForienAdminUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admin_users', function (Blueprint $table) {
            $table->foreign('prefix_id')->references('id')->on('prefixes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('position_id')->references('id')->on('admin_positions')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('department_id')->references('id')->on('admin_departments')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_users', function (Blueprint $table) {
            $table->dropForeign(env('DB_PREFIX').'admin_users_prefix_id_foreign');
            $table->dropForeign(env('DB_PREFIX').'admin_users_position_id_foreign');
            $table->dropForeign(env('DB_PREFIX').'admin_users_department_id_foreign');
        });
    }
}
