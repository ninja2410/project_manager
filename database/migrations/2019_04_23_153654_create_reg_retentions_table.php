<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegRetentionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reg_retentions', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('calculated_value');
            $table->date('date');
            $table->decimal('real_value');
            $table->unsignedInteger('retention_id');
            $table->foreign('retention_id')->references('id')->on('retentions');
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
        Schema::table('reg_retentions', function($table){
          $table->dropForeign(['project_id']);
          $table->dropColumn('project_id');
          $table->dropForeign(['retention_id']);
          $table->dropColumn('retention_id');
        });
        Schema::drop('reg_retentions');
    }
}
