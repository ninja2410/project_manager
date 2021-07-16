@extends('layouts/default')

@section('title',trans('credit.customer_payment'))
@section('page_parent',trans('credit.credits'))

@section('header_styles')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css" />  --}}
<link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css" />
<!--  Calendario -->
<link href="{{ asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css"/>
{{-- select 2 --}}
<link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css" />
{{-- wizard --}}
<link href="{{ asset('assets/css/pages/wizard.css') }}" rel="stylesheet">
@stop
@section('content')

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">
						<i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
						{{trans('credit.customer_payment')}}
					</h3>
					<span class="pull-right clickable">
						<i class="glyphicon glyphicon-chevron-up"></i>
					</span>
				</div>
				<div class="panel-body">
										
					<br>					
					{!!Form::open(array('url'=>'credit/savePayment','id'=>'addCustomerPayment'))!!}
					<input type="hidden" name="_token" value="{{ csrf_token() }}" />
					{{-- <br>					 --}}
					{{--  --}}
					<div id="rootwizard">
						<ul>
							<li class="nav-item"><a href="#tabAbono" data-toggle="tab" class="nav-link">{{trans('credit.amount_payment')}}</a></li>
							<li class="nav-item"><a href="#tabFacturas" data-toggle="tab" class="nav-link ml-2">{{trans('credit.pending_invoices')}}</a></li>                                
						</ul>						
						<div class="tab-content">
							<div class="tab-pane " id="tabAbono">
								<div class="row">
									<div class="col-lg-6">
										<input type="hidden" name="customer_id" value="{!!$cliente->id!!}" class="form-control">
										<label for="">Nombre del cliente: </label>
										<div class="input-group select2-bootstrap-prepend">
											<div class="input-group-addon"><i class="fa fa-users"></i></div>
											<input type="text" name="nombreCliente" id="nombreCliente" value="{{$cliente->name}}" disabled class="form-control">
										</div>
									</div>
									
									<div class="col-lg-6">
										<label for="">Monto de cuenta por cobrar: </label>
										<div class="input-group select2-bootstrap-prepend">
											<div class="input-group-addon">Q</div>
											<input type="text" name="saldoCredito" id="saldoCredito" value="@money($totalSaldo)" disabled class="form-control" style="text-align:right" >
											<input type="hidden" name="saldo_total_cliente" id="saldo_total_cliente" value="{{$totalSaldo}}" >
										</div>
									</div>
								</div>
								
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
											{!! Form::label('payment_method', trans('revenues.payment_method')) !!}
											<div class="input-group select2-bootstrap-prepend">
												<div class="input-group-addon"><i class="fa fa-credit-card"></i></div>
												<select class="form-control" title="trans('revenues.payment_method')" name="payment_method" id="payment_method" onchange="cambiopago(this.value);">
													<option value="">--Seleccione--</option>
													@foreach($payments as $item)
													<option value="{!! $item->id !!}" @if(old('payment_method') === $item->id) selected="selected" @endif>{{ $item->name }}</option>
													@endforeach
												</select>
											</div>
										</div>									
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group">										
											{!! Form::label('account_id', trans('revenues.account')) !!}
											<div class="input-group select2-bootstrap-prepend">
												<div class="input-group-addon"><i class="fa fa-money"></i></div>
												<select class="form-control" name="account_id" id="account_id">												
												</select>
											</div>										
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											{!! Form::label('amount', trans('revenues.amount')) !!} 
											<div class="input-group">
												<span class="input-group-addon"><strong>Q</strong></span>
												{!! Form::text('amount', Input::old('amount'), array('class' => 'form-control money_efectivo2','placeholder'=>'Monto','onblur'=>'SetMontoAbono(this)')) !!}
											</div>
										</div>									
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											{!! Form::label('description', trans('revenues.description').' ') !!} 
											{!! Form::textarea('description', Input::old('description'), array('class' => 'form-control','size' => '30x2','placeholder'=>'Motivo del ingreso')) !!}
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-4">
										<div class="form-group">
											{!! Form::label('bank_name', trans('revenues.bank_name')) !!}
											<input type="text" class="form-control" id="bank_name" name="bank_name">
										</div>
									</div>
									<div class="col-lg-2">
										<div class="form-group">
											{!! Form::label('same_bank', trans('revenues.same_bank')) !!}
											<select class="form-control" title="trans('revenues.same_bank')" name="same_bank" id="same_bank">
												<option value="0">-No-</option>
												<option value="1">-Si-</option>												
											</select>
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											{!! Form::label('reference', trans('revenues.reference')) !!} 
											{!! Form::text('reference', Input::old('reference'), array('class'
											=> 'form-control', 'placeholder'=>'No. de documento u otra referencia')) !!}
										</div>
									</div>
									
								</div>
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group">
											{!! Form::label('card_name', trans('revenues.card_name')) !!}
											<input type="text" class="form-control" id="card_name" name="card_name">										
										</div>
									</div>								
									<div class="col-lg-6">
										<div class="form-group">
											{!! Form::label('card_number', trans('revenues.card_number')) !!} 
											{!! Form::text('card_number', Input::old('card_number'), array('class'=> 'form-control', 'placeholder'=>'XXXX')) !!}
										</div>
									</div>
									
								</div>
								<div class="row">
									{{--
										<div class="col-lg-4">
											<div class="form-group">
												{!! Form::label('customer_id', trans('revenues.customer')) !!}
												<select class="form-control" title="trans('revenues.customer')" name="customer_id" id="customer_id">
													<option value="">-Seleccione cliente-</option>
													@foreach($customers as $item)
													<option value="{!! $item->id !!}" >{{ $item->name }}</option>
													@endforeach
												</select>
											</div>
										</div> --}}
									</div>
									<input type="hidden" name="balance" id="balance">
									<input type="hidden" name="currency" id="currency" value="Q">
									<input type="hidden" name="currency_rate" id="currency_rate" value="1">
									{{-- <input type="hidden" name="payment_method" id="payment_method" value="1"> --}}
									<input type="hidden" name="status" id="status" value="5"> {{-- No conciliado --}}								
									<input type="hidden" name="user_id" id="user_id" value="{{Auth::user()->id}}">									
									{{-- <br> --}}
									{{-- <div class="col-lg-12">
										@include('partials.buttons',['cancel_url'=>"/banks/revenues"])
									</div> --}}
									
								</div> 
								{{-- Fin Div abono --}}
								<div class="tab-pane" id="tabFacturas" disabled="disabled">
									{{-- <div class="tab-pane fade" id="facturas">						 --}}
										<br>
										<div class="row">
											<div class="col-lg-6">
												{{-- <input type="hidden" name="id_credit" value="{!!$dataCredit->id!!}" class="form-control" disabled> --}}
												<label for="">Nombre del cliente: </label>
												<div class="input-group select2-bootstrap-prepend">
													<div class="input-group-addon"><i class="fa fa-users"></i></div>
													<input type="text" name="nombreCliente" id="nombreCliente" value="{{$cliente->name}}" disabled class="form-control">
												</div>
											</div>
											
											<div class="col-lg-6">
												<label for="">Monto abono: </label>
												<div class="input-group select2-bootstrap-prepend">
													<div class="input-group-addon">Q</div>
													<input type="text" name="monto_pago" id="monto_pago" value="" readonly class="form-control" style="text-align:right" >
												</div>
											</div>
										</div>
										
										<!--  Asignamos el monto total del credito-->
										<h4 style="text-align:center"><strong>FACTURAS PENDIENTES DE PAGO</strong></h4>
										<div class="row">
											<div  class="col-lg-12">
												{{-- <table class="table table-striped table-bordered table-hover dataTable no-footer" id="facturas_pendientes" role="grid"> --}}
													<table class="table table-bordered" id="facturas_pendientes">
														<thead>
															<tr role="row" style="text-align:right"">
																<th style="width: 5%;">No.</th>
																<th  tabindex="0" style="width: 20%;">Documento</th>
																<th  tabindex="0"  style="width: 20%;" >Monto factura</th>
																<th  tabindex="0"  style="width: 15%;" >Saldo</th>
																<th  tabindex="0"  style="width: 20%;">Vence</th>
																<th  tabindex="0"  style="width: 15%;">Abono</th>								
																{{-- <th  tabindex="0"  style="width: 15%;">Abonar?</th> --}}
															</tr>
														</thead>
														<tbody>
															@foreach($dataCredit as $key => $value)
															<tr role="row" class="odd">
																<input type="hidden" value="{{$value->id}}" name="id_credito_{{$value->id}}" id="id_credito_{{$value->id}}">
																<td>{{$key+1}}<input type="hidden" value="{{$value->id_factura}}" name="id_factura_{{$value->id}}" id="id_factura_{{$value->id}}"> </td>
																<td class="sorting_1">{{!empty($value->invoice->serie->document->name) ? $value->invoice->serie->document->name: trans('general.na')}}  {{!empty($revenue->invoice->serie->name) ? $revenue->invoice->serie->name: ''}}{{!empty($value->invoice->correlative) ? $value->invoice->correlative: ''}}</td>
																<td style="text-align:right">@money($value->credit_total)</td>
																<td style="text-align:right">@money($value->balance)<input type="hidden" id="saldo_{{$value->id}}" name="saldo_{{$value->id}}" value="{{$value->balance}}"></td>
																<td style="text-align:center">{{$value->date_payments}}</td>
																<td class="abonito"><input type="number" step="0.01" class="form-control input-small addPayment" value="0.00" id="abono_{{$value->id}}" name="abono_{{$value->id}}" onblur="setAmount(this)" style="text-align:right" correlativo={{$value->id}}></td>
															</tr>
															@endforeach
														</tbody>
														<tfoot>
															<tr>								
																<th colspan="2" style="text-align:right">Total:</th>
																<th></th>
																<th>@money($totalSaldo)</th>
																<th></th>
																<th>
																	<div class="form-group {{ $errors->first('total_pagos', 'has-error') }}">
																		{!! Form::text('total_pagos', null, array('class' => 'form-control','id'=>'total_pagos','style'=>'text-align:right;','readonly'=>'readonly')) !!}
																		{!! $errors->first('total_pagos', '<span class="help-block">:message</span>') !!}
																	</div>
																</th>
																{{-- <th></th> --}}
															</tr>
														</tfoot>
													</table>
												</div>	
												<input type="hidden" value="{{$key+1}}" name="invoices_count" id="invoices_count">
												{{-- <div class="col-lg-2">
													<div class="form-group">
														<a class="btn btn-info	" href="{{ url('/credit') }}">
															{{trans('button.apply')}}
														</a>
													</div>
												</div> --}}
												
											</div>
										</div>
									</div>{{-- div facturas pendientes --}}				
									<ul class="pager wizard">
										<li class="previous"><a href="#">Anterior</a></li>                                    
										<li class="next"><a href="#"><strong>Siguiente</strong></a></li>
										<li class="next finish" style="display:none;"><a class="btn-success" href="javascript:;">Guardar</a></li>
										{{-- <li class="next finish" style="display:none;"><a class="btn-success" style="color:white !important;" href="javascript:;">Guardar</a></li> --}}
									</ul>
								</div>				
							</div> {{-- div root wizard  --}}									
							{!! Form::close() !!}
							@include('partials.confirm-save')
						</div>
					</section>
					
					@endsection
					@section('footer_scripts')
					{{-- wizard --}}
					<script src="{{ asset('assets/vendors/bootstrapwizard/jquery.bootstrap.wizard.js') }}" type="text/javascript"></script>
					<!--  Calendario -->			
					<script src="{{ asset('assets/vendors/moment/js/moment.min.js') }} " type="text/javascript "></script>
					<script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }} " type="text/javascript "></script>
					<script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }} " type="text/javascript"></script>
					<!-- Valiadaciones -->
					<script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
					{{-- Select2 --}}
					<script type="text/javascript" src="{{ asset('assets/vendors/select2/js/select2.js')}} "></script>
					<script src="{{ asset('assets/js/pages/addcustomerpayment.js') }}"></script>
					<script type="text/javascript">
						$( document ).ready(function() {
							/* ABONO INICIO*/
							$('#account_name').focus();
							
							$('select').select2({ 
								allowClear: true,
								theme: "bootstrap",
								placeholder: "Buscar"
							});      							
							
							cambiopago($('#payment_method').val())
							// 
							var table = $('#facturas_pendientes');
							
							var oTable = table.DataTable({
								"language": {
									"url": "{{ asset('assets/json/Spanish.json') }}"
								},
								xscrollable:true,
								dom: 'frt',
								"footerCallback": function ( row, data, start, end, display ) {
									var api = this.api(), data;
									
									// Remove the formatting to get integer data for summation
									var intVal = function ( i ) {
										return typeof i === 'string' ?
										i.replace(/[\$,Q,]/g, '')*1 :
										typeof i === 'number' ?
										i : 0;
									};
									
									// Total monto
									total = api
									.column( 2 )
									.data()
									.reduce( function (a, b) {
										return intVal(a) + intVal(b);
									}, 0 );			
									
									// Update footer
									$( api.column(2 ).footer() ).html(
									'Q  '+number_format(total,2)
									);										
									
								},
							});							
							// 
							
						});
						
						function SetMontoAbono (amount)
						{
							$('#monto_pago').val(amount.value);
						}
						
						function setAmount (input)
						{
							// console.log(input);
							
							var idElement=input.id;
							var correlativo = $('#'+idElement).attr('correlativo');
							var monto_abono = input.value;

							// console.log('correlativo '+correlativo);
							// console.log('id '+idElement);
							// console.log('abono '+monto_abono);
							
							var saldo=$('#saldo_'+correlativo).val();
							// console.log(' abono '+monto_abono+' saldo '+saldo);
							if(cleanNumber(monto_abono)>cleanNumber(saldo)){
								toastr.error("No puede pagar más (Q "+monto_abono+") que el saldo pendiente (Q "+saldo+")");
								$('#abono_'+correlativo).val(saldo);
								calculateTotal();
							}else {
								calculateTotal();
							}
							
						};
						/*Funciones para sumar los acumulados*/
						function calculateTotal(){
							// console.log(qtTemp);
							// var totalInputs=document.getElementsByClassName('addPayment');
							// var elementsArray=Array.prototype.slice.apply(totalInputs);
							// var qT=elementsArray.length;
							//console.log(qT);
							var totalGeneral=0;
							// for (var i = 0; i < qT; i++) {
							// 	var idEl=elementsArray[i].id;
							// 	totalGeneral=totalGeneral+cleanNumber(document.getElementById(idEl).value);
							// }
							// var tjq = 0;
							$('.addPayment').each(function(){
								totalGeneral += cleanNumber(this.value);
							});							
							// console.log(' calculateTotal ciclo '+totalGeneral+ ' con jquery '+tjq)
							var monto_a_abonar=cleanNumber(document.getElementById('amount').value);
							if(parseFloat(totalGeneral)>parseFloat(monto_a_abonar)){
								toastr.error("Solo puede abonar un máximo de :"+monto_a_abonar);
								totalGeneral=0;
								$(".addPayment").val(0);									// totalGeneral.value=0;
								calculateTotal();
							}
							document.getElementById('total_pagos').value=number_format(totalGeneral,2);
							// document.getElementById('idEsteRecibo').value=monedaChange(totalGeneral);
							// saldoActual(totalGeneral);
						}
						
						/*TABS */
						$('#tabsAbono').slimscroll({
							height: '100%',
							size: '3px',
							color: '#D84A38',
							opacity: 1
							
						});
						
						function cambiopago (pago_id) {
							// console.log('account_id antes: '+account_id);
							
							if(pago_id) {
								$.get(APP_URL+'/banks/get-account-type/'+[pago_id]+'/1',function(data) {
									//console.log('respuesta ajax cambiopaciente : '+data);
									$('#account_id').empty();
									$('#account_id').append('<option value="">Seleccione cuenta</option>');
									$.each(data, function(index,accounts){
										$('#account_id').append('<option value="'+ accounts.id +'">'+ accounts.name +' - ' +accounts.pct_interes+'</option>');
									});
								});
							}else{
								$('select[name="account_id"]').empty();
							};
							$('#payment_method').val(pago_id);
							// console.log('Adm id: '+$('#account_id').val());
						};
						
						var dateNow = new Date();
						$("#paid_at ").datetimepicker({ sideBySide: true, locale:'es', format:'DD/MM/YYYY', defaultDate:dateNow}).parent().css("position :relative ");
						
						function calcular(input)
						{
							var totalito = cleanNumber($('#total_pagos').val());
							
							console.log(cleanNumber(input.value));
							$('#total_pagos').val(totalito+cleanNumber(input.value));
						}
						
						//variables para los creditos
						var saldoCredito=document.getElementById('saldoTotal');
						
						
						
					</script>
					@endsection
					