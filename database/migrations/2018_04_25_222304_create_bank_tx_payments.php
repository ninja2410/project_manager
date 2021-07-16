<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankTxPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_tx_payments', function (Blueprint $table) {
            //

            $table->increments('id');
            $table->integer('company_id')->unsigned();
            /*Cuenta bancaria asociada*/
            $table->integer('account_id')->unsigned();
            $table->foreign('account_id')->references('id')->on('bank_accounts')
                ->onUpdate('cascade')->onDelete('cascade');


            $table->date('paid_at');
            $table->double('amount', 15, 4);
            $table->string('currency',5)->default('Q');
            // $table->enum('currency', ['Q', '$', 'MX $', 'L', 'C$', '₡', 'Bz$'])->default('Q');
            $table->double('currency_rate', 15, 8);

            /*Compra*/
            $table->integer('bill_id')->nullable();

            /*Proveedor*/
            $table->unsignedInteger('supplier_id')->nullable();
            $table->text('description')->nullable();

            // /*Categoria de gasto*/
            // $table->integer('category_id')->unsigned();
            // $table->foreign('category_id')->references('id')->on('bank_transactions_catalogue')
            //     ->onUpdate('cascade')->onDelete('cascade');


            /*Metodo de pago*/
            $table->integer('payment_method')->unsigned();
            $table->foreign('payment_method')->references('id')->on('pagos')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->string('reference')->nullable();
            $table->boolean('status')->default(1);

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');


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
        Schema::dropIfExists('bank_tx_payments');

    }
}
