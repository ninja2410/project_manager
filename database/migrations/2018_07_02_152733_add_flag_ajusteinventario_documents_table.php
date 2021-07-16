<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFlagAjusteinventarioDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documents',function(Blueprint $table){
            $table->integer('ajuste_inventario')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documents',function(Blueprint $table){
            $table->dropColumn('ajuste_inventario');
        });
    }
}
