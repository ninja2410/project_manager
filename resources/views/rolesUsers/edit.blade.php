@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">{{trans('employee.update_employee')}}</div>

				<div class="panel-body">
					{!! Html::ul($errors->all()) !!}

					{!! Form::model($employee, array('route' => array('employees.update', $employee->id), 'method' => 'PUT', 'files' => true)) !!}
					<div class="form-group">
					{!! Form::label('name', trans('employee.name').' *') !!}
					{!! Form::text('name', null, array('class' => 'form-control')) !!}
					</div>

					<div class="form-group">
					{!! Form::label('email', trans('employee.email').' *') !!}
					{!! Form::text('email', null, array('class' => 'form-control')) !!}
					</div>

					<div class="form-group">
					{!! Form::label('password', trans('employee.password')) !!}
					<input type="password" class="form-control" name="password" placeholder="Password">
					</div>

					<div class="form-group">
					{!! Form::label('password_confirmation', trans('employee.confirm_password')) !!}
					<input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password">
					</div>
					<div class="form-group">
						{!! Form::label('size', trans('Estado')) !!}
						<select  name="id_state" class="form-control">
							@foreach($state_cellar as $value)
							<option value="{!!$value->id!!}"  {{ ($value->id == $employee->user_state) ?  'selected="selected"' : '' }}>{!!$value->name!!}</option>
							@endforeach
						</select>
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
