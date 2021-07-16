<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankTxRevenues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_tx_revenues', function (Blueprint $table) {
            //

            $table->increments('id');
            $table->integer('company_id')->unsigned();
            /*Cuenta bancaria asociada*/
            $table->integer('account_id')->unsigned();
            $table->foreign('account_id')->references('id')->on('bank_accounts');

            $table->date('paid_at');
            $table->double('amount', 15, 4);
            $table->string('currency',5)->default('Q');
            // $table->enum('currency', ['Q', '$', 'MX $', 'L', 'C$', '₡', 'Bz$'])->default('Q');
            $table->double('currency_rate', 15, 8);

            /*Factura*/
            $table->integer('invoice_id')->nullable();

            /*Cliente*/
            $table->integer('customer_id')->nullable();
            $table->text('description')->nullable();

            /*Tipo de transaccion*/
            // $table->integer('category_id')->unsigned();
            // $table->foreign('category_id')->references('id')->on('bank_transactions_catalogue');


            /*Metodo de pago*/
            $table->integer('payment_method')->unsigned();
            $table->foreign('payment_method')->references('id')->on('pagos');

            $table->string('reference')->nullable();
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
        Schema::table('bank_tx_revenues', function (Blueprint $table) {
            $table->dropForeign(['account_id']);
            // $table->dropForeign(['category_id']);
            $table->dropForeign(['payment_method']);
            $table->dropForeign(['user_id']);
            $table->dropColumn(['account_id', 'payment_method', 'user_id']);
        });

        Schema::dropIfExists('bank_tx_revenues');

    }
}
