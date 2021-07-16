<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users',function(Blueprint $table){
          $table->integer('user_state')->unsigned();
          $table->foreign('user_state')->references('id')->on('state_cellars');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('users', function(Blueprint $table){
        // DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        // $table->dropColumn('user_state');
        // DB::statement('SET FOREIGN_KEY_CHECKS = 1');
      });
    }
}
