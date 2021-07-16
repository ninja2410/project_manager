<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettlementRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settlement_routes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tour');
            $table->string('week');
            $table->date('date_begin');
            $table->date('date_end');
            $table->decimal('amount_expenses');
            $table->decimal('amount_sales');
            $table->decimal('amount_payments');
            $table->string('comment_expenses', 500);
            $table->string('comment_sales', 500);
            $table->string('comment_payments', 500);
            $table->unsignedInteger('correlative');
            $table->decimal('comission');
            $table->decimal('diference');
            $table->softDeletes();
            /**
             * LLAVES FORÁNEAS
             */
            $table->unsignedInteger('user_asigned');
            $table->foreign('user_asigned')->references('id')->on('users');
            $table->unsignedInteger('route_id');
            $table->foreign('route_id')->references('id')->on('routes');
            $table->unsignedInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedInteger('updated_by');
            $table->foreign('updated_by')->references('id')->on('users');
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
        Schema::drop('settlement_routes');
    }
}
