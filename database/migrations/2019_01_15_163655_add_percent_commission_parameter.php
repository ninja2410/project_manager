<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPercentCommissionParameter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('parameters', function(Blueprint $table){
        $table->decimal('percent_commission');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('parameters', function(Blueprint $table){
        $table->dropColumn('percent_commission');
      });
    }
}
