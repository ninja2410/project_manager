<ul id="menu" class="page-sidebar-menu">
    <li>
        <a href="/">
            <i class="livicon" data-name="home" data-size="18" data-c="#418BCA" data-hc="#418BCA" data-loop="true"></i>
            <span class="title">Dashboard</span>
        </a>
    </li>
    @if (Auth::check())
    <!-- Obtenemos los permisos de cada uno de los usuarios -->
    <?php $permisos=Session::get('permisions');
    /*$permisos=Session::get('permisions');*/ $array_p =array_column(json_decode(json_encode($permisos), True),'ruta');  ?>
    {{-- *********************************** SALES MENU **************************************************  --}}
        @if(in_array('sales-menu',$array_p))
    <li {!! (Request::is( 'sales') || Request::is( 'sales/*') || Request::is( 'quotation/header/*') || Request::is( 'quotation/load_sale/*') || Request::is( 'quotation/header')  || Request::is( 'credit_note/*') || Request::is( 'credit_note')
            || Request::is( 'routes/*') || Request::is( 'routes') || Request::is( 'customers') || Request::is( 'customers/*') || Request::is( 'credit') || Request::is( 'credit/*')  ?
        'class="active"' : '') !!}>
        <a href="#">
            <i class="livicon" data-name="shopping-cart" data-size="18" data-c="#00bc8c" data-hc="#00bc8c" data-loop="true"></i>
            <span class="title">{{trans('menu.sales')}}</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="sub-menu">
            @if (in_array('quotation/header',$array_p))
            <li {!! (Request::is( 'quotation/header/*') || Request::is( 'quotation/header') || Request::is( 'quotation/load_sale/*') ? 'class="active"' :
                '') !!}>
                <a href="{{ url('/quotation/header') }}">
                    <i class="fa fa-file-text-o"></i> {{trans('menu.quotation')}}
                </a>
            </li>
            @endif

            @if (in_array('sales?id=0',$array_p))
                <li {!! (Request::is( 'sales') || Request::is( 'sales/complete/*') || Request::is( 'credit/completeCredit') ? 'class="active"' :
            '') !!}>
                    <a href="{{ url('/sales') }}">
                        <i class="fa fa-inbox"></i> {{trans('menu.sales')}}
                    </a>
                </li>
            @endif

            @if (in_array('sales?id=0',$array_p))
            <li {!! (Request::is( 'sales/create') || Request::is( 'sales/create/*') || Request::is( 'credit/completeCredit') ? 'class="active"' :'') !!}>
                <a href="{{ url('/sales/create') }}">
                    <i class="fa fa-shopping-cart"></i> {{trans('menu.sales_new')}}
                </a>
            </li>
            @endif
            @if (in_array('customers',$array_p))
            <li {!! (Request::is( 'customers') || Request::is( 'customers/*')  || Request::is( 'customer/*') ? 'class="active"' : '') !!}>
                <a href="{{ url('/customers') }}">
                    <i class="fa fa-users"></i> {{trans('menu.customers')}}
                </a>
            </li>
            @endif
            @if(in_array('routes',$array_p))
            <li {!! ( Request::is( 'routes') || Request::is( 'routes/create') || Request::is( 'routes/*') || Request::is( 'routes/*')
                ? 'class="active"' : '') !!}>
                <a href="{{ url('routes') }}">
                    <i class="fa fa-random"></i>Rutas</a></li>
            @endif
            @if(in_array('credit_note',$array_p))
                <li {!! (Request::is( 'credit_note') || Request::is( 'credit_note/*') ? 'class="active"' : '') !!}>
                    <a href="{{ url('credit_note') }}">
                        <i class="fa fa-files-o"></i>{{trans('menu.credit_note')}}</a></li>
            @endif
            @if(in_array('credit',$array_p))
            <li {!! (Request::is( 'credit') || Request::is( 'credit/*') ? 'class="active"' : '') !!}>
                <a href="{{ url('credit') }}">
                    <i class="fa fa-calendar"></i>{{trans('menu.cxc')}}</a></li>
            @endif
        </ul>
    </li>
    @endif @if(in_array('receivings-menu',$array_p))
    <li {!! (Request::is( 'receivings') || Request::is( 'credit_suppliers') || Request::is( 'credit_suppliers/*') || Request::is( 'cancel_bill_receivings') || (Request::is( 'cancel_bill_receivings/*') && !Request::is('cancel_bill_receivings/report_cancel_bill_receivings'))
            || Request::is( 'receivings/*') || Request::is( 'suppliers') || Request::is(
        'suppliers/*') ? 'class="active"' : '') !!}>
        <a href="#">
            <i class="livicon" data-name="truck" data-c="#5bc0de" data-hc="#5bc0de" data-size="18" data-loop="true"></i>
            <span class="title">{{trans('menu.receivings')}} </span>
            <span class="fa arrow"></span>
        </a>
        <ul class="sub-menu">
            @if(in_array('receivings',$array_p))
            <li {!! (Request::is( 'receivings') ? 'class="active"' : '') !!}>
                <a href="{{ url('/receivings') }}">
                    <i class="fa fa-truck"></i>{{trans('menu.receivings')}}</a>
            </li>
            @endif
            @if(in_array('receivings',$array_p))
            <li {!! (Request::is( 'receivings/create') ? 'class="active"' : '') !!}>
                <a href="{{ url('/receivings/create') }}">
                    <i class="fa fa-credit-card"></i>{{trans('menu.receivings_new')}}</a>
            </li>
            @endif
            @if(in_array('suppliers',$array_p))
            <li {!! (Request::is( 'suppliers') || Request::is( 'suppliers/*') ? 'class="active"' : '') !!}>
                <a href="{{ url('/suppliers') }}">
                    <i class="fa fa-male"></i>{{trans('menu.suppliers')}}</a>
            </li>
            @endif
            @if(in_array('credit_suppliers',$array_p))
            <li {!! (Request::is( 'credit_suppliers') || Request::is( 'credit_suppliers/*') ? 'class="active"' : '') !!}>
                <a href="{{ url('credit_suppliers') }}">
                    <i class="fa fa-clock-o"></i>{{trans('menu.cxp')}}</a></li>
            @endif
        </ul>
    </li>
    @endif
    {{-- *********************************** PROJECTS MENU **************************************************  --}}
    @if(in_array('project/projects',$array_p))
    <li {!! (Request::is( 'project/*') || Request::is( 'line-template/*') || Request::is( 'line-template')  ? 'class="active"' : '') !!}>
        <a href="#">
            <i class="livicon" data-name="brush" data-c="#f5af15" data-hc="#f5af15" data-size="18" data-loop="true"></i>
            <span class="title">{{trans('menu.projects')}} </span>
            <span class="fa arrow"></span>
        </a>
        <ul class="sub-menu">
            @if(in_array('project/projects',$array_p))
            <li {!! (Request::is( 'project/projects') || Request::is( 'project/*/budget/*') || Request::is( 'project/*/budget') || Request::is( 'project/retentions/*') || Request::is( 'project/expenses/*') || Request::is( 'project/revenues/*') || Request::is( 'project/projects/*') || Request::is( 'project/stages_project*') ? 'class="active"' : '') !!}>
                <a href="{{ url('project/projects') }}">
                    <i class="fa fa-table"></i>{{trans('menu.projects')}}</a>
            </li>
            @endif
            @if(in_array('project/stages',$array_p))
            <li {!! (Request::is( 'project/stages') || Request::is( 'project/stages/*') ? 'class="active"' : '') !!}>
                <a href="{{ url('project/stages') }}">
                    <i class="fa fa-list-ol"></i>{{trans('menu.stages')}}</a>
            </li>
            @endif
            @if(in_array('project/atributes',$array_p))
            <li {!! (Request::is( 'project/atributes') || Request::is( 'project/atributes/*') ? 'class="active"' : '') !!}>
                <a href="{{ url('project/atributes') }}">
                    <i class="fa fa-tags"></i>{{trans('menu.atributes')}}</a>
            </li>
            @endif
            @if(in_array('line-template',$array_p))
            <li {!! (Request::is( 'line-template') || Request::is( 'line-template/*') ? 'class="active"' : '') !!}>
                <a href="{{ url('line-template') }}">
                    <i class="fa fa-tasks"></i>{{trans('menu.line-template')}}</a>
            </li>
            @endif
        </ul>
    </li>
    @endif
    {{-- *********************************** ITEMS MENU **************************************************  --}}
    @if(in_array('ítems-menu',$array_p))
    <li {!! (Request::is( 'items')|| Request::is( 'transfer_to_storage') || Request::is( 'items/*') || Request::is( 'services') || Request::is( 'services/create') || Request::is('item-kits-vue') || Request::is('item-kits-vue/*') || Request::is( 'items/*') || Request::is('categorie_product') || Request::is( 'categorie_product/*') ||
        Request::is( 'almacen') || Request::is( 'almacen/create') || Request::is( 'almacen/*') || Request::is( 'transfer_to_storage') ||  Request::is( 'transfer_to_storage/*')  || Request::is( 'inventory_adjustment') || Request::is( 'inventory_adjustment/*') || Request::is('unit_measure') || Request::is( 'unit_measure/*') ||
        Request::is( 'inventory_closing/*') || Request::is( 'inventory_closing') || Request::is( 'inventory_adjustment/sale') || Request::is( 'inventory/*') || Request::is('prices') || Request::is('prices/*') ? 'class="active"' : '') !!}>
        <a href="#">
            <i class="livicon" data-name="list-ul" data-c="#F89A14" data-hc="#F89A14" data-size="18" data-loop="true"></i>
            <span class="title">{{trans('menu.inventory')}}</span>
            <span class="fa arrow"></span>
        </a>
            @if(in_array('products-services',$array_p))
            {{-- PRODUCTOS Y SERVICIOS --}}
            <ul class="sub-menu">
                <li {!! (Request::is( 'items') || Request::is( 'items/*') || Request::is( 'services') || Request::is( 'services/*') || Request::is('item-kits-vue') || Request::is('inventory/*') || Request::is('item-kits-vue/*') || Request::is('unit_measure') || Request::is( 'unit_measure/*') || Request::is('categorie_product')  ? 'class="active"' : '') !!}>
                    <a href="#">
                    <i class="fa fa-archive"></i>{{trans('menu.items_and_services')}}</a>
                    <ul class="sub-menu">
                        @if(in_array('ítems',$array_p))
                        <li {!! (Request::is( 'items') || Request::is( 'items/create') || Request::is( 'items/*/edit') || Request::is( 'items/filter/*')  ? 'class="active"' : '') !!}>
                            <a href="{{ url('/items') }}">
                                <i class="fa fa-barcode"></i> {{trans('menu.item_catalog')}} </a>
                        </li>
                        @endif
                        @if(in_array('services',$array_p))
                        <li {!! (Request::is( 'services') || Request::is( 'services/create') || Request::is( 'services/create') || Request::is( 'services/*/edit') || Request::is( 'items/filter/*')  ? 'class="active"' : '') !!}>
                            <a href="{{ url('/services') }}">
                                <i class="fa fa-gears (alias)"></i> {{trans('menu.services_catalog')}} </a>
                        </li>
                        @endif
                        @if(in_array('items/search',$array_p))
                        <li {!! (Request::is( 'items/search') || Request::is( 'items/search/*')  ? 'class="active"' : '') !!}>
                            <a href="{{ url('/items/search') }}">
                                <i class="fa fa-search"></i> {{trans('menu.item_search')}} </a>
                        </li>
                        @endif
                        @if(in_array('items/show',$array_p))
                        <li {!! (Request::is( 'items/show') || Request::is( 'items/show/*') || Request::is( 'inventory/*') ? 'class="active"' : '') !!}>
                            <a href="{{ url('/items/show') }}">
                                <i class="fa fa-building-o"></i> {{trans('menu.item_inventory')}} </a>
                        </li>
                        @endif
                        {{-- @if(in_array('item-kits',$array_p))
                        <li {!! (Request::is( 'item-kits') || Request::is( 'item-kits/create') ? 'class="active"' : '') !!}>
                            <a href="{{ url('/item-kits') }}"> <i class="fa fa-angle-double-right"></i> {{trans('menu.item_kits')}} </a>
                        </li>
                        @endif --}}
                        @if(in_array('item-kits',$array_p))
                        <li {!! (Request::is( 'item-kits-vue') || Request::is( 'item-kits-vue/create') ||Request::is( 'item-kits-vue/*')  ? 'class="active"' : '') !!}>
                            <a href="{{ url('/item-kits-vue') }}">
                                <i class="fa fa-sitemap"></i> {{trans('menu.item_kits')}} </a>
                        </li>
                        @endif
                        @if(in_array('categorie_product',$array_p))
                        <li {!! (Request::is( 'categorie_product') || Request::is( 'categorie_product/create') || Request::is(
                            'categorie_product/*') ? 'class="active"' : '') !!}>
                            <a href="{{ url('/categorie_product') }}">
                                <i class="fa fa-tags"></i> {{trans('menu.item_categories')}}</a>
                        </li>
                        @endif
                        @if(in_array('unit_measure',$array_p))
                        <li {!! (Request::is( 'unit_measure') ||  Request::is('unit_measure/*') ? 'class="active"' : '') !!}>
                            <a href="{{ url('/unit_measure') }}">
                                <i class="fa fa-glass"></i> {{trans('unit_measure.unit_measure')}}</a>
                        </li>

                        @endif
                    </ul>
                </li>
            </ul>
            @endif
            @if(in_array('settings-movements',$array_p))
            {{-- HERRAMIENTAS Y TRANSACCIONES --}}
            <ul class="sub-menu">
                <li {!! (Request::is( 'prices') || Request::is( 'prices/*') || Request::is( 'inventory_closing') || Request::is( 'almacen') || Request::is( 'items/product_and_cellars') || Request::is( 'transfer_to_storage') || Request::is( 'transfer_to_storage/*') || Request::is( 'inventory_adjustment') || Request::is( 'inventory_adjustment/*') ? 'class="active"' : '') !!}>
                    <a href="#"><i class="fa fa-check-square-o"></i>{{trans('menu.item_tx_and_tools')}}</a>
                    <ul class="sub-menu">
                        {{-- LISTADOS DE PRECIOS --}}
                        @if(in_array('prices',$array_p))
                        <li {!! (Request::is( 'prices') || Request::is( 'prices/*') ? 'class="active"' : '') !!}>
                            <a href="{{ url('/prices') }}">
                                <i class="fa fa-usd"></i> {{trans('menu.prices_types')}} </a>
                        </li>
                        @endif
                        {{-- CIERRES DE INVENTARIO --}}
                        @if(in_array('inventory_closing',$array_p))
                            <li {!! (Request::is( 'inventory_closing') || Request::is( 'inventory_closing/*') ? 'class="active"' : '') !!}>
                                <a href="{{ url('/inventory_closing') }}">
                                    <i class="fa fa-times-circle"></i> {{trans('menu.inventory_closing')}} </a>
                            </li>
                        @endif
                        @if(in_array('almacen',$array_p))
                        <li {!! (Request::is( 'almacen') || Request::is( 'almacen/create') || Request::is( 'almacen/*') || Request::is(
                            'bodega_usuario/*') ? 'class="active"' : '') !!}>
                            <a href="{{ url('/almacen') }}">
                                <i class="fa fa-sitemap"></i>Bodegas</a></li>
                        @endif
                        @if(in_array('almacen',$array_p))
                        <li {!! (Request::is( 'items/product_and_cellars') ? 'class="active"' : '') !!}>
                            <a href="{{ url('/items/product_and_cellars') }}">
                            <i class="fa fa-edit (alias)"></i>{{trans('menu.product_and_cellars')}}</a>
                        </li>
                        @endif
                        @if(in_array('transfer_to_storage',$array_p))
                        <li {!! (Request::is( 'transfer_to_storage/*') || Request::is( 'transfer_to_storage') ? 'class="active"' : '') !!}>
                            <a href="{{ url('/transfer_to_storage') }}">
                                <i class="fa fa-exchange"></i>{{trans('menu.transfer_to_storage')}}
                            </a>
                        </li>
                        @endif
                        @if(in_array('inventory_adjustment',$array_p))
                        <li {!! (Request::is( 'inventory_adjustment/index/input')||Request::is( 'inventory_adjustment/input')||Request::is( 'inventory_adjustment/detail/input/*') ? 'class="active"' : '') !!}>
                            <a href="{{ url('/inventory_adjustment/index/input') }}">
                                <i class="fa fa-sign-in"></i>{{trans('Ingreso de inventario (Ajuste)')}}</a>
                        </li>
                        @endif
                        {{-- @if(in_array('inventory_adjustment',$array_p))
                        <li {!! (Request::is( 'inventory_adjustment') ? 'class="active"' : '') !!}>
                            <a href="{{ url('/inventory_adjustment') }}"><i class="fa fa-angle-double-right"></i>{{trans('Ingreso de inventario (Ajuste)')}}</a>
                        </li>
                        @endif  --}}
                        @if(in_array('inventory_adjustment/sale',$array_p))
                        <li {!! (Request::is( 'inventory_adjustment/index/output')||Request::is( 'inventory_adjustment/output')||Request::is( 'inventory_adjustment/detail/output/*') ? 'class="active"' : '') !!}>
                            <a href="{{ url('/inventory_adjustment/index/output') }}">
                                <i class="fa fa-sign-out"></i>{{trans('Salida de inventario (Ajuste)')}}</a>
                        </li>
                        @endif
                        {{-- @if(in_array('inventory_adjustment/sale',$array_p))
                        <li {!! (Request::is( 'inventory_adjustment/sale') ? 'class="active"' : '') !!}>
                            <a href="{{ url('/inventory_adjustment/sale') }}"><i class="fa fa-angle-double-right"></i>{{trans('Salida de inventario (Ajuste)')}}</a>
                        </li>
                        @endif --}}
                    </ul>
                </li>
            </ul>
            @endif
    </li>
    @endif
    {{-- *********************************** REPORTS MENU **************************************************  --}}
    @if(in_array('reports-menu',$array_p))
    <li {!! (Request::is( 'reports') || Request::is( 'reports/inventory_week') || Request::is( 'reports/inventory_week/*') || Request::is( 'reports/profit_by_product') || Request::is( 'reports/receivings') || Request::is( 'reports/receivings/*') || Request::is( 'reports/sales') || Request::is( 'reports/route/profit') || Request::is( 'reports/route/sales') || Request::is( 'reports/reporte_venta') || Request::is( 'listAdjustmentSale') || Request::is(
        'listAdjustmentAdd') || Request::is( 'credit-report') || Request::is( 'defailter') || Request::is( 'defailter/*') || Request::is( 'detailsAdd/*') || Request::is( 'detailsSale/*') || Request::is( 'reports/items_quantity_sales') || Request::is( 'reports/reporte_compra') || Request::is( 'reports/product_and_cellars_notPrice')
        || Request::is( 'reports/expired_report') || Request::is( 'reports/stock') ? 'class="active"' : '') !!}>
        <a href="#">
            <i class="livicon" data-name="barchart" data-size="18" data-c="#00bc8c" data-hc="#00bc8c" data-loop="true"></i>
            <span class="title">{{trans('menu.reports')}}</span>
            <span class="fa arrow"></span>
        </a>
            @if(in_array('reports-sales-section',$array_p))
            <ul class="sub-menu">
                <li {!! (Request::is( 'reports/sales') || Request::is( 'reports/inventory_week') || Request::is( 'reports/inventory_week/*') || Request::is( 'reports/profit_by_product')  || Request::is( 'reports/reporte_venta')|| Request::is( 'reports/route/profit')  || Request::is( 'reports/route/sales') || Request::is( 'reports/items_quantity_sales') ? 'class="active"' : '') !!}>
                    <a href="#">
                    <i class="fa fa-shopping-cart"></i>{{trans('menu.sales')}}</a>
                    <ul class="sub-menu">
                            @if(in_array('reports/sales',$array_p))
                            <li {!! (Request::is( 'reports/sales') ? 'class="active"' : '') !!}>
                                <a href="{{ url('/reports/sales') }}">
                                <i class="fa fa-cloud-download"></i>{{trans('menu.sales_report')}}</a>
                            </li>
                            @endif
                            @if(in_array('reports/reporte_venta',$array_p))
                            {{-- <li {!! (Request::is( 'reports/reporte_venta') ? 'class="active"' : '') !!}>
                                <a href="{{ url('/reports/reporte_venta') }}">
                                <i class="fa fa-angle-double-right"></i>{{trans('menu.sales_cash_registe')}}</a>
                            </li> --}}
                            @endif
                            @if(in_array('reports/items_quantity_sales',$array_p))
                            <li {!! (Request::is( 'reports/items_quantity_sales') ? 'class="active"' : '') !!}>
                                <a href="{{ url('/reports/items_quantity_sales') }}">
                                <i class="fa fa-list-ol"></i>{{trans('menu.items_quantity_sales')}}</a>
                            </li>
                            @endif
                            @if(in_array('reports/route/sales',$array_p))
                            <li {!! (Request::is( 'reports/route/sales') ? 'class="active"' : '') !!}>
                                <a href="{{ url('/reports/route/sales') }}">
                                <i class="fa fa-list-alt"></i>{{trans('menu.route_total_sales')}}</a>
                            </li>
                            @endif
                            @if(in_array('reports/route/profit',$array_p))
                            <li {!! (Request::is( 'reports/route/profit') ? 'class="active"' : '') !!}>
                                <a href="{{ url('/reports/route/profit') }}">
                                <i class="fa fa-bar-chart-o"></i>{{trans('menu.route_profit')}}</a>
                            </li>
                            @endif
                            @if(in_array('reports/inventory_week',$array_p))
                                <li {!! (Request::is( 'reports/inventory_week') || Request::is( 'reports/inventory_week/*') ? 'class="active"' : '') !!}>
                                    <a href="{{ url('/reports/inventory_week') }}">
                                        <i class="fa fa-random"></i>{{trans('menu.weekly_settlement')}}</a>
                                </li>
                            @endif
                            @if(in_array('reports/profit_by_product',$array_p))
                            <li {!! (Request::is( 'reports/profit_by_product') || Request::is( 'reports/profit_by_product/*') ? 'class="active"' : '') !!}>
                                <a href="{{ url('/reports/profit_by_product') }}">
                                    <i class="fa fa-bar-chart-o"></i>{{trans('menu.products_profit')}}</a>
                            </li>
                            @endif
                    </ul>
                </li>
            </ul>
        @endif
        @if(in_array('reports-receivings-section',$array_p))
            <ul class="sub-menu">
                    <li {!! (Request::is( 'reports/receivings') || Request::is( 'reports/reporte_compra') ? 'class="active"' : '') !!}>
                        <a href="#">
                        <i class="fa fa-truck"></i>{{trans('menu.receivings')}}</a>
                        <ul class="sub-menu">
                                @if(in_array('reports/receivings',$array_p))
                                <li {!! (Request::is( 'reports/receivings') ? 'class="active"' : '') !!}>
                                    <a href="{{ url('/reports/receivings') }}">
                                    <i class="fa fa-cloud-upload"></i>{{trans('menu.receivings_report')}}</a>
                                </li>
                                @endif
                                @if(in_array('reports/reporte_compra',$array_p))
                                <li {!! (Request::is( 'reports/reporte_compra') ? 'class="active"' : '') !!}>
                                    <a href="{{ url('/reports/reporte_compra') }}">
                                    <i class="fa fa-money"></i>{{trans('menu.report_receiving_today')}}</a>
                                </li>
                                @endif
                        </ul>
                    </li>
                </ul>
        @endif
        @if(in_array('reports-inventory-section',$array_p))
        <ul class="sub-menu">
                <li {!! (Request::is( 'reports/product_and_cellars_notPrice') || Request::is( 'reports/expired_report') || Request::is( 'listAdjustmentAdd') || Request::is( 'listAdjustmentSale') || Request::is( 'reports/stock') ? 'class="active"' : '') !!}>
                    <a href="#">
                    <i class="fa fa-check-square"></i>{{trans('menu.inventory')}}</a>
                    <ul class="sub-menu">
                        @if(in_array('reports/stock',$array_p))
                        <li {!! (Request::is( 'reports/stock') ? 'class="active"' : '') !!}>
                            <a href="{{ url('/reports/stock') }}">
                            <i class="fa fa-inbox"></i>{{trans('menu.stock')}}</a>
                        </li>
                        @endif
                        <li {!! (Request::is( 'reports/product_and_cellars_notPrice') ? 'class="active"' : '') !!}>
                            <a href="{{ url('/reports/product_and_cellars_notPrice') }}">
                            <i class="fa fa-table"></i>{{trans('menu.product_and_cellarsNotPrice')}}</a>
                        </li>
                        @if(in_array('reports/expired_report',$array_p))
                        <li {!! (Request::is( 'reports/expired_report') ? 'class="active"' : '') !!}>
                            <a href="{{ url('/reports/expired_report') }}">
                            <i class="fa fa-times"></i>{{trans('menu.expired_report')}}</a>
                        </li>
                        @endif
                        @if(in_array('report_adjustment_in',$array_p))
                        <li {!! (Request::is( 'listAdjustmentAdd') || Request::is( 'detailsAdd/*') ? 'class="active"' : '') !!}>
                            <a href="{{url('listAdjustmentAdd')}}">
                                <i class="fa fa-sign-in"></i>{{trans('report-sale.adjustment_add_report')}}
                            </a>
                        </li>
                        @endif
                        @if(in_array('report_adjustment_out',$array_p))
                        <li {!! (Request::is( 'listAdjustmentSale') || Request::is( 'detailsSale/*') ? 'class="active"' : '') !!}>
                            <a href="{{url('listAdjustmentSale')}}">
                                <i class="fa fa-sign-out"></i>{{trans('report-sale.adjustment_out_report')}}
                            </a>
                        </li>
                        @endif
                </ul>
            </li>
        </ul>
        @endif
    </li>
    @endif
    {{-- *********************************** CHARTS MENU **************************************************  --}}
    @if(in_array('charts',$array_p))
    <li {!! (Request::is( 'charts') || Request::is( 'charts/*') ? 'class="active"' : '') !!}>
        <a href="#">
              <i class="livicon" data-name="linechart" data-c="#418BCA" data-hc="#418BCA" data-size="18" data-loop="true"></i>
              <span class="title">{{trans('Gráficas')}}</span>
              <span class="fa arrow"></span>
          </a>
        <ul class="sub-menu">
            @if(in_array('charts',$array_p))
            <li {!! (Request::is( 'charts/cobrochart') || Request::is( 'charts/cobrochart/*') ? 'class="active"' : '') !!}>
                <a href="#"><i class="fa fa-bar-chart-o"></i>Pendientes</a></li>
            @endif
        </ul>
    </li>
    @endif
{{-- *********************************** BANKS MENU **************************************************  --}}
    @if(in_array('banks',$array_p))
    <li {!! (Request::is( 'banks') || Request::is( 'banks/*') || Request::is( 'bank_reconciliation/header') || Request::is( 'bank_reconciliation/header/*') || Request::is( 'bank_reconciliation/account/*') || Request::is( 'banks/accounts/') || Request::is(
        'banks/accounts/*') || Request::is( 'banks/payments/*') || Request::is( 'banks/revenues/*') || Request::is( 'banks/transfers/*') || Request::is(
        'banks/tx-types/*') || Request::is('banks/desk_closing') || Request::is('desk_closing/*') ? 'class="active"' : '') !!}>
        <a href="#">
              <i class="livicon" data-name="bank" data-c="#418BCA" data-hc="#418BCA" data-size="18" data-loop="true"></i>
              <span class="title">{{trans('menu.banks_admin')}}</span>
              <span class="fa arrow"></span>
          </a>
        <ul class="sub-menu">
            @if(in_array('accounts',$array_p))
            <li {!! (Request::is( 'bank_reconciliation/account/*')  || Request::is( 'banks/accounts') || Request::is( 'banks/accounts/*') ? 'class="active"' : '') !!}>
            <a href="{{ url('banks/accounts') }}">
                <i class="fa fa-shield"></i>{{trans('menu.bank_accounts')}}</a></li>
            @endif
            @if(in_array('cash_register',$array_p))
            <li {!! (Request::is( 'banks/cash_register/*')  || Request::is( 'banks/cash_register') || Request::is( 'desk_closing') || Request::is( 'desk_closing/*') ? 'class="active"' : '') !!}>
            <a href="{{ url('banks/cash_register') }}">
                <i class="fa fa-external-link"></i>{{trans('menu.cash_registers')}}</a></li>
            @endif
            @if(in_array('accounts',$array_p))
            <li {!! (Request::is( 'bank_reconciliation/header') || Request::is( 'bank_reconciliation/header/*') ? 'class="active"' : '') !!}>
                <a href="{{ url('bank_reconciliation/header') }}">
                    <i class="fa fa-suitcase"></i>{{trans('menu.conciliation')}}</a>
            </li>
            @endif
            @if(in_array('banks/revenues',$array_p))
            <li {!! (Request::is( 'banks/revenues') || Request::is( 'banks/revenues/*') ? 'class="active"' : '') !!}>
                <a href="{{ url('banks/revenues/create') }}">
                    <i class="fa fa-money"></i>{{trans('menu.create_cash_revenue')}}</a>
            </li>
            @endif
            @if(in_array('banks/deposits',$array_p))
                <li {!! (Request::is( 'banks/deposits') || Request::is( 'banks/deposits*') ? 'class="active"' : '') !!}>
                    <a href="{{ url('banks/deposits') }}">
                        <i class="fa fa-level-down"></i>{{trans('menu.deposits')}}</a>
                </li>
            @endif
            @if(in_array('banks/expenses',$array_p))
            <li {!! (Request::is( 'banks/expenses') || Request::is( 'banks/expenses/*') ? 'class="active"' : '') !!}>
                <a href="{{ url('banks/expenses') }}">
                    <i class="fa fa-credit-card"></i>{{trans('menu.bank_expenses')}}</a>
            </li>
            @endif
            @if(in_array('banks/expense_categories',$array_p))
            <li {!! (Request::is( 'banks/expense_categories') || Request::is( 'banks/expense_categories/*') ? 'class="active"' : '') !!}>
                <a href="{{ url('banks/expense_categories') }}">
                <i class="fa fa-tags">
                    </i>{{trans('menu.expense_categories')}}</a>
            </li>
            @endif
            @if(in_array('accounts/transfers',$array_p))
            <li {!! (Request::is( 'banks/transfers') || Request::is( 'banks/transfers/*') ? 'class="active"' : '') !!}>
                <a href="{{ url('banks/transfers') }}">
                    <i class="fa fa-retweet"></i>{{trans('menu.bank_transfers')}}</a>
            </li>
            @endif
            @if(in_array('banks/retention',$array_p))
            <li {!! (Request::is( 'banks/retention') || Request::is( 'banks/retention/*') ? 'class="active"' : '') !!}>
                <a href="{{ url('banks/retention') }}"><i class="fa fa-legal"></i>{{trans('Retenciones')}}</a>
            </li>
            @endif
        </ul>
    </li>
    @endif
    {{-- @if(in_array('administration-menu',$array_p))
    <li {!! (Request::is( 'expenses')    ? 'class="active"' : '') !!}>
        <a href="#">
              <i class="livicon" data-name="linechart" data-c="#418BCA" data-hc="#418BCA" data-size="18" data-loop="true"></i>
              <span class="title">{{trans('menu.administration')}}</span>
              <span class="fa arrow"></span>
          </a>
        <ul class="sub-menu">
            @if(in_array('expenses',$array_p))
            <li {!! (Request::is( 'expenses') || Request::is( 'expenses/*') ? 'class="active"' : '') !!}>
                <a href="{{ url('expenses') }}">
                <i class="fa fa-angle-double-right"></i>{{trans('menu.expenses')}}</a></li>
            @endif
        </ul>
        <ul class="sub-menu">
                @if(in_array('banks/expense_categories',$array_p))
                <li {!! (Request::is( 'banks/expense_categories') || Request::is( 'banks/expense_categories/*') ? 'class="active"' : '') !!}>
                    <a href="{{ url('banks/expense_categories') }}">
                    <i class="fa fa-angle-double-right"></i>{{trans('menu.expense_categories')}}</a></li>
                @endif
            </ul>
    </li>
    @endif  --}}
    {{-- *********************************** VOID MENU **************************************************  --}}
    @if(in_array('anulaciones-menu',$array_p))
    <li {!! (Request::is( 'cancel_bill') || Request::is( 'cancel_bill/*') || Request::is( 'cancel_bill_receivings') || Request::is( 'cancel_bill_receivings/*') || Request::is( 'cxp/*') ? 'class="active"' : '') !!}>
        <a href="#">
            <i class="livicon" data-name="ban" data-size="18" data-c="#00bc8c" data-hc="#00bc8c" data-loop="true"></i>
            <span class="title">{{trans('menu.anulaciones')}}</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="sub-menu">
            @if(in_array('cancel_bill',$array_p))
            {{-- <li {!! (Request::is( 'cancel_bill') ? 'class="active"' : '') !!}>
                <a href="{{ url('/cancel_bill') }}">
                <i class="fa fa-angle-double-right"></i>{{trans('menu.cancel_bill')}}</a></li> --}}
            @endif
            @if(in_array('cancel_bill/report_canceled_sales',$array_p))
                <li {!! (Request::is( 'cancel_bill/report_canceled_sales') ? 'class="active"' : '') !!}>
                    <a href="{{ url('/cancel_bill/report_canceled_sales') }}">
                    <i class="fa fa-minus-square"></i>{{trans('menu.report_cancel_bill')}}</a>
                </li>
            @endif
            @if(in_array('cancel_bill_receivings',$array_p))
                {{-- <li {!! (Request::is( 'cancel_bill_receivings') ? 'class="active"' : '') !!}>
                    <a href="{{ url('/cancel_bill_receivings') }}">
                    <i class="fa fa-angle-double-right"></i>{{trans('menu.cancel_bill_receivings')}}</a>
                </li> --}}
            @endif
            @if(in_array('cancel_bill_receivings/report_cancel_bill_receivings',$array_p))
            <li {!! (Request::is( 'cancel_bill_receivings/report_cancel_bill_receivings') ? 'class="active"' : '') !!}>
                <a href="{{ url('/cancel_bill_receivings/report_cancel_bill_receivings') }}">
                <i class="fa fa-plus-square"></i>{{trans('menu.report_cancel_bill_receivings')}}</a>
            </li>
            @endif

        </ul>
    </li>
    @endif
    {{-- *********************************** ACCESS MENU **************************************************  --}}
    @if(in_array('access-menu',$array_p))
    <li {!! (Request::is( 'roles') || Request::is( 'roles/*') || Request::is( 'roles/create') || Request::is(
        'role_permission/*') || Request::is( 'permissions') || Request::is( 'permissions/create') || Request::is( 'permissions/*') || Request::is(
        'user_role') || Request::is( 'user_role/*') || Request::is( 'employees') || Request::is( 'employees/create') || Request::is(
        'employees/*') ? 'class="active"' : '') !!}>
        <a href="#">
            <i class="livicon" data-name="lock" data-size="18" data-c="#5bc0de" data-hc="#5bc0de" data-loop="true"></i>
            <span class="title">{{trans('menu.access')}} </span>
            <span class="fa arrow"></span>
        </a>
        <ul class="sub-menu">
            @if(in_array('roles',$array_p))
            <li {!! (Request::is( 'roles') || Request::is( 'roles/*') || Request::is( 'role_permission/*') ? 'class="active"' : '') !!}>
                <a href="{{ url('/roles') }}">
                    <i class="fa fa-group (alias)"></i> Roles</a></li>
            @endif
            @if(in_array('permissions',$array_p))
            <li {!! (Request::is( 'permissions') || Request::is( 'permissions/*') ? 'class="active"' : '') !!}>
                <a href="{{ url('/permissions') }}">
                    <i class="fa fa-lock"></i> Permisos</a></li>
            @endif
            {{-- @if(in_array('user_role',$array_p))
            <li {!! (Request::is( 'user_role') || Request::is( 'user_role/*') ? 'class="active"' : '') !!}>
                <a href="{{ url('/user_role') }}">
                    <i class="fa fa-star"></i> Agregar roles a usuarios</a></li>
            @endif --}}
            @if(in_array('employees',$array_p))
            <li {!! (Request::is( 'employees') || Request::is( 'employees/*') ? 'class="active"' : '') !!}>
                <a href="{{ url('/employees') }}">
                    <i class="fa fa-user"></i> {{trans('menu.employees')}}</a></li>
            @endif
        </ul>
    </li>
    @endif
{{-- *********************************** PARAMETERS MENU **************************************************  --}}
    @if(in_array('parametes-menu',$array_p))
    <li {!! ( Request::is( 'budget_config') || Request::is( 'budget_config/*') || Request::is( 'parameters') || Request::is( 'parameters/*') ||Request::is( 'invoice_type') || Request::is(
        'invoice_type/*') || Request::is( 'pago') || Request::is( 'pago/*') || Request::is( 'documents') || Request::is( 'documents/*') || Request::is(
        'series') || Request::is( 'class/*') ||Request::is( 'series/*') || Request::is( 'percent/*') || Request::is( 'percent') || Request::is(
        'class') || Request::is( 'typeMoney/*') || Request::is( 'typeMoney') || Request::is( 'general-parameters') || Request::is( 'general-parameters/*') ? 'class="active"' : '') !!}>
        <a href="#">
                <i class="livicon" data-name="gear" data-c="#EF6F6C" data-hc="#EF6F6C" data-size="18" data-loop="true"></i>
                <span class="title">{{trans('menu.parameters')}}</span>
                <span class="fa arrow"></span>
        </a>
        <ul class="sub-menu">
            @if(in_array('pago',$array_p))
            <li {!! (Request::is( 'pago') || Request::is( 'pago/*') ? 'class="active"' : '') !!}>
                <a href="{{ url('/pago') }}">
                        <i class="fa fa-ticket"></i>{{trans('menu.parameters_menu.payment_forms')}}
                    </a>
            </li>
            @endif
            @if(in_array('documents',$array_p))
            <li {!! (Request::is( 'documents') || Request::is( 'documents/*') ? 'class="active"' : '') !!}>
                <a href="{{ url('/documents') }}">
                        <i class="fa fa-book"></i> {{trans('menu.parameters_menu.documents')}}
                    </a>
            </li>
            @endif
            @if(in_array('series',$array_p))
            <li {!! (Request::is( 'series') || Request::is( 'series/*') ? 'class="active"' : '') !!}>
                <a href="{{ url('/series') }}">
                        <i class="fa fa-sort-amount-asc"></i> {{trans('menu.parameters_menu.series')}}
                    </a>
            </li>
            @endif
            @if(in_array('percent', $array_p))
            <li {!! (Request::is( 'percent') || Request::is( 'percent/*') ? 'class="active"' : '') !!}>
                <a href="{{ url('/percent') }}">
                        <i class="fa fa-angle-double-right"></i> % Facturación
                    </a>
            </li>
            @endif
            @if(in_array('invoice_type', $array_p))
            <li {!! (Request::is( 'invoice_type') || Request::is( 'invoice_type/*') ? 'class="active"' : '') !!}>
                <a href="{{ url('/invoice_type') }}">
                        <i class="fa fa-angle-double-right"></i> Tipo de Facturación
                    </a>
            </li>
            @endif
            @if(in_array('class', $array_p))
            <li {!! (Request::is( 'class') || Request::is( 'class/*') ? 'class="active"' : '') !!}>
                <a href="{{ url('/class') }}">
                        <i class="fa fa-angle-double-right"></i> Clasificación de clientes
                    </a>
            </li>
            @endif
            @if(in_array('typeMoney', $array_p))
            <li {!! (Request::is( 'typeMoney') || Request::is( 'typeMoney/*') ? 'class="active"' : '') !!}>
                <a href="{{ url('/typeMoney') }}">
                        <i class="fa fa-money"></i> Denominación Monedas
                    </a>
            </li>
            @endif
            @if(in_array('parameters', $array_p))
            <li {!! (Request::is( 'parameters') || Request::is( 'parameters/*') ? 'class="active"' : '') !!}>
                <a href="{{ url('/parameters') }}">
                        <i class="fa fa-briefcase"></i> {{trans('menu.parameters_menu.company')}}
                    </a>
            </li>
            @endif
            @if(in_array('budget_config', $array_p))
                <li {!! (Request::is( 'budget_config') || Request::is( 'budget_config/*') ? 'class="active"' : '') !!}>
                    <a href="{{ url('/budget_config') }}">
                        <i class="fa fa-cogs"></i> {{trans('menu.parameters_menu.budget_config')}}
                    </a>
                </li>
            @endif
            @if(in_array('parameters', $array_p))
            <li {!! (Request::is( 'general-parameters') || Request::is( 'general-parameters/*') ? 'class="active"' : '') !!}>
                <a href="{{ url('/general-parameters') }}">
                        <i class="fa fa-wrench"></i> {{trans('menu.parameters_menu.general')}}
                    </a>
            </li>
            @endif
        </ul>
    </li>
    @endif
    @endif
</ul>
