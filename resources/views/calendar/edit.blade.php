@extends('app')
@section('header_styles')
<link href="{{ asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />
@stop
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">{{trans('item.update_item')}}</div>

				<div class="panel-body">
					{!! Html::ul($errors->all()) !!}

					{!! Form::model($item, array('route' => array('items.update', $item->id), 'method' => 'PUT', 'files' => true)) !!}
					<div class="col-lg-6">
						<div class="form-group">
							{!! Form::label('upc_ean_isbn', trans('item.upc_ean_isbn')) !!}
							{!! Form::text('upc_ean_isbn', null, array('class' => 'form-control')) !!}
						</div>
					</div>
					<div class="col-lg-6">
						<div class="form-group">
						{!! Form::label('item_name', trans('item.item_name')) !!}
						{!! Form::text('item_name', null, array('class' => 'form-control')) !!}
						</div>
					</div>
					<div class="col-lg-6">
						<div class="form-group">
							{!! Form::label('size', trans('Categoria')) !!}
							{!! Form::select('id_categorie', $categorie_product, Input::old('id'), array('class' => 'form-control')) !!}
						</div>
					</div>
					<div class="col-lg-6">
						<div class="form-group">
							{!! Form::label('size', trans('item.size')) !!}
							{!! Form::text('size', null, array('class' => 'form-control')) !!}
						</div>
					</div>

					<div class="col-lg-12">
						<div class="form-group">
							{!! Form::label('description', trans('item.description')) !!}
							{!! Form::textarea('description', null, array('class' => 'form-control','size' => '50x5')) !!}
						</div>
					</div>
					<div class="col-lg-6">
						<div class="form-group">
							{!! Form::label('avatar', trans('item.choose_avatar')) !!}
							{!! Form::file('avatar', null, array('class' => 'form-control')) !!}
						</div>
					</div>
					<div class="col-lg-12">
						<div class="form-group">
							<div class="form-group" id="mensaje">
							</div>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="form-group">
						{!! Form::label('cost_price', trans('item.cost_price').' *') !!}
						{!! Form::text('cost_price', Input::old('cost_price'), array('id'=>'precio_compra','class' => 'form-control')) !!}
						</div>
					</div>

					<div class="col-lg-4">
						<div class="form-group">
							{!! Form::label('selling_price', trans('item.selling_price').' *') !!}
							{!! Form::text('selling_price', Input::old('selling_price'), array('id'=>'precio_venta','class' => 'form-control'))  !!}
						</div>
					</div>
					<div class="col-lg-4">
						<div class="form-group">
							{!! Form::label('low_price', trans('Precio minimo')) !!}
							{!! Form::text('low_price', Input::old('low_price'), array('id'=>'precio_minimo','class' => 'form-control'))   !!}
						</div>
					</div>
					<div class="col-lg-4">
						<div class="form-group">
							{!! Form::label('minimal_existence', trans('Existencia mínima')) !!}
							<input type="text" name="minimal_existence" id="minimal_existence" value="50" class="form-control">
						</div>
					</div>
					<div class="col-lg-6">
						<div class="form-group">
							{!! Form::label('expiration_date', trans('Fecha de expiración')) !!}
							{!! Form::text('expiration_date', Input::old('expiration_date'), array('id'=>'expiration_date','class' => 'form-control'))   !!}
						</div>
					</div>
					<!-- <div class="form-group">
					{!! Form::label('quantity', trans('item.quantity')) !!}
					{!! Form::text('quantity', null, array('class' => 'form-control')) !!}
					</div> -->
					<div class="col-lg-12">
						{!! Form::submit(trans('item.submit'), array('class' => 'btn btn-primary')) !!}
						<a class="btn btn-danger" href="{{ url('/items') }}">
	                        Cancelar
	                    </a>
						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
var mensaje=document.getElementById('mensaje');
var precio_compra=document.getElementById('precio_compra');
var precio_venta=document.getElementById('precio_venta');
console.log(precio_venta.value);

var setValorPrecioMinimo=function(e){
var precio_minimo=document.getElementById('precio_minimo');


var precioVenta=parseFloat(precio_venta.value);
var precioCompra=parseFloat(precio_compra.value);

if( precioVenta<precioCompra){

		mensaje.innerHTML="<div class='alert alert-danger'><strong>El precio venta no puede ser menor al precio costo...</strong></div>";
		precio_venta.value=precio_compra.value;
}
	precio_minimo.value=precio_venta.value;
	e.preventDefault();
}
var setValorPrecioVenta=function(e){
	var precioVenta=parseFloat(precio_venta.value);
	var precioMinimo=parseFloat(precio_minimo.value);
	var precioCompra=parseFloat(precio_compra.value);

	if(precioMinimo>precioVenta){
		mensaje.innerHTML="<div class='alert alert-danger'><strong>El precio minimo no puede ser mayor al precio venta...</strong></div>";
		precio_minimo.value=precio_venta.value;
	}
	if(precioMinimo<precioCompra){
		mensaje.innerHTML="<div class='alert alert-danger'><strong>El precio minimo no puede ser mayor al precio costo...</strong></div>";
		precio_minimo.value=precio_compra.value;
	}

}
precio_venta.addEventListener('change',setValorPrecioMinimo);
precio_minimo.addEventListener('change',setValorPrecioVenta);
</script>
@endsection
@section('footer_scripts')
<script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
<script>
$("#expiration_date").datetimepicker({ sideBySide: true, locale:'es',format:'DD/MM/YYYY'}).parent().css("position :relative");
</script>
@stop
