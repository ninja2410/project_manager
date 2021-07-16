<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKitIdItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('receiving_temps', function (Blueprint $table) {
        $table->integer('kit_id');
      });
      Schema::table('receiving_temps', function (Blueprint $table) {
        $table->integer('sale_id')->nullable();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('receiving_temps', function ( $table) {
          if (Schema::hasColumn('receiving_temps', 'kit_id')) {
            $table->dropColumn(['kit_id']);
          }
          if (Schema::hasColumn('receiving_temps', 'sale_id')) {
            $table->dropColumn('sale_id');
          }
        });
    }
}
