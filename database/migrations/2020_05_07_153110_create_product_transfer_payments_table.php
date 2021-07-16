<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTransferPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_transfer_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('amount', 10,2);
            $table->decimal('confirm_amount', 10,2);
            $table->unsignedInteger('product_transfer_id');
            $table->foreign('product_transfer_id')->references('id')->on('product_transfers');
            $table->unsignedInteger('account_id');
            $table->foreign('account_id')->references('id')->on('bank_accounts');
            $table->unsignedInteger('transaction_id');
            $table->foreign('transaction_id')->references('id')->on('bank_tx_payments');
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
        Schema::table('product_transfer_payments', function ($table) {
            $table->dropForeign(['account_id']); // Drop foreign key 'user_id' from 'posts' table('account_id');
            $table->dropForeign(['product_transfer_id']); // Drop foreign key 'user_id' from 'posts' table('product_transfer_id');
            $table->dropForeign(['transaction_id']); // Drop foreign key 'user_id' from 'posts' table('transaction_id');
        });
        Schema::drop('product_transfer_payments');
    }
}
