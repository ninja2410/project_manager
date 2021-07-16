@extends('layouts/default')

@section('title',trans('Configurar cuenta por cobrar'))
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
				<!-- <div class="panel-heading"> <center> Configurar crédito</center></div> -->
				{!!Form::open(array('url'=>'credit','id'=>'formSaveCredit'))!!}
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-6">
							<label for="">Número de factura:</label>
							<input type="hidden" name="id_factura" value="{!!$idFactura!!}">
							<input type="text" name="factura_id" value="{!!
								DB::table('documents')->join('series','documents.id','=','series.id_document')->where('series.id_document',
								'=',$sales->id_serie)->value('documents.name')!!} {!!DB::table('series')->where('id','=',$sales->id_serie)->value('name')!!}-{!!$sales->correlative!!}" class="form-control" disabled>
						</div>
						<div class="col-lg-6">
							<input type="hidden" name="id_cliente" value="{!!$id_cliente!!}">
							<label for="">Nombre del cliente: </label>
							<input type="text" name="nombreCliente" id="nombreCliente" value="{!!$name!!}" disabled
							class="form-control">
						</div>
					</div>
				</div>
				<div class="panel-body">
				<div class="row">
					<div class="col-lg-6">
						<label for="">Monto total de la Factura: </label>
						<input type="text"  value="Q <?php echo number_format($monto,2); ?>" disabled
						class="form-control">
						<input type="hidden" name="totalFactura" id="totalFactura" value="{!!$monto!!}" disabled
						class="form-control">
					</div>
					<div class="col-lg-6">
						<label for="">Tasa de interés: % </label>
						<input type="text" id="totalInteres" name="total_interes" value="" class="form-control" >
					</div>
				</div>
			</div>
				<!-- Calendario dinamico  -->
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-6">
							<label for="">Total de cuenta por cobrar: <b>Q</b> </label>
							<input type="hidden" name="" value="">
							<input type="text" id="totalCredito" name="montoCredito" value="" class="form-control">
						</div>
						<div class="col-lg-6">
							<label for="">Enganche: <b>Q</b></label>
							<input type="text" id="totalEganche" name="total_eganche" value="" class="form-control">
						</div>
					</div>
					<!-- <input type="text" id="totalCredito" name="montoCredito" value="{!!$monto!!}" class="form-control"> -->
				</div>

				<div class="panel-body">
					<div class="row">
						<div class="col-lg-6">
							{!! Form::label('name', trans('Fecha de pago').' (Seleccione la primer fecha de pago )') !!}
							{!! Form::text('date_payments', Input::old('date_payments'), array('id'=>'admited_at','class' => 'form-control')) !!}
						</div>
						<div class="col-lg-6">
							<label for="">Total de Cuotas</label>
							<!--  Aca va el dropdown-->
							<select class="form-control" name="total_pagos" id="montoCredito" >
								<option value="0">Seleccione una opción</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
								<option value="8">8</option>
								<option value="9">9</option>
							</select>
						</div>
					</div>
				</div>
				<!-- <p>Date: <input type="text" id="datepicker" class="form-control"></p> -->
				<div class="panel-body">
					<br>
					<br>
					<button type="button" name="button" id="generarPagos" class="btn btn-info">Generar Pagos</button>
					<!-- <button type="button" name="button" id="obtenerFecha" class="btn btn-primary">Obtener fecha</button> -->
				</div>

				<div class="panel-body">
					<h2>Detalle de cuenta por cobrar</h2>
					<table class="table table-bordered table-striped" id="tablaId">
						<thead>
							<th style="text-align: center;" width="20%;">Cuota Número:</th>
							<th style="text-align: right;">Monto del pago</th>
							<th style="text-align: center;">Fechas de pago</th>
						</thead>
						<tbody>
						</tbody>
					</table>
					{!!Form::submit(trans('Guardar cuenta por cobrar'),array('class'=>'btn btn-primary','id'=>'btnSaveForms'))!!}
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
$("#admited_at").datetimepicker({ sideBySide: true, locale:'es',format:'DD/MM/YYYY'}).parent().css("position :relative");
</script>
<script src="{{ asset('assets/js/createcredit/createcredit.js') }}" type="text/javascript"></script>
@stop
