<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToPagoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->boolean('venta');
            $table->integer('orden_venta')->default(0);
            $table->boolean('default_venta')->default(0);

            $table->boolean('compra');
            $table->integer('orden_compra')->default(0);
            $table->boolean('default_compra')->default(0);

            $table->boolean('banco_in');
            $table->integer('orden_banco_in')->default(0);
            $table->boolean('default_banco_in')->default(0);

            $table->boolean('banco_out');
            $table->integer('orden_banco_out')->default(0);
            $table->boolean('default_banco_out')->default(0);
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pagos', function (Blueprint $table) {
//            $table->dropColumn('venta');
//            $table->dropColumn('orden_venta');
//            $table->dropColumn('default_venta');
//
//            $table->dropColumn('compra');
//            $table->dropColumn('orden_compra');
//            $table->dropColumn('default_compra');
//
////            $table->dropColumn('banco');
//            $table->dropColumn('orden_banco');
//            $table->dropColumn('default_banco');
        });
    }
}
