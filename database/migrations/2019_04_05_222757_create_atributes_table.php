<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAtributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('atributes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('control');
            $table->integer('status');
            $table->string('size');
            $table->string('type');
            $table->unsignedInteger('stage_id');
            $table->foreign('stage_id')->references('id')->on('stages');
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
        Schema::table('atributes', function($table){
          $table->dropForeign(['stage_id']);
          $table->dropColumn('stage_id');
        });
        Schema::drop('atributes');
    }
}
