<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaidAmountReceiving extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('receivings', function (Blueprint $table) {
            $table->decimal('total_paid')->default('0');;
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->decimal('total_paid')->default('0');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('receivings', function (Blueprint $table) {
            $table->dropColumn('total_paid');
        });
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn('total_paid');
        });
    }
}
