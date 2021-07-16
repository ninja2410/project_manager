<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnTotalCostInSales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('sales', function (Blueprint $table){
        $table->decimal('total_cost',9,2);
        $table->decimal('total_paid',9,2);
      });
      Schema::table('receivings',function (Blueprint $table){
        $table->decimal('total_cost',9,2);
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('sales',function (Blueprint $table){
        //$table->dropColumn('total_cost');
        //$table->dropColumn('total_paid');
      });
      Schema::table('receivings',function (Blueprint $table){
        $table->dropColumn('total_cost');
      });
    }
}
