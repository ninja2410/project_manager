<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRetentionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retentions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->decimal('percent');
            $table->string('description');
            $table->boolean('status');
            $table->unsignedInteger('account_id');
            $table->foreign('account_id')->references('id')->on('bank_accounts');
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
        Schema::table('retentions', function($table){
          $table->dropForeign(['account_id']);
          $table->dropColumn('account_id');
        });
        Schema::drop('retentions');
    }
}
