<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeskClosingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('desk_closings', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('account_id')->nullable();
            $table->foreign('account_id')->references('id')->on('bank_accounts');

            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('users');

            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('users');

            $table->integer('correlative');

            $table->integer('serie_id')->unsigned();
            $table->foreign('serie_id')->references('id')->on('series');

            $table->dateTime('startDate');
            $table->dateTime('finalDate');
            $table->decimal('cash_amount', 9, 2)->nullable();
            $table->decimal('deposit_amount', 9, 2)->nullable();
            $table->decimal('check_amount', 9, 2)->nullable();
            $table->decimal('transfer_amount', 9, 2)->nullable();
            $table->decimal('card_amount', 9, 2)->nullable();
            $table->decimal('total', 9, 2)->nullable();

            $table->unsignedInteger('status_id');
            $table->foreign('status_id')->references('id')->on('state_cellars')->onDelete('restrict');

            $table->decimal('initial_balance', 9, 2)->nullable();
            $table->decimal('final_balance', 9, 2)->nullable();

            $table->unique(['serie_id', 'correlative']);
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
        Schema::table('desk_closings', function (Blueprint $table) {
            $table->dropForeign(['account_id']);
            $table->dropColumn('account_id');
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by');
            $table->dropForeign(['serie_id']);
            $table->dropColumn('serie_id');
            $table->dropForeign(['status_id']);
            $table->dropColumn('status_id');
        });
        Schema::drop('desk_closings');
    }
}
