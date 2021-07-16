@extends('layouts/default')

@section('title',trans('unit_measure.edit'))
@section('page_parent',trans('unit_measure.unit_measure'))

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
						{{trans('unit_measure.edit')}}
					</h3>
					<span class="pull-right clickable">
						<i class="glyphicon glyphicon-chevron-up"></i>
					</span>
				</div>
				<div class="panel-body">

					{!! Form::model($unit,array('route' => array('unit_measure.update', $unit->id), 'id'=>'frmUnit', 'method'=>'PUT')) !!}
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                {!! Form::label('name', trans('unit_measure.name')) !!}
                <div class="input-group select2-bootstrap-prepend">
                  <div class="input-group-addon"><i class="fa fa-align-justify"></i></div>
                  {!! Form::text('name', Input::old('name'), array('class' => 'form-control')) !!}
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                {!! Form::label('abbreviation', trans('unit_measure.abbreviation')) !!}
                <div class="input-group select2-bootstrap-prepend">
                  <div class="input-group-addon"><i class="fa fa-align-justify"></i></div>
                  {!! Form::text('abbreviation', Input::old('abbreviation'), array('class' => 'form-control')) !!}
                </div>
              </div>
            </div>
          </div>

					@include('partials.buttons',['cancel_url'=>"/unit_measure"])
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
		$('#frmUnit').bootstrapValidator({
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
				abbreviation: {
					validators: {
						remote: {
							message: 'La abreviatura ya esta registrada en otra unidad de medida.',
							data: function(validator) {
								return {
									type_fel: validator.getFieldElements('abbreviation').val(),
                  unit_id: {{$unit->id}}
								};
							},
							url: APP_URL+'/verify_abbreviation',
						}
					}
				}
			}
		});
	});
</script>
@stop
