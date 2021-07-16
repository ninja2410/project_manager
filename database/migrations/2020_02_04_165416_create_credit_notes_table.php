<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_notes', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('amount', 12, 2);
            $table->unsignedInteger('correlative');
            $table->date('date');
            $table->string('comment');
            $table->string('reference');
            $table->unsignedInteger('type');
            $table->unsignedInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->unsignedInteger('status_id');
            $table->foreign('status_id')->references('id')->on('state_cellars');
            $table->unsignedInteger('sale_id');
            $table->foreign('sale_id')->references('id')->on('sales');
            $table->unsignedInteger('serie_id');
            $table->foreign('serie_id')->references('id')->on('series');
            $table->unsignedInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedInteger('updated_by')->nullable();
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
        Schema::drop('credit_notes');
    }
}
