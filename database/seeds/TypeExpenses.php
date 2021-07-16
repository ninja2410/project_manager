<?php

use App\ExpenseCategory;
use Illuminate\Database\Seeder;

class TypeExpenses extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        ExpenseCategory::query()->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        //      REGISTRO DE TIPO DE GASTO COMBUSTIBLE
        DB::table('expense_categories')->insert([
            'name'=>'Impuestos',
            'description'=>'Pago de impuestos',
            'status'=>1,
            'created_by'=>1,
            'updated_by'=>1,
            'tax_application'=>0,
            'taxes'=>0
        ]);
        //      REGISTRO DE TIPO DE GASTO COMBUSTIBLE
        DB::table('expense_categories')->insert([
            'name'=>'Combustibles',
            'description'=>'Combustibles e inflamables',
            'status'=>1,
            'created_by'=>1,
            'updated_by'=>1,
            'tax_application'=>1,
            'taxes'=>3
        ]);

        DB::table('expense_categories')->insert([
            'name'=>'Compras',
            'description'=>'Compra de mercaderías',
            'status'=>1,
            'created_by'=>1,
            'updated_by'=>1,
            'tax_application'=>0,
            'taxes'=>0
        ]);

        DB::table('expense_categories')->insert([
            'name'=>'Traslados de bodega',
            'description'=>'Traslado de bodega',
            'status'=>1,
            'created_by'=>1,
            'updated_by'=>1,
            'tax_application'=>0,
            'taxes'=>0
        ]);


        DB::table('expense_categories')->insert([
            'name'=>'Sueldos',
            'description'=>'Sueldos',
            'status'=>1,
            'created_by'=>1,
            'updated_by'=>1,
            'tax_application'=>0,
            'taxes'=>0
        ]);

        DB::table('expense_categories')->insert([
            'name'=>'Renta',
            'description'=>'Renta',
            'status'=>1,
            'created_by'=>1,
            'updated_by'=>1,
            'tax_application'=>0,
            'taxes'=>0
        ]);

        DB::table('expense_categories')->insert([
            'name'=>'Luz, Agua y IUSI',
            'description'=>'Luz, Agua y IUSI',
            'status'=>1,
            'created_by'=>1,
            'updated_by'=>1,
            'tax_application'=>0,
            'taxes'=>0
        ]);

        DB::table('expense_categories')->insert([
            'name'=>'Internet',
            'description'=>'Internet',
            'status'=>1,
            'created_by'=>1,
            'updated_by'=>1,
            'tax_application'=>0,
            'taxes'=>0
        ]);

        DB::table('expense_categories')->insert([
            'name'=>'Teléfono',
            'description'=>'Teléfono',
            'status'=>1,
            'created_by'=>1,
            'updated_by'=>1,
            'tax_application'=>0,
            'taxes'=>0
        ]);

        DB::table('expense_categories')->insert([
            'name'=>'Publicidad y propaganda',
            'description'=>'Publicidad y propaganda',
            'status'=>1,
            'created_by'=>1,
            'updated_by'=>1,
            'tax_application'=>0,
            'taxes'=>0
        ]);
        //  REGISTRO DE TIPO DE GASTO GASTO SOBRE COMPRA
        DB::table('expense_categories')->insert([
            'name'=>'Gastos sobre compras',
            'description'=>'Compra de mercadería',
            'status'=>1,
            'created_by'=>1,
            'updated_by'=>1,
            'tax_application'=>0,
            'taxes'=>0
        ]);

        DB::table('expense_categories')->insert([
            'name'=>'Papeleria y útiles',
            'description'=>'Papeleria y útiles',
            'status'=>1,
            'created_by'=>1,
            'updated_by'=>1,
            'tax_application'=>0,
            'taxes'=>0
        ]);

        DB::table('expense_categories')->insert([
            'name'=>'Insumos de limpieza',
            'description'=>'Insumos de limpieza',
            'status'=>1,
            'created_by'=>1,
            'updated_by'=>1,
            'tax_application'=>0,
            'taxes'=>0
        ]);

        DB::table('expense_categories')->insert([
            'name'=>'Mantenimiento',
            'description'=>'Mantenimiento',
            'status'=>1,
            'created_by'=>1,
            'updated_by'=>1,
            'tax_application'=>0,
            'taxes'=>0
        ]);

        DB::table('expense_categories')->insert([
            'name'=>'Reparaciones y repuestos',
            'description'=>'Reparaciones y repuestos',
            'status'=>1,
            'created_by'=>1,
            'updated_by'=>1,
            'tax_application'=>0,
            'taxes'=>0
        ]);
        DB::table('expense_categories')->insert([
            'name'=>'Depósitos por cuadre',
            'description'=>'Movimiento de caja a cuentas bancarias',
            'status'=>1,
            'created_by'=>1,
            'updated_by'=>1,
            'tax_application'=>0,
            'taxes'=>0,
            'type'=>2
        ]);
        //REGISTRO DE IMPUESTOS APLICADOS A COMBUSTIBLES
        DB::table('expense_taxes')->insert([
            'name'=>'Diesel',
            'value'=>4.5,
            'percent'=>0,
            'units'=>'Galon',
            'description'=>'Impuesto a combustible tipo Diesel por galón',
            'expense_categorie_id'=>2,
            'created_by'=>1,
            'updated_by'=>1,
        ]);
        DB::table('expense_taxes')->insert([
            'name'=>'Regular',
            'value'=>6,
            'percent'=>0,
            'units'=>'Galon',
            'description'=>'Impuesto a combustible tipo Regular por galón',
            'expense_categorie_id'=>2,
            'created_by'=>1,
            'updated_by'=>1,
        ]);
        DB::table('expense_taxes')->insert([
            'name'=>'Super',
            'value'=>9,
            'percent'=>0,
            'units'=>'Galon',
            'description'=>'Impuesto a combustible tipo Super por galón',
            'expense_categorie_id'=>2,
            'created_by'=>1,
            'updated_by'=>1,
        ]);
    }
}

