<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTransferCost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('receivings', function (Blueprint $table) {
            $table->unsignedInteger('status_transfer_id')->nullable();
            $table->foreign('status_transfer_id')->references('id')->on('state_cellars');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('receivings', function (Blueprint $table) {
            $table->dropForeign(['status_transfer_id']);
            $table->dropColumn('status_transfer_id');
        });
    }
}
