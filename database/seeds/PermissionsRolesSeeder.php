<?php

use App\Permission;
use App\Role;
use App\UserRole;
use Illuminate\Database\Seeder;

class PermissionsRolesSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Permission::query()->truncate();
        Role::query()->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        /* ************************************************************************************
        **************************************** PERMISOS  ***********************************
        ************************************************************************************ */

        /* --------------------------------------------------
        ---------------- MENU DE VENTAS  ----------------
        ----------------------------------------------------- */
        $i = 1;
        $descripcion[$i] = 'Menu de Venta';
        $ruta[$i] = 'sales-menu';
        $ruta_padre[$i] = '';


        $i++;
        $descripcion[$i] = 'Menú cotización [M.Venta]';
        $ruta[$i] = 'quotation/header';
        $ruta_padre[$i] = 'sales-menu';
        $i++;

        $descripcion[$i] = 'Nueva venta [M.Venta]';
        $ruta[$i] = 'sales?id=0';
        $ruta_padre[$i] = 'sales-menu';
        $i++;
        $descripcion[$i] = 'Clientes [M.Venta]';
        $ruta[$i] = 'customers';
        $ruta_padre[$i] = 'sales-menu';
        $i++;
        $descripcion[$i] = 'Crear cliente [M. venta]';
        $ruta[$i] = 'customers/create';
        $ruta_padre[$i] = 'sales-menu';
        $i++;
        $descripcion[$i] = 'Modificar cliente [M. venta]';
        $ruta[$i] = 'customers/edit';
        $ruta_padre[$i] = 'sales-menu';
        $i++;
        $descripcion[$i] = 'eliminar cliente [M. venta]';
        $ruta[$i] = 'customers/delete';
        $ruta_padre[$i] = 'sales-menu';
        $i++;
        $descripcion[$i] = 'Estado de cuenta clientes [M. venta]';
        $ruta[$i] = 'customers/statement';
        $ruta_padre[$i] = 'sales-menu';

        $i++;
        $descripcion[$i] = 'Rutas [M. venta]';
        $ruta[$i] = 'routes';
        $ruta_padre[$i] = 'sales-menu';
        $i++;
        $descripcion[$i] = 'Crear ruta [M. venta]';
        $ruta[$i] = 'routes/create';
        $ruta_padre[$i] = 'sales-menu';
        $i++;
        $descripcion[$i] = 'Modificar ruta  [M. venta]';
        $ruta[$i] = 'routes/edit';
        $ruta_padre[$i] = 'sales-menu';
        $i++;
        $descripcion[$i] = 'Eliminar ruta  [M. venta]';
        $ruta[$i] = 'routes/delete';
        $ruta_padre[$i] = 'sales-menu';


        $i++;
        $descripcion[$i] = 'Cuentas por cobrar [M. venta]';
        $ruta[$i] = 'credit';
        $ruta_padre[$i] = 'sales-menu';
        $i++;
        $descripcion[$i] = 'Notas de crédito';
        $ruta[$i] = 'credit_note';
        $ruta_padre[$i] = 'credit_note';

        // $i++;
        // $descripcion[$i] = 'Morosos [M. venta]';
        // $ruta[$i] = 'reports/customers_pending_to_pay';
        // $ruta_padre[$i] = 'sales-menu';


        /*  SEPARACION MENU */
        /* --------------------------------------------------
        ---------------- MENU DE COMPRAS  ----------------
        ----------------------------------------------------- */
        $i++;
        $descripcion[$i] = 'Menu de compras';
        $ruta[$i] = 'receivings-menu';
        $ruta_padre[$i] = '';
        $i++;
        $descripcion[$i] = 'Nueva compra [M. compra]';
        $ruta[$i] = 'receivings';
        $ruta_padre[$i] = 'receivings-menu';

        $i++;
        $descripcion[$i] = 'Proveedores [M. compra]';
        $ruta[$i] = 'suppliers';
        $ruta_padre[$i] = 'receivings-menu';
        $i++;
        $descripcion[$i] = 'Crear proveedor [M. compra]';
        $ruta[$i] = 'suppliers/create';
        $ruta_padre[$i] = 'receivings-menu';
        $i++;
        $descripcion[$i] = 'Modificar proveedor [M. compra]';
        $ruta[$i] = 'suppliers/edit';
        $ruta_padre[$i] = 'receivings-menu';
        $i++;
        $descripcion[$i] = 'Eliminar proveedor [M. compra]';
        $ruta[$i] = 'suppliers/delete';
        $ruta_padre[$i] = 'receivings-menu';

        $i++;
        $descripcion[$i] = 'Cuentas por pagar [M. compra]';
        $ruta[$i] = 'credit_suppliers';
        $ruta_padre[$i] = 'credit_suppliers';
        $i++;
        $descripcion[$i] = 'Estado de cuenta proveedores [M. compra]';
        $ruta[$i] = 'suppliers/statement';
        $ruta_padre[$i] = 'receivings-menu';

        /*  SEPARACION MENU */
        /* --------------------------------------------------
        ---------------- MENU DE PROYECTOS  ----------------
        ----------------------------------------------------- */
        // $i++;
        // $descripcion[$i] = 'Proyectos';
        // $ruta[$i] = 'project/projects';
        // $ruta_padre[$i] = 'project';
        // $i++;
        // $descripcion[$i] = 'Etapas';
        // $ruta[$i] = 'project/stages';
        // $ruta_padre[$i] = 'project';
        // $i++;
        // $descripcion[$i] = 'Atributos';
        // $ruta[$i] = 'project/atributes';
        // $ruta_padre[$i] = 'project';
        // $i++;
        // $descripcion[$i] = 'Renglones';
        // $ruta[$i] = 'line-template';
        // $ruta_padre[$i] = 'line-template';

        // $i++;
        // $descripcion[$i] = 'Modificar precios de artículos en creación y edición de renglones';
        // $ruta[$i] = 'line-template/item/config_budget_price';
        // $ruta_padre[$i] = 'line-template';

        // $i++;
        // $descripcion[$i] = 'Ver monto acordado de proyecto [M. Proyectos]';
        // $ruta[$i] = 'project/stages_project/';
        // $ruta_padre[$i] = 'project';

        // $i++;
        // $descripcion[$i] = 'Ver saldo de proyecto [M. Proyectos]';
        // $ruta[$i] = 'project/stages_project/';
        // $ruta_padre[$i] = 'project';

        // $i++;
        // $descripcion[$i] = 'Ver ingresos de proyectos [M. Proyectos]';
        // $ruta[$i] = 'project/stages_project/';
        // $ruta_padre[$i] = 'project';
        // $i++;

        // $descripcion[$i] = 'Ver egresos de proyectos [M. Proyectos]';
        // $ruta[$i] = 'project/stages_project/';
        // $ruta_padre[$i] = 'project';

        /*  SEPARACION MENU */
        /* --------------------------------------------------
        ---------------- MENU DE INVENTARIO  ----------------
        ----------------------------------------------------- */

        $i++;
        $descripcion[$i] = 'Menu de Inventario';
        $ruta[$i] = 'ítems-menu';
        $ruta_padre[$i] = '';

        /* ---------------- SUBMENU  ---------------- */
        $i++;
        $descripcion[$i] = 'Sub-menu Productos & Servicios [M. Productos]';
        $ruta[$i] = 'products-services';
        $ruta_padre[$i] = 'ítems-menu';


        $i++;
        $descripcion[$i] = 'Catálogo/precios Productos [M. Productos]';
        $ruta[$i] = 'ítems';
        $ruta_padre[$i] = 'ítems-menu';
        $i++;
        $descripcion[$i] = 'Crear productos [M. Productos]';
        $ruta[$i] = 'ítems/create';
        $ruta_padre[$i] = 'ítems-menu';
        $i++;
        $descripcion[$i] = 'Modificaar productos [M. Productos]';
        $ruta[$i] = 'ítems/edit';
        $ruta_padre[$i] = 'ítems-menu';
        $i++;
        $descripcion[$i] = 'Eliminar productos [M. Productos]';
        $ruta[$i] = 'ítems/delete';
        $ruta_padre[$i] = 'ítems-menu';
        $i++;
        $descripcion[$i] = 'Detalle productos [M. Productos]';
        $ruta[$i] = 'ítems/detail';
        $ruta_padre[$i] = 'ítems-menu';

        $i++;
        $descripcion[$i] = 'Catálogo/precios Servicios [M. Productos]';
        $ruta[$i] = 'services';
        $ruta_padre[$i] = 'ítems-menu';
        $i++;
        $descripcion[$i] = 'Crear servicios [M. Productos]';
        $ruta[$i] = 'services/create';
        $ruta_padre[$i] = 'ítems-menu';
        $i++;
        $descripcion[$i] = 'Modificaar servicios [M. Productos]';
        $ruta[$i] = 'services/edit';
        $ruta_padre[$i] = 'ítems-menu';
        $i++;
        $descripcion[$i] = 'Eliminar servicios [M. Productos]';
        $ruta[$i] = 'services/delete';
        $ruta_padre[$i] = 'ítems-menu';
        $i++;
        $descripcion[$i] = 'Detalle servicios [M. Productos]';
        $ruta[$i] = 'services/detail';
        $ruta_padre[$i] = 'ítems-menu';



        $i++;
        $descripcion[$i] = 'Busqueda de productos / Kardex [M. Productos]';
        $ruta[$i] = 'items/search';
        $ruta_padre[$i] = 'ítems-menu';
        $i++;
        $descripcion[$i] = 'Existencias por bodega [M. Productos]';
        $ruta[$i] = 'items/show';
        $ruta_padre[$i] = 'ítems-menu';
        $i++;
        $descripcion[$i] = 'Kits [M. Productos]';
        $ruta[$i] = 'item-kits';
        $ruta_padre[$i] = 'ítems-menu';
        $i++;
        $descripcion[$i] = 'Crear kit [M. Productos]';
        $ruta[$i] = 'item-kits/create';
        $ruta_padre[$i] = 'ítems-menu';
        $i++;
        $descripcion[$i] = 'Modificar kit [M. Productos]';
        $ruta[$i] = 'item-kits/edit';
        $ruta_padre[$i] = 'ítems-menu';
        $i++;
        $descripcion[$i] = 'Eliminar kit [M. Productos]';
        $ruta[$i] = 'item-kits/delete';
        $ruta_padre[$i] = 'ítems-menu';

        $i++;
        $descripcion[$i] = 'Categorias de productos [M. Productos]';
        $ruta[$i] = 'categorie_product';
        $ruta_padre[$i] = 'ítems-menu';
        $i++;
        $descripcion[$i] = 'Crear categorias de productos [M. Productos]';
        $ruta[$i] = 'categorie_product/create';
        $ruta_padre[$i] = 'ítems-menu';
        $i++;
        $descripcion[$i] = 'Modificar categorias de productos [M. Productos]';
        $ruta[$i] = 'categorie_product/edit';
        $ruta_padre[$i] = 'ítems-menu';
        $i++;
        $descripcion[$i] = 'Eliminar categorias de productos [M. Productos]';
        $ruta[$i] = 'categorie_product/delete';
        $ruta_padre[$i] = 'ítems-menu';

        $i++;
        $descripcion[$i] = trans('unit_measure.unit_measure');
        $ruta[$i] = 'unit_measure';
        $ruta_padre[$i] = 'ítems-menu';

        /* -------------------------------- SUBMENU -------------------------------- */
        $i++;
        $descripcion[$i] = 'Sub-menu Configuraciones y Movimientos [M. Productos]';
        $ruta[$i] = 'settings-movements';
        $ruta_padre[$i] = 'ítems-menu';


        $i++;
        $descripcion[$i] = 'Listados de precios [M. Productos]';
        $ruta[$i] = 'prices';
        $ruta_padre[$i] = 'ítems-menu';

        $i++;
        $descripcion[$i] = 'Gestión de cierres de inventario [M. Productos]';
        $ruta[$i] = 'inventory_closing';
        $ruta_padre[$i] = 'ítems-menu';

        $i++;
        $descripcion[$i] = 'Bodegas [M. Productos]';
        $ruta[$i] = 'almacen';
        $ruta_padre[$i] = 'ítems-menu';
        $i++;
        $descripcion[$i] = 'Crear bodega [M. Productos]';
        $ruta[$i] = 'almacen/create';
        $ruta_padre[$i] = 'ítems-menu';
        $i++;
        $descripcion[$i] = 'Modificar bodega [M. Productos]';
        $ruta[$i] = 'almacen/edit';
        $ruta_padre[$i] = 'ítems-menu';
        $i++;
        $descripcion[$i] = 'Eliminar bodega [M. Productos]';
        $ruta[$i] = 'almacen/delete';
        $ruta_padre[$i] = 'ítems-menu';

        $i++;
        $descripcion[$i] = 'Traslados de bodega [M. Productos]';
        $ruta[$i] = 'list_transfer';
        $ruta_padre[$i] = 'ítems-menu';
        $i++;
        $descripcion[$i] = 'Crear traslado de bodega [M. Productos]';
        $ruta[$i] = 'transfer_to_storage';
        $ruta_padre[$i] = 'ítems-menu';

        $i++;
        $descripcion[$i] = 'Crear ajuste de inventario (+) [M. Productos]';
        $ruta[$i] = 'inventory_adjustment';
        $ruta_padre[$i] = 'ítems-menu';
        $i++;
        $descripcion[$i] = 'Crear ajuste de inventario (-) [M. Productos]';
        $ruta[$i] = 'inventory_adjustment/sale';
        $ruta_padre[$i] = 'ítems-menu';
        $i++;
        $descripcion[$i] = 'Costo del Inventario';
        $ruta[$i] = 'reports/product_and_cellars';
        $ruta_padre[$i] = 'ítems-menu';



        /*  SEPARACION MENU */
        /* --------------------------------------------------
        ---------------- MENU DE REPORTES  ----------------
        ----------------------------------------------------- */
        $i++;
        $descripcion[$i] = 'Menu de Reportes';
        $ruta[$i] = 'reports-menu';
        $ruta_padre[$i] = '';
        /* -------------------------------- SUBMENU COMPRAS -------------------------------- */
        $i++;
        $descripcion[$i] = 'Sección de Reportes de Compras [M. reportes]';
        $ruta[$i] = 'reports-receivings-section';
        $ruta_padre[$i] = 'reports-menu';
        $i++;
        $descripcion[$i] = 'Compras [M. reportes]';
        $ruta[$i] = 'reports/receivings';
        $ruta_padre[$i] = 'reports-menu';
        $i++;
        $descripcion[$i] = 'Compras por tipo de pago [M. reportes]';
        $ruta[$i] = 'reports/reporte_compra';
        $ruta_padre[$i] = 'reports-menu';


        /* -------------------------------- SUBMENU VENTAS -------------------------------- */
        $i++;
        $descripcion[$i] = 'Seccion de Reportes de Ventas [M. reportes]';
        $ruta[$i] = 'reports-sales-section';
        $ruta_padre[$i] = 'reports-menu';
        $i++;
        $descripcion[$i] = 'Ventas [M. reportes]';
        $ruta[$i] = 'reports/sales';
        $ruta_padre[$i] = 'reports-menu';
        $i++;
        $descripcion[$i] = 'Arqueo de Caja [M. reportes]';
        $ruta[$i] = 'reports/reporte_venta';
        $ruta_padre[$i] = 'reports-menu';
        $i++;
        $descripcion[$i] = 'Ventas totales por ruta [M. reportes]';
        $ruta[$i] = 'reports/route/sales';
        $ruta_padre[$i] = 'reports-menu';

        $i++;
        $descripcion[$i] = 'Productos más vendidos [M. reportes]';
        $ruta[$i] = 'reports/items_quantity_sales';
        $ruta_padre[$i] = 'reports-menu';
        $i++;
        $descripcion[$i] = 'Rentabilidad por ruta [M. reportes]';
        $ruta[$i] = 'reports/route/profit';
        $ruta_padre[$i] = 'reports-menu';
        $i++;
        $descripcion[$i] = 'Rentabilidad por producto [M. reportes]';
        $ruta[$i] = 'reports/profit_by_product';
        $ruta_padre[$i] = 'reports-menu';
        $i++;
        $descripcion[$i] = 'Liquidación semanal';
        $ruta[$i] = 'reports/inventory_week';
        $ruta_padre[$i] = 'reports-menu';


        /* -------------------------------- SUBMENU -------------------------------- */
        $i++;
        $descripcion[$i] = 'Sección de Reportes de Inventario [M. reportes]';
        $ruta[$i] = 'reports-inventory-section';
        $ruta_padre[$i] = 'reports-menu';


        $i++;
        $descripcion[$i] = 'Existencias [M. reportes]';
        $ruta[$i] = 'reports/stock';
        $ruta_padre[$i] = 'reports-menu';
        $i++;
        $descripcion[$i] = 'Ajustes de inventario Ingresos (+) [M. reportes]';
        $ruta[$i] = 'report_adjustment_in';
        $ruta_padre[$i] = 'reports-menu';
        $i++;
        $descripcion[$i] = 'Ajustes de inventario  Salidas (-) [M. reportes]';
        $ruta[$i] = 'report_adjustment_out';
        $ruta_padre[$i] = 'reports-menu';
        $i++;
        $descripcion[$i] = 'Productos por fecha de expiración [M. reportes]';
        $ruta[$i] = 'reports/expired_report';
        $ruta_padre[$i] = 'reports-menu';







        /*  SEPARACION MENU */
        /* --------------------------------------------------
        ---------------- MENU DE BANCOS  ----------------
        ----------------------------------------------------- */

        $i++;
        $descripcion[$i] = 'Bancos';
        $ruta[$i] = 'banks';
        $ruta_padre[$i] = '';
        /* -------------------------------- SUBMENU -------------------------------- */
        $i++;
        $descripcion[$i] = 'Cuentas Bancarias [M. bancos]';
        $ruta[$i] = 'accounts';
        $ruta_padre[$i] = 'banks';
        $i++;
        $descripcion[$i] = 'Crear cuenta [M. bancos]';
        $ruta[$i] = 'accounts/create';
        $ruta_padre[$i] = 'banks';
        $i++;
        $descripcion[$i] = 'Modificar cuenta [M. bancos]';
        $ruta[$i] = 'accounts/edit';
        $ruta_padre[$i] = 'banks';
        $i++;
        $descripcion[$i] = 'Eliminar cuenta [M. bancos]';
        $ruta[$i] = 'accounts/delete';
        $ruta_padre[$i] = 'banks';
        $i++;
        $descripcion[$i] = 'Estado de cuenta [M. bancos]';
        $ruta[$i] = 'accounts/statement';
        $ruta_padre[$i] = 'banks';
        $i++;
        $descripcion[$i] = 'Transferencias [M. bancos]';
        $ruta[$i] = 'accounts/transfers';
        $ruta_padre[$i] = 'banks';

        $i++;
        $descripcion[$i] = 'Cajas [M. bancos]';
        $ruta[$i] = 'cash_register';
        $ruta_padre[$i] = 'banks';
        $i++;
        $descripcion[$i] = 'Crear caja [M. bancos]';
        $ruta[$i] = 'cash_register/create';
        $ruta_padre[$i] = 'banks';
        $i++;
        $descripcion[$i] = 'Modificar caja [M. bancos]';
        $ruta[$i] = 'cash_register/edit';
        $ruta_padre[$i] = 'banks';
        $i++;
        $descripcion[$i] = 'Eliminar caja [M. bancos]';
        $ruta[$i] = 'cash_register/delete';
        $ruta_padre[$i] = 'banks';
        $i++;
        $descripcion[$i] = 'Estado de caja [M. bancos]';
        $ruta[$i] = 'cash_register/statement';
        $ruta_padre[$i] = 'banks';

        $i++;
        $descripcion[$i] = 'Ver cierres de caja [M. bancos]';
        $ruta[$i] = 'desk_closing/index';
        $ruta_padre[$i] = 'banks';

        $i++;
        $descripcion[$i] = 'Crear cierres de caja [M. bancos]';
        $ruta[$i] = 'desk_closing/create';
        $ruta_padre[$i] = 'banks';



        $i++;
        $descripcion[$i] = 'Egresos de cuenta [M. bancos]';
        $ruta[$i] = 'banks/expenses';
        $ruta_padre[$i] = 'banks';

        $i++;
        $descripcion[$i] = 'Anular egresos de cuenta [M. bancos]';
        $ruta[$i] = 'banks/expenses/delete';
        $ruta_padre[$i] = 'banks';

        $i++;
        $descripcion[$i] = 'Ingresos a Caja [M. bancos]';
        $ruta[$i] = 'banks/revenues';
        $ruta_padre[$i] = 'banks';

        $i++;
        $descripcion[$i] = 'Depósitos [M. bancos]';
        $ruta[$i] = 'banks/deposits';
        $ruta_padre[$i] = 'banks';

        $i++;
        $descripcion[$i] = 'Anular ingresos de cuenta [M. bancos]';
        $ruta[$i] = 'banks/revenues/delete';
        $ruta_padre[$i] = 'banks';
        //Retenciones
        $i++;
        $descripcion[$i] = 'Retenciones';
        $ruta[$i] = 'banks/retention';
        $ruta_padre[$i] = 'banks';

        /*  SEPARACION MENU */
        /* -------------------------------- SUBMENU -------------------------------- */
        $i++;
        $descripcion[$i] = 'Administracion [M. administracion]';
        $ruta[$i] = 'administration-menu';
        $ruta_padre[$i] = 'banks';
        $i++;
        $descripcion[$i] = 'Gastos [M. administracion]';
        $ruta[$i] = 'expenses';
        $ruta_padre[$i] = 'banks';
        $i++;
        $descripcion[$i] = 'Crear gasto [M. administracion]';
        $ruta[$i] = 'expenses/create';
        $ruta_padre[$i] = 'banks';

        $i++;
        $descripcion[$i] = 'Categorias de gastos [M. administracion]';
        $ruta[$i] = 'banks/expense_categories';
        $ruta_padre[$i] = 'banks';
        $i++;
        $descripcion[$i] = 'Crear categoria de gasto [M. administracion]';
        $ruta[$i] = 'banks/expense_categories/create';
        $ruta_padre[$i] = 'banks';



        // $i++;
        // $descripcion[$i] = 'Planilla';
        // $ruta[$i] = '';
        // $ruta_padre[$i] = 'administration-menu';
        $i++;
        $descripcion[$i] = 'Depreciaciones';
        $ruta[$i] = '';
        $ruta_padre[$i] = 'administration-menu';

        /*  SEPARACION MENU */
        $i++;
        $descripcion[$i] = 'Graficas';
        $ruta[$i] = 'charts';
        $ruta_padre[$i] = '';


        /*  SEPARACION MENU */
        /* --------------------------------------------------
        ---------------- MENU DE ANULACIONES  ----------------
        ----------------------------------------------------- */
        $i++;
        $descripcion[$i] = 'Menu de anulaciones';
        $ruta[$i] = 'anulaciones-menu';
        $ruta_padre[$i] = '';
        $i++;
        $descripcion[$i] = 'Anulacion de compras [M. anulaciones]';
        $ruta[$i] = 'cancel_bill_receivings';
        $ruta_padre[$i] = 'anulaciones-menu';
        $i++;
        $descripcion[$i] = 'Compras anuladas [M. anulaciones]';
        $ruta[$i] = 'cancel_bill_receivings/report_cancel_bill_receivings';
        $ruta_padre[$i] = 'anulaciones-menu';
        $i++;
        $descripcion[$i] = 'Anulacion de ventas [M. anulaciones]';
        $ruta[$i] = 'cancel_bill';
        $ruta_padre[$i] = 'anulaciones-menu';
        $i++;
        $descripcion[$i] = 'Ventas anuladas [M. anulaciones]';
        $ruta[$i] = 'cancel_bill/report_canceled_sales';
        $ruta_padre[$i] = 'anulaciones-menu';
        /*  SEPARACION MENU */
        /* --------------------------------------------------
        ---------------- MENU DE ACCESO  ----------------
        ----------------------------------------------------- */

        $i++;
        $descripcion[$i] = 'Menu de Acceso';
        $ruta[$i] = 'access-menu';
        $ruta_padre[$i] = '';
        $i++;
        $descripcion[$i] = 'Roles [M. acceso]';
        $ruta[$i] = 'roles';
        $ruta_padre[$i] = 'access-menu';
        $i++;
        $descripcion[$i] = 'Permisos [M. acceso]';
        $ruta[$i] = 'permissions';
        $ruta_padre[$i] = 'access-menu';
        $i++;
        $descripcion[$i] = 'Usuarios Roles [M. acceso]';
        $ruta[$i] = 'user_role';
        $ruta_padre[$i] = 'access-menu';
        $i++;
        $descripcion[$i] = 'Empleados /Usuarios [M. acceso]';
        $ruta[$i] = 'employees';
        $ruta_padre[$i] = 'access-menu';

        /*  SEPARACION MENU */
        /* --------------------------------------------------
        ---------------- MENU DE CONFIGURACION/PARAMETROS  ----------------
        ----------------------------------------------------- */
        $i++;
        $descripcion[$i] = 'Menu de configuración';
        $ruta[$i] = 'parametes-menu';
        $ruta_padre[$i] = '';
        $i++;
        $descripcion[$i] = 'Listado de tipos de pago [M. parametros]';
        $ruta[$i] = 'pago';
        $ruta_padre[$i] = 'parametes-menu';
        $i++;
        $descripcion[$i] = 'Crear tipo de pago [M. parametros]';
        $ruta[$i] = 'pago/create';
        $ruta_padre[$i] = 'parametes-menu';
        $i++;
        $descripcion[$i] = 'Modificar tipo de pago [M. parametros]';
        $ruta[$i] = 'pago/edit';
        $ruta_padre[$i] = 'parametes-menu';
        $i++;
        $descripcion[$i] = 'Eliminar tipo de pago [M. parametros]';
        $ruta[$i] = 'pago/delete';
        $ruta_padre[$i] = 'parametes-menu';

        $i++;
        $descripcion[$i] = 'Listado de Documentos de inventario [M. configuracion]';
        $ruta[$i] = 'documents';
        $ruta_padre[$i] = 'parametes-menu';
        $i++;
        $descripcion[$i] = 'Crear documento [M. configuracion]';
        $ruta[$i] = 'documents/create';
        $ruta_padre[$i] = 'parametes-menu';
        $i++;
        $descripcion[$i] = 'Modificar documento [M. configuracion]';
        $ruta[$i] = 'documents/edit';
        $ruta_padre[$i] = 'parametes-menu';
        $i++;
        $descripcion[$i] = 'Eliminar documento [M. configuracion]';
        $ruta[$i] = 'documents/delete';
        $ruta_padre[$i] = 'parametes-menu';

        $i++;
        $descripcion[$i] = 'Listado de Series de doctos. [M. configuracion]';
        $ruta[$i] = 'series';
        $ruta_padre[$i] = 'parametes-menu';
        $i++;
        $descripcion[$i] = 'Crear serie [M. configuracion]';
        $ruta[$i] = 'series/create';
        $ruta_padre[$i] = 'parametes-menu';
        $i++;
        $descripcion[$i] = 'Modificar serie [M. configuracion]';
        $ruta[$i] = 'series/edit';
        $ruta_padre[$i] = 'parametes-menu';
        $i++;
        $descripcion[$i] = 'Eliminar serie [M. configuracion]';
        $ruta[$i] = 'series/delete';
        $ruta_padre[$i] = 'parametes-menu';


        $i++;
        $descripcion[$i] = 'Parametros Generales [M. configuracion]';
        $ruta[$i] = 'parameters';
        $ruta_padre[$i] = '/parameters';

        $i++;
        $descripcion[$i] = 'Configuración de presupuestos';
        $ruta[$i] = 'budget_config';
        $ruta_padre[$i] = '/budget_config';

        $i++;
        $descripcion[$i] = 'Denominación de Monedas';
        $ruta[$i] = 'typeMoney';
        $ruta_padre[$i] = '/typeMoney';






        //hacemos las inserciones dentro de la tabla
        // PERMISOS
        for ($n = 1; $n <= $i; $n++) {
            DB::table('permissions')->insert([
                'descripcion' => $descripcion[$n],
                'ruta' => $ruta[$n],
                'ruta_padre' => $ruta_padre[$n],
                ]);
            }//termina el ciclo de insertar los permisos


        /* ************************************************************************************
        **************************************** ROLES  ***********************************
        ************************************************************************************ */
        //Insertamos un rol administrador
        DB::table('roles')->insert([
            'role' => 'Super Administrador',
            'admin' => '1',
            ]);
        DB::table('roles')->insert([
            'role' => 'Admin',
            'admin' => '1',
            ]);

            //Insertamos un rol no administrador
        DB::table('roles')->insert([
            'role' => 'Usuario',
            'admin' => '0',
            ]);

        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // Permission::query()->truncate();
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        //insertamos los permisos al rol de administrador
        for ($n = 1; $n <= $i; $n++) {
            DB::table('role_permissions')->insert([
                'id_rol' => '1',
                'id_permission' => $n,
                'estado_permiso' => 1,
                ]);

                DB::table('role_permissions')->insert([
                    'id_rol' => '2',
                    'id_permission' => $n,
                    'estado_permiso' => 1,
                    ]);
            }


    }
}
