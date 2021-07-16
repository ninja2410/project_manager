<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumsSerieAndIncrementSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales', function (Blueprint $table){
          $table->integer('id_serie')->unsigned();
          $table->foreign('id_serie')->references('id')->on('series');
          $table->integer('correlative');
          $table->unique(array('id_serie', 'correlative'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales', function (Blueprint $table){
          // $table->dropColumn('id_serie');
          // $table->dropColumn('correlative');
        });
    }
}
