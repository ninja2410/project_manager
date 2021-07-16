<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccountOriginDestinationTransfersStorage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('receivings', function (Blueprint $table) {
            $table->unsignedInteger('id_account_origin')->nullable();
            $table->foreign('id_account_origin')->references('id')
                ->on('bank_accounts');
            $table->unsignedInteger('id_account_destination')->nullable();
            $table->foreign('id_account_destination')->references('id')
                ->on('bank_accounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('receivings', function ($table) {
            $table->dropForeign(['id_account_origin']);
            $table->dropColumn('id_account_origin');
            $table->dropForeign(['id_account_destination']);
            $table->dropColumn('id_account_destination');
        });
    }
}
