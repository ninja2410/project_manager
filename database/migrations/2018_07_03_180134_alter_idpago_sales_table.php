<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterIdpagoSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('sales',function(Blueprint $table){
        //     $table->integer('id_pago')->nullable()->change();
        // });
        // Schema::table('sales',function(Blueprint $table){
        //     $table->dropForeign('id_pago');
        //     // $table->dropColumn('id_pago');
        // });
        // Schema::table('sales',function(Blueprint $table){
        //     $table->integer('id_pago')->unsigned()->nullable();
        //     $table->foreign('id_pago')->references('id')->on('pagos');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
