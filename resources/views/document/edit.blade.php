@extends('layouts/default')

@section('title',trans('general.update_document_type'))
@section('page_parent',trans('general.inventory_documents'))
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
						<i class="livicon" data-name="edit" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
						{{trans('general.update_document_type')}}
					</h3>
					<span class="pull-right clickable">
						<i class="glyphicon glyphicon-chevron-up"></i>
					</span>
				</div>
				<div class="panel-body">
					{!! Html::ul($errors->all()) !!}
					{!! Form::model($dataDocumento, array('route' => array('documents.update', $dataDocumento->id), 'method' => 'PUT', 'files' => true, 'id'=>'frmDoc')) !!}
					<div class="form-group">
						{!! Form::label('name', trans('Nombre del documento').' *') !!}
						<!-- {!! Form::text('name', Input::old('document_name'), array('class' => 'form-control')) !!} -->
						<input type="text" name="name_document" value="{{$dataDocumento->name}}" class="form-control">
					</div>
					<div class="form-group">
						{!! Form::label('Signo', trans('Signo').' ') !!}
						<select class="form-control" name="sign_document">
							<option value="+" {{ ($dataDocumento->sign == '+') ?  'selected="selected"' : '' }}>+</option>
							<option value="-" {{ ($dataDocumento->sign == '-') ?  'selected="selected"' : '' }}>-</option>
							<option value="=" {{ ($dataDocumento->sign == '=') ?  'selected="selected"' : '' }}>=</option>
						</select>
					</div>
					<div class="form-group">
						{!! Form::label('lbltype_fel', 'Tipo de documento FEL') !!}
						{!! Form::text('type_fel', Input::old('type_fel'), array('class' => 'form-control')) !!}
					</div>
					<div class="form-group">
						{!! Form::label('ajuste_inventario', trans('Es ajuste de inventario?')) !!}
						<select class="form-control" name="ajuste_inventario" id="ajuste_inventario">
							<option value="0" {{ ($dataDocumento->ajuste_inventario == '0') ?  'selected="selected"' : '' }}>No</option>
							<option value="1" {{ ($dataDocumento->ajuste_inventario == '1') ?  'selected="selected"' : '' }}>Si</option>
						</select>
					</div>
					<div class="form-group">
						{!! Form::label('size', trans('Estado')) !!}
						{!! Form::select('id_state', $state_cellar, Input::old('state_cellar'), array('class' => 'form-control')) !!}
					</div>
					@include('partials.buttons',['cancel_url'=>"/documents"])
					<!-- {!! Form::submit(trans('Aceptar'), array('class' => 'btn btn-primary')) !!}
						<a class="btn btn-danger" href="{{ url('/documents') }}">
							Cancelar
						</a> -->
						{!! Form::close() !!}
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
			$('#frmDoc').bootstrapValidator({
				feedbackIcons: {
					valid: 'glyphicon glyphicon-ok',
					invalid: 'glyphicon glyphicon-remove',
					validating: 'glyphicon glyphicon-refresh'
				},
				message: 'Valor no valido',
				fields:{
					name_document:{
						validators:{
							notEmpty:{
								message:'Debe ingresar nombre del tipo de pago.'
							},
							stringLength:{
								min:3,
								message:'Debe ingrear por lo menos 3 caracteres.'
							}
						}
					},
					type_fel: {
						validators: {
							remote: {
								message: 'El tipo de DTE FEL ya esta ingresado en otro documento.',
								data: function(validator) {
									return {
										type_fel: validator.getFieldElements('type_fel').val(),
										document_id: {{$dataDocumento->id}}
									};
								},
								url: APP_URL+'/verify_type_fel',
							}
						}
					}
				}
			});
		});
	</script>
	@stop
