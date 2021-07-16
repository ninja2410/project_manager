@extends('layouts/default')

@section('title',trans('general.document_types'))
@section('page_parent',trans('general.inventory_documents'))

@section('content')
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading clearfix">
					<h4 class="panel-title pull-left"> <i class="livicon" data-name="list-ul" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
						{{trans('general.document_types')}}
					</h4>
					<div class="pull-right">
						<a href="{{ URL::to('documents/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> {{trans('general.new_document_type')}} </a>
					</div>
				</div>
				<div class="panel-body">
					
					<hr />					
					<table class="table table-striped table-bordered" id="table1">
						<thead>
							<tr>
								<th></th>
								<th style="width:5%">No.</th>
								<th>Nombre del documento</th>
								<th>Signo</th>
								<th>Ajuste de Inventario</th>
								<th>Estado</th>
								<th style="width:10%">Acciones</th>
								
							</tr>
						</thead>
						@foreach($dataDocumento as $i=>$value)
						<tr>
							<td></td>
							<td>{{$i+1}}</td>
							<td>{{$value->name}}</td>
							<td>{{$value->sign}}</td>
							<td>@if($value->ajuste_inventario=='1') Si @else No @endif </td>
							<td>
								@if($value->id_state==1)
								<span class="label label-sm label-success">{!! 'Activo' !!}</span>
								@else
								<span class="label label-sm label-danger">{!! 'Inactivo' !!}</span>
								@endif								
							</td>
							<td>
								<a class="btn btn-info" href="{{ URL::to('documents/' . $value->id . '/edit') }}" data-toggle="tooltip" data-original-title="Editar">
									<span class="glyphicon glyphicon-edit"></span>
								</a>								
								{!! Form::open(array('url' => 'documents/' . $value->id, 'class' => 'pull-right')) !!}
								{!! Form::hidden('_method', 'DELETE') !!}
								<button type="submit" class="btn btn-primary btn-danger" type="submit"  data-toggle="tooltip" data-original-title="{{trans('customer.delete')}}">
									<span class="glyphicon glyphicon-remove-circle"></span>
								</button>
								{!! Form::close() !!}
							</td>
						</tr>
						@endforeach
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection
@section('footer_scripts')

<script type="text/javascript">
	$(document).ready(function() {
		$('#table1').DataTable({
			language: {
				"url":" {{ asset('assets/json/Spanish.json') }}"
			},
			dom: 'Bfrtip',
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
					title: document.title,
					exportOptions:{
						columns: 'th:not(:last-child)'
					}
				},
				{
					extend:'excel',
					title: document.title,
					exportOptions:{
						columns: 'th:not(:last-child)'
					}
				},
				{
					extend:'pdf',
					title: document.title,
					exportOptions:{
						columns: 'th:not(:last-child)'
					}
				},
				{
					extend:'print',
					text: 'Imprimir',
					title: document.title,
					exportOptions:{
						columns: 'th:not(:last-child)'
					},
				}          
				]   
			},        
			],
		}) ;
	});
	
</script>
@stop