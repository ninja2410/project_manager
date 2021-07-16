<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCreditNoteExpenses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->unsignedInteger('credit_note_id')->nullable();
            $table->foreign('credit_note_id')->references('id')->on('credit_notes');
            $table->unsignedInteger('bank_expense_id')->nullable();
            $table->foreign('bank_expense_id')->references('id')->on('bank_tx_payments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expenses', function ($table) {
            $table->dropForeign(['credit_note_id']);
            $table->dropForeign(['bank_expense_id']);
            $table->dropColumn('credit_note_id', 'bank_expense_id');
        });
    }
}
