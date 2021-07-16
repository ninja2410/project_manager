<?php

use App\BankAccountType;
use Illuminate\Database\Seeder;

class BankAccountTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        BankAccountType::query()->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        /** 1 */
        DB::table('bank_account_types')->insert([
            'name' => 'Caja chica',
            'description' => 'Caja chica',
            'status' => '1',
            'created_by' => '1',
            'updated_by' => '1',
            'created_at' =>date("Y-m-d H:i:s"),
            'updated_at' =>date("Y-m-d H:i:s"),
        ]);
        /** 2 */
        DB::table('bank_account_types')->insert([
            'name' => 'Monetarios',
            'description' => 'Monetarios',
            'status' => '1',
            'created_by' => '1',
            'updated_by' => '1',
            'created_at' =>date("Y-m-d H:i:s"),
            'updated_at' =>date("Y-m-d H:i:s"),
        ]);
        /** 3 */
        DB::table('bank_account_types')->insert([
            'name' => 'Ahorros',
            'description' => 'Ahorros',
            'status' => '1',
            'created_by' => '1',
            'updated_by' => '1',
            'created_at' =>date("Y-m-d H:i:s"),
            'updated_at' =>date("Y-m-d H:i:s"),
        ]);
        /** 4 */
        DB::table('bank_account_types')->insert([
            'name' => 'Tarjeta de Crédito',
            'description' => 'Tarjeta de Crédito',
            'status' => '1',
            'created_by' => '1',
            'updated_by' => '1',
            'created_at' =>date("Y-m-d H:i:s"),
            'updated_at' =>date("Y-m-d H:i:s"),
        ]);
        /** 5 */
        DB::table('bank_account_types')->insert([
            'name' => 'Prestamos',
            'description' => 'Prestamos',
            'status' => '1',
            'created_by' => '1',
            'updated_by' => '1',
            'created_at' =>date("Y-m-d H:i:s"),
            'updated_at' =>date("Y-m-d H:i:s"),
        ]);
        /** 6 */
        DB::table('bank_account_types')->insert([
            'name' => 'Inversion',
            'description' => 'Inversion',
            'status' => '1',
            'created_by' => '1',
            'updated_by' => '1',
            'created_at' =>date("Y-m-d H:i:s"),
            'updated_at' =>date("Y-m-d H:i:s"),
        ]);
        /** 7 */
        DB::table('bank_account_types')->insert([
            'name' => 'Caja',
            'description' => 'Caja para recepción de ingresos en ventas',
            'status' => '1',
            'created_by' => '1',
            'updated_by' => '1',
            'created_at' =>date("Y-m-d H:i:s"),
            'updated_at' =>date("Y-m-d H:i:s"),
        ]);
    }
}
