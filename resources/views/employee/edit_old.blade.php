@extends('layouts/default')
@section('title',trans('employee.update_employee'))
@section('page_parent',trans('Accesos'))

@section('header_styles')
<link href="{{ asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css"/>
<!-- Tabs -->
<link href="{{ asset('css/pages/tab.css') }}" rel="stylesheet" type="text/css" />
<!-- Validaciones -->
<!-- Validaciones -->
<link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css" /> {{-- CHECKBOX STYLE --}}
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/checklist/chklist.css') }}">
@stop
@section('content')
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">
						<i class="livicon" data-name="edit" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
						{{trans('employee.update_employee')}}
					</h3>
					<span class="pull-right clickable">
						<i class="glyphicon glyphicon-chevron-up"></i>
					</span>
				</div>
				<input type="hidden" id="access" name="access" value="{{$employee->password}}">
				<div class="panel-body">
						
					{!! Form::model($employee, array('route' => array('employees.update', $employee->id),'id'=>'frmEmploye', 'method'
					=> 'PUT', 'files' => true)) !!}
					<input type="hidden" id="finish_route" name="finish_route" value="{{$finish_route}}">
					<div class="panel-body">
						<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
							<div class="panel panel-info">
								<div class="panel-heading" role="tab" id="headingOne" >
									<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
										<h4 class="panel-title"  style="color:white !important">Datos básicos</h4>
									</a>
								</div>
								<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
									<div class="panel-body">
										<div class="row">
											<div class="col-lg-6">
												<div class="form-group">
													{!! Form::label('name', trans('employee.name').' *') !!}
													{!! Form::text('name', Input::old('name'), array('class' => 'form-control')) !!}
													{!! $errors->first('name', '<span class="help-block">:message</span>') !!}
												</div>
											</div>
											<div class="col-lg-6">
												<div class="form-group">
													{!! Form::label('lblLastname', trans('employee.last_name').' *') !!}
													{!! Form::text('last_name', Input::old('last_name'), array('class' => 'form-control')) !!}
													{!! $errors->first('last_name', '<span class="help-block">:message</span>') !!}
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-6">
												<div class="form-group">
													{!! Form::label('email', trans('employee.email').' *') !!}
													{!! Form::text('email', Input::old('email'), array('class' => 'form-control')) !!}
													{!! $errors->first('email', '<span class="help-block">:message</span>') !!}
												</div>
											</div>
											<div class="col-lg-6">
												<div class="form-group">
													{!! Form::label('lblMobile', trans('employee.mobile').' *') !!}
													{!! Form::text('mobile', Input::old('mobile'), array('class' => 'form-control', 'maxlength'=>'8')) !!}
													{!! $errors->first('mobile', '<span class="help-block">:message</span>') !!}
												</div>
											</div>
										</div>
										<div class="form-group">
											<label>Acceso al sistema: </label>
											<label class="custom-control custom-checkbox">
												<input id="chk" type="checkbox" name="access_" class="custom-control-input" onchange="evalaccess(this)">
												<span class="custom-control-indicator"></span>
											</label>
										</div>
										<div class="row">
											<div id="access2" style="display:none">
												<div class="col-lg-6">
													<div class="form-group">
														{!! Form::label('password', trans('employee.password').' *') !!}
														<input type="password" class="form-control" name="password" placeholder="Password">
													</div>
												</div>
												<div class="col-lg-6">
													<div class="form-group">
														{!! Form::label('password_confirmation', trans('employee.confirm_password').' *') !!}
														<input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password">
													</div>
												</div>
											</div>											
										</div>
										<div class="row">
											<div class="col-lg-6">
												<div class="form-group">
													{!! Form::label('size', trans('Estado')) !!}
													{!! Form::select('user_state', $state_cellar, Input::old('user_state'), array('class' => 'form-control')) !!}
												</div>
											</div>
											<div class="col-lg-6">
												<div class="form-group">
													{!! Form::label('lblNumber', trans('employee.number')) !!}
													{!! Form::text('number', Input::old('number'), array('class' => 'form-control')) !!}
													{!! $errors->first('number', '<span class="help-block">:message</span>') !!}
												</div>												
											</div>
										</div>
										<div class="row">
											<div class="col-lg-6">
												<div class="form-group">
													{!! Form::label('lblcoments', trans('employee.comments')) !!}
													{!! Form::textarea('comments', Input::old('comments'), array('class' => 'form-control','rows' => 2, 'cols' => 10)) !!}
												</div>
											</div>
											<div class="col-lg-3">
												<div class="form-group">
													<br>
													{!! Form::label('lblAvatar', trans('employee.avatar')) !!}
													{!! Form::file('avatar', Input::old('avatar'), array('class' => 'form-control')) !!}																			
												</div>
											</div>
											<div class="col-lg-3">
												<div class="form-group">
													{!! Form::label('lblAvatar', 'Foto actual') !!} <br>
													<img class="img_modal" src="{!! asset('images/users/') . '/' . $employee->avatar !!}" style="width:auto;height:auto;">
												</div>
											</div>
										</div>
										<div class="row">
											
										</div>
									</div>
								</div>
							</div>
							<div class="panel panel-info">
								<div class="panel-heading" role="tab" id="headingTwo" >
									<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
										<h4 class="panel-title" style="color:white !important">Datos personales</h4>
									</a>
								</div>
								<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
									<div class="panel-body">
										<div class="row">
											<div class="col-lg-6">
												<div class="form-group">
													{!! Form::label('lblbirthdate', trans('employee.birthdate')) !!}
													{!! Form::text('birthdate', Input::old('birthdate'), array('class' => 'form-control date')) !!}
												</div>
											</div>
											<div class="col-lg-6">
												<div class="form-group">
													{!! Form::label('lblDPI', trans('employee.dpi')) !!}
													{!! Form::text('dpi', Input::old('dpi'), array('class' => 'form-control', 'maxlength'=>'13')) !!}
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-6">
												<div class="form-group">
													{!! Form::label('lbladdress', trans('employee.address')) !!}
													{!! Form::text('address', Input::old('address'), array('class' => 'form-control')) !!}
												</div>
											</div>
											<div class="col-lg-6">
												<div class="form-group">
													{!! Form::label('lblalternativeaddress', trans('employee.alt_address')) !!}
													{!! Form::text('alternative_address', Input::old('alternative_address'), array('class' => 'form-control')) !!}
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-6">
												<div class="form-group">
													{!! Form::label('lblNationality', trans('employee.nationality')) !!}
													{!! Form::text('nationality', Input::old('nationality'), array('class' => 'form-control')) !!}
												</div>
											</div>
											<div class="col-lg-6">
												<div class="form-group">
													{!! Form::label('lblphone', trans('employee.phone')) !!}
													{!! Form::text('phone', Input::old('phone'), array('class' => 'form-control', 'maxlength'=>'8')) !!}
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-6">
												<div class="form-group">
													{!! Form::label('lblEm_name', trans('employee.em_name')) !!}
													{!! Form::text('emergency_name', Input::old('emergency_name'), array('class' => 'form-control')) !!}
												</div>
											</div>
											<div class="col-lg-6">
												<div class="form-group">
													{!! Form::label('lblEm_phone', trans('employee.em_phone')) !!}
													{!! Form::text('emergency_phone', Input::old('emergency_phone'), array('class' => 'form-control', 'maxlength'=>'8')) !!}
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="panel panel-info">
								<div class="panel-heading" role="tab" id="headingThree"  >
									<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
										<h4 class="panel-title" style="color:white !important"> Datos laborales</h4>
									</a>
								</div>
								<div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
									<div class="panel-body">
										<div class="row">
											<div class="col-lg-6">
												<div class="form-group">
													{!! Form::label('lbldate_hire', trans('employee.date_hire')) !!}
													{!! Form::text('date_hire', Input::old('date_hire'), array('class' => 'form-control date')) !!}
												</div>
											</div>
											<div class="col-lg-6">
												<div class="form-group">
													{!! Form::label('lbldate_dimissal', trans('employee.date_dimissal')) !!}
													{!! Form::text('date_dimissal', Input::old('date_dimissal'), array('class' => 'form-control date')) !!}
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-6">
												<div class="form-group">
													{!! Form::label('lbligss', trans('employee.igss')) !!}
													{!! Form::text('igss', Input::old('igss'), array('class' => 'form-control')) !!}
												</div>											
											</div>
											<div class="col-lg-6">
												<div class="form-group">
													{!! Form::label('lblshoe', trans('employee.shoes')) !!}
													{!! Form::text('shoe_size', Input::old('shoe_size'), array('class' => 'form-control')) !!}
												</div>
											</div>
										</div>
										
										<div class="row">
											<div class="col-lg-6">
												<div class="form-group">
													{!! Form::label('lbltrouser', trans('employee.trouser')) !!}
													{!! Form::text('trouser_size', Input::old('trouser_size'), array('class' => 'form-control')) !!}
												</div>
											</div>
											<div class="col-lg-6">
												<div class="form-group">
													{!! Form::label('lblshirt', trans('employee.shirt')) !!}
													{!! Form::text('shirt_size', Input::old('shirt_size'), array('class' => 'form-control')) !!}
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>	
					
					@include('partials.buttons_back')
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
</section>
@endsection

