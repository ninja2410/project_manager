<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryAdjustmentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_adjustment_details', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('inventory_adjustment_id')->unsigned();
            $table->foreign('inventory_adjustment_id')->references('id')->on('inventory_adjustments');

            $table->integer('item_id')->unsigned();
            $table->foreign('item_id')->references('id')->on('items');

            $table->decimal('new_quantity', 9, 2)->nullable();
            $table->decimal('quantity', 9, 2)->nullable();
            $table->decimal('previous_quantity', 9, 2)->nullable();
            
            
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
        Schema::dropIfExists('inventory_adjustment_details');
    }
}
