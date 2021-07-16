@extends('layouts/default')

@section('title',trans('general.asign_role_to_user'))
@section('page_parent',trans('Accesos'))

@section('content')
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">
						<i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
						{{trans('general.asign_role_to_user')}}
					</h3>
					<span class="pull-right clickable">
						<i class="glyphicon glyphicon-chevron-up"></i>
					</span>
				</div>
				<div class="panel-body">
					@if (Session::has('message'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
					@endif
					{!! Html::ul($errors->all()) !!}
					{!! Form::model($dataUser, array('route' => array('user_role.update', $dataUser->id), 'method' => 'PUT', 'files' => true)) !!}
					<div class="form-group">
						{!! Form::label('name', trans('Nombre del empleado')) !!}
						<input type="hidden" name="idUser" value="{{$dataUser->id}}">
						<input type="text" name="" value="{{$dataUser->name}}" class="form-control" disabled>
					</div>
					<div class="form-group">
						{!! Form::label('name', trans('Usuario')) !!}
						<input type="text" name="" value="{{$dataUser->email}}" class="form-control" disabled>
					</div>
					<div class="form-group">
						{!! Form::label('name', trans('Seleccione el rol para el usuario')) !!}
						<select class="form-control" name="id_rol">
							<option value="">Seleccionar rol</option>
							@foreach($dataRoles as $value)
							<option value="{{$value->id}}">{{$value->role}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group">
						@include('partials.buttons',['cancel_url'=>"/user_role"])						
					</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
</section>
@endsection
