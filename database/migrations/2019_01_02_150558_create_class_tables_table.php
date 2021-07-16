<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_tables', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('arrears');
            $table->decimal('pctRen');
            $table->decimal('pctAmountRen');
            $table->boolean('noPaySurcharge');
            $table->string('color');
            $table->string('description');
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
        Schema::drop('class_tables');
    }
}
