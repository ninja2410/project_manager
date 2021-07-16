<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('role_permissions',function(Blueprint $table){
        $table->increments('id');
        $table->integer('id_rol')->unsigned();
        $table->foreign('id_rol')->references('id')->on('roles');
        $table->integer('id_permission')->unsigned();
        $table->foreign('id_permission')->references('id')->on('permissions');
        $table->integer('estado_permiso');
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
        Schema::dropIfExists('role_permissions');
    }
}
