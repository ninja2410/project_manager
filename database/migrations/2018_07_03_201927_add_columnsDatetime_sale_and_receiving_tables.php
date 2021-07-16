<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsDatetimeSaleAndReceivingTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('receivings', function (Blueprint $table) {
            $table->dateTime('creation_date')->nullable();
        });
        Schema::table('sales', function (Blueprint $table) {
            $table->unsignedInteger('pagare_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('sales', 'creation_date')) {
            Schema::table('sales', function (Blueprint $table) {
                $table->dropColumn(['creation_date']);
            });
        }

        if (Schema::hasColumn('receivings', 'creation_date')) {
            Schema::table('receivings', function (Blueprint $table) {
                $table->dropColumn('creation_date');
            });
        }

    }
}
