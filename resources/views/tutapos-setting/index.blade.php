@extends('layouts/default')

@section('title',"Configuraciones de Aplicacion")
@section('page_parent',"Home")


@section('content')
<section class="content">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<!-- <div class="panel-heading">Configuraciones de Aplicacion</div> -->

				<div class="panel-body">
					@if (Session::has('message'))
                    	<div class="alert alert-info">{{ Session::get('message') }}</div>
                	@endif
					{!! Html::ul($errors->all()) !!}

					{!! Form::model($tutapos_settings, array('route' => array('cacaopos-settings.update', $tutapos_settings->id), 'method' => 'PUT', 'files' => true)) !!}

					<div class="form-group">
					{!! Form::label('language', 'Idioma') !!}
					{!! Form::select('language', array('en' => 'English', 'es' => 'Spanish'), Input::old('language'), array('class' => 'form-control')) !!}
					</div>

					@include('partials.buttons',['cancel_url'=>"/"])
					<!-- {!! Form::submit('Submit', array('class' => 'btn btn-primary')) !!} -->

					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
</section>
@endsection