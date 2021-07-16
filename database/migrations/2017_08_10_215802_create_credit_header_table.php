<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditHeaderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credits',function(Blueprint $table){
            $table->increments('id');
            $table->integer('id_cliente')->unsigned();
            $table->foreign('id_cliente')->references('id')->on('customers');
            $table->integer('id_factura')->unsigned();
            $table->foreign('id_factura')->references('id')->on('sales');
            $table->integer('number_payments');
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
        Schema::dropIfExists('credits');
    }
}
