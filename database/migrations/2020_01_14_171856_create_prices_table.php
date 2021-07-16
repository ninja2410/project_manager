<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->decimal('pct');
            $table->decimal('pct_min');
            $table->decimal('amount');
            $table->decimal('amount_min');
            $table->decimal('cant_min');
            $table->decimal('cant_max');
            $table->date('date_min');
            $table->date('date_max');
            $table->integer('order');
            $table->boolean('main')->default(0);
            $table->boolean('active')->default(1);
            $table->boolean('system')->default(0);
            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('prices');
    }
}
