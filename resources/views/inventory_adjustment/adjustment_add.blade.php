@extends('layouts/default')

@section('title',trans('Ingreso de Inventario (Ajuste) '))
@section('page_parent',trans('Bodegas'))

@section('header_styles')
<!-- <link rel="stylesheet" href="{{asset('assets/vendors/print/css/bootstrap.min.css')}}"> -->
<link rel="stylesheet" href="{{asset('assets/css/bootstrap/bootstrap.min.css')}}">

<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/colReorder.bootstrap.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/rowReorder.bootstrap.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/scroller.bootstrap.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/pages/tables.css') }}" />
<!-- Calendario  -->

<link href="{{ asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />
<!-- Alertify -->
<link rel="stylesheet" href="{{asset('assets/css/alertifyjs/alertify.min.css')}}">
<!-- Alertify -->
<style type="text/css">
	.loader {
		border: 16px solid #ababab;
		border-radius: 50%;
		border-top: 16px solid #3498db;
		width: 100px;
		height: 100px;
		-webkit-animation: spin 2s linear infinite; /* Safari */
		animation: spin 2s linear infinite;
	}

	/* Safari */
	@-webkit-keyframes spin {
		0% { -webkit-transform: rotate(0deg); }
		100% { -webkit-transform: rotate(360deg); }
	}

	@keyframes spin {
		0% { transform: rotate(0deg); }
		100% { transform: rotate(360deg); }
	}
</style>
</style>
@stop

