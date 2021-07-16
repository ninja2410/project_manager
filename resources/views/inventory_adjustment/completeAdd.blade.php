@extends('layouts/default')

@section('title',trans('Detalle de ingreso de inventario'))
@section('page_parent',trans('Bodegas'))


@section('content')
<section class="content">
	<div class="row" id="inventory_adjustment">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">
						<i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
						Detalle de ingreso de inventario
					</h3>
					<span class="pull-right clickable">
						<i class="glyphicon glyphicon-chevron-up"></i>
					</span>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								Fecha: {{date('d/m/Y H:i:s',strtotime($receivingsReport[0]->created_at))}}
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								Documento: {{$receivingsReport[0]->document.' '.$receivingsReport[0]->serie.'-'.$receivingsReport[0]->correlative }} 
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								Usuario: {{$receivingsReport[0]->nameUser}}
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								Proveedor: 
								@if($receivingsReport[0]->company_name)
								{{$receivingsReport[0]->company_name}}
								@else
								N/A
								@endif
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								Bodega: {{$receivingsReport[0]->storage_name}}
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<center>
									<h3>
										Detalle
									</h3>
								</center>
								<table class="table table-bordered table-striped">
									<thead>
										<th style="text-align: center; width: 25%;">No.</th>
										<th style="width: 45%;">Producto</th>
										<th style="text-align: center;width: 30%;">Cantidad</th>
									</thead>
									<tbody>
										@foreach($dataItems as $index => $value)
										<tr>
											<td style="text-align: center;">{{$index+1}}</td>
											<td>{{$value->item_name}}</td>
											<td style="text-align: center;">{{$value->quantity}}</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Comentario</label>
								<textarea class="form-control" readonly="">{{$receivingsReport[0]->comments}}</textarea>
							</div>
						</div>
					</div>					
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12">
						<center>
							<a  href="{{url('listAdjustmentAdd')}}" class="btn btn-danger">
								Cancelar
							</a>
							<a href="{{url('/inventory_adjustment')}}" class="btn btn-success">
								Nuevo ajuste
							</a>
							<button type="button" onclick="cacao_print('inventory_adjustment','/css/app1.css','Ingreso de inventario');"  class="btn btn-primary hidden-print">{{trans('Imprimir')}}</button>
						</center>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection
@section('footer_scripts')
<script type="text/javascript" src="{{ asset('/js/cacaojs/cgt_tools.js') }}"></script>
@endsection