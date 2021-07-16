<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypepaymentQuotation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('quotations','payment_id')){
            Schema::table('quotations', function (Blueprint $table) {
                // $table->dropForeign(['payment_id']);
                $table->dropColumn('payment_id');
            });
        }
        
            Schema::table('quotations', function (Blueprint $table) {            
                    $table->unsignedInteger('payment_id')->default(1);
                    $table->foreign('payment_id')->references('id')->on('pagos');            
            });    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('quotations','payment_id')){        
            Schema::table('quotations', function (Blueprint $table) {                        
                $table->dropForeign(['payment_id']);
                $table->dropColumn('payment_id');
            });
        }
    }
}
