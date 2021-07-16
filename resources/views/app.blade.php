<!DOCTYPE html>
<html ng-app="tutapos">


<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>cacaoPOS</title>

	<link href="{{ asset('/css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/footer.css') }}" rel="stylesheet">
	<!-- Fonts -->
	<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
	<link rel="shortcut icon" href="{{ asset('/images/system/logo_100x92.png') }}" type="image/x-icon" />

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
@yield('header_styles')
</head>
<body>
	<nav class="navbar navbar-default">
		<!-- <div class="container-fluid" style="background-color: #738899"> -->
		<div class="container-fluid" style="background-color: #073963">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="/" style="padding: 0px">
					<img src="{{ asset('images/system/logo_main.png') }}" alt="logo" height="50">
				</a>
				<!-- <a class="navbar-brand" href="http://mastertechgt.com">MasterPOS</a> -->
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li><a href="{{ url('/') }}">{{trans('menu.dashboard')}}</a></li>
					@if (Auth::check())
					<!-- Obtenemos los permisos de cada uno de los usuarios -->
					<?php $permisos=Session::get('permisions'); $array_p =array_column(json_decode(json_encode($permisos), True),'ruta');  ?>


						  @if(in_array('sales-menu',$array_p))
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{trans('menu.sales')}} <span class="caret"></span></a>
									<ul class="dropdown-menu" role="menu">
										@if (in_array('sales?id=0',$array_p))
										<li><a href="{{ url('/sales') }}">{{trans('menu.sales')}}</a></li>
										@endif
										@if (in_array('customers',$array_p))
										<li><a href="{{ url('/customers') }}">{{trans('menu.customers')}}</a></li>
										@endif
									</ul>
								</li>
							@endif
							@if(in_array('receivings-menu',$array_p))
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{trans('menu.receivings')}} <span class="caret"></span></a>
									<ul class="dropdown-menu" role="menu">
										@if(in_array('receivings',$array_p))
										<li><a href="{{ url('/receivings') }}">{{trans('menu.receivings')}}</a></li>
										@endif
										@if(in_array('suppliers',$array_p))
										<li><a href="{{ url('/suppliers') }}">{{trans('menu.suppliers')}}</a></li>
										@endif
									</ul>
								</li>
							@endif

							@if(in_array('ítems-menu',$array_p))
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{trans('menu.items')}} <span class="caret"></span></a>
									<ul class="dropdown-menu" role="menu">
										@if(in_array('ítems',$array_p))
										<li><a href="{{ url('/items') }}">{{trans('menu.list_product2')}}</a></li>
										@endif
										@if(in_array('items/show',$array_p))
										<li><a href="{{ url('/items/show') }}">{{trans('menu.list_product')}}</a></li>
										@endif
										@if(in_array('categorie_product',$array_p))
										<li><a href="{{ url('/categorie_product') }}">Categorias</a></li>
										@endif
										@if(in_array('ítems-kits',$array_p))
										<li><a href="{{ url('/item-kits') }}">{{trans('menu.item_kits')}}</a></li>
										@endif
									</ul>
								</li>
							@endif
							@if(in_array('parametes-menu',$array_p))
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{trans('menu.parameters')}} <span class="caret"></span></a>
									<ul class="dropdown-menu" role="menu">
										@if(in_array('pago',$array_p))
										<li><a href="{{ url('/pago') }}">Tipos de pago</a></li>
										@endif
										@if(in_array('documents',$array_p))
										<li><a href="{{ url('/documents') }}">Tipos de Documentos</a></li>
										@endif
										@if(in_array('series',$array_p))
										<li><a href="{{ url('/series') }}">Series de Documentos</a></li>
										@endif
									</ul>
								</li>
							@endif

							@if(in_array('credit',$array_p))
								<li><a href="{{ url('/credit') }}">{{trans('menu.credits')}}</a></li>
							@endif
							@if(in_array('reports-menu',$array_p))
								<li class="dropdown">
						           
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{trans('menu.reports')}} <span class="caret"></span></a>
									<ul class="dropdown-menu" role="menu">
										@if(in_array('reports/receivings',$array_p))
										<li><a href="{{ url('/reports/receivings') }}">{{trans('menu.receivings_report')}}</a></li>
										@endif
										@if(in_array('reports/reporte_compra',$array_p))
										<li><a href="{{ url('/reports/reporte_compra') }}">{{trans('menu.report_receiving_today')}}</a></li>
										@endif
										@if(in_array('reports/report_cancel_bill_receivings',$array_p))
										<li><a href="{{ url('/reports/report_cancel_bill_receivings') }}">{{trans('menu.report_cancel_bill_receivings')}}</a></li>
										@endif
										@if(in_array('reports/expired',$array_p))
										@endif
										@if(in_array('reports/sales',$array_p))
										<li><a href="{{ url('/reports/sales') }}">{{trans('menu.sales_report')}}</a></li>
										@endif
										@if(in_array('reports/reporte_venta',$array_p))
										<li><a href="{{ url('/reports/reporte_venta') }}">{{trans('menu.report_sale_today')}}</a></li>
										@endif
										@if(in_array('reports/report_cancel_bill',$array_p))
										<li><a href="{{ url('/reports/report_cancel_bill') }}">{{trans('menu.report_cancel_bill')}}</a></li>
										@endif
										@if(in_array('reports/customers_pending_to_pay',$array_p))
										<li><a href="{{ url('/reports/customers_pending_to_pay') }}">{{trans('menu.customers_pending_to_pay')}}</a></li>
										@endif
										@if(in_array('reports/items_quantity_sales',$array_p))
										<li><a href="{{ url('/reports/items_quantity_sales') }}">{{trans('menu.items_quantity_sales')}}</a></li>
										<li><a href="{{ url('/reports/product_and_cellars') }}">{{trans('menu.product_and_cellars')}}</a></li>
										@endif

										{{-- @if(in_array('reports/product_and_cellarsNotPrice',$array_p)) --}}
										<li><a href="{{ url('/reports/product_and_cellars_notPrice') }}">{{trans('menu.product_and_cellarsNotPrice')}}</a></li>
										{{-- @endif --}}
										@if(in_array('reports/expired_report',$array_p))
										<li><a href="{{ url('/reports/expired_report') }}">{{trans('menu.expired_report')}}</a></li>
										@endif
									</ul>
								</li>
							@endif

							@if(in_array('access-menu',$array_p))
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{trans('menu.access')}} <span class="caret"></span></a>
									<ul class="dropdown-menu" role="menu">
										@if(in_array('roles',$array_p))
										<li><a href="{{ url('/roles') }}">Roles</a></li>
										@endif
										@if(in_array('permissions',$array_p))
										<li><a href="{{ url('/permissions') }}">Lista de permisos</a></li>
										@endif
										@if(in_array('user_role',$array_p))
										<li><a href="{{ url('/user_role') }}">Agregar roles a usuarios</a></li>
										@endif
										@if(in_array('employees',$array_p))
										<li><a href="{{ url('/employees') }}">{{trans('menu.employees')}}</a></li>
										@endif
									</ul>
								</li>
							@endif
							@if(in_array('bodegas-menu',$array_p))
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{trans('menu.storage')}} <span class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">
									@if(in_array('almacen',$array_p))
									<li><a href="{{ url('/almacen') }}">Bodegas</a></li>
									@endif
									@if(in_array('list_transfer',$array_p))
									<li><a href="{{ url('/list_transfer') }}">{{trans('menu.transfer_to_storage')}}</a></li>
									@endif
								</ul>
							</li>
							@endif
							@if(in_array('Anulaciones-menu',$array_p))
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{trans('menu.anulaciones')}} <span class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">
									@if(in_array('cancel_bill',$array_p))
									<li><a href="{{ url('/cancel_bill') }}">{{trans('menu.cancel_bill')}}</a></li>
									@endif
									@if(in_array('cancel_bill_receivings',$array_p))
									<li><a href="{{ url('/cancel_bill_receivings') }}">{{trans('menu.cancel_bill_receivings')}}</a></li>
									@endif
								</ul>
							</li>
							@endif
					@endif
				</ul>

				<ul class="nav navbar-nav navbar-right">
					@if (Auth::guest())
						<li><a href="{{ url('/auth/login') }}">Login</a></li>
					@else
						<li class="dropdown">
							<a  class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>

							<ul class="dropdown-menu" role="menu">
								<li><a href="{{ URL::to('employees/' . Auth::user()->id . '/edit') }}">{{trans('menu.profile')}}</a></li>
								<li class="divider"></li>
								<li><a href="{{ url('/auth/logout') }}">{{trans('menu.logout')}}</a></li>
							</ul>
						</li>
					@endif
				</ul>
			</div>
		</div>
	</nav>

	@yield('content')

	<footer class="footer hidden-print" style="background-color: #073963">
      <div class="container" >
        <p class="text-muted" style="float: right;transform: translate(15%, -36%);">{{trans('menu.youre_using')}}<a href="http://cacaogtpos.com"  target="_blank" style="color: #ff7411;"> CacaoPOS</a> de <a target="_blank" href="http://cacao.gt"><img src="{{ asset('images/system/logo_footer_180x44.png') }}" alt="logo" ></a>
        </p>
      </div>
    </footer>
	<!-- Scripts tet jramirez -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
@yield('footer_scripts')
</body>
</html>
