<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWeeklySettlementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weekly_settlements', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->unsignedInteger('bodega_id');
            $table->foreign('bodega_id')->references('id')->on('almacens');
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
        Schema::drop('weekly_settlements');
    }
}
