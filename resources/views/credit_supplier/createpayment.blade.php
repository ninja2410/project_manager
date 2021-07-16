@extends('layouts/default')

@section('title',trans('credit_supplier.customer_payment'))
@section('page_parent',trans('credit_supplier.credits'))

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
<?php
$key = 0;
?>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">
						<i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
						{{trans('credit_supplier.customer_payment')}}
					</h3>
					<span class="pull-right clickable">
						<i class="glyphicon glyphicon-chevron-up"></i>
					</span>
				</div>
				<div class="panel-body">
					
					<br>
					{!!Form::open(array('url'=>'credit_suppliers','id'=>'addCustomerPayment'))!!}
					<input type="hidden" name="_token" value="{{ csrf_token() }}" />
					<input type="hidden" name="supplier_id" value="{{$cliente->id}}">
					<div id="rootwizard">
						<ul>
							<li class="nav-item"><a href="#tabFacturas" data-toggle="tab" class="nav-link ml-2">{{trans('credit_supplier.pending_invoices')}}</a></li>
							<li class="nav-item"><a href="#tabAbono" data-toggle="tab" class="nav-link">{{trans('credit_supplier.amount_payment')}}</a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane" id="tabFacturas" >
								<br>
								<div class="row">
									<div class="col-lg-6">
										{{-- <input type="hidden" name="id_credit" value="{!!$dataCredit->id!!}" class="form-control" disabled> --}}
										<label for="">{{trans('credit_supplier.supplier_name')}}: </label>
										<div class="input-group select2-bootstrap-prepend">
											<div class="input-group-addon"><i class="fa fa-users"></i></div>
											<input type="text" name="nombreCliente" id="nombreCliente" value="{{$cliente->company_name}}" disabled class="form-control">
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
													<th  tabindex="0" style="width: 20%;">{{trans('credit_supplier.document')}}</th>
													<th  tabindex="0"  style="width: 20%;" >{{trans('credit_supplier.amount_invoice')}}</th>
													<th  tabindex="0"  style="width: 15%;" >{{trans('credit_supplier.balance')}}</th>
													<th  tabindex="0"  style="width: 20%;">{{trans('credit_supplier.due_date')}}</th>
													<th  tabindex="0"  style="width: 15%;">{{trans('credit_supplier.amount_payment')}}</th>
													{{-- <th  tabindex="0"  style="width: 15%;">Abonar?</th> --}}
												</tr>
											</thead>
											<tbody>
												@foreach($dataCredit as $key_ => $value)
												<tr role="row" class="odd">
													<input type="hidden" value="{{$value->id}}" name="id_credito_{{$value->id}}" id="id_credito_{{$value->id}}">
													<td>{{$key_+1}}<input type="hidden" value="{{$value->receiving_id}}" name="id_factura_{{$value->id}}" id="id_factura_{{$value->id}}"> </td>
													<td class="sorting_1"><input id="document_{{$value->id}}" value="{{!empty($value->invoice->serie->document->name) ? $value->invoice->serie->document->name: trans('general.na')}}  {{!empty($value->invoice->serie->name) ? $value->invoice->serie->name: ''}}{{!empty($value->invoice->correlative) ? $value->invoice->correlative: ''.($value->reference==''? '': $value->reference)}}" readonly="readonly" tabindex="-1"></td>
													<td style="text-align:right">@money($value->credit_total)</td>
													<td style="text-align:right">@money($value->balance)<input type="hidden" id="saldo_{{$value->id}}" name="saldo_{{$value->id}}" value="{{$value->balance}}"></td>
													<td style="text-align:center; @if($value->vencimiento<1) background-color: #f0b9b6; @endif" >{{$value->date_payments}}</td>
													<td class="abonito"><input type="text" class="form-control input-small addPayment" value="0.00" id="abono_{{$value->id}}" name="abono_{{$value->id}}" onblur="setAmount(this)" style="text-align:right" correlativo={{$value->id}}></td>
													<?php
													$key = $key_;
													?>
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
									<input type="hidden" value="{{$key+1}}" name="invoices_count" id="invoices_count">
								</div>{{-- div row --}}
							</div>{{-- div tabFacturas --}}
							<div class="tab-pane " id="tabAbono" disabled="disabled">
								<input type="hidden" name="balance_supplier" id="balance_supplier" value="{{$totalSaldo}}">
								@include('partials.payment-types.supplier_payment')
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
<script type="text/javascript" src="{{ asset('assets/js/credit_supplier/payment_type_payments.js')}} "></script>
<script src="{{ asset('assets/js/pages/addsupplierpayment.js') }}"></script>
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

		cambiopago($('#payment_method').val());
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

	/*
	* MOSTRAR LOADING CUANDO SE ENVÍE EL FORMULARIO
	* */

	$('#btn_save_confirm').click(function () {
		$('#confirmSave').modal('hide');
		showLoading("Guardando abonos, por favor espere...");
	});

	/*
	*Setear campo con monto de abono cuando cambie el monto, pero ya
	*no se usara
	*/
	function SetMontoAbono (amount)
	{
		// $('#monto_pago').val(amount.value);
	}

	function setAmount (input)
	{
		// console.log(input);

		var idElement=input.id;
		var correlativo = $('#'+idElement).attr('correlativo');
		var monto_abono = parseFloat(input.value).toFixed(2);
		input.value = cleanNumber(input.value);
		// console.log('monto abono '+cleanNumber(input.value));

		// console.log('correlativo '+correlativo);
		// console.log('id '+idElement);
		// console.log('abono '+monto_abono);

		var saldo=parseFloat($('#saldo_'+correlativo).val()).toFixed(2);
		// console.log(' abono '+monto_abono+' saldo '+saldo);
		if(cleanNumber(monto_abono)>cleanNumber(saldo)){
			toastr.error("No puede pagar más (Q "+monto_abono+") que el saldo pendiente (Q "+saldo+")");
			$('#abono_'+correlativo).val(saldo);
		}
		var document = $('#document_'+correlativo).val();
		var descripcion = 'Abono';
		if(cleanNumber(monto_abono)==cleanNumber(saldo))
		{
			descripcion = 'Pago total';
		}
		if ($('#description').val()==""){
			$('#description').val(descripcion+' : '+document);
		}
		else{
			$('#description').val($('#description').val()+' | '+document);
		}
		calculateTotal();

	};
	/*Funciones para sumar los acumulados*/
	function calculateTotal(){

		var totalGeneral=0;

		/**
		* Recorro los abonos a facturas para obtener el total
		*/
		$('.addPayment').each(function(){
			totalGeneral += cleanNumber(this.value);
		});
		totalGeneral = parseFloat(totalGeneral).toFixed(2);
		/**
		* Seteo el monto total en el campo monto
		* en el tab de forma de pago
		*/
		document.getElementById('amount').value = totalGeneral;
		document.getElementById('paid').value = totalGeneral;
		$('#amount').change();
		$('#paid').change();

		$('#monto_pago').val(totalGeneral);
		$('#addCustomerPayment').bootstrapValidator('revalidateField', 'total_pagos');
		$('#addCustomerPayment').bootstrapValidator('revalidateField', 'monto_pago');
		/*
		* Sección vieja
		*/
		// var monto_a_abonar=cleanNumber(document.getElementById('amount').value);
		// if(parseFloat(totalGeneral)>parseFloat(monto_a_abonar)){
			// 	toastr.error("Solo puede abonar un máximo de :"+monto_a_abonar);
			// 	totalGeneral=0;
			// 	$(".addPayment").val(0);									// totalGeneral.value=0;
			// 	calculateTotal();
			// }
			// document.getElementById('total_pagos').value=number_format(totalGeneral,2);
			$('#total_pagos').val(totalGeneral);

			$('#addCustomerPayment').bootstrapValidator('revalidateField', 'total_pagos');

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
				showLoading("Cargando cuentas con el tipo de pago seleccionado...");
				$.get(APP_URL+'/banks/get-account-type/'+[pago_id]+'/1',function(data) {
					//console.log('respuesta ajax cambiopaciente : '+data);
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
