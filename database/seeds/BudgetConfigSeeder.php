<?php

use App\BudgetConfig;
use Illuminate\Database\Seeder;

class BudgetConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // se desahilitan los chequeos de llaves foraneas para poder truncar la tabla
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        BudgetConfig::query()->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::table('budget_configs')->insert([
            'name'=>'Material y equipo',
            'description' => 'Solo se permite agregar productos',
            'custom_text' => 'Material y equipo',
            'type' => '1',
            'active' => '1',
            'color' => 'grey',
            'icon' => 'fas fa-align-justify',
            'order' => '1',
            'created_by' => '1',
            'updated_by' => '1'
        ]);
        DB::table('budget_configs')->insert([
            'name'=>'Mano de obra',
            'description' => 'Solo se permite agregar servicios',
            'custom_text' => 'Mano de obra',
            'type' => '1',
            'active' => '1',
            'color' => 'grey',
            'icon' => 'fas fa-align-justify',
            'order' => '2',
            'created_by' => '1',
            'updated_by' => '1'
        ]);
    }
}
