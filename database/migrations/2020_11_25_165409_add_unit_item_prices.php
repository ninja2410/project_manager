<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUnitItemPrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_prices', function(Blueprint $table)
        {
          $table->decimal('quantity', 10,2);
          $table->boolean('default');
          $table->unsignedInteger('unit_id')->nullable();
          $table->foreign('unit_id')->references('id')->on('unit_measures');
        });
        Schema::table('items', function(Blueprint $table)
        {
          $table->decimal('budget_cost', 10,2)->nullable()->change();
          $table->integer('days_valid')->nullable()->change();
          $table->integer('monts_valid')->nullable()->change();
          $table->date('updated_budget_cost_at')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_prices', function($table)
        {
          $table->dropForeign(['unit_id']);
          $table->dropColumn('unit_id', 'quantity');
        });
    }
}
