<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterItemsForeingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('items', function(Blueprint $table)
  		{
        $table->integer('id_categorie')->unsigned();
  			$table->foreign('id_categorie')->references('id')->on('categories');
  		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('items', function(Blueprint $table)
  		{
        $table->dropForeign(['id_categorie']);
        $table->dropColumn('id_categorie');
      });
    }
}
