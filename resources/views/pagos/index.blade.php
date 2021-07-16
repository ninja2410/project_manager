@extends('layouts/default')

@section('title',trans('general.payment_types'))
@section('page_parent',trans('general.payment'))

@section('content')
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading clearfix">
					<h4 class="panel-title pull-left"> <i class="livicon" data-name="list-ul" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
						{{trans('general.payment_types')}}
					</h4>
					<div class="pull-right">
						<a href="{{ URL::to('pago/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> {{trans('general.new_payment_type')}} </a>
					</div>
				</div>
				<div class="panel-body">
					
					<hr />					
					<table class="table table-striped table-bordered" id="table1">
						<thead>
							<tr>
								<th></th>
								<th style="width: 5%;">No.</th>
								<th style="width: 25%;">{{trans('payment.name')}}</th>
								<th style="width: 20%;">{{trans('payment.account_type_in')}}</th>
								<th style="width: 20%;">{{trans('payment.account_type_out')}}</th>
								<th style="width: 15%;">{{trans('payment.visibility')}}</th>
								<th style="width: 8%">{{trans('payment.acctions')}}</th>
							</tr>
						</thead>
						@foreach($pagoss as $i=>$value)
						<tr>
							<td></td>
							<td>{{$i+1}}</td>
							<td>{{$value->name}}</td>
							{{-- <td>
								@if($value->type === '1') Efectivo @endif
								@if($value->type === '2') Cheque @endif
								@if($value->type === '3') Depósito @endif
								@if($value->type === '4') Tarjeta @endif
								@if($value->type === '5') Transferencia @endif
								@if($value->type === '6') Crédito @endif
							</td> --}}							
							<td>
								@foreach($value->accountsin as $key => $item)
								<span class="badge badge-info">{{ $item->name }}</span>
								@endforeach
							</td>
							<td>
								@foreach($value->accountsout as $key => $item)
								<span class="badge badge-red">{{ $item->name }}</span>
								@endforeach
							</td>
							<td>
								@if($value->venta === '1') <span class="badge badge-blue">Ventas</span> @endif
								@if($value->compra === '1') <span class="badge badge-blue">Compras</span> @endif
								@if($value->banco_in === '1') <span class="badge badge-blue">Ingresos Bancarios</span> @endif
								@if($value->banco_out === '1') <span class="badge badge-blue">Egresos Bancarios</span> @endif
							</td>
							<td>
								<a class="btn btn-info" style="width: 40px" href="{{ URL::to('pago/' . $value->id . '/edit') }}" data-toggle="tooltip" data-original-title="Editar">
									<span class="glyphicon glyphicon-edit"></span>
								  </a>
								{!! Form::open(array('url' => 'pago/' . $value->id, 'class' => 'pull-right')) !!}
								{!! Form::hidden('_method', 'DELETE') !!}
								<button type="submit" style="width: 40px" class="btn btn-danger" data-toggle="tooltip" data-original-title="{{trans('customer.delete')}}">
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