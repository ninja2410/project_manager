<?php

use Illuminate\Database\Seeder;

class BankAccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* CUENTAS BANCARIAS */
        /** Cuenta monetarios */
        DB::table('bank_accounts')->insert([
            'account_name' => 'Monetarios',
            'account_number' => '0000000001',            
            'currency' => 'Q',
            'bank_id' => 1,
            'bank_name' => 'Banrural',
            'opening_balance' => 0.00,
            'pct_interes' => 0.00,
            'user_id' => '1',
            'account_responsible' => '1',
            'status' => '1',
            'categorie_id' => '1',
            'account_type_id' => 2,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('bank_accounts')->insert([
            'account_name' => 'Caja',
            'account_number' => '0001',            
            'currency' => 'Q',
            'bank_name' => '',
            'opening_balance' => 0.00,
            'pct_interes' => 0.00,
            'user_id' => '1',
            'account_responsible' => '1',
            'status' => '1',
            'categorie_id' => '1',
            'account_type_id' => 7,
            'almacen_id' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        // efectivo / 
        /**
         * FOrma de pago EFECTIVO
         * Ingreso 
         */
        DB::table('bank_accounts_pagos')->insert([
            'bank_account_type_id' => 1,
            'pago_id' => 1,
            'ingreso' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('bank_accounts_pagos')->insert([
            'bank_account_type_id' => 7,
            'pago_id' => 1,
            'ingreso' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
        // Salida
        DB::table('bank_accounts_pagos')->insert([
            'bank_account_type_id' => 1,
            'pago_id' => 1,
            'ingreso' => 0,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
        
        /**
         * CHEQUE
         * Ingreso
         */

        DB::table('bank_accounts_pagos')->insert([
            'bank_account_type_id' => 1,
            'pago_id' => 2,
            'ingreso' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('bank_accounts_pagos')->insert([
            'bank_account_type_id' => 2,
            'pago_id' => 2,
            'ingreso' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('bank_accounts_pagos')->insert([
            'bank_account_type_id' => 3,
            'pago_id' => 2,
            'ingreso' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('bank_accounts_pagos')->insert([
            'bank_account_type_id' => 7,
            'pago_id' => 2,
            'ingreso' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
        // Salida
        DB::table('bank_accounts_pagos')->insert([
            'bank_account_type_id' => 2,
            'pago_id' => 2,
            'ingreso' => 0,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
        
        /**
         * Depositos = 3
         * Ingreso
         */

        DB::table('bank_accounts_pagos')->insert([
            'bank_account_type_id' => 1,
            'pago_id' => 3,
            'ingreso' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('bank_accounts_pagos')->insert([
            'bank_account_type_id' => 2,
            'pago_id' => 3,
            'ingreso' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('bank_accounts_pagos')->insert([
            'bank_account_type_id' => 3,
            'pago_id' => 3,
            'ingreso' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('bank_accounts_pagos')->insert([
            'bank_account_type_id' => 7,
            'pago_id' => 3,
            'ingreso' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        /**
         * Tarjeta debito/credito =4
         * Ingreso
         */
        DB::table('bank_accounts_pagos')->insert([
            'bank_account_type_id' => 1,
            'pago_id' => 4,
            'ingreso' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('bank_accounts_pagos')->insert([
            'bank_account_type_id' => 2,
            'pago_id' => 4,
            'ingreso' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('bank_accounts_pagos')->insert([
            'bank_account_type_id' => 3,
            'pago_id' => 4,
            'ingreso' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
        // Salida
        DB::table('bank_accounts_pagos')->insert([
            'bank_account_type_id' => 4,
            'pago_id' => 4,
            'ingreso' => 0,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('bank_accounts_pagos')->insert([
            'bank_account_type_id' => 7,
            'pago_id' => 4,
            'ingreso' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        /**
         * Transferencia =5
         * Ingreso
         */
        DB::table('bank_accounts_pagos')->insert([
            'bank_account_type_id' => 2,
            'pago_id' => 5,
            'ingreso' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
    
        DB::table('bank_accounts_pagos')->insert([
            'bank_account_type_id' => 3,
            'pago_id' => 5,
            'ingreso' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]); 
        DB::table('bank_accounts_pagos')->insert([
            'bank_account_type_id' => 7,
            'pago_id' => 5,
            'ingreso' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);       
        // Salida
        DB::table('bank_accounts_pagos')->insert([
            'bank_account_type_id' => 2,
            'pago_id' => 5,
            'ingreso' => 0,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);            

        DB::table('bank_accounts_pagos')->insert([
            'bank_account_type_id' => 3,
            'pago_id' => 5,
            'ingreso' => 0,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
    }
}
