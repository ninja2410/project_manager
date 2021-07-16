<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApplyedAmountCreditNote extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('credit_notes', function (Blueprint $table) {
            $table->decimal('amount_applied', 10, 2);
        });
        Schema::table('customers', function (Blueprint $table) {
            $table->decimal('positive_balance', 10, 2)->default(0);
        });
        Schema::table('credit_note_details', function (Blueprint $table) {
            $table->boolean('discount')->default(0);
            $table->unsignedInteger('bodega_id')->nullable();
            $table->foreign('bodega_id')->references('id')->on('almacens');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('credit_notes', function ($table) {
            $table->dropColumn('amount_applied');
        });
        Schema::table('customers', function ($table) {
            $table->dropColumn('positive_balance');
        });
        Schema::table('credit_note_details', function ($table) {
            $table->dropColumn('discount');
            $table->dropForeign(['bodega_id']);
            $table->dropColumn('bodega_id');
        });
    }
}
