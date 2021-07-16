@extends('layouts/default')

@section('title',trans('Crear permiso'))
@section('page_parent',trans('Accesos'))

@section('content')
<section class="content">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<!-- <div class="panel-heading">Crear permiso</div> -->
				<div class="panel-body">
					{!! Html::ul($errors->all()) !!}
					{!! Form::open(array('url' => 'permissions')) !!}
					<div class="form-group">
						{!! Form::label('name', trans('Descripción').' *') !!}
						{!! Form::text('descripcion', Input::old('descripcion'), array('class' => 'form-control')) !!}
					</div>
					<div class="form-group">
						{!! Form::label('url', trans('Url')) !!}
						{!! Form::text('url', Input::old('url'), array('class' => 'form-control')) !!}
					</div>
					@include('partials.buttons',['cancel_url'=>"/permissions"])
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
</section>
@endsection
