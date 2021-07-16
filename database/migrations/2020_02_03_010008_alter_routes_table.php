<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('routes', function (Blueprint $table) {
            $table->string('description',200)->nullable();
            $table->decimal('goal_amount', 9, 2)->nullable();
            $table->unsignedInteger('status_id');
            $table->foreign('status_id')->references('id')->on('state_cellars')->onDelete('restrict');
            // Add User Create Route
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('users');
            // Add User Update Route
            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('users');

            $table->dropForeign(['user_id']);
            $table->dropForeign(['state_id']);
            $table->dropForeign(['user_create']);
            $table->dropColumn(['user_id','state_id','user_create']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
