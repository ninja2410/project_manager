<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypePriceReceiving extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('receivings', function (Blueprint $table) {
            $table->unsignedInteger('price_id')->nullable()->default('1');;
            $table->foreign('price_id')->references('id')->on('prices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('receivings', function ($table) {
            $table->dropForeign(['price_id']); // Drop foreign key 'user_id' from 'posts' table('change');
            $table->dropColumn('price_id');
        });
    }
}
