<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWeeklySettlementDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weekly_settlement_details', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('quantity', 10,2);
            $table->unsignedInteger('item_id');
            $table->foreign('item_id')->references('id')
                ->on('items');
            $table->unsignedInteger('weekly_id');
            $table->foreign('weekly_id')->references('id')
                ->on('weekly_settlements');
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
        Schema::drop('weekly_settlement_details');
    }
}
