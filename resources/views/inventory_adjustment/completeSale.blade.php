@extends('layouts/default')

@section('title',trans('Detalle de salida de inventario'))
@section('page_parent',trans('Bodegas'))

@section('content')
<section class="content">
	<div class="row" id="inventory_adjustment">
		<div class="col-md-12">
			<div class="panel panel-default">
				<!-- <div class="panel-heading">
					Detalle de salida de inventario
				</div> -->
				<div class="panel-body">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								Fecha: {{date('d/m/Y H:i:s',strtotime($salesReport[0]->created_at))}}
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								Documento: {{$salesReport[0]->document_and_correlative}}
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								Usuario: {{$salesReport[0]->user_name}}
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								Cliente: 
								@if($salesReport[0]->customer_name)
									{{$salesReport[0]->customer_name}}
								@else 
									N/A
								@endif
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								Bodega:&nbsp;{{$dataItems[0]->storage_name}}
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
										<th style="text-align: center;">No.</th>
										<th>Producto</th>
										<th style="text-align: center;">Cantidad</th>
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
								<textarea class="form-control" readonly="">{{$salesReport[0]->comments}}</textarea>
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
							<a  href="{{url('listAdjustmentSale')}}" class="btn btn-danger">
								Cancelar
							</a>
								<a href="{{url('/inventory_adjustment/sale')}}" class="btn btn-success">
									Nuevo ajuste
								</a>
								<button type="button" onclick="cacao_print('inventory_adjustment','/css/app1.css','Salida de inventario');"  class="btn btn-primary hidden-print">{{trans('Imprimir')}}</button>

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