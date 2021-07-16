@extends('layouts/default')

@section('title',trans('employee.new_employee'))
@section('page_parent',trans('Accesos'))
@section('header_styles')
<link href="{{ asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet"  />
<link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet"  />
<!-- Tabs -->
{{-- <link href="{{ asset('css/pages/tab.css') }}" rel="stylesheet" type="text/css" /> --}}
<!-- Validaciones -->
<link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" />
{{-- CHECKBOX STYLE --}}
<link rel="stylesheet" href="{{ asset('assets/vendors/checklist/chklist.css') }}">
{{-- wizard --}}
<link href="{{ asset('assets/css/pages/wizard.css') }}" rel="stylesheet">
{{-- select2 --}}
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
						{{trans('employee.new_employee')}}
					</h3>
					<span class="pull-right clickable">
						<i class="glyphicon glyphicon-chevron-up"></i>
					</span>
				</div>
				<div class="panel-body">
					

					{!! Form::open(array('url' => 'employees', 'id'=>'frmEmploye', 'files'=>true,'autocomplete'=>"off")) !!}
					<input type="hidden" name="_token" value="{{ csrf_token() }}" />
					<div id="rootwizard">
						<ul>
							<li class="nav-item"><a href="#tab1" data-toggle="tab" class="nav-link">Datos básicos</a></li>
							<li class="nav-item"><a href="#tab2" data-toggle="tab" class="nav-link ml-2">Datos Personales</a></li>
							<li class="nav-item"><a href="#tab3" data-toggle="tab" class="nav-link ml-2">Datos Laborales</a></li>
						</ul>

						<div class="tab-content">
							<div class="tab-pane " id="tab1">
								{{-- <h2 class="hidden">&nbsp;</h2> --}}
								<p class="text-danger">*Requeridos</p><br>
								<div class="row">
									<div class="col-sm-2"><label for="name" class="control-label">{{trans('employee.name')}} </label><span class="text-danger">*</span></div>
									<div class="col-lg-4">
										<div class="form-group">
											<input id="name" name="name" type="text" placeholder="Nombres" class="form-control required" value="{!! old('name') !!}" autocomplete="off"/>
											{!! $errors->first('name', '<span class="help-block">:message</span>') !!}
										</div>
									</div>
									<div class="col-sm-2"><label for="lblLastname" class="control-label">{{trans('employee.last_name')}} </label><span class="text-danger">*</span></div>
									<div class="col-lg-4">
										<div class="form-group">
											{!! Form::text('last_name', Input::old('last_name'), array('class' => 'form-control','placeholder'=>'Apellidos')) !!}
											{!! $errors->first('last_name', '<span class="help-block">:message</span>') !!}
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-2"><label for="email" class="control-label">{{trans('employee.email')}} </label><span class="text-danger">*</span></div>
									<div class="col-lg-4">
										<div class="form-group">
											{!! Form::text('email', Input::old('email'), array('class' => 'form-control','placeholder'=>'Email')) !!}
											{!! $errors->first('email', '<span class="help-block">:message</span>') !!}
										</div>
									</div>
									<div class="col-sm-2"><label for="lblMobile" class="control-label">{{trans('employee.mobile')}} </label><span class="text-danger">*</span></div>
									<div class="col-lg-4">
										<div class="form-group">
											{!! Form::text('mobile', Input::old('mobile'), array('class' => 'form-control', 'maxlength'=>'8','placeholder'=>'Teléfono')) !!}
											{!! $errors->first('mobile', '<span class="help-block">:message</span>') !!}
										</div>
									</div>
								</div>
								{{-- <div class="form-group">
									<label>Acceso al sistema: </label>
									<label class="custom-control custom-checkbox">
										<input id="chk" type="checkbox" name="access_" class="custom-control-input" onchange="evalaccess(this)">
										<span class="custom-control-indicator"></span>
									</label>
								</div> --}}
								<div class="row">
									{{-- <div id="access" style="display:none"> --}}
										<div class="col-sm-2"><label for="password" class="control-label">{{trans('employee.password')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												<input type="password" class="form-control" name="password" placeholder="Password" autocomplete="new-password">
											</div>
										</div>
										<div class="col-sm-2"><label for="password_confirmation" class="control-label">{{trans('employee.confirm_password')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												<input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" autocomplete="off">
											</div>
										</div>
										{{-- </div>--}}
									</div>
									<div class="row">
										<div class="col-sm-2"><label for="state" class="control-label">{{trans('employee.state')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												{!! Form::select('id_state', $state_cellar, Input::old('id_state'), array('class' => 'form-control','id'=>'id_state')) !!}
											</div>
										</div>
										<div class="col-sm-2"><label for="lblNumber" class="control-label">{{trans('employee.number')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												{!! Form::text('number', $number, array('class' => 'form-control','placeholder'=>'No. de empleado')) !!}
												{!! $errors->first('number', '<span class="help-block">:message</span>') !!}
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-2"><label for="lblcoments" class="control-label">{{trans('employee.comments')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												{!! Form::textarea('comments', Input::old('comments'), array('class' => 'form-control','rows' => 2, 'cols' => 8,'placeholder'=>'Info. adicional')) !!}
											</div>
										</div>
										<div class="col-sm-2"><label for="lblAvatar" class="control-label">{{trans('employee.avatar')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												<br>
												{!! Form::file('avatar', Input::old('avatar'), array('class' => 'form-control')) !!}
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
												{!! Form::text('birthdate', Input::old('birthdate'), array('class' => 'form-control date','id'=>'birthdate')) !!}
											</div>
										</div>
										<div class="col-sm-2"><label for="lblDPI" class="control-label">{{trans('employee.dpi')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												{!! Form::text('dpi', Input::old('dpi'), array('class' => 'form-control', 'maxlength'=>'13')) !!}
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-2"><label for="lbladdress" class="control-label">{{trans('employee.address')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												{!! Form::text('address', Input::old('address'), array('class' => 'form-control')) !!}
											</div>
										</div>
										<div class="col-sm-2"><label for="lblalternativeaddress" class="control-label">{{trans('employee.alt_address')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												{!! Form::text('alternative_address', Input::old('alternative_address'), array('class' => 'form-control')) !!}
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-2"><label for="lblNationality" class="control-label">{{trans('employee.nationality')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												{!! Form::text('nationality', Input::old('nationality'), array('class' => 'form-control')) !!}
											</div>
										</div>
										<div class="col-sm-2"><label for="lblphone" class="control-label">{{trans('employee.phone')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												{!! Form::text('phone', Input::old('phone'), array('class' => 'form-control', 'maxlength'=>'8')) !!}
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-2"><label for="lblEm_name" class="control-label">{{trans('employee.em_name')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												{!! Form::text('emergency_name', Input::old('emergency_name'), array('class' => 'form-control')) !!}
											</div>
										</div>
										<div class="col-sm-2"><label for="lblEm_phone" class="control-label">{{trans('employee.em_phone')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												{!! Form::text('emergency_phone', Input::old('emergency_phone'), array('class' => 'form-control', 'maxlength'=>'8')) !!}
											</div>
										</div>
									</div>
								</div>{{-- tab 2 --}}

								<div class="tab-pane" id="tab3" disabled="disabled">

									<div class="row">
										<div class="col-sm-2"><label for="lbldate_hire" class="control-label">{{trans('employee.date_hire')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												{!! Form::text('date_hire', Input::old('date_hire'), array('class' => 'form-control date','id'=>'date_hire')) !!}
											</div>
										</div>
										<div class="col-sm-2"><label for="lbldate_dimissal" class="control-label">{{trans('employee.date_dimissal')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												{!! Form::text('date_dimissal', Input::old('date_dimissal'), array('class' => 'form-control date')) !!}
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-2"><label for="lbligss" class="control-label">{{trans('employee.igss')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												{!! Form::text('no_IGSS', Input::old('no_IGSS'), array('class' => 'form-control')) !!}
											</div>
										</div>
										<div class="col-sm-2"><label for="lblshoe" class="control-label">{{trans('employee.shoes')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												{!! Form::text('shoe_size', Input::old('shoe_size'), array('class' => 'form-control')) !!}
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-sm-2"><label for="lbltrouser" class="control-label">{{trans('employee.trouser')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												{!! Form::text('trouser_size', Input::old('trouser_size'), array('class' => 'form-control')) !!}
											</div>
										</div>
										<div class="col-sm-2"><label for="lblshirt" class="control-label">{{trans('employee.shirt')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												{!! Form::text('shirt_size', Input::old('shirt_size'), array('class' => 'form-control')) !!}
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-2"><label for="lbltrouser" class="control-label">{{trans('employee.sales_goal')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												{!! Form::number('sales_goal', Input::old('sales_goal'), array('class' => 'form-control')) !!}
											</div>
										</div>
										<div class="col-sm-2"><label for="lblshirt" class="control-label">{{trans('employee.collection_goal')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												{!! Form::number('collection_goal', Input::old('collection_goal'), array('class' => 'form-control')) !!}
											</div>
										</div>

									</div>
									<div class="row">
										<div class="col-sm-2"><label for="lblshirt" class="control-label">{{trans('employee.expenses_max')}} </label><span class="text-danger"></span></div>
										<div class="col-lg-4">
											<div class="form-group">
												{!! Form::number('expenses_max', Input::old('expenses_max'), array('class' => 'form-control')) !!}
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
													<option value="{{$value->id}}" >{{$value->role}}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
								</div>{{-- tab 3 --}}
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
		<script language="javascript" type="text/javascript" src="{{ asset('assets/vendors/select2/js/select2.js') }}"></script>
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
						//
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
						//

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
									message: 'Debe seleccionar una fecha de nacimiento válida.',
									callback: function(value, validator, $field)
									{
										// var tenYears = new Date();
										// tenYears.setTime(tenYears.valueOf() - 10 * 365 * 24 * 60 * 60 * 1000);
										// console.log(tenYears);
										// var fecha = new Date(value);
										// var fecha = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
										// fecha.setTime(fecha.valueOf() - 18 * 365 * 24 * 60 * 60 * 1000);
										// var fecha = new Date();
										// fecha.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
										// fecha.setFullYear(fecha.getFullYear() – 18);
										// console.log(fecha);									
										console.log(value);

										if (value !== ''){											
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
				on('change click blur', '[name="birthdate"]', function() {
					$('#frmEmploye').bootstrapValidator('revalidateField', 'birthdate');
				}).
				on('change click blur', '[name="date_hire"]', function() {
					$('#frmEmploye').bootstrapValidator('revalidateField', 'date_hire');
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
					var maxBirthdayDate = new Date();
					maxBirthdayDate.setFullYear( maxBirthdayDate.getFullYear() - 18 );
					/*Se establece una fecha de nacimiento máxima 18 años a partir de hoy para
					no permitir menores de edad*/
					$("#birthdate").datetimepicker({
						locale:'es',
						defaultDate:'',
						minDate: new Date(1900, 01-1, 01),					
						maxDate: maxBirthdayDate,
						format:'DD/MM/YYYY',
					}).parent().css("position :relative ");

					$("#date_hire").datetimepicker({
						locale:'es',
						// defaultDate:new Date(1900, 01-1, 01),
						minDate: new Date(1900, 01-1, 01),					
						// maxDate: maxBirthdayDate,
						format:'DD/MM/YYYY',
					}).parent().css("position :relative ");
					$("#date_dimissal").datetimepicker({
						locale:'es',
						// defaultDate:new Date(1900, 01-1, 01),
						minDate: new Date(1900, 01-1, 01),					
						// maxDate: maxBirthdayDate,
						format:'DD/MM/YYYY',
					}).parent().css("position :relative ");
				});
			</script>
			@stop
