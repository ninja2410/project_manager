<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('detail_accounts', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->decimal('amount');
        //     $table->boolean('type');
        //     $table->string('description');
        //     $table->unsignedInteger('account_id');
        //     $table->foreign('account_id')->references('id')->on('accounts')->onDelete('restrict');
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
        // Schema::drop('detail_accounts');
    }
}
