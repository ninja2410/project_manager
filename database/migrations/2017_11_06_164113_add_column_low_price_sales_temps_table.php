<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnLowPriceSalesTempsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_temps',function (Blueprint $table){
          $table->decimal('low_price',9, 2);
        });
        Schema::table('sale_items',function (Blueprint $table){
          $table->decimal('low_price',9, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('sale_temps',function (Blueprint $table){
        $table->dropColumn('low_price');
      });
      Schema::table('sale_items',function (Blueprint $table){
        $table->dropColumn('low_price');
      });
    }
}
