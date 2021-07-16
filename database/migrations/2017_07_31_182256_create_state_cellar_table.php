<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStateCellarTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('state_cellars', function (Blueprint $table) {
      $table->increments('id');
      $table->string('name');
      $table->integer('type_number');
      $table->string('type');
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
    Schema::dropIfExists('state_cellars');
    DB::statement('SET FOREIGN_KEY_CHECKS = 1');
  }
}
