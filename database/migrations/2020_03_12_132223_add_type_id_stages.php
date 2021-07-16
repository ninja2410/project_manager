<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeIdStages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('stages','type_id')){
            Schema::table('stages', function (Blueprint $table) {
                // $table->dropForeign(['payment_id']);
                $table->dropColumn('type_id');
            });
        }
        Schema::table('stages', function (Blueprint $table) {
            $table->unsignedInteger('type_id')->nullable();
            $table->foreign('type_id')->references('id')
                ->on('type_projects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stages', function ($table) {
            $table->dropForeign(['type_id']);
            $table->dropColumn('type_id');
        });
    }
}
