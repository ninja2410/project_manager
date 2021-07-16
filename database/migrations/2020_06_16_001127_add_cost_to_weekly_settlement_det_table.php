<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCostToWeeklySettlementDetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('weekly_settlement_details', function (Blueprint $table) {
            $table->decimal('cost', 10,3);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('weekly_settlement_details', function ($table) {
            $table->dropColumn('cost');
        });
    }
}
