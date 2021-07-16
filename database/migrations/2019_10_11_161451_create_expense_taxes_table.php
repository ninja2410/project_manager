<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpenseTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_taxes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->decimal('value');
            $table->boolean('percent');
            $table->string('units');
            $table->string('description');
            $table->unsignedInteger('expense_categorie_id');
            $table->foreign('expense_categorie_id')->references('id')->on('expense_categories');
            $table->unsignedInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedInteger('updated_by');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->softDeletes();
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
        Schema::table('expense_taxes', function (Blueprint $table) {
            $table->dropForeign(['expense_categorie_id']);
            $table->dropColumn('expense_categorie_id');
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by');
        });
        Schema::drop('expense_taxes');
    }
}
