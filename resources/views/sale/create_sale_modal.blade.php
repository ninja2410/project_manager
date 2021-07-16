@extends('layouts/default')
@section('title',trans('sale.sales_register'))
@section('page_parent',trans('sale.sales'))
@section('header_styles')
<link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css" />
<!-- Toast -->
<link href="{{ asset('assets/css/toast/toastr.min.css') }}" rel="stylesheet" type="text/css" />
{{-- select 2 --}}
<link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css" />
{{-- date time picker --}}
<link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet"
  type="text/css" />
{{-- autocomplete --}}
<link href="{{ asset('assets/css/easy-autocomplete.css') }}" rel="stylesheet" type="text/css" />
@stop
@section('content')
<section class="content">
  <div class="row" style="padding-top:5px;">
    <div class="col-lg-12 ">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">
            <i class="livicon" data-name="shopping-cart-out" data-size="18" data-c="#fff" data-hc="#fff"
              data-loop="true"></i>
            {{trans('sale.sales_register')}}
          </h3>
          <span class="pull-right clickable">
            <i class="glyphicon glyphicon-chevron-up"></i>
          </span>
        </div> {{-- panel-heading--}}

        <div class="panel-body">
          <div class="bs-example">
            <ul class="nav nav-tabs" style="margin-bottom: 15px;">
              <li class="active" id="tab_venta">
                <a href="#venta" data-toggle="tab">Venta</a>
              </li>
              <li style="display:none" id="tab_pago">
                <a href="#pago" data-toggle="tab" id="link_pago">Pago</a>
              </li>
            </ul>
            <div id="tabsVentas" class="tab-content">
              <div class="tab-pane fade active in" id="venta">

                {!! Form::open(array('url' => 'sales','method' => 'post','id'=>'id_save_sales','class'=>'form')) !!}
                <input type="hidden" name="token" value="{{csrf_token()}}" id="token_">
                <input type="hidden" name="path" id="path" value="{{ url('/') }}">
                <input type="hidden" name="new_details" id="new_details" value="">
                {{--    INFORMACIÓN DE COTIZACIONES    --}}
                <div>
                  <input type="hidden" name="quotation_id" id="quotation_id" value="{{$quotation_id}}">
                  <input type="hidden" name="cellar_id" id="cellar_id" value="{{$cellar_id}}">
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    {!! Form::label('date_tx', trans('Fecha')) !!}
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="livicon" data-name="calendar" data-size="16" data-c="#555555" data-hc="#555555"
                          data-loop="true"></i>
                      </div>
                      {!! Form::text('date_tx', Input::old('date_tx'), array('id'=>'date_tx','class' => 'form-control date_sale'))
                      !!}
                    </div>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    {!! Form::label('id_bodega', trans('sale.storage'), ['class' => 'control-label']) !!}
                    <div class="input-group select2-bootstrap-prepend">
                      <div class="input-group-addon"><i class="fa fa-archive"></i></div>
                      <select class="form-control" name="id_bodegas" id="id_bodega">
                        <option value="0">Seleccione bodega</option>
                        @foreach($almacen as $value)
                        <option value="{!! $value->id !!}" {{($value->id==$id_almacen ? 'selected' : '')}}>
                          {{ $value->name }}
                        </option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="form-group">
                    {!! Form::label('customer_id', trans('sale.customer'), ['class' => 'control-label']) !!}
                    <div class="input-group select2-bootstrap-prepend">
                      <div class="input-group-addon"><i class="fa fa-users"></i></div>
                      <select class="form-control" name="customer_id" id="customer_id">
                        <option value="0">Seleccione cliente</option>
                        @foreach($customer as $value)
                        <option value="{!! $value->id !!}" max_credit_amount="{!! $value->max_credit_amount !!}"
                          days_credit="{!! $value->days_credit !!}">
                          {{ $value->name }}
                        </option>
                        @endforeach
                      </select>
                      <div class="input-group-btn">
                        <a href="#" style="font-size: 14px" id="add_customer_btn" class="btn btn-default btn-icon"
                          data-toggle="modal" data-target="#modal-2"><i class="fa fa-plus"></i></a>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-2">
                  <div class="form-group">
                    {!! Form::label('customer_balance', trans('sale.customer_balance'), ['class' => 'control-label'])
                    !!}
                    <div class="input-group select2-bootstrap-prepend">
                      <div class="input-group-addon">Q</div>
                      {!! Form::text('customer_balance', Input::old('customer_balance'),
                      array('id'=>'customer_balance','class' =>
                      'form-control','readonly'=>'readonly','style'=>'text-align: right;') ) !!}
                    </div>
                  </div>
                </div>

                <div class=" row">
                  <div class="col-lg-4">
                    <div class="form-group">
                      {!! Form::label('lblpayment_method', trans('sale.prices')) !!}
                      <div class="input-group select2-bootstrap-prepend">
                        <div class="input-group-addon"><i class="fa fa-usd"></i></div>
                        <select class="form-control" title="{{trans('sale.prices')}}" name="id_price" id="id_price" @if(isset($precios_solo_admin) && (intval($precios_solo_admin)==0)) disabled="disabled" @endif >
                          <option value="0">--Seleccione--</option>
                          @foreach($prices as $item)
                          <option value="{!! $item->id !!}" @if( (isset($precio_default)) &&
                            (intval($precio_default)===intval($item->id))) selected="selected" @endif>{{ $item->name }}
                          </option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label for=" serie_id" class="control-label">{{trans('sale.document')}}</label>
                      <div class="input-group select2-bootstrap-prepend">
                        <div class="input-group-addon"><i class="fa fa-folder"></i></div>
                        <select class="form-control" name="serie_id" id="id_serie">
                          @foreach($serieFactura as $value)
                          <option value="{!!$value->id!!}" @if($idFac==$value->id) selected="selected" @endif
                            >{!!$value->nombre!!} - {!!$value->name!!}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-1">
                    <div class="form-group">
                      {!! Form::label('user_relation', trans('sale.number'), ['class' => 'control-label']) !!}
                      {!! Form::text('correlativo_num', Input::old('id_correlativo'),
                      array('id'=>'id_correlativo','class' => 'form-control') ) !!}
                    </div>
                  </div>
                  <div class=" col-lg-3">
                    <div class="form-group">
                      {!! Form::label('user_relation', trans('sale.salesman'), ['class' => 'control-label']) !!}
                      <div class="input-group select2-bootstrap-prepend">
                        <div class="input-group-addon"><i class="fa fa-user"></i></div>
                        <select class="form-control" name="user_relation" id="user_relation">
                          @foreach($dataUsers as $id => $name)
                          <option value="{!! $id !!}" {{($id==$idUserActive ? 'selected' : '')}}>{{ $name }}
                          </option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                {{--  --}}
                <br>
                <div class="col-lg-2">
                  <div class="form-group" style="text-align: center;">
                    <label class="control-label" style="padding-top: 0.5em;">{{trans('sale.search_item')}}</label>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="livicon" data-name="barcode" data-size="16" data-c="#555555" data-hc="#555555"
                          data-loop="true"></i>
                      </div>
                      <input type="text" autocomplete="off" id="codigo" class="form-control"
                        placeholder="Ingrese código">
                    </div>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="livicon" data-name="search" data-size="16" data-c="#555555" data-hc="#555555"
                          data-loop="true"></i>
                      </div>
                      {{-- <div class="easy-autocomplete" style="width: 305px !important"> --}}
                      <input type="text" id="autocom" class="easy-autocomplete" placeholder="Buscar por nombre"
                        autocomplete="off">
                      {{-- </div> --}}
                      {{-- <input type="text" id="autocom" class="easy-autocomplete" placeholder="Autocomplete" autocomplete="off"> --}}
                    </div>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <a class="btn btn-info btn-block" href="#" id="add_item_btn" data-toggle="modal"
                      data-target="#modal-products"><span
                        class="glyphicon glyphicon-list-alt"></span>&nbsp;&nbsp;Mostrar listado completo</a>
                  </div>
                </div>

                <div class="row">
                  <div class="form-group">
                    <div class="col-md-12">
                      <table id="target" class="table table-bordered">
                        <thead style="background: #9CBBD9">
                          <tr>
                            {{-- <th>No.</th> --}}
                            <th>Código</th>
                            <th style="width:30%">Producto</th>
                            <th>{!! trans('unit_measure.unit_measure') !!}</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                            <th style="width:0%">&nbsp;</th>
                            <th style="width:0%">&nbsp;</th>
                            <th style="width:5%;text-align:left;">&nbsp;</th>
                            <th style="width:0%">&nbsp;</th>
                            {{--  <th>&nbsp;</th>  --}}
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>


                <div class="row">
                  <div class="col-7 col-md-7">
                    @if(isset($mostrar_pedido) && ($mostrar_pedido==1))
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="order" class="control-label col-md-4">{{trans('sale.order')}}</label>
                        <div class="input-group col-md-8">
                          <div class="input-group-addon"><i class="fa fa-plane"></i></div>
                          {!! Form::text('order', Input::old('order'), array('id'=>'order','class' =>
                          'form-control','placeholder'=>'Ingrese # de pedido') ) !!}
                        </div>
                      </div>
                    </div>
                    @else
                    <input type="hidden" name="order" value="" id="order">
                    @endif
                    @if(isset($mostrar_transporte) && ($mostrar_transporte==1))
                    <div class="col-md-12">
                      <div class="form-group">
                        {!! Form::label('transport', trans('sale.transport'), ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8 input-group">
                          <div class="input-group-addon"><i class="fa fa-truck"></i></div>
                          {!! Form::text('transport', Input::old('transport'), array('id'=>'transport','class' =>
                          'form-control', 'placeholder'=>'Ingrese información del transporte utilizado en la venta.') )
                          !!}
                        </div>
                      </div>
                    </div>
                    @else
                    <input type="hidden" name="transport" value="" id="transport">
                    @endif
                    @if(isset($mostrar_imprimible) && ($mostrar_imprimible==1))
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="printable_comment"
                          class="control-label col-md-4">{{trans('sale.comment_printable')}}</label>
                        <div class="input-group col-md-8">
                          <div class="input-group-addon"><i class="fa fa-file-text-o"></i></div>
                          <input type="text" placeholder="Ingrese comentario a imprimir en la factura"
                            class="form-control" name="printable_comment" id="printable_comment" />
                        </div>
                      </div>
                    </div>
                    @else
                    <input type="hidden" name="printable_comment" value="" id="printable_comment">
                    @endif
                    <hr>
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="comments" class="control-label col-md-4">{{trans('sale.comments')}}</label>
                        <div class="input-group col-md-8">
                          <div class="input-group-addon"><i class="fa fa-file-text-o"></i></div>
                          <input type="text" placeholder="Ingrese comentario interno sobre la venta"
                            class="form-control" name="comments" id="comments" tabindex="1000" />
                        </div>
                      </div>
                    </div>


                  </div>


                  <div class="col-5 col-md-5" >
                    <input type="hidden" name="item_quantity" value="0" id="item_quantity">
                    <input type="hidden" name="max_credit_amount" value="-1" id="max_credit_amount">
                    <input type="hidden" name="days_credit" value="0" id="days_credit">
                    <input type="hidden" id="image_url" value="{{ asset('images/items/') . '/' }}">
                    <input type="hidden" name="total_cost" id="total_general" value="0">
                    <input type="hidden" name="max_desc_vta" id="max_desc_vta" value="{{ $max_desc_vta }}">
                    <input type="hidden" name="desc_vta" id="desc_vta" value="{{ $desc_vta }}">
                    <input type="hidden" name="cuenta_default" id="cuenta_default" value="{{ $cuenta_default }}">
                    <div class="row" @if((intval($desc_vta)!==1) ||(intval($descto_admin)!==1)) style="display:none;" @endif>
                      <div class="col-5 col-md-5 titulo-totales" >
                        <label>Sub-Total:</label>
                      </div>
                      <div class="col-7  col-md-7 titulo-totales">
                        <input type="text" name="total_cost1" style="border: none;text-align:center;background-color: white;" disabled id="total_general1"
                        value="0">
                      </div>
                    </div>

                    <div class="row" @if((intval($desc_vta)!==1) ||(intval($descto_admin)!==1)) style="display:none;" @endif>
                      <div class="col-12 col-md-4" style="text-align:right;">
                        <label style="font-size:20px;">Descuento:</label>
                      </div>
                      <div class="col-md-1" style="text-align:right;">%&nbsp;</div>
                      <div class="col-6 col-md-3" style="text-align:right;">
                        <input type="number" name="discount_pct" style="text-align:right;" class="input-res" id="discount_pct" min="0" max="{{ $max_desc_vta }}" step="0.01">
                      </div>
                      <div class="col-6  col-md-4">
                        <input type="number" name="discount_amount" style="text-align:right" class="input-res" id="discount_amount" min="0" max="999999" step="0.01">
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-6 col-md-5 titulo-totales" >
                        <label>Total:</label>
                      </div>
                      <div class="col-6  col-md-7 titulo-totales" >
                        <input type="text" name="total_cost2" style="border: none;text-align:center;background-color: white;" disabled id="total_general2"
                        value="0">
                      </div>
                    </div>


                    <input type="hidden" name="validar_precio_minimo" id="validar_precio_minimo" value="{{ $validar_precio_minimo }}">
                    <input type="hidden" name="permitir_varios_items" id="permitir_varios_items" value="{{ $permitir_varios_items }}">
                    <input type="hidden" name="dias_de_pago_default" id="dias_de_pago_default" value="{{ $dias_de_pago_default }}">
                    <input type="hidden" name="max_lineas" id="max_lineas" value="{{ $max_lineas }}">
                    <input type="hidden" name="imprimir_propietario" id="imprimir_propietario" value="{{ $imprimir_propietario }}">


                  </div>
                </div>
                {!! Form::close() !!}


              </div> {{-- fin tab venta --}}
              <div class="tab-pane fade" id="pago">
                @include('partials.payment-types.sale_payment')
              </div> {{-- Fin tab pago --}}
              <div class="row">
                <div class="form-group">
                  <div class="col-md-12">
                    <div class="form-group">
                      <div class="col-sm-4" style="text-align:center;"></div>
                      <div class="col-sm-4" style="text-align:center;"></div>
                      <div class="col-sm-4" style="text-align:center;">
                        <div class="form-group">
                          <button type="submit" class="btn btn-primary btn-block" id="idVenta">
                            {{trans('sale.submit')}}
                          </button>
                          {{-- <a href="#" class="btn btn-primary btn-block" id="idVenta"> </a>--}}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div> {{-- panel-body--}}
        </div>{{-- panel-primary--}}
      </div>{{-- col-lg-12 --}}
    </div>{{-- row --}}
    @include('partials.confirm-sale')
    @include('partials.new-customer-sale')
    {{-- Inicio Modal productos --}}
    @include('partials.list-products')
    {{-- Fin Modal productos --}}

    {{--  MODAL VER IMAGEN DE PDORUCTO--}}
    @include('partials.show_image')
    {{--  FIN MODAL VER IMAGEN DE PRODUCTO--}}
</section>
@endsection

@section('footer_scripts')
  {{-- FORMATO DE MONEDAS --}}
  <script src="{{asset('assets/js/vuejs/cleave.min.js')}} "></script>
  <script src="{{asset('assets/js/vuejs/vue-cleave.min.js')}} "></script>
<!-- Valiadaciones -->
<script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('assets/js/toast/toastr.min.js')}} "></script>
{{-- Select2 --}}
<script type="text/javascript" src="{{ asset('assets/vendors/select2/js/select2.js')}} "></script>
<!--  Calendario -->
<script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript">
</script>
{{-- CUSTOM JS --}}
<script type="text/javascript" src="{{ asset('assets/js/sales/customer_add.js')}} "></script>
<script type="text/javascript" src="{{ asset('assets/js/sales/validations_new.js')}} "></script>
<script type="text/javascript" src="{{ asset('assets/js/sales/new_sale.js')}} "></script>
<script type="text/javascript" src="{{ asset('assets/js/sales/payment_type_sale.js')}} "></script>
<script type="text/javascript" src="{{ asset('assets/js/sales/quotation.js')}} "></script>
<script type="text/javascript" src="{{ asset('assets/js/sales/products-table.js')}} "></script>

