@extends('layouts/default')

@section('title',trans('Lista de bodegas'))
@section('page_parent',trans('Bodegas'))

@section('header_styles')

@stop
@section('content')
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading clearfix">
					<h4 class="panel-title pull-left"> <i class="livicon" data-name="list-ul" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
						Bodegas
					</h4>
					<div class="pull-right">
						<a href="{{ URL::to('almacen/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> Agregar bodega </a>
					</div>
				</div>
				<div class="panel-body">
					{{--  --}}
					<table class="table table-striped table-bordered" id="table1">
						<thead>
							<tr>
								<th></th>
								<th style="width:5%" data-priority="1001">No.</th>
								<th style="width: 15%" data-priority="2">Nombre</th>
								<th data-priority="5">Teléfono</th>
								<th data-priority="4">Dirección</th>
								<th style="width: 10%" data-priority="6">Estado</th>
								<th data-priority="3">Usuarios</th>
								<th style="width: 10%">Acciones</th>
							</tr>
						</thead>
						<tbody>
							@foreach($almacen as $value)
							<tr>
								<td></td>
								<td>{{ $value->id }}</td>
								<td>{{ $value->nombre }}</td>
								<td>{{ $value->phone }}</td>
								<td>{{ $value->adress }}</td>
								<td>{{ $value->estado }}</td>
								<td>
									@foreach($value->users as $key => $item)
									<span class="badge badge-info">{{ $item->name }}</span>
									@endforeach
								</td>
								<td>
									{{-- <a class="btn btn-small btn-primary" href="{{ URL::to('bodega_usuario/' . $value->id . '/edit') }}" >{{trans('Agregar usuarios')}}</a> --}}
									<a class="btn btn-info" style="width:40px" href="{{ URL::to('almacen/' . $value->id . '/edit') }}" data-toggle="tooltip" data-original-title="Editar">
										<span class="glyphicon glyphicon-edit"></span>
									</a>
									{!! Form::open(array('url' => 'almacen/' . $value->id, 'class' => 'pull-right')) !!}
									{!! Form::hidden('_method', 'DELETE') !!}
									<button type="submit" style="width: 40px" class="btn btn-primary btn-danger" type="submit"  title="Borrar" data-toggle="tooltip" data-original-title="trans('customer.delete')">
										<span class="glyphicon glyphicon-remove-circle"></span>
									</button>
									{!! Form::close() !!}
								</td>
							</tr>

							@endforeach
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
	$(document).ready(function(){
        setDataTable("table1", [], "{{asset('assets/json/Spanish.json')}}");
    });

</script>
@stop
