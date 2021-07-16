<?php

use App\Pago;
use Illuminate\Database\Seeder;

class PaymentFormsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Pago::query()->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        /* Seeder de Tipos de Pago */
    DB::table('pagos')->insert([
        'name' => 'Efectivo',
  
        'type' => 1,/** no credito */
        'venta' => 1,
        'orden_venta' => 1,
        'default_venta' => 1,
  
        'compra' => 1,
        'orden_compra' => 2,
        'default_compra' => 0,
  
        'banco_in' => 1,
        'orden_banco_in' => 2,
        'default_banco_in' => 0,
  
        'banco_out' => 1,
        'orden_banco_out' => 0,
        'default_banco_out' => 0,
      ]);
  
      DB::table('pagos')->insert([
        'name' => 'Cheque',
        'type' => 2,/** no credito */
  
        'venta' => 1,
        'orden_venta' => 2,
        'default_venta' => 0,
  
        'compra' => 1,
        'orden_compra' => 1,
        'default_compra' => 1,
  
        'banco_in' => 1,
        'orden_banco_in' => 1,
        'default_banco_in' => 1,
  
        'banco_out' => 1,
        'orden_banco_out' => 1,
        'default_banco_out' => 0,
      ]);
  
  
      DB::table('pagos')->insert([
        'name' => 'Depósito',
        'type' => 3,/** no credito */
  
        'venta' => 1,
        'orden_venta' => 3,
        'default_venta' => 0,
  
        'compra' => 0,
        'orden_compra' => 0,
        'default_compra' => 0,
  
        'banco_in' => 1,
        'orden_banco_in' => 4,
        'default_banco_in' => 0,
  
        'banco_out' => 0,
        'orden_banco_out' => 0,
        'default_banco_out' => 0,
      ]);
  
      DB::table('pagos')->insert([
        'name' => 'Tarjeta de Crédito/Debito',
        'type' => 4,/** no credito */
  
        'venta' => 1,
        'orden_venta' => 4,
        'default_venta' => 0,
  
        'compra' => 1,
        'orden_compra' => 3,
        'default_compra' => 0,
  
        'banco_in' => 1,
        'orden_banco_in' => 3,
        'default_banco_in' => 0,
  
        'banco_out' => 1,
        'orden_banco_out' => 3,
        'default_banco_out' => 0,
      ]);
  
      DB::table('pagos')->insert([
        'name' => 'Transferencia',
        'type' => 5,/** no credito */
  
        'venta' => 1,
        'orden_venta' => 6,
        'default_venta' => 0,
  
        'compra' => 1,
        'orden_compra' => 6,
        'default_compra' => 0,
  
        'banco_in' => 1,
        'orden_banco_in' => 5,
        'default_banco_in' => 0,
  
        'banco_out' => 1,
        'orden_banco_out' => 5,
        'default_banco_out' => 0,
      ]);
  
      DB::table('pagos')->insert([
        'name' => 'Crédito',
        'type' => 6,/**  credito */
  
        'venta' => 1,
        'orden_venta' => 7,
        'default_venta' => 0,
  
        'compra' => 1,
        'orden_compra' => 7,
        'default_compra' => 0,
  
        'banco_in' => 0,
        'orden_banco_in' => 0,
        'default_banco_in' => 0,
  
        'banco_out' => 0,
        'orden_banco_out' => 0,
        'default_banco_out' => 0,
      ]);
    }
}
