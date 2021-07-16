<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReceiptNumberToRevenueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bank_tx_revenues', function (Blueprint $table) {
            $table->integer('receipt_number')->after('account_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bank_tx_revenues', function (Blueprint $table) {
            $table->dropColumn('receipt_number');
        });
    }
}
