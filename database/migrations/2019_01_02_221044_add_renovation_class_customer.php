<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRenovationClassCustomer extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('class_tables', function (Blueprint $table) {
      $table->boolean('renovation');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('class_tables', function ($table) {
      $table->dropColumn('renovation');
    });
  }
}
