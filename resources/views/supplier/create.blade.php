@extends('layouts/default')

@section('title',trans('supplier.new_supplier'))
@section('page_parent',trans('supplier.suppliers'))
@section('header_styles')
    <!-- Validaciones -->
    <link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet"
          type="text/css"/>
@stop
@section('content')
    <section class="content">
        <!-- <div class="container"> -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff"
                               data-loop="true"></i>
                            {{trans('supplier.new_supplier')}}
                        </h3>
                        <span class="pull-right clickable">
								<i class="glyphicon glyphicon-chevron-up"></i>
							</span>
                    </div>
                    <div class="panel-body">
                        
                        <p class="text-danger">*Requeridos</p><br>
                        {!! Form::open(array('url' => 'suppliers', 'files' => true, 'id'=>'frmSup')) !!}

                        <div class="col-sm-2">
                            {!! Form::label('nit_supplier', trans('supplier.nit_supplier')) !!}<span
                                    class="text-danger">*</span>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group {{ $errors->first('nit_supplier', 'has-error') }}">
                                <input type="text" name="nit_supplier" id="nit_supplier" class="form-control" required>
                                {!! $errors->first('nit_supplier', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="col-sm-2">
                            {!! Form::label('company_name', trans('supplier.company_name')) !!}<span
                                    class="text-danger">*</span>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group {{ $errors->first('company_name', 'has-error') }}">
                                {!! Form::text('company_name', Input::old('company_name'), array('class' => 'form-control')) !!}
                                {!! $errors->first('company_name', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="col-sm-2">
                            {!! Form::label('name', trans('supplier.name')) !!}
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group {{ $errors->first('name', 'has-error') }}">
                                {!! Form::text('name', Input::old('name'), array('class' => 'form-control')) !!}
                                {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="col-sm-2">
                            {!! Form::label('address', trans('supplier.address' ))!!}<span class="text-danger">*</span>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group {{ $errors->first('address', 'has-error') }}">
                                {!! Form::text('address', Input::old('address'),array('class' => 'form-control')) !!}
                                {!! $errors->first('address', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="col-sm-2">
                            {!! Form::label('phone_number', trans('supplier.phone_number')) !!}
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group {{ $errors->first('phone_number', 'has-error') }}">
                                {!! Form::text('phone_number', Input::old('phone_number'), array('class' => 'form-control', 'maxlength'=>8)) !!}
                                {!! $errors->first('phone_number', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="col-sm-2">
                            {!! Form::label('email', trans('supplier.email')) !!}
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group {{ $errors->first('email', 'has-error') }}">
                                {!! Form::email('email', Input::old('email'), array('class' => 'form-control')) !!}
                                {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

						<div class="col-sm-2">
							{!! Form::label('name_on_checks', trans('supplier.name_on_checks')) !!}
						</div>
						<div class="col-sm-4">
							<div class="form-group {{ $errors->first('name_on_checks', 'has-error') }}">
								{!! Form::text('name_on_checks', Input::old('name_on_checks'), array('class' => 'form-control')) !!}
								{!! $errors->first('name_on_checks', '<span class="help-block">:message</span>') !!}
							</div>
						</div>

						<div class="col-sm-2">
							{!! Form::label('credit', trans('supplier.credit')) !!}
						</div>
						<div class="col-sm-4">
							<div class="form-group {{ $errors->first('name_on_checks', 'has-error') }}">
								{!! Form::number('credit', Input::old('credit'), array('class' => 'form-control')) !!}
								{!! $errors->first('credit', '<span class="help-block">:message</span>') !!}
							</div>
						</div>

                        <div class="col-sm-2">
                            {!! Form::label('bank', trans('supplier.bank')) !!}
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group {{ $errors->first('bank', 'has-error') }}">
                                {!! Form::text('bank', Input::old('bank'), array('class' => 'form-control')) !!}
                                {!! $errors->first('bank', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="col-sm-2">
                            {!! Form::label('account_number', trans('supplier.account_number')) !!}
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group {{ $errors->first('account_number', 'has-error') }}">
                                {!! Form::text('account_number', Input::old('bank'), array('class' => 'form-control')) !!}
                                {!! $errors->first('account_number', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

						<div class="col-sm-2">
							{!! Form::label('days_credit', trans('supplier.days_credit')) !!}
						</div>
						<div class="col-sm-4">
							<div class="form-group {{ $errors->first('days_credit', 'has-error') }}">
								{!! Form::number('days_credit', Input::old('days_credit'), array('class' => 'form-control')) !!}
								{!! $errors->first('days_credit', '<span class="help-block">:message</span>') !!}
							</div>
						</div>

                        <div class="col-sm-2">
                            {!! Form::label('avatar', trans('supplier.choose_avatar')) !!}
                        </div>
						<div class="col-sm-4">
							<div class="form-group {{ $errors->first('avatar', 'has-error') }}">
								<div class="fileinput fileinput-new" data-provides="fileinput">
									<span class="btn btn-default btn-file">
													<span class="fileinput-new">Seleccione imagen</span>
													<span class="fileinput-exists">Cambiar</span>
													<input id="pic" name="avatar" type="file" class="form-control"/>
												</span>
									<a href="#" class="btn btn-danger fileinput-exists"
									   data-dismiss="fileinput">Quitar</a>
								</div>
							</div>
							<span class="help-block">{{ $errors->first('avatar', ':message') }}</span>
						</div>

                        <br>
                        <div class="col-lg-12">
                            @include('partials.buttons',['cancel_url'=>"/suppliers"])
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        <!-- </div> -->
    </section>
@endsection
@section('footer_scripts')
    <!-- Valiadaciones -->
    <script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#frmSup').bootstrapValidator({
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                message: 'Valor no valido',
                fields: {
                    company_name: {
                        validators: {
                            notEmpty: {
                                message: 'Debe ingresar nombre de la compañia proveedora.'
                            },
                            stringLength: {
                                min: 3,
                                message: 'Debe ingrear por lo menos 3 caracteres.'
                            }
                        }
                    },
                    nit_supplier: {
                        validators: {
                            notEmpty: {
                                message: 'Debe ingresar NIT de la compañia proveedora.'
                            },
                            callback: {
                                message: 'El NIT ingresado no es válido',
                                callback: function (value) {
                                    return nitValid(value);
                                }
                            }
                        }
                    },
					phone_number: {
                        validators: {
                            stringLength: {
                            	min:8,
								max:8,
                                message: 'El número de teléfono debe tener 8 caracteres de tipo numérico.'
                            },
							regexp: {
								regexp: /^[0-9-.?]+$/,
								message: 'El número de teléfono solo puede contener dígitos'
							}
                        }
                    },
					days_credit: {
						validators: {
							regexp: {
								regexp: /^[0-9-.?]+$/,
								message: 'El número de días de crédito solo puede contener dígitos'
							}
						}
					},
					credit: {
						validators: {
							regexp: {
								regexp: /^[0-9-.?]+$/,
								message: 'El monto de crédito solo puede contener dígitos'
							}
						}
					},
                    address: {
                        validators: {
                            notEmpty: {
                                message: 'Debe ingresar dirección del proveedor.'
                            }
                        }
                    }
                }
            });
        });
    </script>
@stop
