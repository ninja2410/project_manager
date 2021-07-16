<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_items', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('quantity', 12,2);
            $table->decimal('unit_cost',12,2);
            $table->decimal('total_cost',12,2);
            $table->unsignedInteger('item_id');
            $table->foreign('item_id')->references('id')->on('items');
            $table->unsignedInteger('budget_detail_id');
            $table->foreign('budget_detail_id')->references('id')->on('budget_details')->onDelete('cascade');
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
        Schema::table('budget_items', function ($table) {
            $table->dropForeign(['item_id']);
            $table->dropForeign(['budget_detail_id']);
        });
        Schema::drop('budget_items');
    }
}
