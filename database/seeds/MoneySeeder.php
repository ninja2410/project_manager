<?php

use Illuminate\Database\Seeder;

class MoneySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      /*Tipos de denominación de moneda */
      DB::table('money_types')->insert([
          'name' => 'BILLETES DE Q 100.00',
          'value' => '100',
          'status_id' => '1',
      ]);
      DB::table('money_types')->insert([
          'name' => 'BILLETES DE Q 50.00',
          'value' => '50',
          'status_id' => '1',
      ]);
      DB::table('money_types')->insert([
          'name' => 'BILLETES DE Q 20.00',
          'value' => '20',
          'status_id' => '1',
      ]);
      DB::table('money_types')->insert([
          'name' => 'BILLETES DE Q 200.00',
          'value' => '200',
          'status_id' => '1',
      ]);
      DB::table('money_types')->insert([
          'name' => 'BILLETES DE Q 10.00',
          'value' => '10',
          'status_id' => '1',
      ]);
      DB::table('money_types')->insert([
          'name' => 'BILLETES DE Q 5.00',
          'value' => '5',
          'status_id' => '1',
      ]);
      DB::table('money_types')->insert([
          'name' => 'BILLETES DE Q 1.00',
          'value' => '1',
          'status_id' => '1',
      ]);
      DB::table('money_types')->insert([
        'name' => 'MONEDA DE Q 1.00',
        'value' => '1',
        'status_id' => '1',
      ]);
      DB::table('money_types')->insert([
        'name' => 'MONEDA DE Q 0.50',
        'value' => '0.5',
        'status_id' => '1',
      ]);
      DB::table('money_types')->insert([
        'name' => 'MONEDA DE Q 0.25',
        'value' => '0.25',
        'status_id' => '1',
      ]);
      DB::table('money_types')->insert([
        'name' => 'MONEDA DE Q 0.10',
        'value' => '0.1',
        'status_id' => '1',
      ]);
      DB::table('money_types')->insert([
        'name' => 'MONEDA DE Q 0.05',
        'value' => '0.05',
        'status_id' => '1',
      ]);
      DB::table('money_types')->insert([
        'name' => 'MONEDA DE Q 0.01',
        'value' => '0.01',
        'status_id' => '1',
      ]);
    }
}
