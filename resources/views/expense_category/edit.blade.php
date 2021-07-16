@extends('layouts/default')

@section('title',trans('expenses.edit_expense_category'))
@section('page_parent',trans('bank_expenses.expenses'))
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
						{{trans('expenses.edit_expense_category')}}
					</h3>
					<span class="pull-right clickable">
						<i class="glyphicon glyphicon-chevron-up"></i>
					</span>
				</div>
				<div class="panel-body">
					
					
					{!! Form::model($expense_category, array('route' => array('banks.expense_categories.update', $expense_category->id), 'method' => 'PUT', 'files' => true, 'id'=>'frmTipo')) !!}
					<div class="col-md-10 col-md-offset-1">

						<div class="form-group {{ $errors->first('name', 'has-error') }}">                                            
								{!! Form::label('name', trans('Nombre').' *') !!}
								<input id="name" name="name" type="text" placeholder="Nombre" class="form-control required" value="{!! old('name',$expense_category->name) !!}"/>                                                
								{!! $errors->first('name', '<span class="help-block">:message</span>') !!}
							</div>   
					</div>
					<br>
					<div class="form-group">
						@include('partials.buttons',['cancel_url'=>"/pago"])
					</div>
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
							message:'Debe ingresar nombre del tipo de pago.'
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
</script>
@stop
