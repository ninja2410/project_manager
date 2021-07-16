<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToBankAccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->unsignedInteger('account_type_id');
            $table->foreign('account_type_id')->references('id')->on('bank_account_types');
            $table->unsignedInteger('almacen_id')->nullable();
            $table->foreign('almacen_id')->references('id')->on('almacens');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bank_accounts', function (Blueprint $table) {
            if (Schema::hasColumn('bank_accounts', 'account_type_id')){
                $table->dropForeign(['account_type_id']);
                $table->dropColumn('account_type_id');
            }

            if (Schema::hasColumn('bank_accounts', 'almacen_id')){
                $table->dropForeign(['almacen_id']);
                $table->dropColumn('almacen_id');
            };
        });
    }
}
