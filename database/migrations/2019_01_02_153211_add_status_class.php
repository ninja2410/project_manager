<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusClass extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('class_tables', function (Blueprint $table) {
      $table->unsignedInteger('status_id');
      // $table->foreign('status_id')->references('id')->on('state_cellars')->onDelete('restrict');
      $table->foreign('status_id')->references('id')->on('state_cellars');

      $table->unsignedInteger('user_id');
      $table->foreign('user_id')->references('id')->on('users');

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

      $table->dropForeign('class_tables_status_id_foreign');
      $table->dropForeign('class_tables_user_id_foreign');
      $table->dropColumn(['status_id', 'user_id']);
    });
  }
}
