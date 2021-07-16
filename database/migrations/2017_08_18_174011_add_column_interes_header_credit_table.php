<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnInteresHeaderCreditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('credits',function(Blueprint $table){
          $table->decimal('total_interes',9,2);
          $table->decimal('paid_amount',9,2);
          $table->unsignedInteger('status_id');
          $table->foreign('status_id')->references('id')->on('state_cellars');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('credits',function(Blueprint $table){
          if (Schema::hasColumn('credits','total_interes')){
            $table->dropColumn('total_interes');
          }
          if (Schema::hasColumn('credits','paid_amount')){
            $table->dropColumn('paid_amount');
          }
          $table->dropForeign(['status_id']);
          $table->dropColumn('status_id');
        });
    }
}
