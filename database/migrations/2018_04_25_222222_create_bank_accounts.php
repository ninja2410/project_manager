<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankAccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_accounts', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('company_id')->unsigned()->nullable();
            $table->string('account_name');
            $table->string('account_number')->nullable();
            // $table->enum('account_type', ['Monetarios', 'Ahorros', 'Prestamos', 'Inversion', 'Tarjeta de Crédito', 'Efectivo'])->default('Monetarios');
            $table->string('currency',5)->default('Q');
            // $table->enum('currency', ['Q', '$', 'MX $', 'L', 'C$', '₡', 'Bz$'])->default('Q');

            $table->integer('bank_id')->unsigned()->nullable();
            $table->string('bank_name');
            $table->string('bank_address');
            $table->string('bank_phone');
            $table->decimal('opening_balance', 9, 2)->default(0.00);


            $table->decimal('pct_interes', 9, 2);
            $table->boolean('status')->default(1);

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');


            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_accounts');

    }
}
