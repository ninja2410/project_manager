<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_configs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->string('color');
            $table->string('icon');
            $table->string('custom_text');
            $table->unsignedInteger('type');
            $table->boolean('active');
            $table->unsignedInteger('order');
            $table->unsignedInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
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
        Schema::table('budget_configs', function ($table) {
            $table->dropForeign(['created_by']); // Drop foreign key 'user_id' from 'posts' table('created_by');
            $table->dropColumn('created_by');
            $table->dropForeign(['updated_by']); // Drop foreign key 'user_id' from 'posts' table('created_by');
            $table->dropColumn('updated_by');
        });
        Schema::drop('budget_configs');
    }
}
