<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->date('paid_date');
            $table->unsignedInteger('credit_id');
            $table->foreign('credit_id')->references('id')->on('credits');            
            $table->unsignedInteger('revenue_id');
            $table->string('comment')->nullable();
            $table->double('amount',10,2)->default(0);

            $table->foreign('revenue_id')->references('id')->on('bank_tx_revenues');
            $table->unsignedInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');            
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
        Schema::drop('credit_payments');
    }
}
