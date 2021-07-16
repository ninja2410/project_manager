<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('accounts', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->string('name');
        //     $table->decimal('amount');
        //     $table->unsignedInteger('user_id');
        //     $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
        //     $table->date('date');
        //     $table->string('description');
        //     $table->unsignedInteger('status_id');
        //     $table->foreign('status_id')->references('id')->on('state_cellars')->onDelete('restrict');
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::drop('accounts');
    }
}
