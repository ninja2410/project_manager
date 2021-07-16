<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankAccountsPagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_accounts_pagos', function (Blueprint $table) {
            $table->increments('id');            
            $table->integer('bank_account_type_id')->unsigned();
            $table->foreign('bank_account_type_id')->references('id')->on('bank_account_types');
            $table->integer('pago_id')->unsigned();
            $table->foreign('pago_id')->references('id')->on('pagos');
            $table->boolean('ingreso')->default(1);
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
        Schema::drop('bank_accounts_pagos');
    }
}
