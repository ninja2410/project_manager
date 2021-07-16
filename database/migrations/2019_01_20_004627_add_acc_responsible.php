<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccResponsible extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->unsignedInteger('account_responsible')->nullable();
            $table->foreign('account_responsible')->references('id')->on('users');

            $table->decimal('max_amount', 9, 2)->default(0.00);

            $table->integer('pago_id')->unsigned()->nullable();
            $table->foreign('pago_id')->references('id')->on('pagos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bank_accounts', function ($table) {
            $table->dropForeign(['account_responsible']);
            $table->dropForeign(['pago_id']);
            $table->dropColumn(['account_responsible', 'max_amount', 'pago_id']);
        });
    }
}
