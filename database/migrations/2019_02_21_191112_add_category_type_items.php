<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCategoryTypeItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('items', function (Blueprint $table) {
        $table->unsignedInteger('type_id')->default('9');
        $table->foreign('type_id')->references('id')->on('categories');
        $table->decimal('price_reference');
        $table->boolean('is_kit');
        $table->string('stock_action');
        $table->decimal('waste_percent');
        $table->boolean('status');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('items', function (Blueprint $table) {
          $table->dropForeign(['type_id']);
          $table->dropColumn(['type_id']);
      });
    }
}
