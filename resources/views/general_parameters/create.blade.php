@extends('layouts/default')

@section('title',trans('general.new_parameter'))
@section('page_parent',trans('general.parameters'))
@section('header_styles')
<!-- Validaciones -->
<link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css" />
@stop
@section('content')
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">
						<i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
						{{trans('general.new_parameter')}}
					</h3>
					<span class="pull-right clickable">
						<i class="glyphicon glyphicon-chevron-up"></i>
					</span>
				</div>
				<div class="panel-body">
					
					{!! Form::open(array('url' => 'general-parameters', 'files' => true, 'id'=>'frmTipo')) !!}
					<div class="row">
						<div class="col-lg-6">
							<div class="form-group">
								{!! Form::label('lblname', trans('general.parameter_type')) !!}
								<div class="input-group">
									<span class="input-group-addon"><li class="glyphicon glyphicon-list"></li> </span>
									<select class="form-control" title="" name="type" id="type" required>
										<option value="">Seleccione módulo</option>
										<option value="Ventas" @if(old('type') === 'Ventas') selected="selected" @endif>Ventas</option>
										<option value="Compras" @if(old('type') === 'Compras') selected="selected" @endif>Compras</option>
										<option value="Proyectos" @if(old('type') === 'Proyectos') selected="selected" @endif>Proyectos</option>
										<option value="Inventario" @if(old('type') === 'Inventario') selected="selected" @endif>Inventario</option>
										<option value="Reportes" @if(old('type') === 'Reportes') selected="selected" @endif>Reportes</option>
										<option value="Gráficas" @if(old('type') === 'Gráficas') selected="selected" @endif>Gráficas</option>
										<option value="Bancos" @if(old('type') === 'Bancos') selected="selected" @endif>Bancos</option>
										<option value="Anulaciones" @if(old('type') === 'Anulaciones') selected="selected" @endif>Anulaciones</option>
										<option value="Acceso" @if(old('type') === 'Acceso') selected="selected" @endif>Acceso</option>
										<option value="Finanzas" @if(old('type') === 'Finanzas') selected="selected" @endif>Acceso</option>
										<option value="Planilla" @if(old('type') === 'Planilla') selected="selected" @endif>Planilla</option>
										<option value="Cuentas por pagar" @if(old('type') === 'Cuentas por pagar') selected="selected" @endif>Cuentas por pagar</option>
										<option value="Rutas" @if(old('type') === 'Rutas') selected="selected" @endif>Rutas</option>
										<option value="Cierre de caja" @if(old('type') === 'Cierre de caja') selected="selected" @endif>Cierre de caja</option>
										<option value="Otros" @if(old('type') === 'Otros') selected="selected" @endif>Otros</option>
									</select>
									{!! $errors->first('type', '<span class="help-block">:message</span>') !!}
								</div>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								{!! Form::label('lblname', trans('general.name')) !!}
								<div class="input-group">
									<span class="input-group-addon"><li class="glyphicon glyphicon-book"></li> </span>
									<input type="text" name="name" class="form-control" value="" required>
									{!! $errors->first('name', '<span class="help-block">:message</span>') !!}
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6">
							<div class="form-group">
								{!! Form::label('lblname', trans('general.description')) !!}
								<div class="input-group">
									<span class="input-group-addon"><li class="glyphicon glyphicon-bookmark"></li> </span>
									<input type="text" name="description" class="form-control" value="" required>
									{!! $errors->first('description', '<span class="help-block">:message</span>') !!}
								</div>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								{!! Form::label('lblname', trans('general.text_value')) !!}
								<div class="input-group">
									<span class="input-group-addon"><li class="glyphicon glyphicon-comment"></li> </span>
									<input type="text" name="text_value" id="text_value" class="form-control parameter" value="">
									{!! $errors->first('text_value', '<span class="help-block">:message</span>') !!}
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6">
							<div class="form-group">
								{!! Form::label('lblname', trans('general.min_amount')) !!}
								<div class="input-group">
									<span class="input-group-addon"><li class="glyphicon glyphicon-usd"></li> </span>
									<input type="number" step="0.01" min="0" name="min_amount" id="min_amount" class="form-control parameter" value="">
									{!! $errors->first('min_amount', '<span class="help-block">:message</span>') !!}
								</div>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								{!! Form::label('lblname', trans('general.max_amount')) !!}
								<div class="input-group">
									<span class="input-group-addon"><li class="glyphicon glyphicon-usd"></li> </span>
									<input type="number" step="0.01" min="0" name="max_amount" id="max_amount" class="form-control parameter" value="">
									{!! $errors->first('max_amount', '<span class="help-block">:message</span>') !!}
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6">
							<div class="form-group">
								{!! Form::label('lblname', trans('general.default_amount')) !!}
								<div class="input-group">
									<span class="input-group-addon"><li class="glyphicon glyphicon-usd"></li> </span>
									<input type="number" step="0.01" min="0" name="default_amount" id="default_amount" class="form-control parameter" value="">
									{!! $errors->first('default_amount', '<span class="help-block">:message</span>') !!}
								</div>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								{!! Form::label('lblname', trans('general.assigned_user_id')) !!}
								<div class="input-group">
									<span class="input-group-addon"><li class="glyphicon glyphicon-user"></li> </span>
									<select class="form-control parameter" name="assigned_user_id" id="assigned_user_id">
										<option value="0">--Seleccione--</option>
										@foreach($users as $id => $name)
										<option value="{!! $id !!}" >{{ $name }}</option>
										@endforeach
									</select>
									{!! $errors->first('assigned_user_id', '<span class="help-block">:message</span>') !!}
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6">
							<div class="form-group">
								{!! Form::label('lblname', trans('general.active')) !!}
								<div class="input-group">
									<span class="input-group-addon"><li class="glyphicon glyphicon-saved"></li> </span>
									<select class="form-control" title="trans('accounts.status')" name="active" id="active" required disabled="disabled">
										<option value="1">Activo</option>
									</select>
									{!! $errors->first('active', '<span class="help-block">:message</span>') !!}
								</div>
							</div>
						</div>
						{{-- <div class="col-lg-6">
							<div class="form-group">
								{!! Form::label('lblname', trans('parameter.phone')) !!}
								<div class="input-group">
									<span class="input-group-addon"><li class="glyphicon glyphicon-list"></li> </span>
									<input type="text" maxlength="8" name="phone" class="form-control" value="">
								</div>
							</div>
						</div> --}}
					</div>
					{!! Form::close() !!}
					<br><br>
					<div class="col-lg-12">
						@include('partials.buttons',['cancel_url'=>"/general-parameters"])
					</div>
					{{--Begin modal--}}
					<div class="modal fade modal-fade-in-scale-up" tabindex="-1" id="confirmSave" role="dialog" aria-labelledby="modalLabelfade" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header bg-info">
									<h4 class="modal-title">Confirmación guardar</h4>
								</div>
								<div class="modal-body">
									<div class="text-center">
										Datos correctos
										<br>
										¿Seguro que desea guardar?
									</div>
								</div>
								<div class="modal-footer" style="text-align:center;">
									<a class="btn  btn-info" id="btn_save_confirm" onclick="document.getElementById('frmTipo').submit();">Aceptar</a>
									<a class="btn  btn-danger" data-dismiss="modal" >Cancelar</a>
								</div>
							</div>
						</div>
					</div>
					{{--End modal--}}
				</div>
			</div>
		</div>
	</div>
