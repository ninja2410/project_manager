@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">{{trans('employee.new_employee')}}</div>
				<div class="panel-body">
					{!! Html::ul($errors->all()) !!}

					{!! Form::open(array('url' => 'employees')) !!}

					<div class="form-group">
					{!! Form::label('name', trans('employee.name').' *') !!}
					{!! Form::text('name', Input::old('name'), array('class' => 'form-control')) !!}
					</div>

					<div class="form-group">
					{!! Form::label('email', trans('employee.email').' *') !!}
					{!! Form::text('email', Input::old('email'), array('class' => 'form-control')) !!}
					</div>

					<div class="form-group">
					{!! Form::label('password', trans('employee.password').' *') !!}
					<input type="password" class="form-control" name="password" placeholder="Password">
					</div>

					<div class="form-group">
					{!! Form::label('password_confirmation', trans('employee.confirm_password').' *') !!}
					<input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password">
					</div>
					<div class="form-group">
						{!! Form::label('size', trans('Estado')) !!}
					  {!! Form::select('id_state', $state_cellar, Input::old('state_cellar'), array('class' => 'form-control')) !!}
					</div>
					<div class="form-group">
						{!! Form::label('size', trans('Seleccione rol de usuario')) !!}
					<select class="form-control" name="id_rol">
						@foreach($dataRoles as $value)
						<option value="{{$value->id}}">{{$value->role}}</option>
						@endforeach

					</select>
					</div>
					{!! Form::submit(trans('employee.submit'), array('class' => 'btn btn-primary')) !!}

					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
