<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnStorageDestinationReceivings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('receivings',function(Blueprint $table){
        $table->integer('storage_origins')->unsigned();
        $table->foreign('storage_origins')->references('id')->on('almacens')->nullable();
        $table->integer('storage_destination')->nullable();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('receivings',function(Blueprint $table){
        $table->dropForeign(['storage_origins']);
        $table->dropColumn('storage_origins');
        $table->dropColumn('storage_destination');
      });
    }
}
