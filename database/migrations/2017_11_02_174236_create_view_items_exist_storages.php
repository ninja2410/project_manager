<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewItemsExistStorages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::Statement("
            create view v_items_exist_storages as
        select id_product,sum(quantity) as quantity
        from bodega_productos
        group by id_product;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::Statement("drop view if exists v_items_exist_storages;");
    }
}
