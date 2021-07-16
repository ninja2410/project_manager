@extends('layouts/default')

@section('title',trans('item.inventory_data_tracking'))
@section('page_parent',trans('item.items'))

@section('content')
<section class="content">
	<!-- <div class="container"> -->
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">{{ trans('item.kardex_of').': '}}<b>{{ $item_name }}</b></div>
					
					<div class="panel-body">
						@if (Session::has('message'))
						<div class="alert alert-info">{{ Session::get('message') }}</div>
						<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
						@endif
						
						{!! Html::ul($errors->all()) !!}
						<div class="row ">
							<div class="col-md-4">
								<br>
								<div class="table-responsive">
									<table class="table  table-striped" id="users">
										<tr>
											<td>{{ trans('item.kardex_of') }}</td>
											<td>
												<strong>{{$items->item_name}}</strong>
												<input type="hidden" id="item_name" name="item_name" value="{{$items->item_name}}">
											</td>
										</tr>
										<tr>
											<td>{{ trans('item.upc_ean_isbn') }}</td>
											<td>
												<strong>{{$items->upc_ean_isbn}}</strong>
											</td>
										</tr>										
										<td>{{ trans('item.item_cellar') }}</td>
										<td>
											<strong>{{$almacen_name}}</strong>
										</td>
									</tr>
									<td>{{ trans('item.existence') }}</td>
									<td>
										<strong>{{$existencia or 'N/A' }}</strong>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="col-md-6">
						<br>
						<div class="form-group">
							<div class="text-center">
								<div class="fileinput fileinput-new" data-provides="fileinput">
									<div class="fileinput-new thumbnail">
										<a href="{{ URL::to('items/detail/' . $items->id ) }}"><img src="{!! asset('images/items/') . '/' . $avatar !!}" class="img-responsive user_image" alt="{{$item_name}}" style="max-width: 150px;" data-toggle="tooltip" data-original-title="Ver detalle"/></a>
									</div>
								</div>
							</div>
							<font>Click para ver detalle del producto</font>
						</div>
					</div>
				</div>
				
				
				<div class="panel-body table-responsive">
					<table id="example" class="table table-striped table-bordered " cellspacing="0" width="100%">
						<thead>
							<tr>
								<th></th>
								<th>{{trans('item.tx_date')}}</th>
								<th>{{trans('item.employee')}}</th>
								<th>{{trans('item.in_out_qty')}}</th>
								<th>Total Acumulado</th>
								<th>{{trans('item.remarks')}}</th>
							</tr>
						</thead>
						<tbody>
							<?php   $acumulado=0; ?>
							@foreach($items_data as $value)
							<tr>
								<td></td>
								<td>{{date_format($value->created_at, 'd/m/Y H:i:s')}}</td>
								<td>{{ $value->name}}</td>
								<td>{{ $value->in_out_qty }}</td>
								<td><?php
									$acumulado=$acumulado + $value->in_out_qty;
									echo $acumulado;?>
								</td>
								<td>{{ $value->remarks }}</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
				<div class="col-lg-12">
					<a class="btn btn-danger" href="{{ URL::previous() }}" >
						{{trans('button.back')}}
					</a>
					<a class="btn btn-info" href="{{ url('/items/search') }}" >
						{{trans('menu.item_search')}}
					</a>
					<a class="btn btn-primary" href="{{ url('items') }}" >
						{{trans('menu.item_catalog')}}
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- </div> -->
</section>
@endsection

@section('footer_scripts')

<script>
	$(document).ready(function() {
		$('#example').DataTable({
			language: {
				"url":" {{ asset('assets/json/Spanish.json') }}"
			},
			dom: 'Bfrtip',
			"pageLength": 50,
			// "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todo"]]
			responsive: {
				details: {
					type: 'column'
				}
			},
			columnDefs: [ {
				className: 'control',
				orderable: false,
				targets:   0
			} ],
			buttons: [
			{        
				extend: 'collection',
				text: 'Exportar/Imprimir',
				buttons: [
				{
					extend:'copy',
					text: 'Copiar',					
					title: 'Kardex del Producto-  '+$('#item_name').val(),
					exportOptions:{
						columns: ':visible'
					}
				},
				{
					extend:'excel',
					title: 'Kardex del Producto-  '+$('#item_name').val(),
					exportOptions:{
						columns: ':visible'
					}
				},
				{
					extend:'pdf',
					title: 'Kardex del Producto-  '+$('#item_name').val(),
					exportOptions:{
						columns: ':visible'
					}
				},
				{
					extend:'print',
					text: 'Imprimir',
					title: 'Kardex del Producto-  '+$('#item_name').val(),
					exportOptions:{
						columns: ':visible'
					},
				}          
				]   
			},        
			],
		}) ;
	});
</script>
@stop
