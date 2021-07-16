@extends('layouts/default')

@section('title',trans('Crear bodega'))
@section('page_parent',trans('Bodegas'))

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
						Crear bodega
					</h3>
					<span class="pull-right clickable">
						<i class="glyphicon glyphicon-chevron-up"></i>
					</span>
				</div>
				<div class="panel-body">
					{{--  --}}

					{!! Form::open(array('url' => 'almacen','id'=>'frmAlmacen')) !!}
					<div class="row">
						<div class="col-lg-6">
							<div class="form-group">
								{!! Form::label('name', trans('almacen.name').' *', array('class' => 'control-label')) !!}
								{!! Form::text('name', Input::old('name'), array('class' => 'form-control')) !!}
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								{!! Form::label('adress', trans('almacen.address').' *', array('class' => 'control-label')) !!}
								{!! Form::text('adress', Input::old('adress'), array('class' => 'form-control')) !!}
							</div>

						</div>
					</div>
					<div class="row">
						<div class="col-lg-6">
							<div class="form-group">
								{!! Form::label('phone', trans('almacen.phone'), array('class' => 'control-label')) !!}
								{!! Form::text('phone', Input::old('phone'), array('class' => 'form-control')) !!}
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								{!! Form::label('size', trans('almacen.status')) !!}
								{!! Form::select('id_state', $state_cellar, Input::old('state_cellar'), array('class' => 'form-control')) !!}
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6">
							<label for="users">{{ trans('almacen.users') }}*
								<span class="btn btn-info btn-xs select-all">{{ trans('general.select_all') }}</span>
								<span class="btn btn-info btn-xs deselect-all">{{ trans('general.deselect_all') }}</span></label>
								<select name="users[]" id="users" class="form-control select2" multiple="multiple">
									@foreach($users as $id => $name)
									<option value="{{ $id }}" {{ (in_array($id, old('users', [])) || isset($almacen) && $almacen->users->contains($id)) ? 'selected' : '' }}>{{ $name }}</option>

									@endforeach
								</select>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									{!! Form::label('comentario', trans('almacen.comment').' ') !!}
									{!! Form::textarea('comentario', Input::old('comentario'), array('class' => 'form-control','size' => '50x4')) !!}
								</div>
							</div>
						</div>
						@include('partials.buttons',['cancel_url'=>"/almacen"])
						{!! Form::close() !!}
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
			$('#frmAlmacen').bootstrapValidator({
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
								message:'Debe ingresar el nombre de la bodega.'
							},
							stringLength:{
								min:3,
								message:'Debe ingrear por lo menos 3 caracteres.'
							}
						}
					},
					phone:{
						validators:{
							// notEmpty:{
								// 	message:'Debe ingresar un numero de teléfono'
								// },
								stringLength:{
									min:8,
									max:50,
									message:'El número de telefono debe tener 8 digitos. '
								},
								regexp:{
									regexp: /^[0-9 /?0-9?]+$/,
									message: 'Solo se permiten números, espacios y diagonal (/).'
								}
							},
							required: false
						},
						adress:{
							validators:{
								notEmpty:{
									message:'Debe ingresar la direccion.'
								},
								stringLength:{
									min:3,
									message:'Debe ingrear por lo menos 3 caracteres.'
								}
							}
						},
						users:{
							validators:{
								notEmpty:{
									message:'Debe ingresar la direccion.'
								}
							}
						}
					}
				});
			});
		</script>
		@stop

