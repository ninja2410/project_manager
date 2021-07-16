<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditSupplierDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_supplier_details', function (Blueprint $table) {
            $table->increments('id');
            $table->date('paid_date');
            $table->unsignedInteger('credit_supplier_id');
            $table->foreign('credit_supplier_id')->references('id')->on('credit_suppliers');
            $table->unsignedInteger('expense_id');
            $table->string('comment')->nullable();
            $table->double('amount',10,2)->default(0);
            $table->foreign('expense_id')->references('id')->on('bank_tx_payments');
            $table->unsignedInteger('receiving_id')->nullable();
            $table->foreign('receiving_id')->references('id')->on('receivings');
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
        Schema::dropIfExists('credit_supplier_details');
    }
}
