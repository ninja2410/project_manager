<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPriceAndDatevalidItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->decimal('budget_cost', 10 ,2);
            $table->date('updated_budget_cost_at');
            $table->unsignedInteger('days_valid')->default('0');
            $table->unsignedInteger('monts_valid')->default('0');
        });
        \DB::statement('UPDATE items SET budget_cost = cost_price, updated_budget_cost_at = created_at');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function ($table) {
            $table->dropColumn('budget_cost');
            $table->dropColumn('updated_budget_cost_at');
            $table->dropColumn('days_valid');
            $table->dropColumn('monts_valid');
        });
    }
}
