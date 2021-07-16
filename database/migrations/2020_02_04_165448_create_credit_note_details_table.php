<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditNoteDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_note_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('credit_note_id');
            $table->foreign('credit_note_id')->references('id')->on('credit_notes');
            $table->unsignedInteger('item_id')->nullable();
            $table->foreign('item_id')->references('id')->on('items');
            $table->string('manual_detail');
            $table->decimal('quantity', 12, 2);
            $table->decimal('price', 12, 2);
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
        Schema::drop('credit_note_details');
    }
}
