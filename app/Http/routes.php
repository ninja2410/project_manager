<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
 */

use App\Http\Controllers\ReceivingApiController;

Route::group(['middleware' => 'languange'], function () {
    Route::get('/', 'HomeController@index');
    // Route::get('/welcome', function () {
    //     return view('welcome');
    // });

    Route::get('home', 'HomeController@index');
    /*
     * NOTIFICACIONES A USUARIOS
     * */
    Route::get('getNotifications','NotificationController@getNotifications');

    // Authentication routes...
    Route::get('auth/login', 'Auth\AuthController@getLogin');
    Route::post('auth/login', 'Auth\AuthController@postLogin');
    Route::get('auth/logout', 'Auth\AuthController@getLogout');

    // Password reset link request routes...
    Route::get('password/email', 'Auth\PasswordController@getEmail');
    Route::post('password/email', 'Auth\PasswordController@postEmail');

    // Password reset routes...
    Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
    Route::post('password/reset', 'Auth\PasswordController@postReset');
    // Route::get('customers/getCustomer',array('as' => 'admin.customer.getCustomer', 'uses' => 'CustomerController@getCustomer'));
    // Route::get('customers/addCustomerAjax','CustomerController@addCustomerAjax')->name('addCustomerAjax');
    Route::get('customers/getCustomer', array('as pagares/send/date' => 'admin.customer.getCustomer', 'uses' => 'CustomerController@getCustomer'));
    Route::post('customers/addCustomerAjax', 'CustomerController@addCustomerAjax');
    Route::post('customers/getReference', 'CustomerController@getReference');
    Route::post('customers/verify', 'CustomerController@verifyData');
    Route::get('customers/profile/{id}', 'CustomerController@getProfile');
    Route::post('customers/editCustomerAjax', 'CustomerController@editCustomerAjax');
    Route::post('customers/addCustomerAjaxPos', 'CustomerController@addCustomerAjaxPos');
    Route::get('customers/references/{id}', 'CustomerController@show_references');


    Route::resource('customers', 'CustomerController');
    Route::get('getCustomers', 'CustomerController@list_customers');
    /* *****************************************************************************
    **************************** PRODUCTOS
    ******************************************************************************/
    Route::resource('prices', 'PricesController');
    Route::get('items/search', 'ItemController@search');
    Route::get('items/detail/{id}', 'ItemController@detail');
    Route::post('items/product_and_cellars', 'SaleReportController@product_and_cellars');
    Route::get('items/product_and_cellars', 'SaleReportController@product_and_cellars');
    /**
     * API
     */
    Route::get('items/index_services_ajax_by_storage', 'ItemController@index_services_ajax_by_storage');
    Route::get('items/index_services_ajax_by_storage_pago', 'ItemController@index_services_ajax_by_storage_pago');
    Route::get('items/index_services_ajax_by_storage_price', 'ItemController@index_services_ajax_by_storage_price');
    Route::get('items/index_services_ajax_by_price', 'ItemController@index_services_ajax_by_price');
    Route::get('items/index_services_ajax_by_pago', 'ItemController@index_services_ajax_by_pago');
    Route::post('items/index_ajax', 'ItemController@index_ajax');
    Route::get('items/index_ajax_by_price', 'ItemController@index_ajax_by_price');

    Route::post('items/index_services_ajax', 'ItemController@index_services_ajax');
    Route::get('items/index_services_ajax_all', 'ItemController@index_services_ajax_all');
    Route::get('items/index_items_ajax_all', 'ItemController@index_items_ajax_all');

    Route::resource('items', 'ItemController');
    // Route::resource('items/create_service', 'ItemController@create_service');
    Route::get('services', 'ItemController@index_services');
    Route::get('services/create', 'ItemController@create_service');


    Route::get('items/filter/{id}', 'ItemController@index');
    Route::get('items/show/{id}/{price}', 'ItemController@show');
    Route::get('items/price-list/{id}', 'ItemController@price-list');


    // Busqueda autocomplete de items
    Route::get('api/items/search_by_code', 'ItemApiController@code');
    Route::get('api/items/search_by_name', 'ItemApiController@name');
    Route::get('api/items/search_by_other', 'ItemApiController@other');
    Route::get('api/items/get_code_by_id/{id}', 'ItemApiController@get_code_by_id');


    // Costo del inventario


    /**
     * CUENTAS POR COBRAR
     */
    Route::resource('credit', 'CreditController');
    Route::get('credit/completeCredit/{id}', 'CreditController@completeCredit');
    Route::get('credit/completeCredit_print/{id}', 'CreditController@completeCredit_print');
    Route::get('credit/statemente/invoice/{id}', 'CreditController@statement_invoice');
    /**
     * Estado de cuenta de cliente
     */
    Route::get('credit/statement/{id}', 'CreditController@statement');
    /**Comprobante de abono a CXC */
    Route::get('credit/completeCredit_print_payment/{id}', 'CreditController@completeCredit_print_payment');

    Route::get('credit/create/{id}', 'CreditController@create');
    Route::get('credit/addPayment/{id}', 'CreditController@addPayment');
    Route::get('credit/printDetail/{id}', 'CreditController@printDetail2');

    Route::get('credit/editPayment/{id}', 'CreditController@editPayment');
    Route::post('credit/savePayment', 'CreditController@savePayment');
    Route::get('credit/complete/{id}', 'CreditController@printDetail');

    #region CUENTAS POR PAGAR
    Route::resource('credit_suppliers', 'CreditSupplierController');
    Route::get('credit_suppliers/statement/invoice/{id}', 'CreditSupplierController@statement_invoice');
    Route::get('credit_suppliers/printPayment/{id}', 'CreditSupplierController@printPayment');
    Route::get('credit_suppliers/statement/{id}', 'CreditSupplierController@statement');
    #endregion


    // Reporte de venta
    Route::get('reports/reporte_venta', 'SaleReportController@reporte_venta');
    Route::post('reports/reporte_venta', 'SaleReportController@reporte_venta');
    Route::get('reports/expired_report', 'SaleReportController@expired_report');
    Route::post('reports/expired_report', 'SaleReportController@expired_report');
    Route::get('reports/inventory_week', 'SaleReportController@inventory_week');

    /* Ventas anuladas */
    Route::get('cancel_bill/report_canceled_sales', 'SaleReportController@report_cancel_bill');
    Route::post('cancel_bill/report_canceled_sales', 'SaleReportController@report_cancel_bill');
    /* Compras anuladas */
    Route::get('cancel_bill_receivings/report_cancel_bill_receivings', 'ReceivingReportController@report_cancel_bill');
    Route::post('cancel_bill_receivings/report_cancel_bill_receivings', 'ReceivingReportController@report_cancel_bill');

    // Reporte de compra
    Route::get('reports/reporte_compra', 'ReceivingReportController@reporte_compra');
    Route::post('reports/reporte_compra', 'ReceivingReportController@reporte_compra');
    // Reporte de clientes morosos
    Route::get('reports/customers_pending_to_pay', 'SaleReportController@customers_pending_to_pay');
    Route::post('reports/customers_pending_to_pay', 'SaleReportController@customers_pending_to_pay');
    // Reporte de productos mas vendidos
    Route::get('reports/items_quantity_sales', 'SaleReportController@items_quantity_sales');
    Route::post('reports/items_quantity_sales', 'SaleReportController@items_quantity_sales');

//reporte de productos en bodegas sin precio
    Route::post('reports/product_and_cellars_notPrice', 'SaleReportController@product_and_cellars_sinPrice');
    Route::get('reports/product_and_cellars_notPrice', 'SaleReportController@product_and_cellars_sinPrice');


    // Reportes para rutas
    Route::get('reports/route/sales','SaleReportController@routeSale');
    Route::post('reports/route/sales','SaleReportController@routeSale');

    Route::get('reports/route/profit','SaleReportController@routeSumarizado');
    Route::post('reports/route/profit','SaleReportController@routeSumarizado');

    /**Reporte de rentabilidad por producto */
    Route::get('reports/profit_by_product','ItemReportController@profitIndex');
    Route::post('reports/profit-by-product-det','ItemReportController@profitByProductByInvoice');
    Route::post('reports/profit-by-product-sum','ItemReportController@profitByProductSum');

    Route::resource('documents', 'DocumentController');
    Route::get('verify_type_fel', 'DocumentController@verifyTypeFel');
    Route::resource('series', 'SerieController');
    Route::resource('roles', 'RoleController');
    Route::resource('permissions', 'PermissionController');
    Route::resource('role_permission', 'RolePermissionController');
    Route::resource('user_role', 'UserRoleController');

    Route::resource('item-kits', 'ItemKitController');

    Route::resource('item-kits-vue', 'ItemKitVueController');

    Route::post('item-kits-vue/index_ajax', 'ItemKitVueController@index_ajax');
    Route::get('api/item-kit-vue/test', 'ItemKitVueController@index_ajax');

    Route::get('inventory/{item}/{almacen}', 'InventoryController@inventory');
    Route::resource('suppliers', 'SupplierController');
    Route::get('getSupplier/{id}', 'SupplierController@getSupplier');
    Route::post('suppliers/ajax', 'SupplierController@store_ajax');
    Route::resource('receivings', 'ReceivingController');
    Route::get('getDetails/{id}', 'ItemKitController@getElements');

    Route::get('sales/get_active_sales_ajax', 'SaleController@sales_ajax_active');
    Route::get('sales/get_details_ajax/{id}', 'SaleController@getSaleDetails');
    Route::resource('sales', 'SaleController');
    Route::get('sales/create/{quotation}/{cellar}', 'SaleController@create');
    // Route::resource('sales2', 'SaleController@index_test');
    Route::get('sales/complete/{id}', 'SaleController@complete')->name('completesale');
    Route::get('sales/ticket/{id}', 'SaleController@print_ticket');
    Route::get('sales/invoice/{id}', 'SaleController@print_invoice');


    // Route::get('sales_new', 'SaleController@create_new');
    /*************************************
     * RUTAS TRASLADOS
     *************************************/
    Route::resource('transfer_to_storage', 'TransferController');
    Route::post('transfer_to_storage/create', 'TransferController@create');
    // Route::post('transfer_to_storage/create', 'TransferController@create');


    Route::get('cancel_bill', 'SaleController@cancel_bill');
    Route::get('cancel_bill/anular', 'SaleController@anular');
    Route::get('cancel_bill_receivings', 'ReceivingController@cancel_bill');
    Route::get('cancel_bill_receivings/anular', 'ReceivingController@anular');
    Route::get('cancel_bill/{id}/confirm-delete', array('as' => 'admin.sales.confirm-delete', 'uses' => 'SaleController@getModalDelete'));

    Route::get('receivings/complete/{id}', 'ReceivingController@complete')->name('completereceivings');


    Route::get('reports/receivings', 'ReceivingReportController@index');
    Route::post('reports/receivings', 'ReceivingReportController@index');
    Route::get('reports/get-details/{id}', 'ReceivingReportController@get_details');
    Route::get('reports/get-outlays/{id}', 'ReceivingReportController@get_outlays');

    Route::get('reports/sales', 'SaleReportController@index');
    Route::post('reports/sales', 'SaleReportController@index');

    Route::get('reports/stock', 'StockReportController@index');
    Route::post('reports/stock', 'StockReportController@index');
    //Reportes usando Generador de Reportes Jimmy
    // Route::get('reports/sales-det-admin', 'SaleReportController@displayReport');
    Route::post('reports/sales-det-admin', 'SaleReportController@salesDetAdmin');

    Route::post('reports/sales-cust-det', 'SaleReportController@salesCustDet');
    Route::post('reports/sales-cust-inv', 'SaleReportController@salesCustInv');
    Route::post('reports/sales-cust-sum', 'SaleReportController@salesCustSum');

    Route::post('reports/sales-pago-det', 'SaleReportController@salesPagoDet');
    Route::post('reports/sales-pago-sum', 'SaleReportController@salesPagoSum');

    Route::post('reports/sales-salesrep-det', 'SaleReportController@salesSalesRepDet');
    Route::post('reports/sales-salesrep-sum', 'SaleReportController@salesSalesRepSum');

    /** Reportes Ventas  Agrupados (custom) usando DomPDF */
    Route::post('reports/sales-cust-inv', 'SalesGroupedReportsController@salesCustInv');
    Route::post('reports/sales-cust-prod', 'SalesGroupedReportsController@salesCustProd');
    Route::post('reports/sales-item-srep', 'SalesGroupedReportsController@salesItemSrep');
    Route::post('reports/sales-salesrep-item', 'SalesGroupedReportsController@salesSalesRepItem');

    Route::get('reports/get-details-sales/{id}', 'SaleReportController@get_details');

    /** Reportes Stock agrupados */

    Route::post('reports/general_stock_report', 'StockReportController@general_stock_report');
    Route::post('reports/stock_report_by_storage', 'StockReportController@stock_report_by_storage');
    Route::post('reports/stock_report_by_product', 'StockReportController@stock_report_by_product');


    Route::resource('employees', 'EmployeeController');
    Route::get('route_employee/{user_id}', 'EmployeeController@getRouteUser');
    Route::get('employees/{id}/edit-profile', array('as'=>'employees.edit-profile','uses' => 'EmployeeController@editProfile') );

    //Route::resource('api/item', 'ReceivingApiController');
    /* Listado de articulos en la venta */
    Route::get('api/item/{id}', 'ReceivingApiController@sales');
    Route::get('api/sales/search', 'SaleApiController@index');
    Route::get('api/sales/search_code', 'SaleApiController@search_code');
    Route::get('api/sales/search_code_storage_pago', 'SaleApiController@search_code_storage_pago');
    Route::get('api/sales/search_code_storage_price', 'SaleApiController@search_code_storage_price');
    Route::get('api/sales/search_id_storage_price', 'SaleApiController@search_id_storage_price');

    Route::get('api/sales/autocomplete', 'SaleApiController@autocomplete');
    Route::get('api/sales/autocompleteStoragePago', 'SaleApiController@autocompleteStoragePago');
    Route::get('api/sales/autocompleteStoragePrice', 'SaleApiController@autocompleteStoragePrice');


//    LISTADO DE ARTICULOS PARA COMPRA
//    CON AUTOCOMPLETE
    Route::get('api/receivings/autocomplete', 'ReceivingApiController@autoComplete');
    Route::get('api/receivings/search_code', 'ReceivingApiController@searchCode');

    /**
     * RUTAS DE AUTOCOMPLETE EN TRASLADOS DE BODEGA
     */
    Route::get('api/transfers/autocomplete', 'TransferApiController@autoCompleteTransfer');
    Route::get('api/transfers/search_code', 'TransferApiController@searchCodeTransfer');
    Route::get('api/transfers/search_id', 'TransferApiController@searchIdTransfer');


    // Route::get('api/{id}/item', 'ReceivingApiController@ver');
    Route::get('getCorrelativeSale/{id}', 'SaleController@getCorrelativeSale');
    Route::post('existCorrelative/', 'SaleController@existCorrelative');
    Route::get('verifyCorrelative/', 'SaleController@existCorrelative');
    Route::get('existCorrelative/receivings/{serie}', 'ReceivingController@existCorrelative');
    Route::get('verifyCorrelative/receivings/{serie}/{number}', 'ReceivingController@verifyCorrelative');
    Route::get('verifyCorrelative/sales/{serie}/{number}', 'SaleController@verifyCorrelative');
    Route::get('api/1/{id}/item', 'ReceivingApiController@correlativoCompra');
    Route::get('api/item', 'ReceivingApiController@index');

    Route::resource('api/receivingtemp', 'ReceivingTempApiController');
    Route::get('api/receivingtemp/{id}/{price}', 'ReceivingTempApiController@addPror');
    Route::resource('api/saletemp', 'SaleTempApiController');

    Route::resource('api/itemkittemp', 'ItemKitController');
    //Rutas para nuevo Kit
    Route::get('api/item-kit-vue/category', 'ItemKitVueController@getCategory');
    Route::get('api/item-kit-vue/item', 'ItemKitVueController@getItems');
    Route::get('api/item-kit-vue/price', 'ItemKitVueController@getPrices');
    Route::get('api/item-kit-vue/itemPrice', 'ItemKitVueController@getItemPrice');
    //Rutas para editar Kit
    Route::get('api/item-kit-vue/item/edit', 'ItemKitVueController@getItemEdit');
    Route::get('api/item-kit-vue/items/edit', 'ItemKitVueController@getItemsEdit');
    // Ruta para duplicar
    Route::get('item-kits-vue/{id}/duplicate', 'ItemKitVueController@duplicate');

    Route::post('api/item-kit-vue/edit', 'ItemKitVueController@update');
    Route::post('api/item-kit-vue/store', 'ItemKitVueController@store');




    Route::get('api/item-kit-temp', 'ItemKitController@itemKitApi');
    Route::get('api/item-kits', 'ItemKitController@itemKits');
    Route::post('store-item-kits', 'ItemKitController@storeItemKits');

    Route::resource('cacaopos-settings', 'TutaposSettingController');
    /**FORMAS DE PAGO */
    Route::resource('pago', 'PagosController');
    Route::get('api/getPaymentTypeByPrice/{price_id}', 'PagosController@getPaymentTypeByPrice');


    Route::resource('almacen', 'AlmacenController');
    Route::get('almacen/get-details/{id}', 'AlmacenController@get_details');
    /**
     * VERIFICAR CUENTA ASOCIADA A ALMACEN
     */
    Route::get('almacen/account/{almacen_id}', 'AlmacenController@getAccountAlmacen');

    //Comentar esto cuando termine la practica
    Route::resource('almacen/show', 'AlmacenController@show');
    Route::resource('almacen/operar', 'AlmacenController@operar');
    ///
    Route::resource('bodega_usuario', 'AlmacenUserController');

    Route::resource('categorie_product', 'ItemCategoryController');
    Route::resource('bodega_product/{id}/', 'BodegaProductoController@borrarVentaTemporal');

    //Route::resource('bodega_product/{idDocumento}/{id}/{idCliente}/{numCorrelative}/{tipoPago}', 'BodegaProductoController@borrarVentaTemporal');
    //Route::resfource('estado', 'StateCellarController');SaleController@index

    Route::get('pruebas', 'ReceivingController@prueba');

    // Rutas para Ajuste de Inventario
    Route::get('api/inventory_adjustment/selectBodega','InventoryAdjustmentController@selectBodega');
    Route::get('api/inventory_adjustment/selectSerie','InventoryAdjustmentController@selectSerie');
    Route::get('api/inventory_adjustment/searchItems','InventoryAdjustmentController@searchItems');
    Route::get('api/inventory_adjustment/correlative','InventoryAdjustmentController@selectCorrelative');

    Route::get('inventory_adjustment/index/input','InventoryAdjustmentController@indexInput');
    Route::get('inventory_adjustment/index/output','InventoryAdjustmentController@indexOutput');
    Route::post('inventory_adjustment/index/input','InventoryAdjustmentController@indexInput');
    Route::post('inventory_adjustment/index/output','InventoryAdjustmentController@indexOutput');

    Route::get('inventory_adjustment/detail/input/{id}','InventoryAdjustmentController@detailInput');
    Route::get('inventory_adjustment/detail/output/{id}','InventoryAdjustmentController@detailInput');

    Route::get('api/inventory_adjustment/existence','InventoryAdjustmentController@getExistence');

    // Ajustes de inventario
    Route::get('inventory_adjustment/input', 'InventoryAdjustmentController@input');
    Route::get('inventory_adjustment/output', 'InventoryAdjustmentController@output');

    Route::post('inventory_adjustment/save','InventoryAdjustmentController@store');

    //obtener bodegas de los usuarios
    Route::get('getStorages', 'AlmacenController@getStorages');
    Route::get('detailsSale/{id}', 'InventoryAdjustmentController@detailsSale');

    Route::get('detailsAdd/{id}', 'InventoryAdjustmentController@detailsAdd');
    Route::get('listAdjustmentAdd', 'InventoryAdjustmentController@listAdjustmentAdd');
    Route::post('listAdjustmentAdd', 'InventoryAdjustmentController@listAdjustmentAdd');
    Route::get('listAdjustmentSale', 'InventoryAdjustmentController@listAdjustmentSale');
    Route::post('listAdjustmentSale', 'InventoryAdjustmentController@listAdjustmentSale');
    Route::get('printPDFSale/{id}', 'InventoryAdjustmentController@printPDFSale');
    Route::get('printPDFAdd/{id}', 'InventoryAdjustmentController@printPDFAdd');
    Route::get('getSeriesAdd/{id}', 'InventoryAdjustmentController@getSeriesAdd');
    Route::get('getReportAdd', 'InventoryAdjustmentController@getReportAdd');
    Route::get('getReportSale', 'InventoryAdjustmentController@getReportSale');
    Route::get('addHeaderAndDetails/{id}', 'InventoryAdjustmentController@addHeaderAndDetails');
    Route::get('saleHeaderAndDetails/{id}', 'InventoryAdjustmentController@saleHeaderAndDetails');
    Route::post('newSaved', 'ReceivingController@newSaved');

    /* SISTEMA DE CREDITOS */
    Route::post('customers/update', 'CustomerController@update')->name('customers.update');

    Route::resource('calendar', 'CalendarController');
    /*Test*/
    // Route::get('/invoice', function() {
    //     return view('partials.inventory_document');
    // });
    // Route::get('routes_balance/valid_Card/{cardNumber}/{route}', 'Pagarecontroller@validCard');
    // Route::get('valid_Card/{cardNumber}', 'Pagarecontroller@valRepeatCard');
    // Route::get('invoice/{id}', 'SaleController@complete_invoice')->name('completesale');
    // Route::get('/invoice2', function () {
    //     return view('partials.inventory_document2');
    // });
    // Route::get('receivings/complete2/{id}','ReceivingController@complete_invoice')->name('completereceivings2');
    /*RUTA PARA NUEVO FORMULARIO PAGOS COBRADOR*/
    // Route::get('/payment-acum/{id}', 'Pagarecontroller@acumulado')->name('payment.acum');
    // Route::post('pagares/payment', 'Pagarecontroller@store_payment');
    /*FACTURAR CREDITO*/
    // Route::get('/invoiceCredit/pdf/{id}', 'InvoiceCreditController@index')->name('invoice.print');
    // Route::get('/invoice/create/{id}', 'InvoiceCreditController@create')->name('invoice.create');
    // Route::get('/invoice/reprint/{id}', 'InvoiceCreditController@reprint')->name('invoice.reprint');
    // Route::get('/invoice/reprintInvoice/{id}', 'InvoiceCreditController@reprintInvoice')->name('invoice.reprintNew');
    // Route::post('/invoice/verify', 'InvoiceCreditController@verify');
    // Route::post('/invoice/save', 'InvoiceCreditController@save');
    /*PARAMETROS GENERALES*/
    Route::get('parameters', 'ParameterController@index');
    Route::post('parameters/storage', 'ParameterController@store')->name('parameter.save');
    Route::post('parameters/create', 'ParameterController@create')->name('parameter.create');
    Route::get('logs', 'ParameterController@log');

    Route::resource('general-parameters', 'GeneralParameterController');
    /*PORCENTAJE MÁXIMO DE FACTURACIÓN*/
    // Route::get('percent', 'PercentController@index');
    // Route::post('percent/store', 'PercentController@store');
    //TIPO DE FACTURACION
    // Route::post('invoice_type/store', 'InvoiceTypeController@store');
    // Route::get('invoice_type', 'InvoiceTypeController@index');
    // Route::get('invoice_type/edit', 'InvoiceTypeController@edit');
    // Route::get('invoice/correlative/{serie_id}', 'InvoiceCreditController@correlativo');
    // Route::get('credit-report', 'CreditReportController@index');
    // Route::post('credit-report', 'CreditReportController@index');
    // Route::get('credit-detail/{id}', 'CreditReportController@get_details');
    Route::get('/invoice/reprintInv/{id}', 'InvoiceCreditController@reprintInv')->name('invoice.reprintInv');
    Route::post('serie/verify', 'SerieController@verify');
    Route::post('serie/verify_number', 'SerieController@verify_number');
    Route::get('serie/verify/{serie}/{no}', 'SerieController@verify_serie_number');

    // CRUD RUTAS (DOCHOA)
    Route::post('routes/index_ajax', 'RouteController@index_ajax');
    Route::delete('routes/delete/{id}', 'RouteController@destroy');
    Route::resource('routes', 'RouteController');
    Route::post('target/{id}', 'RouteController@target');
    Route::get('routes/create', 'RouteController@create');
    Route::post('route/store', 'RouteController@store');
    /**
     * RUTA DE LIQUIDACIÓN DE RUTA
     */

    Route::get('routes/settlement/{route_id}/create', 'SettlementRouteController@settRoute');
    Route::get('routes/settlement/{route_id}', 'SettlementRouteController@index');
    Route::get('routes/settlement/show/{id}', 'SettlementRouteController@show');
    Route::post('routes/settlement', 'SettlementRouteController@store');
    Route::delete('routes/settlement/destroy/{id}', 'SettlementRouteController@destroy');

    // Route::get('routes/edit/{id}', 'RouteController@edit');
    // Route::post('route/update/{id}', 'RouteController@update');
    // Route::post('send_balance', 'Pagarecontroller@routeBalance');
    // Route::get('routes/lastCash/{route}', 'Pagarecontroller@cashLastRoute');
    // Route::get('route-balances/{route}', 'Pagarecontroller@balance_route');
    // Route::get('route-balances-show/{route}', 'Pagarecontroller@balance_show');
    // Route::get('pagares_pending_balance/{route}/{day}/{month}/{year}', 'Pagarecontroller@amnt_pending_balance');
    // Route::get('verify_balance/{route}/{day}/{month}/{year}', 'Pagarecontroller@verify_balance');
    // Route::get('target/{route}/{month}/{year}', 'RouteController@routeStatus');
    // Route::get('routes_balance/payments/{route}/{month}/{year}', 'Pagarecontroller@paymentRoutesAcum');
    // Route::get('make_balance/{route}', 'Pagarecontroller@createBalance');
    // Route::get('detail_balance/{bal}', 'Pagarecontroller@detail_balance');
    // Route::get('routes_balance', 'Pagarecontroller@route_balances');
    // Route::get('edit_payment/{id}', 'Pagarecontroller@editPayment');
    // Route::post('store_payment_edit', 'Pagarecontroller@store_payment_edit');

    //REPORTES POR RUTA
    //*INDICE DE LIQUIDEZ
    // Route::get('liquidityIndex', 'GraphicsController@liquidityIndex');
    //REPORTE DE MOROSOS
    // Route::get('defailter', 'GraphicsController@defailter');
    //GALERÍA DE IMAGENES
    Route::get('images/create', 'ImageController@create');
    Route::post('images/store', 'ImageController@store')->name('images');
    Route::delete('images/delete/{id}', 'ImageController@destroy');
    Route::get('images/index/{id}', 'ImageController@indexCustomer');
    Route::get('images/create/{customer}', 'ImageController@createCustomer');
    Route::get('images/destroy/{id}/{customer}', 'ImageController@destroy');

    //CLASIFICACIÓN DE CLIENTES
    // Route::get('class', 'ClassCustomerController@index');
    // Route::get('class/create', 'ClassCustomerController@create');
    // Route::post('class/store', 'ClassCustomerController@store');
    // Route::get('class/edit/{id}', 'ClassCustomerController@edit');
    // Route::post('class/update/{id}', 'ClassCustomerController@update');
    // Route::get('class/delete/{id}', 'ClassCustomerController@destroy');
    // Route::get('verifyClass/{arrears}', 'ClassCustomerController@verify');
    //Perdonar mora
    // Route::get('forgive/{id}', 'Pagarecontroller@perdonarMora');

    //TIPOS DE MONEDA
    Route::get('typeMoney', 'TypeMoneyController@index');
    Route::get('typeMoney/create', 'TypeMoneyController@create');
    Route::post('typeMoney/store', 'TypeMoneyController@store');
    Route::get('typeMoney/edit/{id}', 'TypeMoneyController@edit');
    Route::post('typeMoney/update/{id}', 'TypeMoneyController@update');
    Route::get('typeMoney/delete/{id}', 'TypeMoneyController@destroy');

    //Autorización de créditos
    // Route::get('authorize/{id}', 'Pagarecontroller@authorize');

    //BANCOS
    Route::group(['prefix' => 'banks'], function () {
        Route::resource('accounts', 'AccountsController');
        Route::get('deposits', 'RevenuesController@deposits');
        Route::get('deposits/create', 'RevenuesController@create');
        Route::post('/deposit/addnumber', 'RevenuesController@addDeposit');
        Route::get('/deposit/verify', 'RevenuesController@verifyDeposit');
        Route::resource('cash_register', 'CashRegisterController');
        Route::get('accounts/statement/{account}', array('as' => 'banks.accounts.statement', 'uses' => 'AccountsController@statement'));
        Route::get('cash_register/statement/{account}', array('as' => 'banks.cash_register.statement', 'uses' => 'CashRegisterController@statement'));
        // Route::resource('payments', 'PaymentsController');
        Route::resource('expenses', 'ExpensesController');
        Route::get('expenses_accounts/{id}', 'ExpensesController@expense_account');
        Route::resource('expenses_accounts', 'ExpensesController');
        Route::resource('expense_categories', 'ExpenseCategoryController');
        Route::resource('revenues', 'RevenuesController');
        Route::get('deposit/create', 'RevenuesController@create');
        Route::get('revenues/print_voucher/{id}/{_pj}/', 'RevenuesController@print');
        Route::get('get-payment-type-in/{account_id}', 'AccountsController@getPaymentTypeIn');
        Route::get('get-payment-type-out/{account_id}', 'AccountsController@getPaymentTypeOut');
        Route::get('get-account-type/{pago_id}/{ingreso}', 'AccountsController@getAccountType');
        Route::get('get-account-type/{pago_id}/deposit', 'AccountsController@getAccountTypeDeposit');
        Route::get('get-account-type-money/{pago_id}', 'AccountsController@getAccountTypeMoney');
        Route::get('get-account-for-transfer/{account_id}', 'AccountsController@getAccountForTransfer');
        Route::get('get-account-project/{id}', 'AccountsController@getAccountProject');
        Route::resource('tx-types', 'TransactionsCataloguesController');
        Route::resource('transfers', 'TransfersController');
        Route::resource('retention', 'RetentionController');
    });

//    CONCILIACIÓN BANCARIA
    Route::group(['prefix' => 'bank_reconciliation'], function () {
        Route::resource('header', 'BankReconciliationController');
        Route::get('account/{id}', 'BankReconciliationController@account');
        Route::get('account/transactions/{id}', 'BankReconciliationController@accountTransactions');
        Route::post('save_documents', 'BankReconciliationController@save_documents');
    });

    //
    #region GESTIÓN DE COTIZACIONES
    Route::group(['prefix' => 'quotation'], function () {
        Route::resource('header', 'QuotationController');
        Route::get('header/duplicate/{id}/{dup}', 'QuotationController@edit');
        Route::get('header/getdetails/{id}', 'QuotationController@getdetails');
        Route::get('load_sale/{id}/{cellar}', 'QuotationController@load_sale');
        Route::get('correlative/{serie}', 'QuotationController@correlative');
        Route::get('autocomplete', 'QuotationController@autoComplete');
        Route::get('search_code', 'QuotationController@searchCode');
        Route::get('search_id', 'QuotationController@searchId');
        Route::post('update_details', 'QuotationController@updateDetails');
    });
    #endregion

    #region GESTIÓN DE CIERRES DE INVENTARIO
    Route::resource('inventory_closing', 'InventoryClosingController');
    #endregion

    //graficas
    Route::group(['prefix' => 'charts'], function () {
        Route::get('cobrochartajax', 'GraphicsController@cobrochartajax');
        Route::get('renovacionchart', 'GraphicsController@renovationReal');
        Route::get('earnings', 'GraphicsController@earnings');
        Route::get('cobrochart', array('as' => 'charts.cobrochart', 'uses' => 'GraphicsController@cobrochart'));
        Route::get('morachart', array('as' => 'charts.morachart', 'uses' => 'GraphicsController@morachart'));
    });

    //Project
    Route::group(['prefix'=>'project'], function(){
        Route::resource('projects', 'ProjectController');
        Route::resource('stages', 'StageController');
        Route::post('repeat/{id}', 'ProjectController@repeat');
        Route::get('projects/logs/{project_id}', 'ProjectController@logs');
        Route::get('stages_project/{id}','ProjectController@stages');
        Route::resource('atributes', 'AtributeController');
        Route::post('valueStages', 'AtributeController@saveValueStage');
        // INGRESOS DE PROYECTO
        Route::get('revenues/{id}', 'ProjectController@getRevenues');
        Route::get('revenues/create/{id}', 'ProjectController@createRevenues');
        Route::post('revenues/store', 'ProjectController@storeRevenue');

        // GASTOS DE PROYECTO
        Route::get('expenses/{id}', 'ProjectController@getExpenses');
        Route::get('expenses/create/{id}', 'ProjectController@createExpenses');
        Route::post('expenses/store', 'ProjectController@storeExpense');

        #region LISTADO DE RETENCIONES
        Route::get('retentions/{project_id}', 'ProjectController@getRetentions');
        #endregion

        #region ACTUALIZAR ESTADOS DE ETAPAS
        Route::post('stages/update', 'StageController@update_status_stage');
        #endregion

        Route::resource('{project_id}/budget/', 'BudgetController');
        Route::get('budget/clone/{id}', 'BudgetController@clone');
        Route::post('budget/clone/{id}', 'BudgetController@storeClon');
        Route::get('{project_id}/budget/{id}/show', 'BudgetController@show');
        Route::get('{project_id}/budget/{id}/edit', 'BudgetController@edit');
        Route::put('{project_id}/budget/{id}', 'BudgetController@update');
        Route::delete('{project_id}/budget/{id}', 'BudgetController@destroy');
    });
    // RUTAS PARA EL CIERRE DE CAJA
    Route::group(['prefix'=>'desk_closing'],function(){
        Route::resource('/','DeskClosingController');
        Route::get('api/billetes','DeskClosingController@getBillete');
        Route::get('api/sales','DeskClosingController@getSales');
        Route::get('create/{id}','DeskClosingController@create');
        Route::get('api/desk/{id}','DeskClosingController@getCaja');
        Route::get('api/documents','DeskClosingController@getDocuments');
        Route::get('api/selectCorrelative/{id}','DeskClosingController@selectCorrelative');
        Route::get('api/getAccounts','DeskClosingController@getAccounts');
        // INDEX CAJAS
        Route::get('index/{id}','DeskClosingController@index');
        Route::post('index/{id}','DeskClosingController@index');
        Route::get('show/{id}','DeskClosingController@show');
    });
    //    GESTION DE IMPUESTOS DE GASTOS
    Route::get('taxes_category/{id}', 'ExpenseCategoryController@taxes_category');

    /*REGISTRO ITEMS PARA PRESUPUESTO*/
    Route::resource('line-template', 'LineTemplateController');

//    GESTION DE IMPUESTOS DE GASTOS
    Route::get('taxes_category/{id}', 'ExpenseCategoryController@taxes_category');

    #retion RUTAs PARA GESTIÓN DE PRESUPUESTOS
    //Route::resource('budget', 'BudgetController');
    Route::post('update_budget_cost', 'BudgetController@updateBudgetCost');
    #endregion

    #region CONFIGURACION DE PRESUPUESTOS Y RENGLONES
    Route::resource('budget_config', 'BudgetConfigController');
    #endregion

    /**
     * RUTA PARA CREACIÓN DE NOTAS DE CRÉDITO
     */
    Route::resource('credit_note', 'CreditNoteController');
    Route::get('credit_note/create/{sale_id}', 'CreditNoteController@create');
    /**
     * **************************************
     */

    #retion RUTAs PARA GESTIÓN DE PRESUPUESTOS
//    Route::resource('budget', 'BudgetController');
    #endregion

    #region RUTA PARA GESTIONAR UNIDADES DE MEDIDA
    Route::resource('unit_measure', 'UnitMeasureController');
    Route::get('verify_abbreviation', 'UnitMeasureController@verify_abbreviation');
    Route::post('name_unit_price', 'UnitMeasureController@getName');
    Route::post('item_prices', 'ItemController@getPricesUnits');
    #endregion



    Route::get('responsible/{account}', 'AccountsController@getResponsible');
    // Route::get('toRefuse/{id}', 'Pagarecontroller@toRefuse');
    //RENOVAR CRÉDITO
    // Route::get('renovation/{id}', 'Pagarecontroller@renovateCredit');
//    ------------------------------------------------------------------------



});
/*
Route::group(['middleware' => 'role'], function()
{
Route::get('items', function()
{
return 'Is admin';
});
});

Route::get('sales', [
'middleware' => 'role',
'uses' => 'SaleController@index'
]);
 */
