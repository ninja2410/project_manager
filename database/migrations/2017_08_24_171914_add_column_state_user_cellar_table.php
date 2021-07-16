<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnStateUserCellarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('almacen_users',function(Blueprint $table){
        $table->integer('estado_user');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('almacen_users',function(Blueprint $table){
        $table->dropColumn('estado_user');
      });
    }
}
