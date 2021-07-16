@extends('layouts/default')

@section('title',trans('credit.fee_payment'))
@section('page_parent',trans('credit.credits'))

@section('header_styles')
<link href="{{ asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />
@stop
@section('content')

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">
						<i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
						{{trans('credit.fee_payment')}}
					</h3>
					<span class="pull-right clickable">
						<i class="glyphicon glyphicon-chevron-up"></i>
					</span>
				</div>
				<div class="panel-body">
					{!!Form::open(array('url'=>'credit/savePayment','id'=>'id_formulario'))!!}
					<h3>Datos generales</h3>
					<input type="hidden" name="id_pago" value="{{$dataDetailCredit->id}}">
					<input type="hidden" name="total_pago" value="{{$dataDetailCredit->total_payments}}" id="total_pago">
					<div class="row">
						<div class="col-lg-6">
							<label for="">Nombre del cliente: </label>
							<input type="text" name="nombreCliente" id="nombreCliente" value="{{$customer_name}}" disabled class="form-control">
						</div>
						<div class="col-lg-3">
							<label for="">Total de pago: </label>
							<input type="text" name="totalPago" id="totalPago" align="right" value="Q {{number_format($dataDetailCredit->total_payments,2)}}" disabled class="form-control">
						</div>
						<div class="col-lg-3">
							<label for="">Fecha sugerida de pago: </label>
							<input type="text" name="fechaPago" id="fechaPago" value="{!! date('d/m/Y', strtotime($dataDetailCredit->date_payments)) !!}" disabled class="form-control">
						</div>												
					</div>
					
					@if($bandera==1)
					<div class="row">
						<div class="col-lg-6">
							<label for="document"><b>Si desea alguno documento seleccione alguno: </b></label>
							<select class="form-control" name="document" id="id_documento">
								<option value="0">Ningun documento</option>
								@foreach($documentos as $value)
								<option value="{{$value->id}}">{{$value->documento}}</option>
								@endforeach
							</select>
							<br>
							<label for="correlative"><b>Ingrese correlativo:</b></label>
							<input type="text" name="correlative" value="" id="id_correlative" class="form-control" size="6">
						</div>
					</div>
					@endif
				</div>
				<div class="panel-body">
					<h3>Datos de pago</h3>
					<div class="col-lg-12">
						<div class="col-lg-6">
							{!! Form::label('name', trans('Fecha real de pago').' *') !!}
							<input type="text" name="payment_real_date"  id='admited_at' class="form-control" >
							<!-- {!! Form::text('payment_real_date', Input::old('payment_real_date'), array('id'=>'admited_at','class' => 'form-control')) !!} -->
						</div>
						<div class="col-lg-6">
							<label for="">Recargo</label>
							<input type="text" name="monto_recargo" value="0" style="text-align: right;" id="monto_recargo" class="form-control" @if($bandera==1) readonly @endif>
						</div>
					</div>
				</div>
				
				<div class="panel-body">
					<div class="col-lg-12">
						<div class="col-lg-6">
							<label for="">Total a pagar</label>
							<input type="text" name="monto_total" style="text-align: right;" id="monto_total2" @if($bandera==1) readonly @endif value="0" class="form-control">
						</div>
						<div class="col-lg-6">
							<label for="">Total con recargo</label>
							<input type="text" name="monto_total_name" value="" style="text-align: right;" id="monto_total" class="form-control" readonly>
						</div>
					</div>
				</div>
				<div class="panel-body">
					<div class="col-lg-12">
						<!-- <div class="col-lg-6"> -->
							<input type="hidden" name="bandera" value="{{$bandera}}" id="id_bandera">
							<!-- {!!Form::submit(trans('Guardar pago'),array('class'=>'btn btn-primary','id'=>'id_guardar_pago'))!!} -->
							<!-- <a class="btn btn-danger" href="{{ url('/credit') }}" > -->
								<!-- <a class="btn btn-danger" onclick="window.history.back();" >
									
									Cancelar
								</a> -->
								@include('partials.buttons',['cancel_url'=>'credit/' . $credit_id . '/edit'])
								<!-- </div> -->
							</div>
						</div>
						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	</section>
	@endsection
	@section('footer_scripts')
	<script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
	<script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
	<script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
	<script>
		$("#admited_at").datetimepicker({ 
			sideBySide: true, 
			locale:'es',format:'DD/MM/YYYY',
			defaultDate: new Date()
		}).parent().css("position :relative");
	</script>
	<script type="text/javascript">
		
		// var fecha= new Date();
		// var diaMes=fecha.getDate();
		// var mes=fecha.getMonth();
		// var mesReal=parseInt(mes)+1;
		// var anio=fecha.getFullYear();
		// var visualizarFecha=document.getElementById('admited_at');
		// visualizarFecha.value=diaMes+'/'+mesReal+'/'+anio;
		var total_pago=$('#total_pago').val();
		//inicializacion 
		$('#monto_total').val($('#total_pago').val());
		$('#monto_total2').val($('#total_pago').val());
		//inicializacion 
		$('#monto_recargo').blur(cambiarPrecios);
		$('#monto_total2').blur(cambiarPrecios);
		function cambiarPrecios(){
			var recargo=$('#monto_recargo').val();
			var total_a_pagar=$('#monto_total2').val();
			var totalNuevo=parseFloat(total_a_pagar)+parseFloat(recargo);
			$('#monto_total').val(monedaChange(totalNuevo));
		}
		
		function monedaChange(cantidad,cif = 3, dec = 2) {
			// tomamos el valor que tiene el input
			let inputNum =cantidad;
			inputNum = inputNum.toString()
			inputNum = inputNum.split('.')
			if (!inputNum[1]) {
				inputNum[1] = '00'
			}
			let separados
			if (inputNum[0].length > cif) {
				let uno = inputNum[0].length % cif
				if (uno === 0) {
					separados = []
				} else {
					separados = [inputNum[0].substring(0, uno)]
				}
				let posiciones = parseInt(inputNum[0].length / cif)
				for (let i = 0; i < posiciones; i++) {
					let pos = ((i * cif) + uno)
					separados.push(inputNum[0].substring(pos, (pos + 3)))
				}
			} else {
				separados = [inputNum[0]]
			}
			return valorTotalFormateado =  separados.join(',') + '.' + inputNum[1];
		}
		$('#id_guardar_pago').click(function(){
			document.getElementById('id_formulario').addEventListener('submit',validaciones,true);
		});
		function validaciones(event){
			if($('#admited_at').val()==""){
				alert("elija la fecha");
				event.preventDefault();
				return -1;
			}else if($('#monto_total2').val()==0){
				$('#monto_total2').focus();
				event.preventDefault();
				return -1;
			}else if($('#monto_total').val()=='0.00'){
				$('#monto_total2').focus();
				event.preventDefault();
				return -1;
			}
			
			if($('#id_bandera').val()==1){
				if($('#id_documento').val()==0){
					event.preventDefault();
					alert("Seleccione un documento");
				}
				else if($('#id_correlative').val()==0 || $('#id_correlative').val()==''){
					event.preventDefault();
					alert("Escriba un correlativo");
					$('#id_correlative').focus();
				}
			}
		}
	</script>
	@stop
	