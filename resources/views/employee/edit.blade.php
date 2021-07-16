@extends('layouts/default')

@section('title',trans('employee.update_employee'))
@section('page_parent',trans('Accesos'))
@section('header_styles')
<link href="{{ asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet"  />
<link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet"  />
{{-- CHECKBOX STYLE --}}
<link rel="stylesheet" href="{{ asset('assets/vendors/checklist/chklist.css') }}">
{{-- wizard --}}
<link href="{{ asset('assets/css/pages/wizard.css') }}" rel="stylesheet">
{{-- select2 --}}
<link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" />
<!-- Validaciones -->
<link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" />
@stop
@section('content')
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">
						<i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
						{{trans('employee.update_employee')}}
					</h3>
					<span class="pull-right clickable">
						<i class="glyphicon glyphicon-chevron-up"></i>
					</span>
				</div>
				<div class="panel-body">
					
					{!! Form::model($employee, array('route' => array('employees.update', $employee->id),'id'=>'frmEmploye', 'method'=> 'PUT', 'files' => true)) !!}
					<input type="hidden" name="_token" value="{{ csrf_token() }}" />
					<input type="hidden" id="finish_route" name="finish_route" value="{{$finish_route}}">
					<div id="rootwizard">
						<ul>
							<li class="nav-item"><a href="#tab1" data-toggle="tab" class="nav-link">Datos básicos</a></li>
							<li class="nav-item"><a href="#tab2" data-toggle="tab" class="nav-link ml-2">Datos Personales</a></li>
							@if($finish_route=="0")
							<li class="nav-item"><a href="#tab3" data-toggle="tab" class="nav-link ml-2">Datos Laborales</a></li>
							@endif
						</ul>
						
						<div class="tab-content">
							<div class="tab-pane " id="tab1">
								{{-- <h2 class="hidden">&nbsp;</h2> --}}
								<p class="text-danger">*Requeridos</p><br>
								<div class="row">
									<div class="col-sm-2"><label for="name" class="control-label">{{trans('employee.name')}} </label><span class="text-danger">*</span></div>
									<div class="col-lg-4">
										<div class="form-group">
											<input id="name" name="name" type="text" placeholder="Nombres" class="form-control required" value="{!! old('name',$employee->name) !!}"/>
											{!! $errors->first('name', '<span class="help-block">:message</span>') !!}
										</div>
									</div>
									<div class="col-sm-2"><label for="lblLastname" class="control-label">{{trans('employee.last_name')}} </label><span class="text-danger">*</span></div>
									<div class="col-lg-4">
										<div class="form-group">
											{!! Form::text('last_name', Input::old('last_name',$employee->last_name), array('class' => 'form-control','placeholder'=>'Apellidos')) !!}
											{!! $errors->first('last_name', '<span class="help-block">:message</span>') !!}
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-2"><label for="email" class="control-label">{{trans('employee.email')}} </label><span class="text-danger">*</span></div>
									<div class="col-lg-4">
										<div class="form-group">
											{!! Form::text('email', Input::old('email',$employee->email), array('class' => 'form-control','placeholder'=>'Email')) !!}
											{!! $errors->first('email', '<span class="help-block">:message</span>') !!}
										</div>
									</div>
									<div class="col-sm-2"><label for="lblMobile" class="control-label">{{trans('employee.mobile')}} </label><span class="text-danger">*</span></div>
									<div class="col-lg-4">
										<div class="form-group">
											{!! Form::text('mobile', Input::old('mobile',$employee->mobile), array('class' => 'form-control', 'maxlength'=>'8','placeholder'=>'Teléfono')) !!}
											{!! $errors->first('mobile', '<span class="help-block">:message</span>') !!}
										</div>
									</div>
								</div>
								@if($employee->password) <p class="text-warning">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Si no desea cambiar la contraseña... por favor dejela en blanco.</p>  
								@else <p class="text-warning">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nota: Sin acceso al sistema, debe ingresar contraseña para poder acceder.</p> @endif
								<div class="row">									
										<div class="col-sm-2"><label for="password" class="control-label">{{trans('employee.password')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												<input type="password" class="form-control" id="password" name="password" placeholder="Password">
											</div>
										</div>
										<div class="col-sm-2"><label for="password_confirmation" class="control-label">{{trans('employee.confirm_password')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												<input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password">
											</div>
										</div>										
									</div>
									<div class="row">
										<div class="col-sm-2"><label for="state" class="control-label">{{trans('employee.state')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												{{-- {!! Form::select('id_state', $state_cellar, Input::old('id_state'), array('class' => 'form-control','id'=>'id_state')) !!} --}}												
												<select class="form-control" title="" name="id_state" id="id_state" required>													
													@foreach($state_cellar as $id =>$name)
													<option value="{{$id}}" @if(intval($employee->user_state) ===intval($id)) selected="selected" @endif>{{ $name}}</option>
													@endforeach													
												</select>
											</div>
										</div>
										<div class="col-sm-2"><label for="lblNumber" class="control-label">{{trans('employee.number')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												{!! Form::text('number', Input::old('number',$employee->number), array('class' => 'form-control','placeholder'=>'No. de empleado')) !!}
												{!! $errors->first('number', '<span class="help-block">:message</span>') !!}
											</div>												
										</div>
									</div>
									<div class="row">
										<div class="col-sm-2"><label for="lblcoments" class="control-label">{{trans('employee.comments')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">										
												{!! Form::textarea('comments', Input::old('comments',$employee->comments), array('class' => 'form-control','rows' => 2, 'cols' => 8,'placeholder'=>'Info. adicional')) !!}
											</div>
										</div>
										<div class="col-sm-2"><label for="lblAvatar" class="control-label">{{trans('employee.avatar')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-2">
											<div class="form-group">
												<br>
												{!! Form::file('avatar', Input::old('avatar'), array('class' => 'form-control')) !!}
											</div>
										</div>
										<div class="col-lg-2">
											<div class="form-group">
												<img class="img_modal" src="{!! asset('images/users/') . '/' . $employee->avatar !!}" style="width:auto;height:auto;">
											</div>
										</div>
									</div>
									<div class="row">
										
									</div>
								</div>{{-- tab 1 --}}
								
								<div class="tab-pane" id="tab2" disabled="disabled">
									
									<div class="row">
										<div class="col-sm-2"><label for="lblNumber" class="control-label">{{trans('employee.birthdate')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												{!! Form::text('birthdate', $employee->birthdate, array('class' => 'form-control date','id'=>'birthdate')) !!}
											</div>
										</div>
										<div class="col-sm-2"><label for="lblDPI" class="control-label">{{trans('employee.dpi')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												{!! Form::text('dpi', Input::old('dpi',$employee->DPI), array('class' => 'form-control', 'maxlength'=>'13')) !!}
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-2"><label for="lbladdress" class="control-label">{{trans('employee.address')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												{!! Form::text('address', Input::old('address',$employee->address), array('class' => 'form-control')) !!}
											</div>
										</div>
										<div class="col-sm-2"><label for="lblalternativeaddress" class="control-label">{{trans('employee.alt_address')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												{!! Form::text('alternative_address', Input::old('alternative_address',$employee->alternative_address), array('class' => 'form-control')) !!}
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-2"><label for="lblNationality" class="control-label">{{trans('employee.nationality')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												{!! Form::text('nationality', Input::old('nationality',$employee->nationality), array('class' => 'form-control')) !!}
											</div>
										</div>
										<div class="col-sm-2"><label for="lblphone" class="control-label">{{trans('employee.phone')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												{!! Form::text('phone', Input::old('phone',$employee->phone), array('class' => 'form-control', 'maxlength'=>'8')) !!}
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-2"><label for="lblEm_name" class="control-label">{{trans('employee.em_name')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												{!! Form::text('emergency_name', Input::old('emergency_name',$employee->emergency_name), array('class' => 'form-control')) !!}
											</div>
										</div>
										<div class="col-sm-2"><label for="lblEm_phone" class="control-label">{{trans('employee.em_phone')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												{!! Form::text('emergency_phone', Input::old('emergency_phone',$employee->emergency_phone), array('class' => 'form-control', 'maxlength'=>'8')) !!}
											</div>
										</div>
									</div>
								</div>{{-- tab 2 --}}
								@if($finish_route=="0")
								<div class="tab-pane" id="tab3" disabled="disabled">
									
									<div class="row">
										<div class="col-sm-2"><label for="lbldate_hire" class="control-label">{{trans('employee.date_hire')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												{!! Form::text('date_hire', $employee->date_hire, array('class' => 'form-control date','id'=>'date_hire')) !!}
											</div>
										</div>
										<div class="col-sm-2"><label for="lbldate_dimissal" class="control-label">{{trans('employee.date_dimissal')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												{!! Form::text('date_dimissal', $employee->date_dimissal, array('class' => 'form-control date','id'=>'date_dimissal')) !!}
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-2"><label for="lbligss" class="control-label">{{trans('employee.igss')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												{!! Form::text('no_IGSS', Input::old('no_IGSS',$employee->no_IGSS), array('class' => 'form-control')) !!}
											</div>											
										</div>
										<div class="col-sm-2"><label for="lblshoe" class="control-label">{{trans('employee.shoes')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												{!! Form::text('shoe_size', Input::old('shoe_size',$employee->shoe_size), array('class' => 'form-control')) !!}
											</div>
										</div>
									</div>
									
									
									<div class="row">
										<div class="col-sm-2"><label for="lbltrouser" class="control-label">{{trans('employee.trouser')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												{!! Form::text('trouser_size', Input::old('trouser_size',$employee->trouser_size), array('class' => 'form-control')) !!}
											</div>
										</div>
										<div class="col-sm-2"><label for="lblshirt" class="control-label">{{trans('employee.shirt')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												{!! Form::text('shirt_size', Input::old('shirt_size',$employee->shirt_size), array('class' => 'form-control')) !!}
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-2"><label for="lbltrouser" class="control-label">{{trans('employee.sales_goal')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												{!! Form::number('sales_goal', Input::old('sales_goal',$employee->sales_goal), array('class' => 'form-control','step'=>'0.01')) !!}
											</div>
										</div>
										<div class="col-sm-2"><label for="lblshirt" class="control-label">{{trans('employee.collection_goal')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												{!! Form::number('collection_goal', Input::old('collection_goal',$employee->collection_goal), array('class' => 'form-control','step'=>'0.01')) !!}
											</div>
										</div>
										
									</div>
									<div class="row">
										<div class="col-sm-2"><label for="lblshirt" class="control-label">{{trans('employee.expenses_max')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												{!! Form::number('expenses_max', Input::old('expenses_max',$employee->expenses_max), array('class' => 'form-control','step'=>'0.01')) !!}
											</div>
										</div>
										<div class="col-sm-2">{!! Form::label('name', trans('Seleccione el rol para el usuario')) !!}<span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												<label for="roles">
													<span class="btn btn-info btn-xs select-all">{{ trans('general.select_all') }}</span>
													<span class="btn btn-info btn-xs deselect-all">{{ trans('general.deselect_all') }}</span></label>
													<select name="roles[]" id="roles" class="form-control select2" multiple="multiple" required>
														<option value="">Seleccionar rol</option>
														@foreach($dataRoles as $value)
														<option value="{{$value->id}}" {{ (in_array($value->id, old('roles', [])) || isset($employee) && $employee->roles->contains($value->id)) ? 'selected' : '' }}>{{$value->role}}</option>
														@endforeach
													</select>
											</div>
										</div>
									</div>
								</div>{{-- tab 3 --}}
								@endif
								<ul class="pager wizard">
									<li class="previous"><a href="#">Anterior</a></li>                                    
									<li class="next"><a href="#">Siguiente</a></li>
									<li class="next finish" style="display:none;"><a class="btn-success" href="javascript:;">Guardar</a></li>
								</ul>
							</div>{{-- tab-content --}}
							
							
							{{-- @include('partials.buttons',['cancel_url'=>"/employees"]) --}}
							{!! Form::close() !!}
						</div>
					</div>
				</div>
			</div>
		</section>
		@endsection
		@section('footer_scripts')
		{{-- wizard --}}
		<script src="{{ asset('assets/vendors/bootstrapwizard/jquery.bootstrap.wizard.js') }}" type="text/javascript"></script>
		
		<script src="{{ asset('assets/vendors/moment/js/moment.min.js') }} " type="text/javascript "></script>
		<script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }} " type="text/javascript "></script>
		<script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }} " type="text/javascript "></script>
		<!-- Tabs -->
		<script src="{{ asset('js/pages/tabs_accordions.js') }} " type="text/javascript "></script>
		<!-- select2 -->
		<script language="javascript" type="text/javascript" src="{{ asset('assets/vendors/select2/js/select2.js') }}"></script>
		<!-- Valiadaciones -->
		<script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
		<script type="text/javascript">
			function evalaccess(control){
				if (control.checked) {
					document.getElementById('access').style.display="inline";
				}
				else{
					document.getElementById('access').style.display="none";
				}
			}
			$(document).ready(function(){
				$('.select-all').click(function () {
					let $select2 = $(this).parent().siblings('.select2')
					$select2.find('option').prop('selected', 'selected')
					$select2.trigger('change')
				});
				$('.deselect-all').click(function () {
					let $select2 = $(this).parent().siblings('.select2')
					$select2.find('option').prop('selected', '')
					$select2.trigger('change')
				});
				$('.select2').select2();
				$('#frmEmploye').bootstrapValidator({
					feedbackIcons: {
						valid: 'glyphicon glyphicon-ok',
						invalid: 'glyphicon glyphicon-remove',
						validating: 'glyphicon glyphicon-refresh'
					},
					message: 'Valor no valido',
					fields:{
						/* Requeridos */
						name:{
							validators:{
								notEmpty:{
									message:'Debe ingresar el nombre del empleado. '
								},
								stringLength:{
									min:3,
									max:50,
									message:'El nombre debe tener al menos 3 caractéres.'
								}
							},
							required:true
						},
						last_name:{
							validators:{
								notEmpty:{
									message:'Debe ingresar el apellido del empleado.'
								},
								stringLength:{
									min:2,
									max:50,
									message:'El Apellido debe tener al menos 3 caractéres.'
								}
							},
							required:true
						},
						email: {
							validators: {
								notEmpty:{
									message:'Debe ingresar correo electrónico del negocio.'
								},
								emailAddress: {
									message: 'No se ha ingresado un Email válido'
								}
							},
							required:true
						},
						mobile:{
							validators:{
								notEmpty:{
									message:'Debe ingresar celular del empleado.'
								},
								regexp:{
									regexp: /^[0-9]*$/,
									message: 'Ingrese un teléfono válido'
								},
								stringLength:{
									min:8,
									max:8,
									message:'El teléfono debe tener 8 dígitos.'
								}
							},
							required:true
						},
						password: {
							validators: {
								identical: {
									field: 'password_confirmation',
									message: 'La contraseña y su confirmación deben ser iguales.'
								}
							}
						},
						password_confirmation: {
							validators: {
								identical: {
									field: 'password',
									message: 'La contraseña y su confirmación deben ser iguales.'
								},
								// callback: {
								// 	message: 'La contraseña y su confirmación deben ser iguales.',
								// 	callback: function (value,validator,$field) {
								// 		var pass = $('#password').val();
								// 		console.log('valor '+value+' conf '+pass);                
								// 		if (value!=pass) {
								// 			return false;
								// 		}
								// 		$('#frmEmploye').bootstrapValidator('revalidateField', 'password');
								// 		return true;
								// 	}
								// }
							}
						},
						id_rol:{
							validators:{
								notEmpty:{
									message:'Debe seleccionar el rol.'
								},
							}
						},
						address:{
							validators:{
								stringLength: {
									min: 5,
									max: 256,
									message: 'Este campo debe contener 4 caracteres como mínimo'
								}
							},
							required: false,
							minlength: 5
						},
						birthdate:{
							validators:{
								callback:
								{
									message: 'Debe seleccionar una fecha válida.',
									callback: function(value, validator, $field)
									{
										console.log(value);
										if (value !== '') {
											return true;
										}
										else {
											return false;
										}
									}
								}
							},
							required: false,
						},
						date_hire:{
							validators:{
								callback:
								{
									message: 'Debe seleccionar una fecha válida.',
									callback: function(value, validator, $field)
									{
										if (value !== '')
										{
											return true;
										}
										else{
											return false;
										}
									}
								}
							},
							required: false,
						},
						phone:{
							validators:{
								regexp:{
									regexp: /^[0-9/?a-z?A-Z?]+$/,
									message: 'El campo teléfono solo puede contener números y diagonales.'
								},
								stringLength:{
									min:8,
									max:50,
									message:'El teléfono debe tener 8 dígitos.'
								}
							},
							required: false
						},
						emergency_name:{
							validators:{
								stringLength: {
									min: 5,
									max: 256,
									message: 'El nombre del contacto de emergencia debe tener al menos 5 caractéres.'
								}
							},
							required: false,
							minlength: 5
						},
						
						emergency_phone:{
							validators:{
								regexp:{
									regexp: /^[0-9/?a-z?A-Z?]+$/,
									message: 'El campo teléfono solo puede contener números y diagonales.'
								},
								stringLength:{
									min:8,
									max:8,
									message:'El teléfono debe tener 8 dígitos.'
								}
							},
							required: false
						},
						number:{
							validators:{
								regexp:{
									regexp: /^[0-9]*$/,
									message: 'Ingrese un número válido'
								},
								stringLength:{
									min:1,
									max:10,
									message:'El número debe tener al menos 1 dígito.'
								}
							},
							required: false
						},
						dpi:{
							validators:{
								regexp:{
									regexp: /^(\d+)+\d+$/,
									message: 'El CUI solo puede contener números.'
								},
								stringLength:{
									min:13,
									max:13,
									message:'El número debe tener 13 dígitos.'
								}
							},
							required: false,
							minlength: 13
						},
						sales_goal:{
							validators:{
								between: {
									min: 0,
									max: 9999999999,
									message: 'Debe ingresar al menos Q 1'
								},
								regexp: {
									regexp: /^[0-9-.?]+$/,
									message: 'Solo puede contener dígitos'
								},
							},
							required: false
						},
						collection_goal:{
							validators:{
								between: {
									min: 0,
									max: 9999999999,
									message: 'Debe ingresar al menos Q 1'
								},
								regexp: {
									regexp: /^[0-9-.?]+$/,
									message: 'Solo puede contener dígitos'
								},
							},
							required: false
						},
						expenses_max:{
							validators:{
								between: {
									min: 0,
									max: 9999999999,
									message: 'Debe ingresar al menos Q 1'
								},
								regexp: {
									regexp: /^[0-9-.?]+$/,
									message: 'Solo puede contener dígitos'
								},
							},
							required: false
						}
						
						
					}
				}).
				on('change', '[name="birthdate"]', function() { 
					$('#frmEmploye').bootstrapValidator('revalidateField', 'birthdate');
				}).
				on('change', '[name="date_hire"]', function() { 
					$('#frmEmploye').bootstrapValidator('revalidateField', 'date_hire');
				}).
				on('change blur', '[name="password"]', function() { 
					$('#frmEmploye').bootstrapValidator('revalidateField', 'password_confirmation');
					$('#frmEmploye').bootstrapValidator('revalidateField', 'password');
				}).
				on('change blur', '[name="password_confirmation"]', function() { 
					$('#frmEmploye').bootstrapValidator('revalidateField', 'password_confirmation');
					$('#frmEmploye').bootstrapValidator('revalidateField', 'password');
				})
				;
				$('#rootwizard').bootstrapWizard({
					'tabClass': 'nav nav-pills',
					'onNext': function(tab, navigation, index) {
						var $validator = $('#frmEmploye').data('bootstrapValidator').validate();
						return $validator.isValid();
					},
					onTabClick: function(tab, navigation, index) {
						return false;
					},
					onTabShow: function(tab, navigation, index) {
						var $total = navigation.find('li').length;
						var $current = index + 1;
						
						// If it's the last tab then hide the last button and show the finish instead
						if ($current >= $total) {
							$('#rootwizard').find('.pager .next').hide();
							$('#rootwizard').find('.pager .finish').show();
							$('#rootwizard').find('.pager .finish').removeClass('disabled');
						} else {
							$('#rootwizard').find('.pager .next').show();
							$('#rootwizard').find('.pager .finish').hide();
						}
					}});
					
					$('#rootwizard .finish').click(function () {
						var $validator = $('#frmEmploye').data('bootstrapValidator').validate();
						if ($validator.isValid()) {
							document.getElementById("frmEmploye").submit();
						}					
					});				
				});
				
			</script>
			<script type="text/javascript">
				$(document).ready(function(){
					
					
					$("#birthdate").datetimepicker({
						locale:'es',
						defaultDate:new Date($("#birthdate").val()),
						minDate: new Date(1900, 01-1, 01),					
						// maxDate: maxBirthdayDate,
						format:'DD/MM/YYYY',
					}).parent().css("position :relative ");

					$("#date_hire").datetimepicker({
						locale:'es',
						defaultDate:{{$employee->date_hire}},
						minDate: new Date(1900, 01-1, 01),					
						// maxDate: maxBirthdayDate,
						format:'DD/MM/YYYY',
					}).parent().css("position :relative ");
					$("#date_dimissal").datetimepicker({
						locale:'es',
						defaultDate:{{$employee->date_dimissal}},
						minDate: new Date(1900, 01-1, 01),					
						// maxDate: maxBirthdayDate,
						format:'DD/MM/YYYY',
					}).parent().css("position :relative ");
				});
			</script>
			@stop
			