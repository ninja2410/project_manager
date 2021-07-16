<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_transfers', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->unsignedInteger('correlative');
            $table->decimal('amount');
            $table->decimal('quantity_items');
            $table->date('date_received');
            $table->string('comment');
            $table->unsignedInteger('serie_id');
            $table->foreign('serie_id')->references('id')->on('series');
            $table->unsignedInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->unsignedInteger('almacen_origin');
            $table->foreign('almacen_origin')->references('id')->on('almacens');
            $table->unsignedInteger('almacen_destination');
            $table->foreign('almacen_destination')->references('id')->on('almacens');
            $table->unsignedInteger('status_id');
            $table->foreign('status_id')->references('id')->on('state_cellars');
            $table->unsignedInteger('account_credit_id')->nullable();
            $table->foreign('account_credit_id')->references('id')->on('bank_accounts');
            $table->unsignedInteger('price_id')->nullable();
            $table->foreign('price_id')->references('id')->on('prices');
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
        Schema::table('product_transfers', function ($table) {
            $table->dropForeign(['serie_id']); // Drop foreign key 'user_id' from 'posts' table('serie_id');
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropForeign(['almacen_origin']);
            $table->dropForeign(['almacen_destination']);
            $table->dropForeign(['status_id']);
            $table->dropForeign(['account_credit_id']);
        });
        Schema::drop('product_transfers');
    }
}
