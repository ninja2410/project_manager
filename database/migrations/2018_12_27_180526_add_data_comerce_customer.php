<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDataComerceCustomer extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('customers', function (Blueprint $table) {
      $table->string('business_name');
      $table->string('business_description');
      $table->string('business_address');
      $table->string('business_phone');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('customers', function ($table) {
      $table->dropColumn('business_name');
      $table->dropColumn('business_description');
      $table->dropColumn('business_address');
      $table->dropColumn('business_phone');
    });
  }
}
