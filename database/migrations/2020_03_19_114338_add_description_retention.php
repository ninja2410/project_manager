<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDescriptionRetention extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reg_retentions', function (Blueprint $table) {
            $table->unsignedInteger('revenue_origin_id')->nullable();
            $table->foreign('revenue_origin_id')->references('id')->on('bank_tx_revenues');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reg_retentions', function ($table) {
            $table->dropForeign(['revenue_origin_id']); // Drop foreign key 'user_id' from 'posts' table('change');
            $table->dropColumn('revenue_origin_id');
        });
    }
}
