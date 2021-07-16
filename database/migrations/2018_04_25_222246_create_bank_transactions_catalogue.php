<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankTransactionsCatalogue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_transactions_catalogue', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('company_id')->unsigned();

            $table->string('transaction_name');
            $table->string('transaction_sign',2)->default('+');
            // $table->enum('transaction_sign', ['+', '-', '='])->default('+');

            $table->boolean('status')->default(1);

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists('bank_transactions_catalogue');

        // Schema::table('bank_transactions_catalogue', function (Blueprint $table) {
        //     //
        // });
    }
}
