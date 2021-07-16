@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Crear bodega</div>
				<div class="panel-body">
					{!! Html::ul($errors->all()) !!}

					{!! Form::open(array('url' => 'Configuracion de credito')) !!}

					<div class="form-group">
						{!! Form::label('name', trans('Nombre Bodega').' *') !!}
						{!! Form::text('name', Input::old('name'), array('class' => 'form-control')) !!}
					</div>
					<div class="form-group">
						{!! Form::label('phone', trans('Telefono').' ') !!}
						{!! Form::text('phone', Input::old('phone'), array('class' => 'form-control')) !!}
					</div>
					<div class="form-group">
						{!! Form::label('adress', trans('Dirección').' *') !!}
						{!! Form::text('adress', Input::old('adress'), array('class' => 'form-control')) !!}
					</div>
					<div class="form-group">
						{!! Form::label('size', trans('Estado')) !!}
					  {!! Form::select('id_state', $state_cellar, Input::old('state_cellar'), array('class' => 'form-control')) !!}
					</div>
					<div class="form-group">
						{!! Form::label('comentario', trans('Comentario').' ') !!}
						{!! Form::textarea('comentario', Input::old('comentario'), array('class' => 'form-control')) !!}
					</div>
					{!! Form::submit(trans('Aceptar'), array('class' => 'btn btn-primary')) !!}
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
