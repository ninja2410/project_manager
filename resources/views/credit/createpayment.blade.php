@extends('layouts/default')

@section('title',trans('credit.customer_payment'))
@section('page_parent',trans('credit.credits'))

@section('header_styles')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css" />
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
					<div id="rootwizard">
						<ul>
							<li class="nav-item"><a href="#tabFacturas" data-toggle="tab" class="nav-link ml-2">{{trans('credit.pending_invoices')}}</a></li>
							<li class="nav-item"><a href="#tabAbono" data-toggle="tab" class="nav-link">{{trans('credit.amount_payment')}}</a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane" id="tabFacturas" >
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
										<table class="table table-bordered" id="facturas_pendientes">
											<thead>
												<tr role="row" style="text-align:right">
													<th style="width: 5%;">No.</th>
													<th  tabindex="0" style="width: 30%;">{{trans('credit.document')}}</th>
													<th  tabindex="0"  style="width: 15%;" >{{trans('credit.amount_invoice')}}</th>
													<th  tabindex="0"  style="width: 15%;" >{{trans('credit.balance')}}</th>
													<th  tabindex="0"  style="width: 15%;">{{trans('credit.due_date')}}</th>
													<th  tabindex="0"  style="width: 15%;">{{trans('credit.amount_payment')}}</th>
													{{-- <th  tabindex="0"  style="width: 15%;">Abonar?</th> --}}
												</tr>
											</thead>
											<tbody>
												@foreach($dataCredit as $key => $value)
												<tr role="row" class="odd">
													<input type="hidden" value="{{$value->id}}" name="id_credito_{{$value->id}}" id="id_credito_{{$value->id}}">
													<td>{{$key+1}}<input type="hidden" value="{{$value->id_factura}}" name="id_factura_{{$value->id}}" id="id_factura_{{$value->id}}"> <input type="hidden"  id="document_{{$value->id}}" value="{{!empty($value->invoice->serie->document->name) ? $value->invoice->serie->document->name: trans('general.na')}}  {{!empty($value->invoice->serie->name) ? $value->invoice->serie->name: ''}}{{!empty($value->invoice->correlative) ? $value->invoice->correlative: ''}}"  tabindex="-1"></td>
													<td class="sorting_1">{{!empty($value->invoice->serie->document->name) ? $value->invoice->serie->document->name: trans('general.na')}}  {{!empty($value->invoice->serie->name) ? $value->invoice->serie->name: ''}}{{!empty($value->invoice->correlative) ? $value->invoice->correlative: ''}}</td>
													<td style="text-align:right">@money($value->credit_total)</td>
													<td style="text-align:right">@money($value->balance)<input type="hidden" id="saldo_{{$value->id}}" name="saldo_{{$value->id}}" value="{{$value->balance}}"></td>
													<td style="text-align:center;font-size:12px; @if($value->vencimiento<1) background-color: #f0b9b6; @endif">{{$value->date_payments}}</td>
													<td class="abonito"><input type="text" class="form-control input-small addPayment" value="0.00" id="abono_{{$value->id}}" name="abono_{{$value->id}}" onblur="setAmount(this)" style="text-align:right" correlativo={{$value->id}}></td>
												</tr>
												@endforeach
											</tbody>
											<tfoot>
												<tr>
													<th colspan="2" style="text-align:right">Total:</th>
													<th style="text-align:right"></th>
													<th style="text-align:right">@money($totalSaldo)</th>
													<th style="text-align:right">Abono Total:</th>
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
									</div>	{{-- Div col-lg-12 --}}
									<?php
									$count = isset($key)?$key+1:0;
									?>
									<input type="hidden" value="{{$count}}" name="invoices_count" id="invoices_count">
								</div>{{-- div row --}}
							</div>{{-- div tabFacturas --}}
							<div class="tab-pane " id="tabAbono" disabled="disabled">
								<div class="row">
									<div class="col-lg-4">
										<input type="hidden" name="customer_id" value="{!!$cliente->id!!}" class="form-control">
										<label for="">Nombre del cliente: </label>
										<div class="input-group select2-bootstrap-prepend">
											<div class="input-group-addon"><i class="fa fa-users"></i></div>
											<input type="text" name="nombreCliente1" id="nombreCliente1" value="{{$cliente->name}}" disabled class="form-control">
										</div>
									</div>

									<div class="col-lg-3">
										<label for="">Saldo de cuenta por cobrar: </label>
										<div class="input-group select2-bootstrap-prepend">
											<div class="input-group-addon">Q</div>
											<input type="text" name="saldoCredito" id="saldoCredito" value="@money($totalSaldo)" disabled class="form-control" style="text-align:right" >
											<input type="hidden" name="saldo_total_cliente" id="saldo_total_cliente" value="{{$totalSaldo}}" >
										</div>
									</div>
									<div class="col-lg-3">
										<div class="form-group">
											{!! Form::label('document_id', trans('revenues.document')) !!}
											<div class="input-group select2-bootstrap-prepend">
												<div class="input-group-addon"><i class="fa fa-file"></i></div>
												<select class="form-control" title="{{trans('revenues.document')}}" name="serie_id" id="serie_id">
													@foreach($series as $item)
														<option value="{!! $item->id !!}" @if(old('serie_id') === $item->id) selected="selected" @endif>{{ $item->document->name.' '.$item->name }}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class="col-lg-2">
										<div class="form-group">
											{!! Form::label('receipt_number', trans('revenues.receipt_number')) !!}
											<div class="input-group">
												<span class="input-group-addon"><strong>#</strong></span>
												{!! Form::number('receipt_number', old('receipt_number',$receipt_number), array('class' => 'form-control','placeholder'=>'No. Recibo','id'=>'receipt_number', 'readonly')) !!}
											</div>
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
												<select class="form-control" title="trans('revenues.payment_method')" name="payment_method" id="payment_method" >
													<option value="">--Seleccione--</option>
													@foreach($payments as $item)
													<option value="{!! $item->id !!}" @if(old('payment_method') === $item->id) selected="selected" @endif type="{!! $item->type !!}">{{ $item->name }}</option>
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
												{!! Form::text('amount', Input::old('amount'), array('class' => 'form-control money_efectivo2','placeholder'=>'Monto','readonly'=>'readonly')) !!}
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
										<div class="form-group banco" style="display:none">
											{!! Form::label('banco', trans('revenues.bank_name')) !!}
											{{-- <input type="text" class="form-control" id="bank_name" name="bank_name"> --}}
											<select class="form-control" title="" name="bank_name" id="bank_name" required>
												<option value="">Seleccione banco</option>
												<option value="Banco Agromercantil (BAM)" @if(old('bank_id') === 'Banco Agromercantil (BAM)') selected="selected" @endif>Banco Agromercantil (BAM)</option>
												<option value="Banco industrial" @if(old('bank_id') === 'Banco industrial') selected="selected" @endif>Banco industrial</option>
												<option value="BAC-Credomatic" @if(old('bank_id') === 'BAC-Credomatic') selected="selected" @endif>BAC-Credomatic</option>
												<option value="Banco Promerica" @if(old('bank_id') === 'Banco Promerica') selected="selected" @endif>Banco Promerica</option>
												<option value="Banco Internacional" @if(old('bank_id') === 'Banco Internacional') selected="selected" @endif>Banco Internacional</option>
												<option value="Banco G&T Continental" @if(old('bank_id') === 'Banco G&T Continental') selected="selected" @endif>Banco G&T Continental</option>
												<option value="Banrural" @if(old('bank_id') === 'Banrural') selected="selected" @endif>Banrural</option>
												<option value="Bantrab" @if(old('bank_id') === 'Bantrab') selected="selected" @endif>Bantrab</option>
												<option value="Vivibanco" @if(old('bank_id') === 'Vivibanco') selected="selected" @endif>Vivibanco</option>
												<option value="Banco Ficohsa" @if(old('bank_id') === 'Banco Ficohsa') selected="selected" @endif>Banco Ficohsa</option>
											</select>
										</div>
									</div>
									<div class="col-lg-2">
										<div class="form-group mismo_banco" style="display:none">
											{!! Form::label('same_bank', trans('revenues.same_bank')) !!}
											<select class="form-control" title="trans('revenues.same_bank')" name="same_bank" id="same_bank">
												<option value="0">-No-</option>
												<option value="1">-Si-</option>
											</select>
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group referencia" style="display:none">
											{!! Form::label('reference', trans('revenues.reference'),array('id'=>'reference_id')) !!}
											{!! Form::text('reference', Input::old('reference'), array('class'
											=> 'form-control', 'placeholder'=>'No. de documento u otra referencia')) !!}
										</div>
									</div>

								</div>
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group tarjeta" style="display:none">
											{!! Form::label('card_name', trans('revenues.card_name')) !!}
											<input type="text" class="form-control" id="card_name" name="card_name">
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group tarjeta" style="display:none">
											{!! Form::label('card_number', trans('revenues.card_number')) !!}
											{!! Form::text('card_number', Input::old('card_number'), array('class'=> 'form-control', 'placeholder'=>'XXXX')) !!}
										</div>
									</div>

								</div>

								<input type="hidden" name="balance" id="balance">
								<input type="hidden" name="currency" id="currency" value="Q">
								<input type="hidden" name="currency_rate" id="currency_rate" value="1">
								<input type="hidden" name="status" id="status" value="5"> {{-- No conciliado --}}
								<input type="hidden" name="user_id" id="user_id" value="{{Auth::user()->id}}">
							</div> {{-- Fin Div abono --}}
							<ul class="pager wizard">
								<li class="previous"><a href="#">Anterior</a></li>
								<li class="next"><a href="#"><strong>Siguiente</strong></a></li>
								<li class="next finish" style="display:none;"><a class="btn-success" href="javascript:;">Guardar</a></li>
							</ul>
						</div>{{-- div tab content  --}}
					</div> {{-- div root wizard  --}}
					{!! Form::close() !!}
					@include('partials.confirm-save')
				</div>{{-- div panel body --}}
			</div>{{-- div panel primary --}}
		</div>{{-- div col m12 --}}
	</div>{{-- div row --}}
