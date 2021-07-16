@extends('layouts/default')

@section('title',trans('Actualizar bodega'))
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
					{!! Form::open(array('url' => 'almacen/operar')) !!}
					<div class="form-group">
						{!! Form::label('size', trans('Seleccione la bodega')) !!}
						{!! Form::select('id_bodega', $bodegas, Input::old('bodegas'), array('class' => 'form-control')) !!}
					</div>
					<div class="form-group">
						{!! Form::label('size', trans('Seleccione los usuarios para esta bodega')) !!}
						<br>
						@foreach($users as $value)
						<label for="">{{$value->name}}</label>
						<input type="checkbox" name="usuarios[]" value="{{$value->id}}">
						<br>
						@endforeach
					</div>
					{!! Form::submit(trans('Aceptar'), array('class' => 'btn btn-primary')) !!}
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
</section>
@endsection
