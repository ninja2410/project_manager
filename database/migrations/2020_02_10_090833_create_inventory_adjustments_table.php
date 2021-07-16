<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryAdjustmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_adjustments', function (Blueprint $table) {
            $table->increments('id');
            // Add User Create Route
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('users');
            // Add User Update Route
            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('users');

            $table->integer('correlative');

            $table->integer('serie_id')->unsigned();
            $table->foreign('serie_id')->references('id')->on('series');

            $table->string('comments');
            $table->string('sign');
            
            $table->integer('almacen_id')->unsigned();
            $table->foreign('almacen_id')->references('id')->on('almacens');
             
            $table->date('inventory_adjustament_date');

            $table->unique(['serie_id', 'correlative']);
            
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
        Schema::dropIfExists('inventory_adjustments');
    }
}
