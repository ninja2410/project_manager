<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('targets', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('goal');
            $table->date('date');
            $table->unsignedInteger('route_id');
            $table->foreign('route_id')->references('id')->on('routes');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::table('targets', function ($table) {
          $table->dropForeign(['route_id']);
          $table->dropForeign(['user_id']);
          $table->dropColumn('user_id');
          $table->dropColumn('route_id');
        });
        Schema::drop('targets');
    }
}
