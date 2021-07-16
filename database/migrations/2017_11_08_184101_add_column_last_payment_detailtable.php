<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnLastPaymentDetailtable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('detail_credits', function (Blueprint $table){
        $table->integer('last_payment')->default(0);
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('detail_credits', function (Blueprint $table){
        $table->dropColumn('last_payment');
      });
    }
}
