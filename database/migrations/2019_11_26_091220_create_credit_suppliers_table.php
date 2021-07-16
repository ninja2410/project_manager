<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_suppliers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('supplier_id')->unsigned();
            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->integer('receiving_id')->unsigned()->nullable();
            $table->decimal('paid_amount');
            $table->decimal('total_interes');
            $table->integer('expense_id')->unsigned()->nullable();
            $table->foreign('expense_id')->references('id')->on('expenses');
            $table->foreign('receiving_id')->references('id')->on('receivings');
            $table->integer('number_payments');
            $table->unsignedInteger('status_id');
            $table->foreign('status_id')->references('id')->on('state_cellars');
            $table->decimal('enganche',9,2);
            $table->date('date_payments');
            $table->decimal('credit_total',9,2);
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
        Schema::dropIfExists('credit_suppliers');
    }
}
