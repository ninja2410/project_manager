<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailCreditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_credits',function(Blueprint $table){
          $table->increments('id');
          $table->integer('id_factura')->unsigned();
          $table->foreign('id_factura')->references('id')->on('credits');
          $table->date('date_payments');
          $table->decimal('total_payments');
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
      Schema::dropIfExists('detail_credits');
    }
}
