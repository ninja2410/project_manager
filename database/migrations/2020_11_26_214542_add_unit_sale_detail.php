<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUnitSaleDetail extends Migration
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
        $table->unsignedInteger('unit_id');
        $table->foreign('unit_id')->references('id')->on('unit_measures');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('sale_items', function($table)
      {
        $table->dropForeign(['unit_id']);
        $table->dropColumn('unit_id');
      });
    }
}
