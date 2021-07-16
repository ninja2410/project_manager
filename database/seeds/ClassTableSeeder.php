<?php

use Illuminate\Database\Seeder;

class ClassTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* Clasificacion de clientes */
        DB::table('class_tables')->insert([
            'name' => 'A',
            'arrears' => '0',
            'pctRen' => '85',
            'pctAmountRen' => '200',
            'noPaySurcharge' => '0',
            'color' => '#00f900',
            'status_id' => '1',
            'user_id' => '1',
            'renovation' => '1',
        ]);

        DB::table('class_tables')->insert([
            'name' => 'B',
            'arrears' => '3',
            'pctRen' => '85',
            'pctAmountRen' => '150',
            'noPaySurcharge' => '0',
            'color' => '#c9e200',
            'status_id' => '1',
            'user_id' => '1',
            'renovation' => '1',
        ]);

        DB::table('class_tables')->insert([
            'name' => 'C',
            'arrears' => '5',
            'pctRen' => '85',
            'pctAmountRen' => '100',
            'noPaySurcharge' => '0',
            'color' => '#f7ee00',
            'status_id' => '1',
            'user_id' => '1',
            'renovation' => '1',
        ]);
    }
}
