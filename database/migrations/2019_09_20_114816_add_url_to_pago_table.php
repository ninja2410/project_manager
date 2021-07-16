<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUrlToPagoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->string('venta_url')->nullable();
            $table->string('compra_url')->nullable();
            $table->string('banco_in_url')->nullable();
            $table->string('banco_out_url')->nullable();
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
            $table->dropColumn('venta_url');
            $table->dropColumn('compra_url');
            $table->dropColumn('banco_in_url');
            $table->dropColumn('banco_out_url');
        });
    }
}
