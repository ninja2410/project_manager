<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('transfers', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->unsignedInteger('account_id');
        //     $table->foreign('account_id')->references('id')->on('accounts')->onDelete('restrict');
        //     $table->decimal('amount');
        //     $table->boolean('type');
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
        // Schema::dropIfExists('transfers');
    }
}