@section('content')
{!! Html::script('js/angular.min.js', array('type' => 'text/javascript')) !!}
{!! Html::script('js/app.js', array('type' => 'text/javascript')) !!}

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">
						<i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
						Ingreso de Inventario (Ajuste)
					</h3>
					<span class="pull-right clickable">
						<i class="glyphicon glyphicon-chevron-up"></i>
					</span>
				</div>
				<div class="panel-body">
					<input type="hidden" name="path" id="path" value="{{ url('/') }}">
					
					<div class="row" ng-controller="listProducts">
						<div class="col-md-3">
							<label>{{trans('receiving.search_item')}} <input id="id_input_search" ng-model="searchKeyword" class="form-control"></label>
							<table class="table table-hover">
								<tr ng-repeat="item in items  | filter: searchKeyword | limitTo:10">
									<td><span id="@{{item.upc_ean_isbn}}"></span>@{{item.item_name}}</td><td><button class="btn btn-primary btn-xs" type="button"  id="nuevo_@{{item.upc_ean_isbn}}"  ng-click="addProduct(item)"><span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span></button></td>
								</tr>
							</table>
						</div>
						<div class="col-md-9">
							{!! Form::open(array('url' => 'inventory_adjustment', 'class' => 'form-horizontal','id'=>'save_receivings')) !!}
							<div class="row">
								<div class="col-md-5">
									<div class="form-group">
										<label for="customer_id" class="col-sm-3 control-label">Bodega</label>
										<div class="col-sm-9">
											<select class="form-control"  id="id_bodega" >
												<option value="0">Seleccione bodega</option>
												@foreach($almacen as $value)
												<option value="{!! $value->id !!}" }>{{ $value->name }}
												</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="form-group">
										<label for="invoice" class="col-sm-3 control-label">Serie</label>
										<div class="col-sm-9">
											<select class="form-control" name="serie_id" id="serie_id" style="font-size: 12px;">
												<option value="0">Seleccione una serie</option>
												@foreach($listDocuments as $value)
												<option value="{!!$value->id_serie!!}">{!!$value->document!!} - {!!$value->serie!!}</option>
												@endforeach
											</select>
										</div>
									</div>

									<div class="form-group">
										<label for="employee" class="col-sm-3 control-label">
											Fecha
										</label>
										<div class="col-sm-9">
											<input type="text" name="date" id="date" style="text-align: left;" class="form-control">
										</div>
									</div>
								</div>
								<div class="col-md-7">
									<div class="form-group">
										<label for="supplier_id" class="col-sm-4 control-label">{{trans('receiving.supplier')}}</label>
										<div class="col-sm-6">
											<!-- {!! Form::select('supplier_id', $supplier, Input::old('supplier_id'), array('class' => 'form-control')) !!} -->
											<input type="hidden" name="supplier_id" value="" class="form-control" id="supplier_id">
											<input type="hidden" name="bodega_id" value="0" class="form-control" id="bodega_id">
											<input type="hidden"  id="qt_items"  value="@{{quantityItems}}">
											<input type="text" name="supplier_name" value="" id="supplier_name" class="form-control" disabled>
										</div>
										<div class="col-ms-4">
											<a href="#" style="font-size: 12px" id="add_supplier_btn" class="btn btn-raised btn-info md-trigger btn-xs" data-toggle="modal" data-target="#modal-1">Buscar</a>
										</div>
									</div>
									<div class="form-group">
										<label for="invoice" class="col-sm-4 control-label">{{trans('Correlativo')}}</label>
										<div class="col-sm-4" >
											<input type="text" name="correlativo_num"  id="correlativo_num" class="form-control inputQt" value="0" style="text-align: center;">
										</div>
									</div>

								</div>
							</div>
							<table class="table table-bordered table-striped">
								<thead>
									<th>Producto</th>
									<th>Cantidad</th>
									<th>Eliminar</th>
								</thead>
								<tbody>
									<tr ng-repeat="value in detailsItems">
										<td>@{{value.item_name}}
											<input type="hidden" name="@{{'item_id_'+$index}}" ng-model="value.item_id" value="@{{value.item_id}}">
										</td>
										<td style="text-align: center;">
											<input type="number" name="@{{'qt_'+$index}}" size="6" step="0.01" ng-model="value.qt"  class="inputQt" style="text-align: center;">
										</td>
										<td style="text-align: center;">
											<button class="btn btn-danger btn-xs" type="button" ng-click="deleteItems($index)">
												<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
											</button>
										</td>
									</tr>
								</tbody>
							</table>
							<div class="form-group">
								<div class="col-md-12">
									<label for="comentario">Comentarios</label>
									<textarea class="form-control" name="comentario" id="comentario"></textarea>
								</div>
							</div>
							<div class="form-group" id="divButton">
								<div class="col-md-12">
									<button type="submit" id="btnVenta" class="btn btn-primary" >
										{{trans('button.save')}}
									</button>
								</div>
							</div>
							<div class="form-group">
								<center>
									<div class="loader" id="divLoading" style="display: none;"></div>
								</center>
							</div>
							{!! Form::close() !!}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--  Ventana modal de listado de proveedores-->
	<div class="modal fade" id="modal-1" role="dialog" aria-labelledby="modalLabelsuccess">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<h4 class="modal-title" id="modalLabelsuccess">Listado de proveedores</h4>
				</div>
				<div class="modal-body" style="margin-left: auto;margin-right: auto; display: block;">
					<table class="table table-bordered table-striped"  id="table_suppliers">
						<thead>
							<td>No.</td>
							<td>Nit</td>
							<th>Nombre empresa</th>
							<th>Nombre contacto</th>
							<th>Teléfono</th>
							<th>Agregar</th>
						</thead>
						<tbody>
							@foreach($supplier as $i=> $value)
							<tr>
								<td>{{$i+1}}</td>
								<td>{{$value->nit_supplier}}</td>
								<td>{{$value->company_name}}</td>
								<td>{{$value->name}}</td>
								<td>{{$value->phone_number}}</td>
								<td>
									<button  type="button" name="button" class="btn btn-primary btn-xs addSupplier" id="name_{{$value->company_name.'/'.$value->id}}"  data-dismiss="modal">
										<span class="glyphicon glyphicon-check" aria-hidden="true"></span>
									</button>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
				<div class="modal-footer">
					<button class="btn  btn-danger" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>


</section>
@endsection
@section('footer_scripts')
<script type="text/javascript" src="//code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" src="{{ asset('/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/metisMenu.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/jquery-slimscroll/jquery.slimscroll.js') }}"></script>

<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}" ></script>

<!-- Calendario  -->
<script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
<!--Canlendario  -->
<!-- Alertify -->
<script type="text/javascript" src="{{asset('assets/js/alertifyjs/alertify.min.js')}}"></script>
<!-- Alertify -->
<script type="text/javascript" src="{{asset('assets/js/inventory_adjustment/add.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/js/inventory_adjustment/initialization.js') }}"></script>
@stop
