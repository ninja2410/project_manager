<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProReceivingTemp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('receiving_temps', function (Blueprint $table) {
        $table->decimal('pr_price');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('receiving_temps', function (Blueprint $table) {
          $table->dropColumn(['pr_price']);
      });
    }
}
