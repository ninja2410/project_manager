<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsDetailsCreditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('detail_credits',function (Blueprint $table){
          $table->date('payment_real_date');
          $table->decimal('surcharge',9,2);
          $table->integer('estado')->default(1);
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('detail_credits',function(Blueprint $table){
        // $table->dropColumn('payment_real_date');
        // $table->dropColumn('surcharge',9,2);
        // $table->dropColumn('estado');
      });
    }
}
