<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterColumnsSalesAndReceivingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //sales 
        Schema::table('sales',function(Blueprint $table){
            $table->integer('customer_id')->nullable()->unsigned()->change();
        });
        // receivings
        Schema::table('receivings',function(Blueprint $table){
            $table->integer('supplier_id')->nullable()->unsigned()->change();
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
