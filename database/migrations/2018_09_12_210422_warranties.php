<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Warranties extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('warranties', function (Blueprint $table) {
          $table->increments('id');
          $table->string('name');
          $table->decimal('price');
          $table->string('description');
          $table->boolean('status');
          $table->string('categoria');
          $table->integer('pagare_id');
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
        Schema::drop('warranties');
    }
}
