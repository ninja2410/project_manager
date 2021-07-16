@extends('layouts/default')

@section('title',trans('expenses.expense_catetories'))
@section('page_parent',trans('bank_expenses.expenses'))

@section('content')
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading clearfix">
					<h4 class="panel-title pull-left"> <i class="livicon" data-name="list-ul" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
						{{trans('expenses.expense_catetories')}}
					</h4>
					<div class="pull-right">
						<a href="{{ URL::to('banks/expense_categories/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> {{trans('expenses.new_expense_category')}} </a>
					</div>
				</div>
				<div class="panel-body">
					
					<hr />					
					<table class="table table-striped table-bordered" id="table1">
						<thead>
							<tr>
								<th></th>
								<th style="width: 10%;">No.</th>
								<th>Nombre</th>								
								<th style="width: 200px">Acciones</th>
							</tr>
						</thead>
						@foreach($expense_catetories as $i=>$value)
						<tr>
							<td></td>
							<td>{{$i+1}}</td>
							<td>{{$value->name}}</td>
							<td>
								<a class="btn btn-info" href="{{ URL::to('banks/expense_categories/' . $value->id . '/edit') }}" data-toggle="tooltip" data-original-title="Editar">
									<span class="glyphicon glyphicon-edit"></span>&nbsp;&nbsp;Editar
								</a>
								<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal{!! $value->id !!}">
									<span class="glyphicon glyphicon-remove"></span>&nbsp;Eliminar
								</button>
								{{--Begin modal--}}
								<div class="modal fade modal-fade-in-scale-up" tabindex="-1" id="modal{!! $value->id !!}" role="dialog" aria-labelledby="modalLabelfade" aria-hidden="true">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header bg-danger">
												<h4 class="modal-title">Confirmación Eliminar</h4>
											</div>
											<div class="modal-body">
												<div class="text-center">
													{!! $value->name !!}
													<br>
													¿Desea eliminar categoria de gasto?
												</div>
											</div>
											<div class="modal-footer" style="text-align:center;">
												{!! Form::open(array('url' => 'banks/expense_categories/' . $value->id, 'class' => 'pull-right')) !!}
												{!! Form::hidden('_method', 'DELETE') !!}
												<button type="submit" class="btn  btn-info">Aceptar</button>
												<button class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
												{!! Form::close() !!}
											</div>
										</div>
									</div>
								</div>
								{{--End modal--}}
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