<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregarIdbodegaidproductoCompratemporalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('receiving_temps',function(Blueprint $table){
          $table->integer('id_bodega');
          $table->integer('id_product');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('receiving_temps',function (Blueprint $table){
        $table->dropColumn('id_bodega');
        $table->dropColumn('id_product');
      });
    }
}
