<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumsReceivingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('receivings',function(Blueprint $table){
          $table->integer('id_pago')->unsigned()->nullable();
          $table->foreign('id_pago')->references('id')->on('pagos');
          $table->integer('id_serie')->unsigned();
          $table->foreign('id_serie')->references('id')->on('series');
          $table->integer('correlative');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('receivings', function(Blueprint $table){
        // $table->dropColumn('id_pago');
        // $table->dropColumn('id_serie');
        // $table->dropColumn('correlative');
      });
    }
}
