<?php

use App\StateCellar;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::statement('SET FOREIGN_KEY_CHECKS=0;');
      StateCellar::query()->truncate();
      DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        /* Seeder de Estados */
        DB::table('state_cellars')->insert([
        'name' => 'Activo','type_number'=>1,'type'=>'General','created_at' =>date("Y-m-d H:i:s"),'updated_at' =>date("Y-m-d H:i:s")
      ]);
      DB::table('state_cellars')->insert([
        'name' => 'Inactivo','type_number'=>1,'type'=>'General','created_at' =>date("Y-m-d H:i:s"),'updated_at' =>date("Y-m-d H:i:s")
      ]);
      DB::table('state_cellars')->insert([
        'name' => 'Pendiente','type_number'=>1,'type'=>'General','created_at' =>date("Y-m-d H:i:s"),'updated_at' =>date("Y-m-d H:i:s")
      ]);
      DB::table('state_cellars')->insert([
        'name' => 'Conciliado','type_number'=>2,'type'=>'BankTx','created_at' =>date("Y-m-d H:i:s"),'updated_at' =>date("Y-m-d H:i:s")
      ]);
      DB::table('state_cellars')->insert([
        'name' => 'No Conciliado','type_number'=>2,'type'=>'BankTx','created_at' =>date("Y-m-d H:i:s"),'updated_at' =>date("Y-m-d H:i:s")
      ]);

      DB::table('state_cellars')->insert([
        'name' => 'Pagado','type_number'=>3,'type'=>'InventoryTx','created_at' =>date("Y-m-d H:i:s"),'updated_at' =>date("Y-m-d H:i:s")
      ]);
      DB::table('state_cellars')->insert([
        'name' => 'Pendiente de pago','type_number'=>3,'type'=>'InventoryTx','created_at' =>date("Y-m-d H:i:s"),'updated_at' =>date("Y-m-d H:i:s")
      ]);

      /*
       * *********** ESTADOS DE TRASLADOS***********
       * */
        DB::table('state_cellars')->insert([
            'name' => 'Enviado','type_number'=>4,'type'=>'transfer','created_at' =>date("Y-m-d H:i:s"),'updated_at' =>date("Y-m-d H:i:s")
        ]);
        DB::table('state_cellars')->insert([
            'name' => 'Recibido','type_number'=>4,'type'=>'transfer','created_at' =>date("Y-m-d H:i:s"),'updated_at' =>date("Y-m-d H:i:s")
        ]);
        /*
         * ******************************************
         * */

        DB::table('state_cellars')->insert([
          'name' => 'Anulado','type_number'=>3,'type'=>'InventoryTx','created_at' =>date("Y-m-d H:i:s"),'updated_at' =>date("Y-m-d H:i:s")
        ]);
        /**
         * ESTADOS EN NOTAS DE CRÉDITO
         */
        DB::table('state_cellars')->insert([
            'name' => 'Aplicado','type_number'=>5,'type'=>'credit_notes','created_at' =>date("Y-m-d H:i:s"),'updated_at' =>date("Y-m-d H:i:s")
        ]);
        DB::table('state_cellars')->insert([
            'name' => 'No Aplicado','type_number'=>5,'type'=>'credit_notes','created_at' =>date("Y-m-d H:i:s"),'updated_at' =>date("Y-m-d H:i:s")
        ]);
        DB::table('state_cellars')->insert([
            'name' => 'Anulado','type_number'=>5,'type'=>'credit_notes','created_at' =>date("Y-m-d H:i:s"),'updated_at' =>date("Y-m-d H:i:s")
        ]);
    }
}
