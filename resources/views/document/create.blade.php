@extends('layouts/default')

@section('title',trans('general.new_document_type'))
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
						<i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
						{{trans('general.new_document_type')}}
					</h3>
					<span class="pull-right clickable">
						<i class="glyphicon glyphicon-chevron-up"></i>
					</span>
				</div>
				<div class="panel-body">

					{!! Form::open(array('url' => 'documents', 'id'=>'frmDoc')) !!}
					<div class="col-md-10 col-md-offset-1">
						<div class="form-group">
							{!! Form::label('name', trans('Nombre del documento').' *') !!}
							{!! Form::text('name', Input::old('name'), array('class' => 'form-control')) !!}
						</div>
						<div class="form-group">
							{!! Form::label('Signo', trans('Signo').' ') !!}
							<select class="form-control" name="sign">
								<option value="+">+</option>
								<option value="-">-</option>
								<option value="=">=</option>
							</select>
						</div>
						<div class="form-group">
							{!! Form::label('lbltype_fel', 'Tipo de documento FEL') !!}
							{!! Form::text('type_fel', Input::old('type_fel'), array('class' => 'form-control')) !!}
						</div>
						<div class="form-group">
							{!! Form::label('ajuste_inventario', trans('Es ajuste de inventario?')) !!}
							<select class="form-control" name="ajuste_inventario" id="ajuste_inventario">
								<option value="0">No</option>
								<option value="1">Si</option>
							</select>
						</div>
						<div class="form-group">
							{!! Form::label('size', trans('Estado')) !!}
							{!! Form::select('id_state', $state_cellar, Input::old('state_cellar'), array('class' => 'form-control')) !!}
						</div>
					</div>
					@include('partials.buttons',['cancel_url'=>"/documents"])
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
				name:{
					validators:{
						notEmpty:{
							message:'Debe ingresar nombre del tipo de documento.'
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
									type_fel: validator.getFieldElements('type_fel').val()
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
