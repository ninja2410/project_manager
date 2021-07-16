<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDataUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('users', function(Blueprint $table){
        $table->string('business_maps');
        $table->string('last_name');
        $table->string('DPI');
        $table->date('birthdate');
        $table->date('date_hire');
        $table->date('date_dimissal');
        $table->string('nationality');
        $table->string('phone');
        $table->string('mobile');
        $table->string('address');
        $table->string('alternative_address');
        $table->string('no_IGSS');
        $table->integer('number');
        $table->string('emergency_name');
        $table->string('emergency_phone');
        $table->string('shoe_size');
        $table->string('trouser_size');
        $table->string('shirt_size');
        $table->string('comments');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
