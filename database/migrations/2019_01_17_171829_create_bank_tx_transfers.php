<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankTxTransfers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_tx_transfers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->unsigned();

            $table->integer('payment_id')->unsigned();
            $table->foreign('payment_id')->references('id')->on('bank_tx_payments');

            $table->integer('revenue_id')->unsigned();
            $table->foreign('revenue_id')->references('id')->on('bank_tx_revenues');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->boolean('status')->default(1);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('bank_tx_transfers', function (Blueprint $table) {
            $table->dropForeign(['payment_id']);
            $table->dropForeign(['revenue_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn(['payment_id', 'revenue_id', 'user_id']);
        });


        Schema::dropIfExists('bank_tx_transfers');
    }
}
