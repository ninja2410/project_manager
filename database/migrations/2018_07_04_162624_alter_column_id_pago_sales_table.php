<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterColumnIdPagoSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('sales',function(Blueprint $table){
        //     $table->dropForeign('sales_id_pago_foreign');
        //      $table->integer('id_pago')->nullable()->change();
        //     // $table->dropColumn('id_pago');
        // });
        // Schema::table('sales',function(Blueprint $table){
        //     $table->integer('id_pago')->unsigned()->nullable();
        //     $table->foreign('id_pago')->references('id')->on('pagos');
        // });
        // DB::Statement("ALTER TABLE sales MODIFY id_pago int DEFAULT NULL;");
        Schema::table('sales',function(Blueprint $table){
            $table->integer('id_pago')->nullable()->unsigned()->change();
        });
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
