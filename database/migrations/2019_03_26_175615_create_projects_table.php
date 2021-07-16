<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->boolean('status');
            $table->date('date');
            $table->string('description');
            $table->unsignedInteger('account_id');
            $table->unsignedInteger('cellar_id');
            $table->unsignedInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('account_id')->references('id')->on('bank_accounts');
            $table->foreign('cellar_id')->references('id')->on('almacens');
            $table->timestamps();
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
            $table->dropForeign(['account_id']);
            $table->dropForeign(['cellar_id']);
            $table->dropForeign(['customer_id']);
            $table->dropColumn('account_id');
            $table->dropColumn('cellar_id');
            $table->dropColumn('customer_id');
            $table->dropColumn('name');
            $table->dropColumn('date');
            $table->dropColumn('status');
            $table->dropColumn('description');
        });
        Schema::drop('projects');
    }
}
