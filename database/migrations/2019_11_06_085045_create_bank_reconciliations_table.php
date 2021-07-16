<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankReconciliationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_reconciliations', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->integer('month');
            $table->integer('year');
            $table->boolean('closed')->default('0');
            $table->decimal('start_balance');
            $table->decimal('countable_balance');
            $table->decimal('bank_balance');
            $table->decimal('transit_revenue');
            $table->decimal('recon_expenses');
            $table->decimal('recon_revenues');
            $table->decimal('outstanding_payments');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedInteger('updated_by');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->unsignedInteger('account_id');
            $table->foreign('account_id')->references('id')->on('bank_accounts');
            $table->string('comment');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bank_reconciliations', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by');
            $table->dropForeign(['account_id']);
            $table->dropColumn('account_id');
        });
        Schema::drop('bank_reconciliations');
    }
}
