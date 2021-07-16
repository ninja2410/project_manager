@extends('layouts/default')

@section('title',trans('Actualizar permiso'))
@section('page_parent',trans('Accesos'))

@section('content')
<section class="content">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<!-- <div class="panel-heading">Actualizar permiso</div> -->
				<div class="panel-body">
					{!! Html::ul($errors->all()) !!}
					{!! Form::model($listaPermiso, array('route' => array('permissions.update', $listaPermiso->id), 'method' => 'PUT', 'files' => true)) !!}
					<div class="form-group">
						{!! Form::label('name', trans('Descripción').' *') !!}
						{!! Form::text('descripcion', Input::old('descripcion'), array('class' => 'form-control')) !!}
					</div>
					<div class="form-group">
						{!! Form::label('url', trans('Url')) !!}
						<input type="text" name="url" value="{!!$listaPermiso->ruta!!}" class="form-control">
					</div>
					@include('partials.buttons',['cancel_url'=>"/permissions"])
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
</section>
@endsection
