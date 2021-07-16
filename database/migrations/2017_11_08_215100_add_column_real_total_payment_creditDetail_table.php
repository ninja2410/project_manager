<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnRealTotalPaymentCreditDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('detail_credits',function (Blueprint $table){
        $table->decimal('real_total_payment',9,2);
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('detail_credits',function (Blueprint $table){
        $table->dropColumn('real_total_payment');
      });
    }
}
