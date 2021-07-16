<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatesInDocumentAndSeriesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documents',function(Blueprint $table){
          $table->integer('id_state')->unsigned();
          $table->foreign('id_state')->references('id')->on('state_cellars');
        });
        Schema::table('series',function (Blueprint $table){
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
        Schema::table('documents',function(Blueprint $table){
          // $table->dropColumn('id_state');
        });
        Schema::table('series',function (Blueprint $table){
          // $table->dropColumn('id_state');
        });
    }
}
