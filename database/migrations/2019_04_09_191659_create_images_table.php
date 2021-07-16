<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('path');
            $table->unsignedInteger('stage_id');
            $table->foreign('stage_id')->references('id')->on('stages');
            $table->unsignedInteger('project_id')->nullable();
            $table->foreign('project_id')->references('id')->on('projects');
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
        Schema::table('images', function($table){
          $table->dropForeign(['stage_id']);
          $table->dropColumn('stage_id');
          $table->dropForeign(['project_id']);
          $table->dropColumn('project_id');
        });
        Schema::drop('images');
    }
}
