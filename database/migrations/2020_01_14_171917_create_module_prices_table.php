<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModulePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pago_id')->nullable();
            $table->foreign('pago_id')->references('id')->on('pagos');
            $table->unsignedInteger('almacen_id')->nullable();
            $table->foreign('almacen_id')->references('id')->on('almacens');
            $table->unsignedInteger('role_id')->nullable();
            $table->foreign('role_id')->references('id')->on('roles');
            $table->unsignedInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories');
            $table->unsignedInteger('price_id');
            $table->foreign('price_id')->references('id')->on('prices');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('module_prices');
    }
}
