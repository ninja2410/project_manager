<?php

use Illuminate\Database\Seeder;

class AlmacenTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

          /* Bodega por default */
        DB::table('almacens')->insert([
            'name' => 'Central',
            'phone' => '7777-8888',
            'adress' => 'Ciudad',
            'comentario' => 'Bodega Central',
            'id_state' => '1',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
    

        DB::table('almacen_users')->insert([
            'id_bodega' => '1',
            'id_usuario' => '1',
            'estado_user' => '1',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

    }
}
