<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAlmacenIdInventory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('inventories', function (Blueprint $table) {
        $table->unsignedInteger('almacen_id')->nullable();
        $table->foreign('almacen_id')->references('id')->on('almacens');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('inventories', function($table){
        $table->dropForeign(['almacen_id']);
        $table->dropColumn('almacen_id');
      });
    }
}
