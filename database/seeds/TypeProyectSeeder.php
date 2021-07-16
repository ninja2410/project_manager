<?php

use App\TypeProject;
use Illuminate\Database\Seeder;

class TypeProyectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        TypeProject::query()->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::table('type_projects')->insert([
            'name'          => 'Planificación',
            'status_id'     => '1',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('type_projects')->insert([
            'name'          => 'Ejecución',
            'status_id'     => '1',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
    }
}
