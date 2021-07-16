@extends('layouts/default')

@section('title',trans('Nuevo Rol'))
@section('page_parent',trans('Accesos'))
@section('header_styles')
<!-- Validaciones -->
<link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css" /> 
<link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" />
@stop
@section('content')
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">
						<i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
						Nuevo rol
					</h3>
					<span class="pull-right clickable">
						<i class="glyphicon glyphicon-chevron-up"></i>
					</span>
				</div>
				<div class="panel-body">
					
					<form id="frmRol" action='{{url('roles')}}' method="post">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<div class="col-md-10 col-md-offset-1">
							<div class="form-group">
								{!! Form::label('name2', trans('Nombre del rol').' *') !!}
								<input type="text" id="role" name="role" class="form-control" value="{{Input::old('role')}}">
							</div>
						</div>
						<div class="col-md-10 col-md-offset-1">
							<div class="form-group {{ $errors->has('permissions') ? 'has-error' : '' }}">
								<label for="permissions">{{ trans('users.permissions') }}*
									<span class="btn btn-info btn-xs select-all">{{ trans('general.select_all') }}</span>
									<span class="btn btn-info btn-xs deselect-all">{{ trans('general.deselect_all') }}</span></label>
									<select name="permissions[]" id="permissions" class="form-control select2" multiple="multiple" required>
										@foreach($permissions as $id => $description)
										<option value="{{ $id }}" {{ (in_array($id, old('permissions', [])) || isset($roles) && $roles->permissions->contains($id)) ? 'selected' : '' }}>{{ $description }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-md-10 col-md-offset-1">
								<div class="form-group">
									{!! Form::label('admin', trans('users.is_admin')) !!}
									<select class="form-control" title="" name="is_admin" id="is_admin" required>
										<option value="0" @if(old('is_admin') === '0') selected="selected" @endif>No</option>
										<option value="1" @if(old('is_admin') === '1') selected="selected" @endif>Si</option>
									</select>
								</div>
							</div>
							@include('partials.buttons',['cancel_url'=>"/roles"])
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
	@endsection
	@section('footer_scripts')
	<script language="javascript" type="text/javascript" src="{{ asset('assets/vendors/select2/js/select2.js') }}"></script>
	<!-- Valiadaciones -->
	<script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('.select-all').click(function () {
				let $select2 = $(this).parent().siblings('.select2')
				$select2.find('option').prop('selected', 'selected')
				$select2.trigger('change')
			})
			$('.deselect-all').click(function () {
				let $select2 = $(this).parent().siblings('.select2')
				$select2.find('option').prop('selected', '')
				$select2.trigger('change')
			})
			$('.select2').select2()
			// validaciones
			$('#frmRol').bootstrapValidator({
				feedbackIcons: {
					valid: 'glyphicon glyphicon-ok',
					invalid: 'glyphicon glyphicon-remove',
					validating: 'glyphicon glyphicon-refresh'
				},
				message: 'Valor no valido',
				fields:{
					name:{
						validators:{
							notEmpty:{
								message:'Debe ingresar el nombre del rol.'
							},
							stringLength:{
								min:3,
								message:'Debe ingrear por lo menos 3 caracteres.'
							}
						}
					}
				}
			});
		});
	</script>
	@stop
	