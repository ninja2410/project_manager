<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnFlagDocumentShowHeaderSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales',function(Blueprint $table){
          $table->integer('show_header')->default(1);/*Se usa para guardar el ID de un credito */
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('sales',function(Blueprint $table){
        $table->dropColumn('show_header');
      });
    }
}
