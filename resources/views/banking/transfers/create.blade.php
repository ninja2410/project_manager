@extends('layouts/default') 
@section('title',trans('transfers.new_transfer')) 
@section('page_parent',trans('transfers.banks'))

@section('header_styles')
<!-- Validaciones -->
{{--
	<link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css" />  --}}
	<link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css" />
	<!--  Calendario -->
	<link href="{{ asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css"/> 
	{{-- select 2 --}}
	<link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css" />
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
								{{trans('transfers.new_transfer')}}
							</h3>
							<span class="pull-right clickable">
								<i class="glyphicon glyphicon-chevron-up"></i>
							</span>
						</div>
						<div class="panel-body">
							 
							{!! Form::open(array('url' => 'banks/transfers', 'files' => true, 'id'=>'frmSup')) !!}
							<div class="row">
								<div class="col-lg-6">
									<div class="form-group">
										<label for="date">Fecha</label>
										<div class="input-group">
											<span class="input-group-addon"><li class="glyphicon glyphicon-calendar"></li> </span>
											<input type="text" class="form-control" id="paid_at" name="paid_at" required>
										</div>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group">
										
										{!! Form::label('amount', trans('transfers.amount')) !!} 
										<div class="input-group">
											<span class="input-group-addon"><strong>Q</strong></span>
											{!! Form::text('amount', Input::old('amount'), array('class' =>'form-control money_efectivo2','placeholder'=>'Monto')) !!}
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6">
									<div class="form-group">
										{!! Form::label('account_id_source', trans('transfers.from_account')) !!}
										<div class="input-group select2-bootstrap-prepend">
											<div class="input-group-addon"><i class="fa fa-money"></i></div>
											<select class="form-control" title="trans('transfers.from_account')" name="account_id_source" id="account_id_source">
												<option value="">-Seleccione cuenta-</option>
												@foreach($accounts as $item)
												<option value="{!! $item->id !!}" >{{ $item->account_name.' / ' }} @money($item->balance)</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group">
										{!! Form::label('account_id_destination', trans('transfers.to_account')) !!}
										<div class="input-group select2-bootstrap-prepend">
											<div class="input-group-addon"><i class="fa fa-money"></i></div>
											<select class="form-control" title="trans('transfers.to_account')" name="account_id_destination" id="account_id_destination">
												<option value="">-Seleccione cuenta-</option>
												@foreach($accounts as $item)
												<option value="{!! $item->id !!}" >{{ $item->account_name.' / ' }} @money($item->balance)</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12">
									<div class="form-group">
										{!! Form::label('description', trans('transfers.description').' ') !!} {!! Form::textarea('description', Input::old('description'),
										array('class' => 'form-control','size' => '30x2')) !!}
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6">
									<div class="form-group">
										{!! Form::label('payment_method', trans('transfers.payment_method')) !!}
										<div class="input-group select2-bootstrap-prepend">
											<div class="input-group-addon"><i class="fa fa-credit-card"></i></div>
											<select class="form-control" name="payment_method" id="payment_method">
											</select>											
										</div>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group">
										{!! Form::label('reference', trans('transfers.reference')) !!} 
										<div class="input-group select2-bootstrap-prepend">
											<div class="input-group-addon"><i class="fa fa-bars"></i></div>
											{!! Form::text('reference', Input::old('reference'), array('class'=> 'form-control', 'placeholder'=>'No. de documento u otra referencia')) !!}
										</div>
									</div>
								</div>									
							</div>
							<div class="row">
								{{--
									<div class="col-lg-4">
										<div class="form-group">
											{!! Form::label('payment_method', trans('transfers.payment_method')) !!}
											<select class="form-control" title="trans('transfers.payment_method')" name="payment_method" id="payment_method">
												@foreach($payments as $item)
												<option value="{!! $item->id !!}" >{{ $item->name }}</option>
												@endforeach
											</select>
										</div>
									</div> --}}
								</div>
								<input type="hidden" name="category_id" id="category_id" value="1">
								<input type="hidden" name="currency" id="currency" value="Q">
								<input type="hidden" name="currency_rate" id="currency_rate" value="1"> {{-- <input type="hidden" name="payment_method"
								id="payment_method" value="1"> --}}
								<input type="hidden" name="status" id="status" value="1">
								<input type="hidden" name="user_id" id="user_id" value="{{Auth::user()->id}}">
								
								<br>
								<div class="col-lg-12">
									@include('partials.buttons',['cancel_url'=>"/banks/transfers"])
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
			<!--  Calendario -->			
			<script src="{{ asset('assets/vendors/moment/js/moment.min.js') }} " type="text/javascript "></script>
			<script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }} " type="text/javascript "></script>
			<script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }} " type="text/javascript"></script>
			{{-- Select2 --}}
			<script type="text/javascript" src="{{ asset('assets/vendors/select2/js/select2.js')}} "></script>
			<!-- Valiadaciones -->
			<script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
			{{-- FORMATO DE MONEDAS --}}
			<script src="{{asset('assets/js/vuejs/cleave.min.js')}} "></script>
			<script src="{{asset('assets/js/vuejs/vue-cleave.min.js')}} "></script>
			<script type="text/javascript">
				
				$('#account_id_source').change(function(){
					/* Cuando cambia la cuenta fuente
					1.- La cuenta destino no puede ser la misma
					2.- Se actualiza el tipo de pago.	*/
					showLoading('Cargando listado de cuentas...');
					var account_id = cleanNumber($('#account_id_source').val());
					if(account_id) {
						$.get(APP_URL+'/banks/get-payment-type-out/'+[account_id],function(data) {
							$('#payment_method').empty();
							$('#payment_method').append('<option value="">--Seleccione--</option>');
							$.each(data, function(index,pagos){
								$('#payment_method').append('<option value="'+ pagos.id +'" type="'+pagos.type+'">'+ pagos.name +'</option>');
							});								
						});
						
						$.get(APP_URL+'/banks/get-account-for-transfer/'+[account_id],function(data) {
							$('#account_id_destination').empty();
							$.each(data, function(index,accounts){
								$('#account_id_destination').append('<option value="'+ accounts.id +'">'+ accounts.name +' - ' +accounts.pct_interes+'</option>');
							});
						});
						
						
						hideLoading();
					}else{
						$('#payment_method').empty();
						$('#account_id_destination').empty();
						hideLoading();
					};
					$('#account_id_source').val(account_id);
					
				});
				$(document).ready(function(){
					$('select').select2({
						allowClear: true,
						theme: "bootstrap",
						placeholder: "Buscar"
					});
					$('#account_name').focus();
					$('#frmSup').bootstrapValidator({
						feedbackIcons: {
							valid: 'glyphicon glyphicon-ok',
							invalid: 'glyphicon glyphicon-remove',
							validating: 'glyphicon glyphicon-refresh'
						},
						message: 'Valor no valido',
						fields:{
							payment_method:{
								validators:{
									notEmpty:{
										message:'Debe seleccionar el metodo de pago.'
									}
								}
							},
							account_id_source:{
								validators:{
									notEmpty:{
										message:'Debe seleccionar la cuenta destino.'
									}
								}
							},
							account_id_destination:{
								validators:{
									notEmpty:{
										message:'Debe seleccionar la cuenta destino.'
									}
								}
							},
							
							amount:{
								validators:{
									notEmpty:{
										message:'Debe ingresar el monto.'
									},
									stringLength:{
										min:1,
										message:'Debe ingresar por lo menos 1 dígito.'
									},
									between: { 
										min: 1,
										max: 9999999999,
										message: 'Debe ingresar al menos 1 Q' 
									},
									regexp:{
										regexp: /^\d+(\.\d{1,2})?$/,
										message: 'Ingrese un número válido.'
									}								
								}
							},
							paid_at: { 
								validators: { 
									date: { 
										format: 'DD/MM/YYYY', 
										message: 'Fecha inválida' 
									} 
								} 
							}
						}
					});
					
					$('#account_id_source').trigger("change");
					
				});
				
				var dateNow = new Date(); 
				$("#paid_at ").datetimepicker({ sideBySide: true, locale:'es', format:'DD/MM/YYYY', defaultDate:dateNow
			}).parent().css("position :relative ");
			
			
			
		</script>
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		@stop