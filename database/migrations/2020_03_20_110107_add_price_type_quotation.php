<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPriceTypeQuotation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::table('quotations', function (Blueprint $table) {
            $table->unsignedInteger('price_id')->default('1');
            $table->foreign('price_id')->references('id')->on('prices');
            $table->dropForeign(['payment_id']); // Drop foreign key 'user_id' from 'posts' table('change');
            $table->dropColumn('payment_id');
        });
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotations', function ($table) {
            $table->dropForeign(['price_id']); // Drop foreign key 'user_id' from 'posts' table('change');
            $table->dropColumn('price_id');
        });
    }
}
