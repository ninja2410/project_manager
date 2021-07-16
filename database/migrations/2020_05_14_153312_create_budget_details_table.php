<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_details', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('quantity', 12,2);
            $table->decimal('unit_cost', 12,2);
            $table->decimal('total_cost', 12,2);
            $table->unsignedInteger('line_template_id');
            $table->foreign('line_template_id')->references('id')->on('line_templates');
            $table->unsignedInteger('header_id');
            $table->foreign('header_id')->references('id')->on('budget_headers')->onDelete('cascade');
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
        Schema::table('budget_details', function ($table) {
            $table->dropForeign(['line_template_id']);
            $table->dropForeign(['header_id']);
        });
        Schema::drop('budget_details');
    }
}
