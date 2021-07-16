@extends('layouts/default')

@section('title',trans('credit.edit_payment'))
@section('page_parent',trans('credit.credits'))

@section('header_styles')
<link href="{{ asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />
@stop
@section('content')

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading"> <center>Agregar pago</center></div>
				{!!Form::open(array('url'=>'credit/savePayment'))!!}
				<div class="panel-body">
					
					<h3>Datos generales</h3>
					<input type="hidden" name="id_pago" value="{{$dataDetailCredit->id}}">
					<input type="hidden" name="total_pago" value="{{$dataDetailCredit->total_payments}}" id="total_pago">
					<!-- <label for=""><strong>Total de pago: </strong>{{$dataDetailCredit->total_payments}}</label> -->					
					<input type="hidden" name="fecha_pago" id="fecha_pago" value="{{$dataDetailCredit->date_payments}}">
					<!-- <label for=""><strong>Fecha sugerida de pago: </strong>{!! date('d/m/Y', strtotime($dataDetailCredit->date_payments)) !!}</label> -->

					<div class="row">
						<div class="col-lg-6">
							<label for="">Nombre del cliente: </label>
							<input type="text" name="nombreCliente" id="nombreCliente" value="{{$customer_name}}" disabled class="form-control">
						</div>
						<div class="col-lg-3">
							<label for="">Monto cuota: </label>
							<input type="text" name="totalPago" id="totalPago" align="right" value="Q {{$dataDetailCredit->total_payments}}" disabled class="form-control">
						</div>
						<div class="col-lg-3">
							<label for="">Fecha sugerida de pago: </label>
							<input type="text" name="fechaPago" id="fechaPago" value="{!! date('d/m/Y', strtotime($dataDetailCredit->date_payments)) !!}" disabled class="form-control">
						</div>												
					</div>
				</div>
				<div class="panel-body">
					<h3>Datos de pago</h3>
						{!! Form::label('name', trans('Fecha real de pago').' *') !!}
						<!-- {!! Form::text('payment_real_date' ,Input::old('payment_real_date'), array('id'=>'admited_at','class' => 'form-control')) !!} -->
						<input type="text" name="payment_real_date" value="{{date('d/m/Y', strtotime($dataDetailCredit->payment_real_date))}}" id='admited_at'class="form-control">
				</div>
				<div class="panel-body">
					{!! Form::label('name', trans('Recargo').' *') !!}
					<input type="text" name="monto_recargo" value='{{$dataDetailCredit->surcharge}}' id="monto_recargo" class="form-control">
				</div>
				<div class="panel-body">
					<label for="">Total a pagar</label>
					<input type="text" name="monto_total" value="" id="monto_total" class="form-control">
				</div>
				<div class="panel-body">
					<!-- {!!Form::submit(trans('Guardar pago'),array('class'=>'btn btn-primary'))!!} -->
					@include('partials.buttons',['cancel_url'=>'credit/' . $credit_id . '/edit'])
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
<script type="text/javascript">
	$("#admited_at").datetimepicker({ sideBySide: true, locale:'es',format:'DD/MM/YYYY'}).parent().css("position :relative");	
		var fecha= new Date();
    var diaMes=fecha.getDate();
    var mes=fecha.getMonth();
    var anio=fecha.getFullYear();
		// var txtFecha=document.getElementById('datepicker');
		// txtFecha.value=anio+'-'+mes+'-'+diaMes;

		//obtenemos los campos para validar

		var total_pago=document.getElementById('total_pago');
		var fecha_pago=document.getElementById('fecha_pago');
		var monto_recargo=document.getElementById('monto_recargo');
		var monto_total=document.getElementById('monto_total');
		// var addPayment_btn=document.getElementById('addPayment_btn');

		// monto_recargo.value=0;
		monto_total.value=parseFloat(total_pago.value) + parseFloat(monto_recargo.value);

		var cambiarTotal_pago=function(e){
			var monto_cambiar=document.getElementById('monto_recargo');
			var valor_monto_real=document.getElementById('total_pago');
			var valor_total=valor_monto_real.value;
			var valor_total1=parseFloat(valor_total);
			var valor_cambiar=monto_cambiar.value;
			var valor_cambiar1=parseFloat(valor_cambiar);
			console.log(valor_total1+" Monto recargo: "+valor_cambiar1);

			var valorNuevo=parseFloat((valor_total1).toFixed(2)) + parseFloat((valor_cambiar1).toFixed(2));
			var valorRealNuevo=parseFloat((valorNuevo).toFixed(2));
			monto_total.value=valorRealNuevo;
			e.preventDefault();
		}

		monto_recargo.addEventListener('change',cambiarTotal_pago);

		//var arregloFecha=txtFecha.value.split("/");
</script>
@endsection
