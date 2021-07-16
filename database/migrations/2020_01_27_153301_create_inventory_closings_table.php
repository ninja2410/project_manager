<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryClosingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_closings', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('comment');
            $table->unsignedInteger('status_id');
            $table->foreign('status_id')->references('id')->on('state_cellars');
            $table->decimal('amount');
            $table->decimal('total_quantity');
            $table->unsignedInteger('almacen_id');
            $table->foreign('almacen_id')->references('id')->on('almacens');
            $table->integer('month');
            $table->integer('year');
            $table->integer('l_month');
            $table->integer('l_year');
            $table->unsignedInteger('correlative')->default('0');;
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
        Schema::drop('inventory_closings');
    }
}
