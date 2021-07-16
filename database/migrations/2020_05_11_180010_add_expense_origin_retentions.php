<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExpenseOriginRetentions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reg_retentions', function (Blueprint $table) {
            $table->unsignedInteger('expense_id');
            $table->foreign('expense_id')->references('id')->on('bank_tx_payments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reg_retentions', function ($table) {
            $table->dropForeign(['expense_id']);
        });
    }
}
