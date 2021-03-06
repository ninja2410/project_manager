@extends('layouts/default')

@section('title',trans('customer.new_customer'))
@section('page_parent',trans('customer.customers'))
@section('header_styles')
<link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" />

<link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet"
	type="text/css" />
<link href="{{asset('assets/css/radios.css')}}" rel="stylesheet" type="text/css" />
{{-- wizard --}}
<link href="{{ asset('assets/css/pages/wizard.css') }}" rel="stylesheet">

{{-- <link href="{{asset('assets/vendors/wizard/jquery-steps/css/jquery.steps.css')}}" rel="stylesheet" > --}}
@stop
@section('content')
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">
						<i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff"
							data-loop="true"></i>
						{{trans('customer.new_customer')}}
					</h3>
					<span class="pull-right clickable">
						<i class="glyphicon glyphicon-chevron-up"></i>
					</span>
				</div>
				<div class="panel-body">
					
					{!! Form::open(array('url' => 'customers', 'id'=>'newCustomer', 'files' =>
					true,'class'=>'form-wizard')) !!}
					<input type="hidden" name="_token" value="{{ csrf_token() }}" />					
					<input type="hidden" name="ruta_requerida" id="ruta_requerida" value="{{$requerido}}"/>
					<div id="rootwizard">
						<ul>
							<li class="nav-item"><a href="#tab1" data-toggle="tab" class="nav-link">Datos básicos</a>
							</li>
							<li class="nav-item"><a href="#tab2" data-toggle="tab" class="nav-link ml-2">Datos Adicionales</a></li>							
						</ul>
						<div class="tab-content">
							<div class="tab-pane " id="tab1">
								{{-- <h2 class="hidden">&nbsp;</h2> --}}
								<p class="text-danger">*Requeridos</p><br>
								<div class="row">
									<div class="col-sm-2"><label for="nit_customer" class="control-label">Nit
										</label><span class="text-danger">*</span></div>
									<div class="col-sm-4">
										<div class="form-group {{ $errors->first('nit_customer', 'has-error') }}">                                                                                            
											<input id="nit_customer" name="nit_customer" type="text" placeholder="Nit" class="form-control required" value="{!! old('nit_customer','C/F') !!}"/>
											{!! $errors->first('nit_customer', '<span class="help-block">:message</span>') !!}
										</div>

									</div>
									<div class="col-sm-2"><label for="name" class="control-label">Nombre </label><span
											class="text-danger">*</span></div>
									<div class="col-sm-4">
										<div class="form-group {{ $errors->first('name', 'has-error') }}">
											<input id="name" name="name" type="text" placeholder="Nombre"
												class="form-control required" value="{!! old('name') !!}" />
											{!! $errors->first('name', '<span class="help-block">:message</span>') !!}
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-2"><label for="address" class="control-label">Dirección
										</label><span class="text-danger">*</span></div>
									<div class="col-sm-4">
										<div class="form-group {{ $errors->first('address', 'has-error') }}">
											<input id="address" name="address" placeholder="Dirección" type="text"
												class="form-control required" value="{!! old('address') !!}" />
											{!! $errors->first('address', '<span class="help-block">:message</span>')
											!!}
										</div>
									</div>
									<div class="col-sm-2"><label for="customer_code" class="control-label">{{trans('customer.customer_code')}} </label><span class="text-danger"></span></div>
									<div class="col-sm-4">
										<div class="form-group {{ $errors->first('customer_code', 'has-error') }}">
											<input id="customer_code" name="customer_code" placeholder="Código cliente"
												type="text" class="form-control" value="{!! old('customer_code',$codigo) !!}" />
											{!! $errors->first('customer_code', '<span
												class="help-block">:message</span>') !!}
										</div>
									</div>

								</div>
								<div class="row">
									<div class="col-sm-2"><label for="max_credit_amount"
											class="control-label">Crédito?</label><span class="text-danger">*</span>
									</div>
									<div class="col-sm-4">
										<div class="form-group {{ $errors->first('max_credit_amount', 'has-error') }}">
											<input id="max_credit_amount" name="max_credit_amount"
												placeholder="Monto máximo de crédito" type="text"
												class="form-control required"
												value="{!! old('max_credit_amount',0) !!}" />
											{!! $errors->first('max_credit_amount', '<span
												class="help-block">:message</span>') !!}
										</div>
									</div>
									<div class="col-sm-2"><label for="max_credit_amount" class="control-label">Días de
											crédito</label><span class="text-danger">*</span></div>
									<div class="col-sm-4">
										<div class="form-group {{ $errors->first('days_credit', 'has-error') }}">
											{!! Form::number('days_credit', Input::old('days_credit',0), array('class' =>
											'form-control','placeholder'=>'Dias de
											crédito','min'=>'0','id'=>'days_credit')) !!}
											{!! $errors->first('days_credit', '<span
												class="help-block">:message</span>') !!}
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-2"><label for="phone_number" class="control-label">Número de
											teléfono</label></div>
									<div class="col-sm-4">
										<div class="form-group {{ $errors->first('phone_number', 'has-error') }}">
											<input id="phone_number" name="phone_number"
												placeholder="Número de teléfono" type="text"
												class="form-control required" value="{!! old('phone_number') !!}" />
											{!! $errors->first('phone_number', '<span
												class="help-block">:message</span>') !!}
										</div>
									</div>
									<div class="col-sm-2"><label for="email" class="control-label">Email</label></div>
									<div class="col-sm-4">
										<div class="form-group {{ $errors->first('email', 'has-error') }}">
											<input id="email" name="email" placeholder="E-mail" type="text"
												class="form-control required email" value="{!! old('email') !!}" />
											{!! $errors->first('email', '<span class="help-block">:message</span>') !!}
										</div>
									</div>

								</div>
								<div class="row">
									<div class="sm-2"><label for="comment" class="col-sm-2 control-label">Información
											adicional</label></div>
									<div class="col-sm-4">
										<div class="form-group {{ $errors->first('comment', 'has-error') }}">
										<textarea name="comment" id="comment" class="form-control resize_vertical"
											rows="4">{!! old('comment') !!}</textarea>
										{!! $errors->first('comment', '<span class="help-block">:message</span>')!!}
										</div>
									</div>
									<div class="sm-2"><label for="ruta" class="col-sm-2 control-label">Ruta @if ($requerido==true) <span class="text-danger">*</span>@endif
									</label></div>
									<div class="col-sm-4">
										<div class="form-group {{ $errors->first('ruta', 'has-error') }}">
										<select name="ruta"" id="ruta" class="form-control">
											<option value="">Seleccione ruta</option>											
											@foreach ($rutas as $item)
												<option value="{{ $item->id }}" {{ (isset($customer) && $customer->routes->contains('id',$item->id)) ? 'selected' : '' }}> {{ $item->name }}</option>
											@endforeach
										</select>
										{!! $errors->first('ruta', '<span class="help-block">:message</span>') !!}
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane" id="tab2" disabled="disabled">
								<h2 class="hidden">&nbsp;</h2>
								<div class="row">
									<div class="col-sm-2"><label class="control-label">Fotografía</label></div>
									<div class="col-sm-4">
										<div class="form-group {{ $errors->first('avatar', 'has-error') }}">
											<div class="fileinput fileinput-new" data-provides="fileinput">
												<div class="fileinput-new thumbnail"
													style="width: 150px; height: 150px;">
													<img src="{!! asset('images/customers/no-foto.png') !!}"
														class="img-responsive user_image" alt=""
														style="max-width: 150px;" />
												</div>
												<div class="fileinput-preview fileinput-exists thumbnail"
													style="max-width: 150px; max-height: 150px;"></div>
												<div>
												</div>
												<span class="btn btn-default btn-file">
													<span class="fileinput-new">Seleccione imagen</span>
													<span class="fileinput-exists">Cambiar</span>
													<input id="pic" name="avatar" type="file" class="form-control" />
												</span>
												<a href="#" class="btn btn-danger fileinput-exists"
													data-dismiss="fileinput">Quitar</a>
											</div>
										</div>
										<span class="help-block">{{ $errors->first('avatar', ':message') }}</span>
									</div>
									<div class="col-sm-2"><label for="birthdate" class="control-label">Fecha de
											nacimiento</label></div>
									<div class="col-sm-4">
										<div class="form-group  {{ $errors->first('birthdate', 'has-error') }}">
											<input id="birthdate" name="birthdate" type="text" class="form-control"
												data-date-format="DD/MM/YYYY" placeholder="dd/mm/yyyy" />
										</div>
										<span class="help-block">{{ $errors->first('birthdate', ':message') }}</span>
									</div>

								</div>

								<div class="row">
									<div class="col-sm-2"><label for="marital_status" class="control-label">Estado civil
										</label></div>
									<div class="col-sm-4">
										<div class="form-group {{ $errors->first('marital_status', 'has-error') }}">
											<select class="form-control" title="Seleccione estado civil..."
												name="marital_status">
												<option value="">Seleccione</option>
												<option value="Casado" @if(old('marital_status')==='Casado' )
													selected="selected" @endif>Casado</option>
												<option value="Soltero" @if(old('marital_status')==='Soltero' )
													selected="selected" @endif>Soltero</option>
												<option value="Otro" @if(old('marital_status')==='Otro' )
													selected="selected" @endif>Otro </option>
											</select>
										</div>
										<span
											class="help-block">{{ $errors->first('marital_status', ':message') }}</span>
									</div>
									<div class="col-sm-2"><label for="dpi" class="control-label">Dpi</label></div>
									<div class="col-sm-4">
										<div class="form-group {{ $errors->first('dpi', 'has-error') }}">
											<input id="dpi" name="dpi" placeholder="Codigo único de identificación"
												type="text" class="form-control required" value="{!! old('dpi') !!}" />
											{!! $errors->first('dpi', '<span class="help-block">:message</span>') !!}
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-2"><label for="state" class="control-label">Departamento</label>
									</div>
									<div class="col-sm-4">
										<div class="form-group {{ $errors->first('state', 'has-error') }}">
											<input id="state" name="state" type="text" class="form-control"
												value="{!! old('state') !!}" />
										</div>
										<span class="help-block">{{ $errors->first('state', ':message') }}</span>
									</div>
									<div class="col-sm-2"><label for="city" class="control-label">Ciudad</label></div>
									<div class="col-sm-4">
										<div class="form-group">
											<input id="city" name="city" type="text" class="form-control"
												value="{!! old('city') !!}" />
										</div>
										<span class="help-block">{{ $errors->first('city', ':message') }}</span>
									</div>
								</div>
							</div>
							<ul class="pager wizard">
								<li class="previous"><a href="#">Anterior</a></li>
								<li class="next"><a href="#">Siguiente</a></li>
								<li class="next finish" style="display:none;"><a class="btn-success"
										href="javascript:;">Guardar</a></li>
								{{-- <li class="next finish" style="display:none;"><a class="btn-success" style="color:white !important;" href="javascript:;">Guardar</a></li> --}}
							</ul>
						</div>											
					</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
</section>
@endsection
@section('footer_scripts')
<script language="javascript" type="text/javascript" src="{{ asset('assets/vendors/select2/js/select2.js') }}"></script>

{{-- wizard --}}
<script src="{{ asset('assets/vendors/bootstrapwizard/jquery.bootstrap.wizard.js') }}" type="text/javascript"></script>

<script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
<script src="{{ asset('assets/vendors/moment/js/moment.min.js') }} " type="text/javascript "></script>
<script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }} " type="text/javascript "></script>
<script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }} "
	type="text/javascript "></script>
<script type="text/javascript" src="{{ asset('assets/js/images/images.js') }}"></script>
{{-- Validaciones y wizard --}}
<script src="{{ asset('assets/js/pages/addcustomer.js') }}"></script>
{{-- wizard --}}
{{-- <script src="{{ asset('assets/vendors/wizard/jquery-steps/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/vendors/wizard/jquery-steps/js/jquery.steps.js') }}"></script>
<script src="{{ asset('assets/vendors/wizard/jquery-steps/js/form_wizard.js') }}"></script> --}}
<script type="text/javascript">

	$(document).ready(function () {
		$('select').select2({
					allowClear: true,
					theme: "bootstrap",
					placeholder: "Buscar"
				});
				
		$("#birthdate").datetimepicker({
			locale: 'es',
			defaultDate: new Date(1900, 01, 01),
			minDate: new Date(1900, 01, 01),
			format: 'DD/MM/YYYY',
		}).parent().css("position :relative ");

		var ruta_requerida = cleanNumber($('#ruta_requerida').val());
		if (ruta_requerida==true){
			$('#newCustomer').bootstrapValidator('enableFieldValidators', 'ruta',true,null);
		};
	});
</script>
@endsection