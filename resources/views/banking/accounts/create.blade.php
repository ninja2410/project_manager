@extends('layouts/default') 
@section('title',trans('accounts.new_account')) 
@section('page_parent',trans('accounts.banks'))

@section('header_styles')
<!-- Validaciones -->
{{--
	<link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css" />  --}}
	<link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css" /> 
	@stop 
	@section('content')
	<section class="content">
		<!-- <div class="container"> -->
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title">
								<i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
								{{trans('accounts.new_account')}}
							</h3>
							<span class="pull-right clickable">
								<i class="glyphicon glyphicon-chevron-up"></i>
							</span>
						</div>
						<div class="panel-body">
							{{--  --}}
							{!! Form::open(array('url' => 'banks/accounts', 'files' => true, 'id'=>'frmSup'))
							!!}
							<div class="row">
								<div class="col-lg-6">
									<div class="form-group">
										{!! Form::label('bank_id', trans('accounts.bank_name').' *') !!}
										<select class="form-control" title="" name="bank_id" id="bank_id" required>
											<option value="">Seleccione banco</option>
											<option value="2" @if(old('bank_id') === '2') selected="selected" @endif>Banco Agromercantil (BAM)</option>
											<option value="3" @if(old('bank_id') === '3') selected="selected" @endif>Banco industrial</option>
											<option value="4" @if(old('bank_id') === '4') selected="selected" @endif>BAC-Credomatic</option>
											<option value="5" @if(old('bank_id') === '5') selected="selected" @endif>Banco Promerica</option>
											<option value="6" @if(old('bank_id') === '6') selected="selected" @endif>Banco Internacional</option>
											<option value="7" @if(old('bank_id') === '7') selected="selected" @endif>Banco G&T Continental</option>
											<option value="8" @if(old('bank_id') === '8') selected="selected" @endif>Banrural</option>
											<option value="9" @if(old('bank_id') === '9') selected="selected" @endif>Bantrab</option>
											<option value="10" @if(old('bank_id') === '10') selected="selected" @endif>Vivibanco</option>
											<option value="11" @if(old('bank_id') === '11') selected="selected" @endif>Banco Ficohsa</option>
										</select>
									</div>
									
								</div>
								
								<div class="col-lg-6">
									<div class="form-group">
										{!! Form::label('account_name', trans('accounts.name').' *') !!}
										<input type="text" name="account_name" id="account_name" class="form-control" value="{!! old('account_name') !!}">
									</div>
									
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6">
									<div class="form-group">
										{!! Form::label('account_number', trans('accounts.account_number').' *') !!} 
										{!! Form::text('account_number', Input::old('account_number'),array('class' => 'form-control')) !!}
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group">
										{!! Form::label('account_type_id', trans('accounts.account_type').' *') !!}
										<select class="form-control" title="" name="account_type_id" id="account_type_id" required>
											<option value="">--Seleccione--</option>
											@foreach($account_type as $item)
											<option value="{!! $item->id !!}" @if(old('account_type_id') === $item->id) selected="selected" @endif>{{ $item->name }}</option>
											@endforeach
										</select>
									</div>
									
								</div>
								
								
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group">
											{!! Form::label('opening_balance',trans('accounts.opening_balance' ))!!}* {!! Form::text('opening_balance','0.00', array('class'=> 'form-control dinero','required'=>'required')) !!}
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											{!! Form::label('max_amount', trans('accounts.max_amount')) !!} {!! Form::text('max_amount', Input::old('max_amount'), array('class'
											=> 'form-control', 'placeholder'=>'Vacío ó 0.00 para no poner límite')) !!}
										</div>
									</div>
									
									
								</div>
								
								{{--
									<div class="col-lg-4">
										<div class="form-group">
											{!! Form::label('pago_id', trans('accounts.payment_restriction')) !!}
											<select class="form-control" title="trans('accounts.payment_restriction')" name="pago_id" id="pago_id">
												<option value="null">--No restringir--</option>
												@foreach($pago as $item)
												<option value="{!! $item->id !!}" >{{ $item->name }}</option>
												@endforeach
											</select>
										</div>
									</div> --}}
								</div>
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group">
											{!! Form::label('status', trans('accounts.status').' *') !!}
											<select class="form-control" title="trans('accounts.status')" name="status" id="status" required disabled="disabled">
												@foreach($state as $item)
												<option value="{!! $item->id !!}" @if($item->id===1) selected='selected' @endif>{{ $item->name }}</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											{!! Form::label('account_responsible', trans('accounts.account_responsible')) !!}
											<select class="form-control" title="trans('accounts.account_responsible')" name="account_responsible" id="account_responsible">
											<option value="">--Seleccione--</option>
											@foreach($users as $item)
											<option value="{!! $item->id !!}" @if($item->id===Auth::user()->id) selected='selected' @endif>{{ $item->name }}</option>
											@endforeach
										</select>
									</div>
									
								</div>
								<input type="hidden" name="account_type" id="account_type" value="1">
								<input type="hidden" name="bank_name" id="bank_name">
								<input type="hidden" name="status" id="status" value="1">
								<input type="hidden" name="user_id" id="user_id" value="{{Auth::user()->id}}">
								
								<br>
								<div class="col-lg-12">
									@include('partials.buttons',['cancel_url'=>"/banks/accounts"])
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
			<script>
				var bank_id=document.getElementById('bank_id');
				bank_id.addEventListener('change',function(){
					$("#bank_name").val($("#bank_id option:selected").text());
					console.log($("#bank_name").val());
				})
				
			</script>
			{{-- FORMATO DE MONEDAS --}}
			<script src="{{asset('assets/js/vuejs/cleave.min.js')}} "></script>
			<script src="{{asset('assets/js/vuejs/vue-cleave.min.js')}} "></script>
			<!-- Valiadaciones -->
			<script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
			<script type="text/javascript">
				$(document).ready(function(){
					$('#bank_id').focus();
					$('#frmSup').bootstrapValidator({
						feedbackIcons: {
							valid: 'glyphicon glyphicon-ok',
							invalid: 'glyphicon glyphicon-remove',
							validating: 'glyphicon glyphicon-refresh'
						},
						message: 'Valor no valido',
						fields:{
							account_name:{
								validators:{
									notEmpty:{
										message:'Debe ingresar nombre de la cuenta.'
									},
									stringLength:{
										min:3,
										message:'Debe ingrear por lo menos 3 caracteres.'
									}
								}
							},
							account_number:{
								validators:{
									notEmpty:{ message:'Debe ingresar el número de la cuenta.' },
									stringLength:{
										min:1,
										max:20,
										message:'El número de cuenta debe tener al menos 1 digitos. '
									},
									regexp:{
										regexp: /^[0-9-?]+$/,
										message: 'Ingrese un número válido.'
									}
								}
							},
							account_type_id: {
								validators:{
									notEmpty:{
										message:'Debe seleccionar el tipo de cuenta'
									}
								}
							},
							bank_id:{
								validators:{
									notEmpty:{
										message:'Debe seleccionar el banco'
									}
								}
							}
						}
					});
				});
				
				var cleave = new Cleave('.dinero', { numeral: true, numeralThousandsGroupStyle: 'thousand' });
				
			</script>
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			@stop