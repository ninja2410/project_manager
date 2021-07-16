<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStateCellarRelation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('almacens',function(Blueprint $table){
          $table->integer('id_state')->unsigned();
          $table->foreign('id_state')->references('id')->on('state_cellars');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('almacens',function(Blueprint $table){
          $table->dropForeign(['id_state']);
          $table->dropColumn('id_state');
        });
    }
}