@section('footer_scripts') {{-- Calendario --}}
<script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
<!-- Tabs -->
<script src="{{ asset('js/pages/tabs_accordions.js') }} " type="text/javascript "></script>
<!-- Valiadaciones -->
<script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
<script type="text/javascript">
	function evalaccess(control){
		if (control.checked) {
			document.getElementById('access2').style.display="inline";
		}
		else{
			document.getElementById('access2').style.display="none";
		}
	}
	$(document).ready(function(){
		$(".dates").datetimepicker({
			locale:'es',
			defaultDate:new Date(2000, 10 - 1, 25),
			minDate: new Date(1900, 10 - 1, 25),
			format:'DD/MM/YYYY',
		}).parent().css("position :relative ");
		if ($('#pass_').val()=="1") {
			$('#chk').attr('checked', true);
			document.getElementById('access2').style.display="inline";
		}
		else{
			document.getElementById('access2').style.display="none";
		}
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
						}
					},
					required:true
				},
				last_name:{
					validators:{
						notEmpty:{
							message:'Debe ingresar el apellido del empleado.'
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
				address:{
					validators:{
						// notEmpty:{
							// 	message:'Debe ingresar dirección del empleado.'
							// },
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
									if (value !== '25/10/1990') {
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
									if (value !== '25/10/1990')
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
							// notEmpty:{
								// 	message:'Debe ingresar teléfono del empleado.'
								// },
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
						
						emergency_phone:{
							validators:{
								// notEmpty:{
									// 	message:'Debe ingresar teléfono de emergencia del empleado.'
									// },
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
									// notEmpty:{
										// 	message:'Debe ingresar DPI del empleado.'
										// },
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
								
								
							}
						});
					});
					$("#birthdate").datetimepicker({ sideBySide: true, locale:'es',format:'DD/MM/YYYY'}).parent().css("position :relative");
					$("#date_hire").datetimepicker({ sideBySide: true, locale:'es',format:'DD/MM/YYYY'}).parent().css("position :relative");
					$("#date_dimissal").datetimepicker({ sideBySide: true, locale:'es',format:'DD/MM/YYYY'}).parent().css("position :relative");
					
				</script>
				@stop
				