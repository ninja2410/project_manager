<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBodegaProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bodega_productos',function(Blueprint $table){
          $table->increments('id');
          $table->integer('id_bodega')->unsigned();
          $table->foreign('id_bodega')->references('id')->on('almacens');
          $table->integer('id_product')->unsigned();
          $table->foreign('id_product')->references('id')->on('items');
          $table->decimal('quantity',10,2);
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
        Schema::dropIfExists('bodega_productos');
    }
}
