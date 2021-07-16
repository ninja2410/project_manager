<?php

use Illuminate\Database\Seeder;

class TransactionCatalogueTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* Tipos de transacciones entre cuentas */
        DB::table('bank_transactions_catalogue')->insert([
            'transaction_name' => 'Transferencia entre cuentas',
            'transaction_sign' => '=',
            'status' => '1',
            'user_id' => '1',
            'created_at' =>date("Y-m-d H:i:s"),
            'updated_at' =>date("Y-m-d H:i:s"),
        ]);

        DB::table('bank_transactions_catalogue')->insert([
            'transaction_name' => 'Depósito',
            'transaction_sign' => '+',
            'status' => '1',
            'user_id' => '1',
            'created_at' =>date("Y-m-d H:i:s"),
            'updated_at' =>date("Y-m-d H:i:s"),
        ]);

        DB::table('bank_transactions_catalogue')->insert([
            'transaction_name' => 'Nota de crédito',
            'transaction_sign' => '+',
            'status' => '1',
            'user_id' => '1',
            'created_at' =>date("Y-m-d H:i:s"),
            'updated_at' =>date("Y-m-d H:i:s"),
        ]);

        DB::table('bank_transactions_catalogue')->insert([
            'transaction_name' => 'Transferencia ingreso',
            'transaction_sign' => '+',
            'status' => '1',
            'user_id' => '1',
            'created_at' =>date("Y-m-d H:i:s"),
            'updated_at' =>date("Y-m-d H:i:s"),
        ]);

        

        DB::table('bank_transactions_catalogue')->insert([
            'transaction_name' => 'Cheque',
            'transaction_sign' => '-',
            'status' => '1',
            'user_id' => '1',
            'created_at' =>date("Y-m-d H:i:s"),
            'updated_at' =>date("Y-m-d H:i:s"),
        ]);        

        DB::table('bank_transactions_catalogue')->insert([
            'transaction_name' => 'Nota de débito',
            'transaction_sign' => '-',
            'status' => '1',
            'user_id' => '1',
            'created_at' =>date("Y-m-d H:i:s"),
            'updated_at' =>date("Y-m-d H:i:s"),
        ]);

        DB::table('bank_transactions_catalogue')->insert([
            'transaction_name' => 'Transferencia egreso',
            'transaction_sign' => '-',
            'status' => '1',
            'user_id' => '1',
            'created_at' =>date("Y-m-d H:i:s"),
            'updated_at' =>date("Y-m-d H:i:s"),
        ]);
        
        


    }
}
