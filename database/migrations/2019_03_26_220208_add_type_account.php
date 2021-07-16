<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('bank_accounts', function (Blueprint $table) {
        $table->unsignedInteger('categorie_id')->nullable();
        $table->foreign('categorie_id')->references('id')->on('categories');
      });
      Schema::table('almacens', function (Blueprint $table) {
        $table->unsignedInteger('categorie_id')->nullable();
        $table->foreign('categorie_id')->references('id')->on('categories');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('bank_accounts', function($table){
        $table->dropForeign(['categorie_id']);
        $table->dropColumn('categorie_id');
      });
      Schema::table('almacens', function($table){
        $table->dropForeign(['categorie_id']);
        $table->dropColumn('categorie_id');
      });
    }
}
