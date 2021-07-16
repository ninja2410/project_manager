<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnLastcostReceivingTempsAndItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('receiving_temps', function (Blueprint $table){
        $table->decimal('last_cost',9, 2);
      });
      Schema::table('receiving_items',function(Blueprint $table){
        $table->decimal('last_cost',9,2);
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('receiving_temps',function(Blueprint $table){
          $table->dropColumn('last_cost');
        });
        Schema::table('receiving_items',function(Blueprint $table){
          $table->dropColumn('last_cost');
        });

    }
}
