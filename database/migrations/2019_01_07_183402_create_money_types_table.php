<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMoneyTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('money_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->decimal('value');
            $table->unsignedInteger('status_id');
            $table->foreign('status_id')->references('id')->on('state_cellars')->onDelete('restrict');
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
        Schema::drop('money_types');
    }
}
