<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         /* Seeder de Categorias */
         DB::table('categories')->insert([
            'name' => 'INMUEBLE',
            'type' => 0,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('categories')->insert([
            'name' => 'ELECTRONICO',
            'type' => 0,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('categories')->insert([
            'name' => 'VEHICULO',
            'type' => 0,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('categories')->insert([
            'name' => 'JOYAS',
            'type' => 0,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('categories')->insert([
            'name' => 'OTROS',
            'type' => 0,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);

              /* Seeder de Categorias */
        DB::table('categories')->insert([
            'name' => 'CATEGORIA 1',
            'type' => 0,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('categories')->insert([
            'name' => 'CATEGORIA 2',
            'type' => 0,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);

        DB::table('categories')->insert([
            'name' => 'SERVICIOS 1',
            'type' => 0,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        //TIPO DE ITEM REGISTRADO
        DB::table('categories')->insert([
            'name' => 'PRODUCTO',
            'type' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('categories')->insert([
            'name' => 'SERVICIO',
            'type' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('categories')->insert([
            'name' => 'MOBILIARIO',
            'type' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);

        DB::table('categories')->insert([
            'name' => 'MATERIAL',
            'type' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        //TIPO DE DETALLE DE PRESUPUESTO
        DB::table('categories')->insert([
            'name' => 'MATERIAL',
            'type' => 2,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('categories')->insert([
            'name' => 'MANO DE OBRA',
            'type' => 2,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('categories')->insert([
            'name' => 'CUENTA DE PROYECTO',
            'type' => 3,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('categories')->insert([
            'name' => 'BODEGA DE PROYECTO',
            'type' => 4,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('categories')->insert([
            'name' => 'CUENTA DE RETENCION',
            'type' => 3,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);

        // Tipos de productos
        DB::table('item_types')->insert([
            'id' => 1,
            'name' => 'Producto',
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);

        DB::table('item_types')->insert([
            'id' => 2,
            'name' => 'Servicio',
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('item_types')->insert([
            'id' => 3,
            'name' => 'Mobiliario',
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);

        /**categorias  */

        // Prime Food GT

        DB::table('item_categories')->insert([
            'name' => 'BEBIDAS',
            'item_type_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);

        DB::table('item_categories')->insert([
            'name' => 'CARNES',
            'item_type_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('item_categories')->insert([
            'name' => 'CERDO',
            'item_type_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('item_categories')->insert([
            'name' => 'CERVEZAS',
            'item_type_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('item_categories')->insert([
            'name' => 'COMPLEMENTOS',
            'item_type_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('item_categories')->insert([
            'name' => 'EMBUTIDOS',
            'item_type_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('item_categories')->insert([
            'name' => 'LACTEOS',
            'item_type_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('item_categories')->insert([
            'name' => 'POLLO',
            'item_type_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);

        // DB::table('item_categories')->insert([
        //     'name' => 'Categoria productos 1',
        //     'item_type_id' => 1,
        //     'created_by' => 1,
        //     'updated_by' => 1,
        //     'created_at' => date("Y-m-d H:i:s"),
        //     'updated_at' => date("Y-m-d H:i:s")
        // ]);

        // DB::table('item_categories')->insert([
        //     'name' => 'Categoria productos 2',
        //     'item_type_id' => 1,
        //     'created_by' => 1,
        //     'updated_by' => 1,
        //     'created_at' => date("Y-m-d H:i:s"),
        //     'updated_at' => date("Y-m-d H:i:s")
        // ]);

        // DB::table('item_categories')->insert([
        //     'name' => 'Categoria servicios 1',
        //     'item_type_id' => 2,
        //     'created_by' => 1,
        //     'updated_by' => 1,
        //     'created_at' => date("Y-m-d H:i:s"),
        //     'updated_at' => date("Y-m-d H:i:s")
        // ]);

        // DB::table('item_categories')->insert([
        //     'name' => 'Categoria servicios 2',
        //     'item_type_id' => 2,
        //     'created_by' => 1,
        //     'updated_by' => 1,
        //     'created_at' => date("Y-m-d H:i:s"),
        //     'updated_at' => date("Y-m-d H:i:s")
        // ]);

        // DB::table('item_categories')->insert([
        //     'name' => 'Categoria mobiliario 1',
        //     'item_type_id' => 3,
        //     'created_by' => 1,
        //     'updated_by' => 1,
        //     'created_at' => date("Y-m-d H:i:s"),
        //     'updated_at' => date("Y-m-d H:i:s")
        // ]);

        // DB::table('item_categories')->insert([
        //     'name' => 'Categoria mobiliario 2',
        //     'item_type_id' => 3,
        //     'created_by' => 1,
        //     'updated_by' => 1,
        //     'created_at' => date("Y-m-d H:i:s"),
        //     'updated_at' => date("Y-m-d H:i:s")
        // ]);
        
    }
}
