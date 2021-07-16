<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_id');
            $table->foreign('category_id')->references('id')->on('expense_categories');
            $table->unsignedInteger('account_id')->nullable();
//            $table->foreign('account_id')->references('id')->on('bank_accounts');
            $table->unsignedInteger('supplier_id')->nullable();
            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->string('description');
            $table->unsignedInteger('payment_type_id');
            $table->foreign('payment_type_id')->references('id')->on('pagos');
            $table->date('expense_date');
            $table->unsignedInteger('state_id');
            $table->foreign('state_id')->references('id')->on('state_cellars');
            $table->unsignedInteger('document_type_id')->nullable();
            $table->foreign('document_type_id')->references('id')->on('documents');
            $table->string('reference');
            $table->unsignedInteger('assigned_user_id')->nullable();
//            $table->foreign('assigned_user_id')->references('id')->on('users');
            $table->unsignedInteger('route_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->integer('cant')->default('0');
            $table->integer('status')->default('0');
            $table->decimal('unit_price')->default('0');
            $table->boolean('payment_status');
            $table->unsignedInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->string('photo')->nullable();
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
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
            $table->dropForeign(['supplier_id']);
            $table->dropColumn('supplier_id');
            $table->dropForeign(['payment_type_id']);
            $table->dropColumn('payment_type_id');
            $table->dropForeign(['state_id']);
            $table->dropColumn('state_id');
            $table->dropForeign(['document_type_id']);
            $table->dropColumn('document_type_id');
//            $table->dropForeign(['assigned_user_id']);
            $table->dropColumn('assigned_user_id');
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by');
        });
        Schema::drop('expenses');
    }
}