</section>
@endsection
@section('footer_scripts')
<!-- Valiadaciones -->
<script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('#frmTipo').bootstrapValidator({
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
							message:'Debe ingresar nombre.'
						},
						stringLength:{
							min:3,
							message:'Debe ingrear por lo menos 3 caracteres.'
						}
					}
				},
				type:{
					validators:{
						notEmpty:{
							message:'Debe ingresar el tipo: Ventas, Clientes, Etc.'
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
	/**/
	$("#frmTipo").submit(function(ev){ev.preventDefault();});
	var idVenta=document.getElementById('btn_save');
	idVenta.addEventListener('click',function(){
		var $validator = $('#frmTipo').data('bootstrapValidator').validate();
		if ($validator.isValid() ) {
			this.style.display='none';
			/**
			* MOstrar modal
			*/
			$('body').loadingModal({
				text: 'Procesando...'
			});
			$('body').loadingModal('show');
			var text_value = document.getElementById("text_value");
			var min_amount=document.getElementById('min_amount');
			var max_amount=document.getElementById('max_amount');
			var default_amount=document.getElementById('default_amount');
			var assigned_user_id=document.getElementById('assigned_user_id');


			console.log(' text value '+text_value.value+' min amount '+cleanNumber(min_amount.value)+' max '+cleanNumber(max_amount.value)+' default '+cleanNumber(default_amount.value)+' user '+assigned_user_id.value);
			if((text_value.value=="")&&(cleanNumber(min_amount.value)==0)&&(cleanNumber(max_amount.value)==0)&&(cleanNumber(default_amount.value)==0)&&(assigned_user_id.value==0))
			{
				// alert('todos vacios');
				$('.parameter').css('border-color','red');
				toastr.error("Debe llenar almenos un campo de parámetro además del nombre");
				this.style.display='inline';
				$('body').loadingModal('hide');
			}
			else {
				$('body').loadingModal('hide');
				this.style.display='inline';
				$('#confirmSave').modal('show');
				// alert('ok');
			}
		}

	});
</script>
@stop
