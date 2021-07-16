<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('series',function(Blueprint $table){
        $table->increments('id');
        $table->string('name',255);
        $table->integer('id_document')->unsigned();
        $table->foreign('id_document')->references('id')->on('documents');
        $table->integer('status')->default(1);
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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        // Schema::dropIfExists('series');
        Schema::drop('series');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
