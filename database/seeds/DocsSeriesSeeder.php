<?php

use App\Document;
use App\Serie;
use Illuminate\Database\Seeder;

class DocsSeriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Document::query()->truncate();
        Serie::query()->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        /* DOCUMENTOS */
        DB::table('documents')->insert([
            'id' => '1',
            'name' => 'Factura',
            'sign' => '-',
            'type_fel'=>'FACT',
            'id_state' => '1',
        ]);
        DB::table('documents')->insert([
            'id' => '2',
            'name' => 'Factura de compra',
            'sign' => '+',
            'id_state' => '1',
        ]);

        DB::table('documents')->insert([
            'id' => '3',
            'name' => 'Traslado de bodega',
            'sign' => '=',
            'id_state' => '1',
        ]);
        DB::table('documents')->insert([
            'id' => '4',
            'name' => 'Salida de bodega',
            'sign' => '-',
            'id_state' => '1',
        ]);
        DB::table('documents')->insert([
            'id' => '5',
            'name' => 'Envio',
            'sign' => '-',
            'id_state' => '1',
        ]);
        // DB::table('documents')->insert([
        //   'name' => 'Factura Crédito',
        //   'sign' => '-',
        //   'id_state' => '1',
        // ]);
        DB::table('documents')->insert([
            'id' => '6',
            'name' => 'Ajuste salida de inventario',
            'sign' => '-',
            'ajuste_inventario' => '1',
            'id_state' => '1',
        ]);
        DB::table('documents')->insert([
            'id' => '7',
            'name' => 'Ajuste ingreso de inventario',
            'sign' => '+',
            'ajuste_inventario' => '1',
            'id_state' => '1',
        ]);
            DB::table('documents')->insert([
                'id' => '8',
                'name' => 'Cotización',
                'sign' => '*',
                'id_state' => '1',
            ]);
            DB::table('documents')->insert([
                'id' => '9',
                'name' => 'Proforma',
                'sign' => '-',
                'id_state' => '1',
            ]);
            DB::table('documents')->insert([
                'id' => '10',
                'name' => 'Nota Crédito',
                'sign' => '+',
                'type_fel'=>'NCRE',
                'id_state' => '1',
            ]);
            DB::table('documents')->insert([
                'id' => '11',
                'name' => 'Recibo de Caja',
                'sign' => '=',
                'id_state' => '1',
            ]);
            DB::table('documents')->insert([
                'id' => '12',
                'name' => 'Cierre de caja',
                'sign' => '=',
                'id_state' => '1',
            ]);


        /* SERIES */
        DB::table('series')->insert([
            'name' => 'A',
            'id_document' => '1',
            'id_state' => '1',
        ]);
        DB::table('series')->insert([
            'name' => 'A',
            'id_document' => '9',
            'id_state' => '1',
            'proforma' => '1',
        ]);
        DB::table('series')->insert([
            'name' => 'A',
            'id_document' => '2',
            'id_state' => '1',
        ]);
        DB::table('series')->insert([
            'name' => 'A',
            'id_document' => '3',
            'id_state' => '1',
        ]);
        DB::table('series')->insert([
            'name' => 'A',
            'id_document' => '4',
            'id_state' => '1',
        ]);
        DB::table('series')->insert([
            'name' => 'A',
            'id_document' => '5',
            'id_state' => '1',
        ]);
        DB::table('series')->insert([
            'name' => 'A',
            'id_document' => '6',
            'credit' => 1,
            'id_state' => '1',
        ]);
        DB::table('series')->insert([
            'name' => 'A',
            'id_document' => '7',
            'id_state' => '1',
        ]);
        DB::table('series')->insert([
            'name' => 'A',
            'id_document' => '8',
            'id_state' => '1',
        ]);
            DB::table('series')->insert([
                'name' => 'A',
                'id_document' => '10',
                'id_state' => '1',
            ]);
            DB::table('series')->insert([
                'name' => 'A',
                'id_document' => '11',
                'id_state' => '1',
            ]);
            DB::table('series')->insert([
                'name' => 'A',
                'id_document' => '12',
                'id_state' => '1',
            ]);
    }
}
