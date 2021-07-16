<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDecimalInventory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->decimal('in_out_qty', 10, 2)->change();
        });
        Schema::table('receiving_items', function (Blueprint $table) {
            $table->decimal('quantity', 10, 2)->change();
        });
        Schema::table('bodega_productos', function (Blueprint $table) {
            $table->decimal('quantity', 10, 2)->change();
        });
        Schema::table('sale_items', function (Blueprint $table) {
            $table->decimal('quantity', 10, 2)->change();
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
