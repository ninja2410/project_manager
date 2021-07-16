<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRevenueIdRetentionDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reg_retentions', function (Blueprint $table) {
            $table->unsignedInteger('revenue_id')->nullable();
            $table->foreign('revenue_id')->references('id')
                ->on('bank_tx_revenues');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
