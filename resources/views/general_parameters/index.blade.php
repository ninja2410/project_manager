@extends('layouts/default')

@section('title',trans('general.general_parameters'))
@section('page_parent',trans('general.parameters'))

@section('content')
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading clearfix">
					<h4 class="panel-title pull-left"> <i class="livicon" data-name="list-ul" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
						{{trans('general.general_parameters')}}
					</h4>
					<div class="pull-right">
						<a href="{{ URL::to('general-parameters/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> {{trans('general.new_parameter')}} </a>
					</div>
				</div>
				<div class="panel-body">
					
					<hr />					
					<table class="table table-striped table-bordered" id="table1">
						<thead>
							<tr>
								<th></th>
								<th style="width: 10%;">{{trans('general.parameter_type')}}</th>
								<th>{{trans('general.name')}}</th>
								{{-- <th>{{trans('general.description')}}</th> --}}
								<th>{{trans('general.text_value')}}</th>
								<th>{{trans('general.min_amount')}}</th>
								<th>{{trans('general.max_amount')}}</th>
								<th>{{trans('general.default_amount')}}</th>
								<th>{{trans('general.assigned_user_id')}}</th>
								<th>{{trans('general.active')}}</th>
								<th style="width: 10%;">{{trans('general.actions')}}</th>
							</tr>
						</thead>
						@foreach($parameters as $i=>$value)
						<tr>
							<td></td>
							<td>{{$value->type}}</td>
							<td>{{$value->name}}</td>
							{{-- <td>{{<strong></strong>_limit($value->description,$limit = 150, $end = '...')}}</td> --}}
							<td>{{$value->text_value}}</td>
							<td>{{$value->min_amount}}</td>
							<td>{{$value->max_amount}}</td>
							<td>{{$value->default_amount}}</td>
							<td>{{!empty($value->assigned_user->name) ? $value->assigned_user->name : trans('general.na')}}</td>
							<td>
								@if($value->active==1)
								<span class="label label-sm label-success">{!! 'Si' !!}</span>
								@else
								<span class="label label-sm label-danger">{!! 'No' !!}</span>
								@endif
							</td>
							<td>
								<a class="btn btn-info" href="{{ URL::to('general-parameters/' . $value->id . '/edit') }}" data-toggle="tooltip" data-original-title="Editar">
									<span class="glyphicon glyphicon-edit"></span>
								</a>
								@if($value->system==0)
								{!! Form::open(array('url' => 'general-parameters/' . $value->id, 'class' => 'pull-right')) !!}
								{!! Form::hidden('_method', 'DELETE') !!}								
								<button type="submit" class="btn btn-primary btn-danger" type="submit"  data-toggle="tooltip" data-original-title="{{trans('customer.delete')}}">
									<span class="glyphicon glyphicon-remove-circle"></span>
								</button>
								{!! Form::close() !!}
								@else
								<button type="submit" class="btn btn-primary btn-default" type="submit"  data-toggle="tooltip" data-original-title="Parametro de sistema, no es posible borrarlo">
									<span class="glyphicon glyphicon-remove-circle"></span>
								</button>
								@endif
								
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