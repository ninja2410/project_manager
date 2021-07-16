<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExpensesSalesGoalUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('sales_goal')->nullable()->default(0);
            $table->decimal('collection_goal')->nullable()->default(0);
            $table->decimal('expenses_max')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('sales_goal');
            $table->dropColumn('collection_goal');
            $table->dropColumn('expenses_max');
        });
    }
}