{{-- autocomplete --}}
<script src="{{asset('assets/js/jquery.easy-autocomplete.min.js')}}"></script>


<script>
  /**
  * Funciones cuando la pagina este cargada - INICIO \/
  */
  var details;
  var detail_;
  var flag = false;
  var counter = 0;
  $(document).ready(function () {

    $.fn.select2.defaults.set("width", "100%");
    document.getElementById('total_general').value = 0;
    document.getElementById('total_general2').value = 0;
    document.getElementById('item_quantity').value = 0;


    document.getElementById("idFormNewCustomer").onkeypress = function (e) {
      var key = e.charCode || e.keyCode || 0;
      if (key == 13) {
        e.preventDefault();
      }
    }

    document.getElementById("id_save_sales").onkeypress = function (e) {
      var key = e.charCode || e.keyCode || 0;
      if (key == 13) {
        e.preventDefault();
        return;
      }
    }

    document.getElementById("frm_payment").onkeypress = function (e) {
      var key = e.charCode || e.keyCode || 0;
      if (key == 13) {
        e.preventDefault();
      }
    }


    document.getElementById("autocom").onkeypress = function (e) {
      var key = e.charCode || e.keyCode || 0;
      if (key == 13) {
        e.preventDefault();
      }
    };
    autocomplete();

    $("#codigo").keydown(function (event) {
      if (event.which == 13) {
        event.preventDefault();
        var codigo = $("#codigo").val().trim();
        if ((typeof $("#id_bodega").val() === 'undefined') || ($("#id_bodega").val() === null) || (cleanNumber($("#id_price").val()) === 0)) {
          toastr.error("Seleccione una bodega y/o tipo de precio.");
        }
        else {
          var bodega = $("#id_bodega").val().trim();
          var id_price = $("#id_price").val().trim();
          if ((codigo !== "")) {
            buscar_codigo(codigo, bodega, id_price);
          }
        }
      };
    });

    /* Inicialización de fechas */
    var dateNow = new Date();
    var dateCredit = new Date();
    dateCredit.setDate(dateCredit.getDate() + 15);
    $("#paid_at ").datetimepicker({ sideBySide: true, locale: 'es', format: 'DD/MM/YYYY', defaultDate: dateNow }).parent().css("position :relative ");

    $("#date_tx").datetimepicker({ sideBySide: true, locale: 'es', format: 'DD/MM/YYYY', defaultDate: dateNow }).parent().css("position :relative");
    $("#paid_at").datetimepicker({ sideBySide: true, locale: 'es', format: 'DD/MM/YYYY', defaultDate: dateNow }).parent().css("position :relative");
    $("#date_payments").datetimepicker({ sideBySide: true, locale: 'es', format: 'DD/MM/YYYY' }).parent().css("position :relative");

    /* Inicialización de dropdowns */
    $('select').select2({
      allowClear: true,
      theme: "bootstrap",
      placeholder: "Buscar"
    });


    // $('#id_bodega').trigger("change");
    $('#id_serie').trigger("change");
    $('#id_price').trigger("change");

    load_quotation();
    /* Evento en campo de % de descuento */
    const discount_pct = document.getElementById('discount_pct');
    if (discount_pct) {
      discount_pct.addEventListener('input', function (e) {
        // update_discount();
        const desc_vta = cleanNumber(document.getElementById('desc_vta').value);
        if (desc_vta == 1) {

          const pct = cleanNumber(e.target.value);
          const max_desc_vta = cleanNumber(document.getElementById('max_desc_vta').value);
          if (pct < 0) {
            e.target.value = 0;
            return;
          } else if (pct > max_desc_vta) {
            toastr.error("Descuento máximo permitido : "+max_desc_vta);
            e.target.value = 0;
            return;
          }


          total_general = cleanNumber(document.getElementById('total_general').value);
          discount_amount = document.getElementById('discount_amount');
          const monto = cleanNumber(total_general * (pct / 100)).toFixed(2);
          discount_amount.value = monto;

          /*Actualizar totales */
          $('#total_general1').val(monedaChangeParam(total_general.toFixed(2)));
          var totalFormateado = monedaChangeParam(cleanNumber(total_general - monto).toFixed(2));
          $('#total_general2').val(totalFormateado);
          setPaymentValues(totalFormateado);

          /*Actualizar precios*/
          // $('.selling_price').each(function(){
          //   split_ = this.name.split("_");
          //   id = split_[2];
          //   let monto_descuento = cleanNumber(this.value * (pct / 100)).toFixed(2);
          //   this.value = this.value-monto_descuento;
          // });

        }
        else {

          toastr.error("No esta permitido realizar descuentos");
          e.target.value = 0;

        }

      });
    }
    /* Evento en campo de Monto de descuento */
    const discount_amnt = document.getElementById('discount_amount');
    if (discount_amnt) {
      discount_amnt.addEventListener('input', function (e) {

        const desc_vta = cleanNumber(document.getElementById('desc_vta').value);
        if (desc_vta == 1) {
          discount_amount = cleanNumber(e.target.value);
          if (discount_amount < 0) {
            e.target.value = 0;
            return;
          }

          total_general = cleanNumber(document.getElementById('total_general').value);

          const pct = cleanNumber((discount_amount / total_general) * 100).toFixed(2);
          const discount_pct = document.getElementById('discount_pct');
          discount_pct.value = pct;

          /*Actualizar totales */
          $('#total_general1').val(monedaChangeParam(total_general.toFixed(2)));
          var totalFormateado = monedaChangeParam(cleanNumber(total_general - discount_amount).toFixed(2));
          $('#total_general2').val(totalFormateado);
          setPaymentValues(totalFormateado);
        }
        else {

          toastr.error("No esta permitido realizar descuentos");
          e.target.value = 0;

        }
      });
    };

  });
  /**
  * Funciones cuando la pagina este cargada - FIN /\
  */

  function update_discount() {
    const pct = cleanNumber(document.getElementById('discount_pct').value);
    total_general = cleanNumber(document.getElementById('total_general').value);
    discount_amount = document.getElementById('discount_amount');
    const monto = cleanNumber(total_general * (pct / 100)).toFixed(2);
    discount_amount.value = monto;

    /*Actualizar totales */
    $('#total_general1').val(monedaChangeParam(total_general.toFixed(2)));
    var totalFormateado = monedaChangeParam(cleanNumber(total_general - monto).toFixed(2));
    $('#total_general2').val(totalFormateado);
    setPaymentValues(totalFormateado);
  }

  /*
  * MODAL PARA MOSTRAR IMAGENES DE PRODUCTOS
  * */
  function showImage(avatar, nombre) {
    $('#lblTitulo').text(nombre);
    $('#image').attr("src", avatar);
    $('#modal-image').modal("show");
  }
  /*--------------------------------------*/



  /*TABS */
  $('#tabsVentas').slimscroll({
    height: '100%',
    size: '3px',
    color: '#D84A38',
    opacity: 1

  });



  $('#date_tx').blur(function () {
    if ($('#date_tx').val() == "") {
      $('#date_tx').val(get_date_today(0));
    } else {
      $('#paid_at').val($('#date_tx').val());
    }
  });

  $('#date_payments').blur(function () {
    if ($('#date_payments').val() == "") {
      $('#date_payments').val(get_date_today(15));
    }
  });

  function get_date_today(dias) {
    var f = new Date();
    // f = f.getDate()+dias;
    f.setDate(f.getDate() + Number(dias));
    var fechaCompleta = f.getDate() + "/" + f.getMonth() + "/" + f.getFullYear();
    return fechaCompleta;
  }

  function buscar_codigo(codigo, bodega, id_price, quotPrice = 0, quotQuantity = 0) {
    // $.get( "{{ url('api/sales/search_code?id=') }}"+codigo+"&bodega_id="+bodega, function( data ) {
    $.get("{{ url('api/sales/search_code_storage_price?id=') }}" + codigo + "&bodega_id=" + bodega + "&id_price=" + id_price, function (data) {
      flag = true;
      // console.log(data);
      if(data){
        agregar(data, quotPrice, quotQuantity);/*Llamamos la funcion para agregar a la tabla de productos*/
      }
      else {
        toastr.error('Producto no encontrado ó sin existecia!');
        // console.log('Producto no encontrado ó sin existecia!');
      }

    });
  };

  function autocomplete() {
    var options = {
      // url: APP_URL+"/api/sales/autocomplete",
      url: APP_URL + "/api/sales/autocompleteStoragePrice",


      getValue: "item_name",

      template: {
        type: "custom",
        method: function (value, item) {
          return '<strong>' + item.upc_ean_isbn + " | " + value + '</strong> | Q.' + item.selling_price;
        }
      },

      list: {
        maxNumberOfElements: 10,
        match: {
          enabled: true
        },
        sort: {
          enabled: true
        },
        onChooseEvent: function () { /*Cuando seleccionan elemento*/
          var value = $("#autocom").getSelectedItemData(); /*Obtenemos el objeto con la data que viene de ajax*/
          agregar(value); /*Llamamos la funcion para agregar a la tabla de productos*/
        }
      },

      theme: "bootstrap",

      ajaxSettings: {
        dataType: "json",
        method: "GET",
        data: {},
        statusCode: {
          401: function () {
            toastr.options = {
              "closeButton": true,
              "timeOut": "0",
              "extendedTimeOut": "0",
              "preventDuplicates": true,
            };
            toastr.options.onHidden = function () { location.reload(); };
            toastr.error('Su sesión ha expirado, debe volver a autenticase!<br>');

          }
        }
      },

      preparePostData: function (data) {
        data.id = $("#autocom").val();
        data.bodega_id = cleanNumber($("#id_bodega").val());
        data.id_price = cleanNumber($("#id_price").val());

        if ((data.bodega_id === 0) || (data.id_price === 0)) {
          toastr.error("Seleccione una bodega y/o tipo de precio.");
          return;
        }
        return data;
      },

      requestDelay: 400
    };

    $("#autocom").easyAutocomplete(options);
  }

</script>
@stop
