<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTransferDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_transfer_details', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('quantity', 10,2);
            $table->decimal('quantity_received', 10,2);
            $table->decimal('cost', 10,2);
            $table->unsignedInteger('item_id');
            $table->foreign('item_id')->references('id')->on('items');
            $table->unsignedInteger('product_transfer_id');
            $table->foreign('product_transfer_id')->references('id')->on('product_transfers');
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
        Schema::table('product_transfer_details', function ($table) {
            $table->dropForeign(['item_id']);
            $table->dropForeign(['product_transfer_id']);
        });
        Schema::drop('product_transfer_details');
    }
}
