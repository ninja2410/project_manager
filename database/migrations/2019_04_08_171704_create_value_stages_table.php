<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateValueStagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('value_stages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('value');
            $table->date('date');
            $table->boolean('status');
            $table->unsignedInteger('atribute_id');
            $table->foreign('atribute_id')->references('id')->on('atributes');
            $table->unsignedInteger('project_id');
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
        Schema::table('value_stages', function($table){
            if (Schema::hasColumn('value_stages','atribute_id')){
            $table->dropForeign(['atribute_id']);
            $table->dropColumn('atribute_id');
            }
            if (Schema::hasColumn('value_stages','project_id')){
            $table->dropForeign(['project_id']);
            $table->dropColumn('project_id');
            }
        });
        if (Schema::hasTable('value_stages')) {
            Schema::drop('value_stages');
        }
    }
}
