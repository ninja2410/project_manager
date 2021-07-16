<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToBankTxPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bank_tx_payments', function (Blueprint $table) {
            $table->double('amount_applied', 15, 4);
            // $table->string('bank_name')->nullable();
            $table->string('recipient')->nullable();
            $table->string('card_number',4)->nullable();
            $table->integer('status')->unsigned()->change();
            $table->foreign('status')->references('id')->on('state_cellars');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bank_tx_payments', function (Blueprint $table) {
            $table->dropForeign(['status']);
            $table->dropColumn('status');
            $table->dropColumn('amount_applied');
            $table->dropColumn('recipient');
            $table->dropColumn('card_number'); 
        });
    }
}