</section>

@endsection
@section('footer_scripts')
{{-- wizard --}}
<script src="{{ asset('assets/vendors/bootstrapwizard/jquery.bootstrap.wizard.js') }}" type="text/javascript"></script>
{{-- FORMATO DE MONEDAS --}}
<script src="{{asset('assets/js/vuejs/cleave.min.js')}} "></script>
<script src="{{asset('assets/js/vuejs/vue-cleave.min.js')}} "></script>
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
		$('.addPayment').each(function(){
			new Cleave(this, {
				numeral: true,
				numeralPositiveOnly: true,
				numeralThousandsGroupStyle: 'none'
			});
		});
		/* ABONO INICIO*/
		$('#account_name').focus();

		$('select').select2({
			allowClear: true,
			theme: "bootstrap",
			placeholder: "Buscar"
		});

		// cambiopago($('#payment_method').val())
		$('#payment_method').trigger("change");
		//
		var table = $('#facturas_pendientes');

		var oTable = table.DataTable({
			"language": {
				"url": "{{ asset('assets/json/Spanish.json') }}"
			},
			"pageLength": 25,
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
		/**
		* Agregar event listener para que los abonos
		* actualicen el monto a pagar en cuanto se cambie
		*/
		var  abonos = document.getElementsByClassName('abonito');
		for (let index = 0; index < abonos.length; index++) {
			abonos[index].addEventListener('input', function (e) {
				setAmount(e.target);
			});

		}


	});

	/*
	* MOSTRAR LOADING CUANDO SE ENVÍE EL FORMULARIO
	* */

	$('#btn_save_confirm').click(function () {
		$('#confirmSave').modal('hide');
		showLoading("Guardando abonos, por favor espere...");
	});

	function setAmount (input)
	{
		// console.log(input);

		var idElement=input.id;
		var correlativo = $('#'+idElement).attr('correlativo');
		var monto_abono = parseFloat(input.value).toFixed(2);
		//input.value = cleanNumber(input.value);

		var saldo=parseFloat($('#saldo_'+correlativo).val()).toFixed(2);
		console.log(monto_abono);
		// console.log(' abono '+monto_abono+' saldo '+saldo);
		if(cleanNumber(monto_abono)>cleanNumber(saldo)){
			toastr.error("No puede pagar más (Q "+monto_abono+") que el saldo pendiente (Q "+saldo+")");
			$('#abono_'+correlativo).val(saldo);
		}
		var document = $('#document_'+correlativo).val();
		// var descripcion = 'Abono';
		// if(cleanNumber(monto_abono)==cleanNumber(saldo))
		// {
		// 	descripcion = 'Pago total';
		// }
		// if ($('#description').val()==""){
		// 	$('#description').val(descripcion+' : '+document);
		// }
		// else{
		// 	$('#description').val($('#description').val()+' | '+document);
		// }
		calculateTotal();

	};
	/*Funciones para sumar los acumulados*/
	function calculateTotal(){

		var totalGeneral=0;

		/**
		* Recorro los abonos a facturas para obtener el total  */
		$('.addPayment').each(function(){
			totalGeneral += cleanNumber(this.value);
		});
		totalGeneral = parseFloat(totalGeneral).toFixed(2);
		/**
		* Seteo el monto total en el campo monto en el tab de forma de pago */
		document.getElementById('amount').value = totalGeneral;

		$('#monto_pago').val(totalGeneral);
		$('#total_pagos').val(totalGeneral);
		$('#addCustomerPayment').bootstrapValidator('revalidateField', 'total_pagos');
		$('#addCustomerPayment').bootstrapValidator('revalidateField', 'monto_pago');

	}

		/*TABS */
		$('#tabsAbono').slimscroll({
			height: '100%',
			size: '3px',
			color: '#D84A38',
			opacity: 1

		});

		$( "#payment_method" ).change(function() {
			var pago_id = cleanNumber($(this).val());
		// function cambiopago (pago_id) {
			console.log('account_id antes: '+account_id);
			console.log('pago : '+pago_id);

			if(pago_id) {
				var paymen_type = cleanNumber($(this).children(':selected').attr('type'));
				console.log('tipo pago  antes: '+paymen_type);
				$('.banco').hide();
				$('.mismo_banco').hide();
				$('.referencia').hide();
				$('.tarjeta').hide();
				// $('.card_number').hide();
				switch (paymen_type) {
					case 2: /*cheque*/
						$('.banco').show();
						$('.mismo_banco').show();
						$('.referencia').show();
						var label = document.getElementById('reference_id');
						label.innerHTML="# de cheque"
					break
					case 5:
					/*transferencia*/
						$('.banco').show();
						$('.mismo_banco').show();
						$('.referencia').show();
						var label = document.getElementById('reference_id');
						label.innerHTML="# de transferencia"
					// console.log(' cheque/transfer '+forma_pago);
					break;
					case 3: /* Deposito */
						$('.referencia').show();
						var label = document.getElementById('reference_id');
						label.innerHTML="# de depósito"
					// console.log(' Depósito '+forma_pago);
					break;
					case 4:
						/*Tarjeta*/
						$('.tarjeta').show();
						$('.referencia').show();
						var label = document.getElementById('reference_id');
						label.innerHTML="# de voucher"
					break;
				}

				showLoading("Cargando cuentas con el tipo de pago seleccionado...");
				$.get(APP_URL+'/banks/get-account-type/'+[pago_id]+'/1',function(data) {
					$('#account_id').empty();
					$('#account_id').append('<option value="">Seleccione cuenta</option>');
					$.each(data, function(index,accounts){
						$('#account_id').append('<option value="'+ accounts.id +'">'+ accounts.name +' - ' +accounts.pct_interes+'</option>');
					});
					hideLoading();
				});
			}else{
				$('select[name="account_id"]').empty();
			};
			$(this).val(pago_id);
			// console.log('Adm id: '+$('#account_id').val());
		});

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
