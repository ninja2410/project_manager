<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLlaveforaneapagoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('sales', function(Blueprint $table)
  		{
        $table->integer('id_pago')->unsigned();
  			$table->foreign('id_pago')->references('id')->on('pagos');
  		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('sales', function(Blueprint $table)
      {
        $table->dropForeign(['id_pago']);
        $table->dropColumn('id_pago');
      });
    }
}
