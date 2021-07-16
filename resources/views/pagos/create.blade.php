@extends('layouts/default')

@section('title',trans('general.new_payment_type'))
@section('page_parent',"Pagos")
@section('header_styles')
<!-- Validaciones -->
<link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css" /> 
{{--  --}}
<link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" />
@stop
@section('content')
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">
						<i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
						{{trans('general.new_payment_type')}}
					</h3>
					<span class="pull-right clickable">
						{{-- <i class="glyphicon glyphicon-chevron-up"></i> --}}
					</span>
				</div>
				<div class="panel-body">
					
					{!! Form::open(array('url' => 'pago', 'id'=>'frmTipo')) !!}
					<div class="row">
						<div class="col-lg-6">
							<div class="form-group">
								{!! Form::label('name', trans('Nombre de Tipo de pago')) !!}<span>*</span>                        
								<div class="form-group {{ $errors->first('nit_supplier', 'has-error') }}">
									{!! Form::text('name', Input::old('name'), array('class' => 'form-control')) !!}
									{!! $errors->first('name', '<span class="help-block">:message</span>') !!}
								</div>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								{!! Form::label('is_credit', trans('payment.type')) !!}
								<select class="form-control" title="" name="type" id="type" required>									
									<option value="">Seleccione tipo</option>
									<option value="1" @if(old('bank_id') === '1') selected="selected" @endif>Efectivo</option>
									<option value="2" @if(old('bank_id') === '2') selected="selected" @endif>Cheque</option>
									<option value="3" @if(old('bank_id') === '3') selected="selected" @endif>Depósito</option>
									<option value="4" @if(old('bank_id') === '4') selected="selected" @endif>Tarjeta de Crédito/Debito</option>
									<option value="5" @if(old('bank_id') === '5') selected="selected" @endif>Transferencia</option>
									<option value="6" @if(old('bank_id') === '6') selected="selected" @endif>Crédito</option>
								</select>
							</div>
						</div>						
					</div>
					
					<div class="row">						
						<div class="col-lg-6">
							<label for="accounts">{{ trans('payment.account_type_in') }}<span>*</span>
							<span class="btn btn-info btn-xs select-all">{{ trans('general.select_all') }}</span>
							<span class="btn btn-info btn-xs deselect-all">{{ trans('general.deselect_all') }}</span></label>
							<select name="accounts_in[]" id="accounts_in" class="form-control select2" multiple="multiple">
								@foreach($accounts as $id => $name)
								<option value="{{ $id }}" {{ (in_array($id, old('accounts_in', [])) || isset($pagoss) && $pagoss->accountsin->contains($id)) ? 'selected' : '' }}>{{ $name }}</option>									
								@endforeach
							</select>
						</div>
						<div class="col-lg-6">
							<label for="accounts">{{ trans('payment.account_type_out') }}<span>*</span>
							<span class="btn btn-info btn-xs select-all">{{ trans('general.select_all') }}</span>
							<span class="btn btn-info btn-xs deselect-all">{{ trans('general.deselect_all') }}</span></label>
							<select name="accounts_out[]" id="accounts_out" class="form-control select2" multiple="multiple">
								@foreach($accounts as $id => $name)
								<option value="{{ $id }}" {{ (in_array($id, old('accounts_out', [])) || isset($pagoss) && $pagoss->accountsout->contains($id)) ? 'selected' : '' }}>{{ $name }}</option>									
								@endforeach
							</select>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-lg-6">
							<div class="form-group">
								{!! Form::label('show_in_sale', trans('payment.show_in_sale')) !!}
								<select class="form-control" title="" name="venta" id="venta" required>
									<option value="0" @if(old('venta') === '0') selected="selected" @endif>No</option>
									<option value="1" @if(old('venta') === '1') selected="selected" @endif>Si</option>
								</select>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								{!! Form::label('order_in_sale', trans('payment.order_in_sale')) !!}
								{!! Form::number('orden_venta', Input::old('orden_venta'), array('class' => 'form-control','step'=>1,'min'=>0)) !!}
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-lg-6">
							<div class="form-group">
								{!! Form::label('show_in_receiving', trans('payment.show_in_receiving')) !!}
								<select class="form-control" title="" name="compra" id="compra" required>
									<option value="0" @if(old('compra') === '0') selected="selected" @endif>No</option>
									<option value="1" @if(old('compra') === '1') selected="selected" @endif>Si</option>
								</select>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								{!! Form::label('order_in_receiving', trans('payment.order_in_receiving')) !!}							
								{!! Form::number('orden_compra', Input::old('orden_compra'), array('class' => 'form-control','step'=>1,'min'=>0)) !!}							
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-lg-6">
							<div class="form-group">
								{!! Form::label('show_in_bank_in', trans('payment.show_in_bank_in')) !!}
								<select class="form-control" title="" name="banco_in" id="banco_in" required>
									<option value="0" @if(old('banco_in') === '0') selected="selected" @endif>No</option>
									<option value="1" @if(old('banco_in') === '1') selected="selected" @endif>Si</option>
								</select>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								{!! Form::label('order_in_bank_in', trans('payment.order_in_bank_in')) !!}
								{!! Form::number('orden_banco_in', Input::old('orden_banco_in'), array('class' => 'form-control','step'=>1,'min'=>0)) !!}
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-lg-6">
							<div class="form-group">
								{!! Form::label('show_in_bank_out', trans('payment.show_in_bank_out')) !!}
								<select class="form-control" title="" name="banco_out" id="banco_out" required>
									<option value="0" @if(old('banco_out') === '0') selected="selected" @endif>No</option>
									<option value="1" @if(old('banco_out') === '1') selected="selected" @endif>Si</option>
								</select>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								{!! Form::label('order_in_bank_out', trans('payment.order_in_bank_out')) !!}
								{!! Form::number('orden_banco_out', Input::old('orden_banco_out'), array('class' => 'form-control','step'=>1,'min'=>0)) !!}
							</div>
						</div>
					</div>
					
					<br><br>
					<div class="row">
						<div class="col-lg-12">
							@include('partials.buttons',['cancel_url'=>"/pago"])
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
<script language="javascript" type="text/javascript" src="{{ asset('assets/vendors/select2/js/select2.js') }}"></script>
<!-- Valiadaciones -->
<script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('.select-all').click(function () {
			let $select2 = $(this).parent().siblings('.select2')
			$select2.find('option').prop('selected', 'selected')
			$select2.trigger('change')
		})
		$('.deselect-all').click(function () {
			let $select2 = $(this).parent().siblings('.select2')
			$select2.find('option').prop('selected', '')
			$select2.trigger('change')
		})
		$('.select2').select2()
		
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
