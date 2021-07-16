<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBodegasUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('almacen_users',function(Blueprint $table){
          $table->increments('id');
          $table->integer('id_bodega')->unsigned();
          $table->foreign('id_bodega')->references('id')->on('almacens');
          $table->integer('id_usuario')->unsigned();
          $table->foreign('id_usuario')->references('id')->on('users');
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
      Schema::dropIfExists('almacen_users');
    }
}
