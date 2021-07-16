<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignIdcellarSaleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('sale_items', function(Blueprint $table)
  		{
        $table->integer('id_bodega');

  		});
      Schema::table('sale_temps',function(Blueprint $table){
        $table->integer('id_bodega');
        $table->integer('cellar_quantity');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('sale_items', function(Blueprint $table)
      {
        $table->dropColumn('id_bodega');
      });
      Schema::table('sale_temps',function (Blueprint $table){
        $table->dropColumn('id_bodega');
        $table->dropColumn('cellar_quantity');
      });
    }
}
