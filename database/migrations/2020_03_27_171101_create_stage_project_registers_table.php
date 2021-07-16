<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStageProjectRegistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stage_project_registers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('project_id');
            $table->foreign('project_id')->references('id')->on('projects');
            $table->unsignedInteger('stage_id');
            $table->foreign('stage_id')->references('id')->on('stages');
            $table->boolean('status');
            $table->unsignedInteger('update_by');
            $table->foreign('update_by')->references('id')->on('users');
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
        Schema::table('stage_project_registers', function ($table) {
            $table->dropForeign(['project_id']);
            $table->dropColumn('project_id');
            $table->dropForeign(['stage_id']);
            $table->dropColumn('stage_id');
            $table->dropForeign(['update_by']);
            $table->dropColumn('update_by');
        });
        Schema::drop('stage_project_registers');
    }
}
