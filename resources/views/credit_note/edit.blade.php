@extends('layouts/default')
@if ($duplicate)
    @section('title',trans('quotation.duplicate'))
@else
    @section('title',trans('quotation.edit'))
@endif
@section('page_parent',trans('quotation.quotation'))
@section('header_styles')
    <link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css"/>
    <!-- Toast -->
    <link href="{{ asset('assets/css/toast/toastr.min.css') }}" rel="stylesheet" type="text/css"/>
    {{-- select 2 --}}
    <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css"/>
    {{-- date time picker --}}
    <link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet"
          type="text/css"/>
    {{-- autocomplete --}}
    <link href="{{ asset('assets/css/easy-autocomplete.css') }}" rel="stylesheet" type="text/css"/>
@stop
@section('content')
    <section class="content">
        <div class="row" style="padding-top:5px;">
            <div class="col-lg-12 ">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="shopping-cart-in" data-size="18" data-c="#fff" data-hc="#fff"
                               data-loop="true"></i>
                            {{trans('quotation.quotation')}}
                        </h3>
                        <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
                    </div>
                    <div class="panel-body">
                        <div class="bs-example">
                            <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                                <li class="active">
                                    <a href="#venta" data-toggle="tab">
                                        @if ($duplicate)
                                            {{trans('quotation.duplicate')}}
                                        @else
                                            {{trans('quotation.edit')}}
                                        @endif

                                    </a>
                                </li>
                                <li id="tab_pago" style="display:none;">
                                    <a href="#pago" data-toggle="tab" id="link_pago">Pago</a>
                                </li>
                            </ul>
                            <div id="tabsVentas" class="tab-content">
                                <div class="tab-pane fade active in" id="venta">
                                    <div class="row">
                                        {!! Form::open(array('url' => 'quotation/header', 'class' => 'form','id'=>'save_quotation')) !!}
                                        <input type="hidden" name="token" value="{{csrf_token()}}" id="token_">
                                        <input type="hidden" id="total_general" value="{{$quotation->amount}}">
                                        <input type="hidden" id="quotation_id" value="{{$quotation->id}}">
                                        <input type="hidden" name="duplicate" value="{{$duplicate}}">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                {!! Form::label('date_tx', trans('Fecha')) !!}
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="livicon" data-name="calendar" data-size="16"
                                                           data-c="#555555" data-hc="#555555" data-loop="true"></i>
                                                    </div>
                                                    <input type="text" name="date_tx" id="date_tx" class="form-control"
                                                           value="{{date('d/m/Y',strtotime($quotation->date))}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="serie_id"
                                                           class="control-label">{{trans('sale.document')}}</label>
                                                    <div class="input-group select2-bootstrap-prepend">
                                                        <div class="input-group-addon"><i class="fa fa-folder"></i>
                                                        </div>
                                                        <select class="form-control select" name="serie_id" id="id_serie"
                                                                onchange="valid_correlative()">
                                                            @foreach($serie as $value)
                                                                <option value="{!!$value->id!!}" {{$value->id==$quotation->serie_id ? 'selected' : ''}}>
                                                                    {!!$value->nombre!!}-{!!$value->name !!}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-1">
                                                <div class="form-group">
                                                    {!! Form::label('user_relation', trans('sale.number'), ['class' => 'control-label']) !!}
                                                    <input type="number" name="correlative" class="form-control" min="1"
                                                           id="correlative" value="{{$quotation->correlative}}">
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    {!! Form::label('user_relation', trans('receiving.employee'), ['class' => 'control-label']) !!}
                                                    <div class="input-group select2-bootstrap-prepend">
                                                        <div class="input-group-addon"><i class="fa fa-user"></i></div>
                                                        <select class="form-control" name="user_relation"
                                                                id="user_relation">
                                                            @foreach($dataUsers as $id => $name)
                                                                <option value="{!! $name->id !!}" {{($name->id==$quotation->user_id ? 'selected' : '')}} >
                                                                    {{ $name->name.' '.$name->last_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    {!! Form::label('customer_id', trans('sale.customer'), ['class' => 'control-label']) !!}
                                                    <div class="input-group select2-bootstrap-prepend">
                                                        <div class="input-group-addon"><i class="fa fa-users"></i></div>
                                                        <select class="form-control" name="customer_id"
                                                                id="customer_id">
                                                            <option value="0">Seleccione cliente</option>
                                                            @foreach($customer as $value)
                                                                <option value="{!! $value->id !!}"
                                                                        @if ($value->id==$quotation->customer_id)
                                                                        selected
                                                                        @endif
                                                                        max_credit_amount="{!! $value->max_credit_amount !!}">
                                                                    {{ $value->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <div class="input-group-btn">
                                                            <a href="#" style="font-size: 14px" id="add_customer_btn"
                                                               class="btn btn-default btn-icon"
                                                               data-toggle="modal" data-target="#modal-2"><i
                                                                        class="fa fa-plus"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    {!! Form::label('payment_method', trans('sale.payment_method')) !!}
                                                    <div class="input-group select2-bootstrap-prepend">
                                                        <div class="input-group-addon"><i class="fa fa-credit-card"></i></div>
                                                        <select class="form-control" title="trans('revenues.payment_method')" name="id_pago" id="id_pago" >
                                                            <option value="0">--Seleccione--</option>
                                                            @foreach($payments as $item)
                                                                <option value="{!! $item->id !!}"
                                                                        @if ($item->id==$quotation->payment_id)
                                                                        selected
                                                                        @endif
                                                                >{{ $item->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label for="employee">{{trans('quotation.comment')}}</label>
                                                    <input type="text" class="form-control" name="comments"
                                                           id="comments" value="{{$quotation->comment}}"/>
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label for="days">{{trans('quotation.days')}}</label>
                                                    <input type="number" class="form-control" name="days" id="days"
                                                           value="{{$quotation->days}}"/>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-lg-2">
                                                <div class="form-group" style="text-align: center;">
                                                    <label class="control-label"
                                                           style="padding-top: 0.5em;">{{trans('sale.search_item')}}</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="livicon" data-name="barcode" data-size="16"
                                                               data-c="#555555" data-hc="#555555" data-loop="true"></i>
                                                        </div>
                                                        <input type="text" autocomplete="off" id="codigo"
                                                               class="form-control" placeholder="Ingrese código">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="livicon" data-name="search" data-size="16"
                                                               data-c="#555555" data-hc="#555555" data-loop="true"></i>
                                                        </div>
                                                        {{-- <div class="easy-autocomplete" style="width: 305px !important"> --}}
                                                        <input type="text" id="autocom" class="easy-autocomplete form-control"
                                                               placeholder="Buscar por nombre" autocomplete="off">
                                                        {{-- </div> --}}
                                                        {{-- <input type="text" id="autocom" class="easy-autocomplete" placeholder="Autocomplete" autocomplete="off"> --}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <a class="btn btn-info btn-block" href="#" id="add_item_btn"
                                                       data-toggle="modal" data-target="#modal-products"><span
                                                                class="glyphicon glyphicon-list-alt"></span>&nbsp;&nbsp;Mostrar
                                                        listado completo</a>
                                                </div>
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
                                                        @foreach ($details as $detail)
                                                            <tr>
                                                                <input type="hidden" id="low_{{$detail->item_id}}"
                                                                       value="{{$detail->item->low_price}}">
                                                                <input type="hidden" id="selling_{{$detail->item_id}}"
                                                                       value="{{$detail->item->selling_price}}">
                                                                <td>{{$detail->item->upc_ean_isbn}}</td>
                                                                <td>{{$detail->item->item_name}}</td>
                                                                <td><input type="number"
                                                                           id="selling_storage_1_id_item_{{$detail->item_id}}"
                                                                           name="selling_price_{{$detail->item_id}}"
                                                                           required="" class="input_price"
                                                                           step="0.000001" style="width: 100%;"
                                                                           value="{{$detail->price}}"></td>
                                                                <td><input type="number"
                                                                           id="id_storage_1_id_item_{{$detail->item_id}}"
                                                                           name="cantidad_{{$detail->item_id}}"
                                                                           required="" min="1"
                                                                           max="Extra Grande"
                                                                           class="form-control input_quantity"
                                                                           style="text-align: center; width: 100%;"
                                                                           value="{{$detail->quantity}}"></td>
                                                                <td><input type="number"
                                                                           id="total_storage_1_id_item_{{$detail->item_id}}"
                                                                           name="total_{{$detail->item_id}}" disabled=""
                                                                           style="text-align: center; width: 100%;"
                                                                           value="{{($detail->price*$detail->quantity)}}">
                                                                </td>
                                                                <td><input type="hidden"
                                                                           name="id_product_{{$detail->item_id}}"
                                                                           required=""
                                                                           min="1" value="{{$detail->item_id}}"
                                                                           class="form-control id_products"
                                                                           style="text-align: center;"></td>
                                                                <td><input type="hidden" name="id_storage_1" required=""
                                                                           min="1" value="1" class="form-control"
                                                                           style="text-align: center;"></td>
                                                                <td>
                                                                    <button class="btn btn-xs btn-danger btn_delete"
                                                                            id="btndelete_storage_1_id_item_{{$detail->item_id}}"
                                                                            name="Extra Grande" type="button">X
                                                                    </button>
                                                                </td>
                                                                <td><input type="hidden"
                                                                           id="quantityExist_storage_1_id_item_{{$detail->item_id}}"
                                                                           required="" min="1" value="Extra Grande"
                                                                           class="form-control"
                                                                           style="text-align: center;">
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6"></div>
                                            <div class="col-md-6" style="text-align: right;font-size: 28px;">
                                                <div class="form-group">
                                                    <label for="supplier_id"
                                                           class="col-sm-4 control-label">Total:</label>
                                                    <div class="col-sm-8">
                                                        <input type="hidden" name="item_quantity" value="0"
                                                               id="item_quantity">
                                                        <input type="hidden" name="max_credit_amount" value="-1"
                                                               id="max_credit_amount">
                                                        <input type="text" name="total_cost2"
                                                               style="border: none;text-align:center;background-color: white;"
                                                               disabled id="total_general2" value="0">
                                                    </div>
                                                </div>
                                                <div>&nbsp;</div>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="tab-pane fade" id="pago">
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-6">
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="" id="sumary_expenses"></label>
                                    </div>
                                    <div class="col-lg-4">
                                        <button type="button" id="idVenta"
                                                class="btn btn-primary btn-block">{{trans('quotation.update')}}</button>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>

                        </div>

                        <input type="hidden" name="path" id="path" value="{{ url('/') }}">
                        
                    </div>
                </div>
            </div>
        </div>

        @include('receiving.expenses')
        @include('partials.new-customer-sale')
        {{-- Inicio Modal productos --}}
        @include('receiving.list_products')
        @include('quotation.confirm')
        {{-- Fin Modal productos --}}
    </section>
@endsection

@section('footer_scripts')

    {{-- FORMATO DE MONEDAS --}}
    <script src="{{asset('assets/js/vuejs/cleave.min.js')}} "></script>
    <script src="{{asset('assets/js/vuejs/vue-cleave.min.js')}} "></script>
    <script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }}" type="text/javascript"></script>
    {{-- Select2 --}}
    <script type="text/javascript" src="{{ asset('assets/vendors/select2/js/select2.js')}} "></script>

    <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"
            type="text/javascript"></script>


    {{-- autocomplete --}}
    <script src="{{asset('assets/js/jquery.easy-autocomplete.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/credit_notes/sales-table.js')}} "></script>
    <script type="text/javascript" src="{{ asset('assets/js/quotation/add.js')}} "></script>
    <script type="text/javascript" src="{{ asset('assets/js/sales/customer_add.js')}} "></script>
    <script type="text/javascript" src="{{ asset('assets/js/quotation/validations_edit.js')}} "></script>
    <script type="text/javascript" src="{{ asset('assets/js/quotation/payment_type_receiving.js')}} "></script>
    <script type="text/javascript">
        var id_storage = 1;
        $(document).ready(function () {
            // var cleave = new Cleave('.money_gasto', {
            //     numeral: true,
            //     numeralThousandsGroupStyle: 'thousand'
            // });


            $.fn.select2.defaults.set("width", "100%");
            var dateNow = new Date();
            $("#date_tx").datetimepicker({
                sideBySide: true,
                locale: 'es',
                format: 'DD/MM/YYYY'
            }).parent().css("position :relative");
            $('select').select2({
                allowClear: true,
                theme: "bootstrap",
                placeholder: "Buscar"
            });
            $('.select').select2({
                allowClear: true,
                theme: "bootstrap",
                placeholder: "Buscar"
            });
            autocomplete();
            $("#codigo").keydown(function (event) {
                if (event.which == 13) {
                    event.preventDefault();
                    var codigo = $("#codigo").val().trim();
                    if ((codigo !== "")) {
                        var id_pago=  cleanNumber($("#id_pago").val().trim());
                        buscar_codigo(codigo,id_pago);
                    }
                }
                ;
            });
            loadEdition();
            @if($duplicate)
            valid_correlative();
            @endif

            $('#id_serie').change(function () {
                valid_correlative();
            });
        });

        function loadEdition() {
            showLoading("Aplicando configuraciones, por favor espere...");
            var inputs = document.getElementsByClassName('input_quantity');
            contador = inputs.length;
            $('#item_quantity').val(inputs.length);
            for (var i = 0; i < inputs.length; i++) {
                inputs[i].addEventListener('change', function () {
                    var id = this.id;
                    string_no_id = id.split("_");
                    no_storage = string_no_id[2];
                    no_id = string_no_id[5];

                    var cantidad_cambio = $('#' + id).val();
                    if (cantidad_cambio == "") {
                        $('#' + id).val(1);
                        var anterior_precio = $('#total_storage_' + no_storage + '_id_item_' + no_id).val();
                        //obtener el valor del input del precio
                        var selling_price = $('#selling_storage_' + id_storage + '_id_item_' + no_id).val();
                        //obtenemos el nuevo precio
                        var total_temp = parseFloat(selling_price) * parseFloat(1);
                        $('#total_storage_' + no_storage + '_id_item_' + no_id).val(total_temp.toFixed(2));

                        //cambiar el precio total
                        var general_temp = $('#total_general').val();
                        general_temp = parseFloat(general_temp) - anterior_precio + parseFloat(total_temp);
                        // $('#total_general').val(general_temp.toFixed(2));

                        // var totalFormateado = monedaChange();
                        // $('#total_general2').val(totalFormateado);
                    } else {

                        //obtenos el total anterior del input sub_total
                        var anterior_precio = $('#total_storage_' + no_storage + '_id_item_' + no_id).val();
                        //obtener el valor del input del precio
                        var selling_price = $('#selling_storage_' + id_storage + '_id_item_' + no_id).val();
                        //obtenemos el nuevo precio
                        var total_temp = parseFloat(selling_price) * parseFloat(cantidad_cambio);
                        $('#total_storage_' + no_storage + '_id_item_' + no_id).val(total_temp.toFixed(2));

                        //cambiar el precio total
                        var general_temp = $('#total_general').val();
                        general_temp = parseFloat(general_temp) - anterior_precio + parseFloat(total_temp);
                        // $('#total_general').val(general_temp.toFixed(2));

                        // var totalFormateado = monedaChange();
                        // $('#total_general2').val(totalFormateado);
                    }
                    $('#total_general').val(general_temp.toFixed(2));

                    var totalFormateado = monedaChange();
                    $('#total_general2').val(totalFormateado);
                    setPaymentValues(totalFormateado);
                });
            }

            var inputs_price = document.getElementsByClassName('input_price');
            for (var i = 0; i < inputs.length; i++) {
                inputs_price[i].addEventListener('change', function () {
                    var id = this.id;
                    string_no_id = id.split("_");
                    no_storage = string_no_id[2];
                    no_id = string_no_id[5];
                    var selling_price = $("#selling_" + no_id).val();
                    var low_price = $('#low_' + no_id).val();
                    // var low_price = parseFloat($('#minprice_'+no_id).val());
                    var costo_unitario = $('#total_cost_' + no_storage + '_id_item_' + no_id);
                    var precio_cambio = $('#' + id).val();
                    if (precio_cambio == "" || parseFloat(precio_cambio) < 1) {
                        $('#' + id).val(selling_price);
                        costo_unitario.val(selling_price);
                        var anterior_precio = $('#total_storage_' + no_storage + '_id_item_' + no_id).val();

                        var cantidad_total = $('#id_storage_' + id_storage + '_id_item_' + no_id).val();
                        var total_temp = parseFloat(cantidad_total) * parseFloat(selling_price);
                        $('#total_storage_' + no_storage + '_id_item_' + no_id).val(parseFloat(cantidad_total) * parseFloat(selling_price));
                        var general_temp = $('#total_general').val();
                        general_temp = parseFloat(general_temp) - anterior_precio + parseFloat(total_temp);
                        // $('#total_general').val(general_temp.toFixed(2));
                        // var totalFormateado = monedaChange();
                        // $('#total_general2').val(totalFormateado);
                    } else {
                        if (parseFloat(precio_cambio) < parseFloat(low_price)) {
                            toastr.error("El precio de venta no debe ser menor al precio mínimo del artículo (Q " + low_price + ")");
                            $('#' + id).val(low_price);
                            costo_unitario.val(low_price);
                            var anterior_precio = $('#total_storage_' + no_storage + '_id_item_' + no_id).val();

                            var cantidad_total = $('#id_storage_' + id_storage + '_id_item_' + no_id).val();
                            var total_temp = parseFloat(cantidad_total) * parseFloat(low_price);
                            $('#total_storage_' + no_storage + '_id_item_' + no_id).val(parseFloat(cantidad_total) * parseFloat(low_price));
                            var general_temp = $('#total_general').val();
                            general_temp = parseFloat(general_temp) - anterior_precio + parseFloat(total_temp);
                        } else {
                            var anterior_precio = $('#total_storage_' + no_storage + '_id_item_' + no_id).val();
                            var cantidad_total = $('#id_storage_' + id_storage + '_id_item_' + no_id).val();
                            var total_temp = parseFloat(cantidad_total) * parseFloat(precio_cambio);
                            $('#total_storage_' + no_storage + '_id_item_' + no_id).val(total_temp.toFixed(2));

                            var general_temp = $('#total_general').val();
                            general_temp = parseFloat(general_temp) - anterior_precio + parseFloat(total_temp);
                            costo_unitario.val(precio_cambio);

                            // $('#total_general').val(general_temp.toFixed(2));
                            // var totalFormateado = monedaChange();
                            // $('#total_general2').val(totalFormateado);
                        }

                    }
                    $('#total_general').val(general_temp.toFixed(2));
                    var totalFormateado = monedaChange();
                    $('#total_general2').val(totalFormateado);
                    setPaymentValues(totalFormateado);
                });
            }
            var btns_delete = document.getElementsByClassName('btn_delete');
            for (var i = 0; i < btns_delete.length; i++) {
                btns_delete[i].onclick = remove;
            }
            var general_temp = $('#total_general').val();
            general_temp = parseFloat(general_temp);
            $('#total_general').val(general_temp.toFixed(2));
            var totalFormateado = monedaChange();
            $('#total_general2').val(totalFormateado);
            setPaymentValues(totalFormateado);
            hideLoading();
        }

        function autocomplete() {
            var options = {
                url: APP_URL + "/quotation/autocomplete",
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
                            toastr.options.onHidden = function () {
                                location.reload();
                            };
                            toastr.error('Su sesión ha expirado, debe volver a autenticase!<br>');

                        }
                    }
                },

                preparePostData: function (data) {
                    data.id = $("#autocom").val();
                    data.id_pago=cleanNumber($("#id_pago").val());
                    if( (data.bodega_id === 0) || (data.id_pago === 0))
                    {
                        toastr.error("Seleccione una bodega y/o forma de pago.");
                        return;
                    }
                    return data;
                },

                requestDelay: 400
            };

            $("#autocom").easyAutocomplete(options);
        }

        function buscar_codigo(codigo,id_pago) {
            $.get("{{ url('quotation/search_code?id=') }}" + codigo+"&id_pago="+id_pago, function (data) {
                if(Object.keys(data).length>0){
                    agregar(data);/*Llamamos la funcion para agregar a la tabla de productos*/
                }
                else{
                    toastr.error("El código ingresado no puede encontrarse.");
                    $('#codigo').val('');
                    $('#codigo').focus();
                }
            });
        };
    </script>
@stop
