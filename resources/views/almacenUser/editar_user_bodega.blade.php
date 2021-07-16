@extends('layouts/default')

@section('title',trans('Asignar usuarios a bodega'))
@section('page_parent',trans('Bodegas'))

@section('content')
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">
						<i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
						Asignar usuarios a bodega
					</h3>
					<span class="pull-right clickable">
						<i class="glyphicon glyphicon-chevron-up"></i>
					</span>
				</div>
				<div class="panel-body">
					{{--  --}}
					{!! Form::model($bodega_user, array('route' => array('bodega_usuario.update', $dataBodega->id), 'method' => 'PUT', 'files' => true)) !!}
					<input type="hidden" name="id_bodega" value="{{$dataBodega->id}}">
					<div class="form-group">
						<h3>Bodega: <strong>{{$dataBodega->name}}</strong></h3>
					</div>
					<div class="form-group">
						{!! Form::label('size', trans('Seleccione los usuarios para esta bodega')) !!}
						<br>
						<table class="table">
							<thead>
								<tr>
									<th>Nombre usuario </th>
									<th>&nbsp;</th>
									<th>Estado</th>
								</tr>
							</thead>
							<tbody>
								@foreach($bodega_user as $value)
								<tr>
									<td>
										<label for="">{{$value->name}}</label>
									</td>
									<td></td>
									<td>
										<input type="checkbox" name="usuarios[]" class="form-control" value="{{$value->idUser}}" {{ ($value->NueValor == 1) ?  'checked="checked"' : '' }}">
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					<!-- <a class="btn btn-small btn-warning" href="{{ URL::to('almacen') }}">{{trans('Regresar')}}</a>
						{!! Form::submit(trans('Aceptar'), array('class' => 'btn btn-info')) !!} -->
						@include('partials.buttons',['cancel_url'=>"/almacen"])
						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	</section>
	@endsection
	