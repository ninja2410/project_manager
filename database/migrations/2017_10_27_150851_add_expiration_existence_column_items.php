<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExpirationExistenceColumnItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function(Blueprint $table){
          $table->date('expiration_date');
          $table->integer('minimal_existence');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('items',function(Blueprint $table){
        $table->dropColumn('expiration_date');
        $table->dropColumn('minimal_existence');
      });
    }
}
