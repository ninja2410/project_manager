<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->boolean('status');
            $table->decimal('amount');
            $table->integer('days');
            $table->string('comment');
            $table->unsignedInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->unsignedInteger('sale_id')->nullable();
            $table->foreign('sale_id')->references('id')->on('sales');
            $table->unsignedInteger('serie_id');
            $table->foreign('serie_id')->references('id')->on('series');
            $table->integer('correlative');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
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
        if (Schema::hasColumn('quotations', 'sale_id')){
            Schema::table('quotations', function (Blueprint $table) {
                $table->dropForeign(['sale_id']);
                $table->dropColumn('sale_id');
            });
        }
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropColumn('customer_id');
            $table->dropForeign(['serie_id']);
            $table->dropColumn('serie_id');
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by');
        });
        Schema::drop('quotations');
    }
}
