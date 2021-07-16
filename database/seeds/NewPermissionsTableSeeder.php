<?php

use App\Permission;
use Illuminate\Database\Seeder;

class NewPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Permission::query()->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        DB::table('permissions')->insert([
            'descripcion' => 'Graficas',
            'ruta' => 'charts',
            'estado' => 1,
            'ruta_padre' => '',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('permissions')->insert([
            'descripcion' => 'Grafica de cobro diario',
            'ruta' => 'cobrochart',
            'estado' => 1,
            'ruta_padre' => 'charts',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('permissions')->insert([
            'descripcion' => 'Cobro vs Mora',
            'ruta' => 'morachart',
            'estado' => 1,
            'ruta_padre' => 'charts',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('permissions')->insert([
            'descripcion' => 'Cobro vs renovación',
            'ruta' => 'renovacionchart',
            'estado' => 1,
            'ruta_padre' => 'charts',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

    }
}
