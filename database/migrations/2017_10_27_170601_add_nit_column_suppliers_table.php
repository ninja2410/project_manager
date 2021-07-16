<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNitColumnSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suppliers',function(Blueprint $table){
            $table->string('nit_supplier')->default('c/f');
            $table->double('balance',10,2)->default(0);
            $table->integer('days_credit')->default(0);
            $table->double('max_credit_amount',10,2)->default(0);
            $table->string('name_on_checks')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('suppliers',function(Blueprint $table){
          $table->dropColumn('nit_supplier');
          $table->dropColumn('balance');
          $table->dropColumn('days_credit');
          $table->dropColumn('max_credit_amount');
          $table->dropColumn('name_on_checks');
        });
    }
}
