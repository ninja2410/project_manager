<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTransactionsReconciliationInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bank_tx_payments', function (Blueprint $table) {
            $table->boolean('reconcilied')->default('0');
            $table->unsignedInteger('reconciliation_id')->nullable();
        });
        Schema::table('bank_tx_revenues', function (Blueprint $table) {
            $table->boolean('reconcilied')->default('0');
            $table->unsignedInteger('reconciliation_id')->nullable();
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
            $table->dropColumn('reconcilied');
            $table->dropColumn('reconciliation_id');
        });
        Schema::table('bank_tx_revenues', function (Blueprint $table) {
            $table->dropColumn('reconcilied');
            $table->dropColumn('reconciliation_id');
        });
    }
}
