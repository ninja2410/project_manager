<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeskClosingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('desk_closing_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('desk_closing_id')->unsigned();
            $table->foreign('desk_closing_id')->references('id')->on('desk_closings');

            $table->unsignedInteger('payment_type_id')->nullable();
            $table->foreign('payment_type_id')->references('id')->on('pagos');

            $table->integer('money_quanity')->default(0);

            $table->unsignedInteger('revenue_id')->nullable();
            $table->foreign('revenue_id')->references('id')->on('bank_tx_revenues');

            $table->unsignedInteger('payment_id')->nullable();
            $table->foreign('payment_id')->references('id')->on('bank_tx_payments');

            $table->unsignedInteger('money_type_quantity_id')->nullable();
            $table->foreign('money_type_quantity_id')->references('id')->on('money_types');

            $table->decimal('amount', 9, 2)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('desk_closing_details', function (Blueprint $table) {
            $table->dropForeign(['desk_closing_id']);
            $table->dropColumn('desk_closing_id');
            $table->dropForeign(['payment_type_id']);
            $table->dropColumn('payment_type_id');
            $table->dropForeign(['revenue_id']);
            $table->dropColumn('revenue_id');
            $table->dropForeign(['payment_id']);
            $table->dropColumn('payment_id');
            $table->dropForeign(['money_type_quantity_id']);
            $table->dropColumn('money_type_quantity_id');
        });
        Schema::drop('desk_closing_details');
    }
}
