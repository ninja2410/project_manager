<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class ProjectflagsCreationAccountBudget extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function ($table) {
            $table->dropForeign(['account_id']);
            $table->dropForeign(['cellar_id']);
        });
        Schema::table('projects', function (Blueprint $table) {

            $table->boolean('create_account');
            $table->boolean('create_cellar');
            $table->unsignedInteger('account_id')->nullable()->change();
            $table->unsignedInteger('cellar_id')->nullable()->change();
            $table->foreign('account_id')->references('id')->on('bank_accounts');
            $table->foreign('cellar_id')->references('id')->on('almacens');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function ($table) {
            $table->dropColumn('create_account', 'create_cellar');
        });
    }
}
