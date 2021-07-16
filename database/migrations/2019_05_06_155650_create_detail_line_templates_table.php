<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailLineTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_line_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('quantity');
            $table->decimal('price');
            $table->unsignedInteger('lineTemplate_id');
            $table->foreign('lineTemplate_id')->references('id')->on('line_templates');
            $table->unsignedInteger('item_id')->nullable();
            $table->foreign('item_id')->references('id')->on('items');
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
        Schema::table('detail_line_templates', function($table){
          $table->dropForeign(['lineTemplate_id']);
          $table->dropColumn('lineTemplate_id');
          $table->dropForeign(['item_id']);
          $table->dropColumn('item_id');
        });
        Schema::drop('detail_line_templates');
    }
}
