@extends('layouts/default')

@section('title',trans('credit.credit_detail'))
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
@stop
@section('content')

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">
						<i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
						{{trans('credit.credit_detail')}}
					</h3>
					<span class="pull-right clickable">
						<i class="glyphicon glyphicon-chevron-up"></i>
					</span>
				</div>
				<div class="panel-body">
										
					<br>
					<div class="row">
						<div class="col-lg-4">
							{{-- <input type="hidden" name="id_credit" value="{!!$dataCredit->id!!}" class="form-control" disabled> --}}
							<label for="">Nombre del cliente: </label>
							<input type="text" name="nombreCliente" id="nombreCliente" value="{{$cliente->name}}" disabled
							class="form-control">
						</div>
						
						<div class="col-lg-4">
							<label for="">Monto de cuenta por cobrar: </label>
							<input type="text" name="saldoCredito" id="saldoCredito" value="@money($totalSaldo)" disabled class="form-control">
						</div>
						<div class="col-lg-4">
							<label for="">Monto a abonar: </label>
							<input type="text" name="montoCredito" id="montoCredito" value="0.00"  class="form-control">							
						</div>
						
					</div>
					<br>
					<div class="bs-example">
						<ul class="nav nav-tabs" style="margin-bottom: 15px;">
							<li class="active">
								<a href="#abono" data-toggle="tab">Abono</a>
							</li>
							<li id="tab_pago">
								<a href="#facturas" data-toggle="tab" id="link_pago">Facturas pendientes</a>
							</li>
						</ul>
						<div id="tabsAbono" class="tab-content">
							<div class="tab-pane fade active in" id="abono">
								
								{!! Form::open(array('url' => 'banks/revenues', 'files' => true, 'id'=>'frmSup')) !!}							
								
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
												{!! Form::text('amount', Input::old('amount'), array('class' => 'form-control
												money_efectivo2','placeholder'=>'Monto', 'onKeyup'=>'applyRetentions(this)')) !!}
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
											{!! Form::label('bank_name', trans('revenues.bank_name_check')) !!}
											<input type="text" class="form-control" id="bank_name" name="bank_name">
										</div>
									</div>
									<div class="col-lg-2">
										<div class="form-group">
											{!! Form::label('same_bank', trans('revenues.same_bank')) !!}
											<select class="form-control" title="trans('revenues.same_bank')" name="same_bank" id="same_bank">
												<option value="1">-Si-</option>
												<option value="0">-No-</option>
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
									<input type="hidden" name="jRetentions" id="jRetentions" value="">
									@if (isset($retentions))
									<input type="hidden" id="_jretentions" value="{{json_encode($retentions)}}">
									<div class="row">
										<h4>Retenciones</h4>
										@foreach ($retentions as $key => $value)
										<div class="col-lg-6">
											<div class="form-group">
												{!! Form::label('amount', $value->name.' sugerido. ('.number_format($value->percent, 2).'%)') !!}
												{!! Form::text('r'.$value->name, Input::old('r'.$value->name), array('id'=>'r'.$value->id, 'class' => 'form-control money','placeholder'=>$value->name, 'readonly'=>'true')) !!}
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
												{!! Form::label('amount', $value->name.' ingresado.') !!}
												{!! Form::text($value->id, Input::old($value->id), array('id'=>$value->id, 'class' => 'form-control money','placeholder'=>$value->name, 'onChange'=>'buildJson()')) !!}
											</div>
										</div>
										@endforeach
									</div>
									
									@endif
									<br>
									<div class="col-lg-12">
										@include('partials.buttons',['cancel_url'=>"/banks/revenues"])
									</div>
									{!! Form::close() !!}
								</div> 
								{{-- Fin Div abono --}}
								<div class="tab-pane fade" id="facturas">						
									<br>
									<!--  Asignamos el monto total del credito-->
									<h4 style="text-align:center"><strong>FACTURAS PENDIENTES DE PAGO</strong></h4>
									<div class="row">
										<div  class="col-lg-10">
											{{-- <table class="table table-striped table-bordered table-hover dataTable no-footer" id="facturas_pendientes" role="grid"> --}}
												<table class="table table-bordered" id="tableDetailId">
													<thead>
														<tr role="row">
															<th class="sorting_asc" tabindex="0" aria-controls="sample_editable_1" rowspan="1" colspan="1">Documento</th>
															<th class="sorting" tabindex="0" aria-controls="sample_editable_1" rowspan="1" colspan="1"  style="width: 222px;">Monto</th>
															<th class="sorting" tabindex="0" aria-controls="sample_editable_1" rowspan="1" colspan="1"  style="width: 222px;">Saldo</th>
															<th class="sorting" tabindex="0" aria-controls="sample_editable_1" rowspan="1" colspan="1"  style="width: 222px;">Vence</th>
															<th class="sorting" tabindex="0" aria-controls="sample_editable_1" rowspan="1" colspan="1"  style="width: 124px;">Abono</th>								
															{{-- <th class="sorting" tabindex="0" aria-controls="sample_editable_1" rowspan="1" colspan="1"  style="width: 88px;">Abonar?</th>								 --}}
														</tr>
													</thead>
													<tbody>
														@foreach($dataCredit as $key => $value)
														<tr role="row" class="odd">
															<td class="sorting_1">{{!empty($value->invoice->serie->document->name) ? $value->invoice->serie->document->name: trans('general.na')}}</td>
															<td>@money($value->credit_total)</td>
															<td>@money($value->credit_total-$value->paid)</td>
															<td class="center">{{$value->date_payments}}</td>
															<td class="abonito"><input type="number" step=".01" id="abono_{{$value->id_factura}}" value="" class="elabono" onblur="calcular(this)"><a class="aplicar" href="javascript:;">Aplicar</a></td>
															{{-- <td>
																<a class="aplicar" href="javascript:;">Aplicar</a>	
															</td> --}}
														</tr>
														@endforeach
													</tbody>
													<tfoot>
														<tr>								
															<th colspan="2" style="text-align:right">Total:</th>
															<th></th>
															<th></th>
															<th>{!! Form::text('total_abono', null, array('class' => 'form-control','id'=>'total_abono')) !!}</th>
														</tr>
													</tfoot>
												</table>
											</div>	
											<div class="col-lg-2">
												<div class="form-group">
													<a class="btn btn-info	" href="{{ url('/credit') }}">
														{{trans('button.apply')}}
													</a>
												</div>
											</div>
											
										</div>
									</div>
								</div>{{-- div facturas pendientes --}}				
							</div>				
						</div>
					</div>
				</section>
				
				@endsection
				@section('footer_scripts')
				<!--  Calendario -->			
				<script src="{{ asset('assets/vendors/moment/js/moment.min.js') }} " type="text/javascript "></script>
				<script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }} " type="text/javascript "></script>
				<script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }} " type="text/javascript"></script>
				<!-- Valiadaciones -->
				<script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
				{{-- Select2 --}}
				<script type="text/javascript" src="{{ asset('assets/vendors/select2/js/select2.js')}} "></script>
				<script type="text/javascript">
					$( document ).ready(function() {
						/* ABONO INICIO*/
						$('#account_name').focus();
						
						$('select').select2({ 
							allowClear: true,
							theme: "bootstrap",
							placeholder: "Buscar"
						});      
						$('#frmSup')
						.bootstrapValidator({
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
								account_id:{
									validators:{
										notEmpty:{
											message:'Debe seleccionar la cuenta.'
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
								},
								description:{
									validators:{
										notEmpty:{
											message:'Debe ingresar la descripción.'
										}									
									}
								},
								bank_name: {
									enabled:false,
									validators:{
										notEmpty:{
											message:'Debe ingresar el nombre del banco.'
										},
										stringLength:{
											min:5,
											message:'Debe ingresar por lo menos 5 caractéres.'
										}
									}
								},
								same_bank: {
									enabled:false,
									validators:{
										notEmpty:{
											message:'Debe confirmar si el cheque es del mismo banco.'
										}
									}
								},
								reference: {
									enabled:false,
									validators:{
										notEmpty:{
											message:'Ingrese el número de cheque/transacción/depósito.'
										},
										stringLength:{
											min:2,
											message:'Debe ingresar por lo menos 2 caractéres.'
										}
									}
								},
								card_name: {
									enabled:false,
									validators:{
										notEmpty:{
											message:'Debe ingresar el nombre en la tarjeta.'
										},
										stringLength:{
											min:7,
											message:'Debe ingresar por lo menos 7 dígitos.'
										}
									}
								},
								
								card_number:{
									enabled:false,
									validators:{
										notEmpty:{
											message:'Debe ingresar los últimos 4 dígitos de la tarjeta.'
										},
										stringLength:{
											min:4,
											max:4,
											message:'Debe ingresar 4 dígitos.'
										}
									}
								}
							}
						})
						.on('change', '[name="payment_method"]', function() {
							/*jramirez 2019.09.19
							* Cuando cambie el tipo de pago habilitaremos o no ciertos validators (validaciones)
							*/
							var forma_pago = Number($(this).val());
							// console.log('cambio pago '+forma_pago);
							/*Por default cada vez que cambie deshabilitamos los validators especificos*/
							$('form').bootstrapValidator('enableFieldValidators', 'card_name',false,null);
							$('form').bootstrapValidator('enableFieldValidators', 'card_number',false,null);
							$('form').bootstrapValidator('enableFieldValidators', 'reference',false,null);								
							$('form').bootstrapValidator('enableFieldValidators', 'same_bank',false,null);
							$('form').bootstrapValidator('enableFieldValidators', 'bank_name',false,null);
							
							/*Dependiendo de la forma de pago habilitamos ciertos validators*/
							switch (forma_pago) {
								case 2: 
								case 5: 
								/*cheque*/ /*transferencia*/
								$('form').bootstrapValidator('enableFieldValidators', 'bank_name',true,null);
								$('form').bootstrapValidator('enableFieldValidators', 'same_bank',true,null);
								$('form').bootstrapValidator('enableFieldValidators', 'reference',true,null);
								// console.log(' cheque/transfer '+forma_pago);
								break;
								case 3: /* Deposito */
								$('form').bootstrapValidator('enableFieldValidators', 'reference',true,null);
								// console.log(' Depósito '+forma_pago);
								break;
								case 4:
								/*Tarjeta*/
								$('form').bootstrapValidator('enableFieldValidators', 'card_name',true,null);
								$('form').bootstrapValidator('enableFieldValidators', 'card_number',true,null);
								$('form').bootstrapValidator('enableFieldValidators', 'reference',true,null);
								// console.log(' Tarjeta '+forma_pago);
								break;												
							}						
						});
						
						cambiopago($('#payment_method').val())
						/* ABONO FIN */
						
						// $(".abonito").keydown(function(event){
							// 	if( event.which == 13)
							// 	{
								// 		event.preventDefault(); 
								// 	}else {				
									// 		console.log(' valor '+$(this).val());
									// 		var monto_pagado = cleanNumber($(this).val());
									// 		$('#total_abono').val(number_format(monto_pagado));
									// 		console.log(monto_pagado);
									// 		console.log('valor '+$(this).parent().val() );
									// 	}
									// });
									
									
								});
								
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
									var totalito = cleanNumber($('#total_abono').val());
									
									console.log(cleanNumber(input.value));
									$('#total_abono').val(totalito+cleanNumber(input.value));
								}
								
								//variables para los creditos
								var saldoCredito=document.getElementById('saldoTotal');
								// console.log(saldoCredito.value);
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
										
										// Total over all pages
										total = api
										.column( 2 )
										.data()
										.reduce( function (a, b) {
											return intVal(a) + intVal(b);
										}, 0 );			
										
										// Update footer
										$( api.column( 2 ).footer() ).html(
										'Q  '+number_format(total,2)
										);
										
									},
								});
								
								table.on('click', '.aplicar', function (e) {
									e.preventDefault();
									/* Get the row as a parent of the link that was clicked on */
									var nRow = $(this).parents('td')[0];
									console.log(nRow.childNodes[0]);
									
									// console.log(' monto '+nRow.find(".elabono"));
									
								});
							</script>
							@endsection
							