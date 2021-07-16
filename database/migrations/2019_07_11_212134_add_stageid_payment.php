<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStageidPayment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('bank_tx_payments', function (Blueprint $table) {
        $table->unsignedInteger('stage_id')->nullable();
        $table->foreign('stage_id')->references('id')->on('stages');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('bank_tx_payments', function($table){
        $table->dropForeign(['stage_id']);
        $table->dropColumn('stage_id');
      });
    }
}
