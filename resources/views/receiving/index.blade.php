@extends('layouts/default')
@section('title',trans('receiving.item_receiving'))
@section('page_parent',"Compras")
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
                        {{trans('receiving.item_receiving')}}
                    </h3>
                    <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
                </div>
                <div class="panel-body">
                    <div class="bs-example">
                        <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                            <li class="active">
                                <a href="#venta" data-toggle="tab">Compra</a>
                            </li>
                            <li id="tab_pago" style="display:none;">
                                <a href="#pago" data-toggle="tab" id="link_pago">Pago</a>
                            </li>
                        </ul>
                        <div id="tabsVentas" class="tab-content">
                            <div class="tab-pane fade active in" id="venta">
                                <div class="row">
                                    {!! Form::open(array('url' => 'receivings', 'class' => 'form','id'=>'save_receivings')) !!}
                                    <input type="hidden" name="token" value="{{csrf_token()}}" id="token_">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            {!! Form::label('date_tx', trans('Fecha')) !!}
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="livicon" data-name="calendar" data-size="16"
                                                    data-c="#555555" data-hc="#555555" data-loop="true"></i>
                                                </div>
                                                {!! Form::text('date_tx', Input::old('date_tx'), array('id'=>'date_tx','class' => 'form-control')) !!}
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
                                                    @foreach($almacendata as $value)
                                                    <option value="{!! $value->id !!}">{{ $value->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            {!! Form::label('payment_method', trans('receiving.supplier')) !!}
                                            <div class="input-group select2-bootstrap-prepend">
                                                <div class="input-group-addon"><i class="fa fa-users"></i></div>
                                                <select class="form-control" name="supplier_id"
                                                id="supplier_id">
                                                <option value="0">Seleccione proveedor</option>
                                                @foreach($supplier as $value)
                                                <option value="{!! $value->id !!}">{{ $value->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            <div class="input-group-btn">
                                                <a href="#" style="font-size: 14px" id="add_customer_btn"
                                                class="btn btn-default btn-icon" data-toggle="modal"
                                                data-target="#modal-2"><i class="fa fa-plus"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        {!! Form::label('customer_balance', trans('sale.customer_balance'), ['class' => 'control-label']) !!}
                                        <div class="input-group select2-bootstrap-prepend">
                                            <div class="input-group-addon">Q</div>
                                            {!! Form::text('customer_balance', Input::old('customer_balance'), array('id'=>'supplier_credit','class' => 'form-control','readonly'=>'readonly','style'=>'text-align: right;') ) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            {!! Form::label('payment_method', trans('sale.payment_method')) !!}
                                            <div class="input-group select2-bootstrap-prepend">
                                                <div class="input-group-addon"><i class="fa fa-credit-card"></i>
                                                </div>
                                                <select class="form-control"
                                                title="trans('revenues.payment_method')" name="id_pago"
                                                id="id_pago" onchange="cambiopago(this.value);">
                                                <option value="0">--Seleccione--</option>
                                                @foreach($pagos as $item)
                                                <option value="{!! $item->id !!}" type="{!! $item->type !!}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="serie_id"
                                        class="control-label">{{trans('sale.document')}}</label>
                                        <div class="input-group select2-bootstrap-prepend">
                                            <div class="input-group-addon"><i class="fa fa-folder"></i>
                                            </div>
                                            <select class="form-control" name="serie_id" id="id_serie">
                                                @foreach($serieFactura as $value)
                                                <option value="{!!$value->id!!}">{!!$value->nombre!!}-{!!$value->name !!}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        {!! Form::label('user_relation', trans('sale.number'), ['class' => 'control-label']) !!}
                                        {!! Form::text('correlativo_num', Input::old('id_correlativo'), array('id'=>'id_correlativo','class' => 'form-control') ) !!}
                                    </div>
                                </div>
                                {{-- <div class="col-lg-3">
                                    <div class="form-group">
                                        {!! Form::label('user_relation', trans('receiving.employee'), ['class' => 'control-label']) !!}
                                        <div class="input-group select2-bootstrap-prepend">
                                            <div class="input-group-addon"><i class="fa fa-user"></i></div>
                                            <select class="form-control" name="user_relation"
                                            id="user_relation">
                                            @foreach($dataUsers as $id => $name)
                                            <option value="{{$name->id}}" {{($id==$idUserActive ? 'selected' : '')}} >
                                                {{$name->name.' '.$name->last_name}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-lg-3">
                                <div class="form-group">
                                    {!! Form::label('reference', trans('receiving.reference'), ['class' => 'control-label']) !!}
                                    <div class="input-group select2-bootstrap-prepend">
                                        <div class="input-group-addon"><i class="fa fa-file-text-o"></i></div>
                                        <input class="form-control" type="text" name="reference" placeholder="Referencia documento del proveedor">
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                            <input type="text" id="autocom" class="easy-autocomplete"
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
                                                    <table id="target" class="table table-bordered" >
                                                        <thead style="background: #9CBBD9">
                                                            <tr>
                                                                {{-- <th>No.</th> --}}
                                                                <th>Código</th>
                                                                <th style="width:30%">Producto</th>
                                                                <th>Costo</th>
                                                                <th>Nuevo costo</th>
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
                                            <div class="col-md-6">
                                                <div>&nbsp;</div>
                                                <div class="form-group">
                                                    <label for="employee"
                                                    class="col-sm-4 control-label">{{trans('receiving.comments')}}</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control" name="comments"
                                                        id="comments"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6" style="text-align: right;font-size: 28px;">
                                                <div class="form-group">
                                                    <label for="supplier_id"
                                                    class="col-sm-4 control-label">Total:</label>
                                                    <div class="col-sm-8" style="text-align: center;">
                                                        <input type="hidden" name="item_quantity" value="0" id="item_quantity">
                                                        <input type="hidden" name="max_credit_amount" value="-1" id="max_credit_amount">
                                                        <input type="hidden" name="total_cost" style="border: none;text-align:center;background-color: white;"   id="total_general" value="0">
                                                        <input type="text" name="total_cost2" style="border: none;text-align:center;background-color: white;" disabled  id="total_general2" value="0">
                                                        <label for="" id="sumary_expenses" style="font-size: 12px"></label>
                                                    </div>
                                                </div>
                                                {!! Form::hidden('total_cost', Input::old('total_cost'), array('class' => 'form-control')) !!}
                                                {!! Form::hidden('outlays','', array('id'=>'dOutlays'))!!}
                                                <div>&nbsp;</div>
                                                <input type="hidden" name="max_credit_amount" value="-1" id="max_credit_amount">
                                                {{-- <input type="hidden" name="total_cost" style="border: none;text-align:center;background-color: white;"   id="total_general" value="0"> --}}
                                                <input type="hidden" name="days_credit" id="days_credit">
                                                {!! Form::close() !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="tab-pane fade" id="pago">
                                    @include('partials.payment-types.receiving_payment')
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-info" onclick="showModal()">
                                                Registrar Gasto
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">

                                    </div>
                                    <div class="col-lg-4">
                                        <button type="button" id="idVenta"
                                        class="btn btn-primary btn-block">{{trans('receiving.submit')}}</button>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <input type="hidden" name="path" id="path" value="{{ url('/') }}">
                        
                    </div>
                </div>
            </div>
        </div>

        @include('receiving.expenses')
        @include('receiving.new_provider_modal')
        {{-- Inicio Modal productos --}}
        @include('receiving.list_products')
        @include('receiving.confirm-receiving')
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
    <script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript " src="{{ asset('js/pages/expenses_receivings.js')}} "></script>
    {{-- Select2 --}}
    <script type="text/javascript" src="{{ asset('assets/vendors/select2/js/select2.js')}} "></script>

    <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"type="text/javascript"></script>


    {{-- autocomplete --}}
    <script src="{{asset('assets/js/jquery.easy-autocomplete.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/recivings/products-table.js')}} "></script>
    <script type="text/javascript" src="{{ asset('assets/js/recivings/add.js')}} "></script>
    <script type="text/javascript" src="{{ asset('assets/js/recivings/add_new_suppliers.js')}} "></script>
    <script type="text/javascript" src="{{ asset('assets/js/recivings/validations.js')}} "></script>
    <script type="text/javascript" src="{{ asset('assets/js/recivings/payment_type_receiving.js')}} "></script>
    <script type="text/javascript">
        $(document).ready(function () {

            document.getElementById("save_receivings").onkeypress = function (e) {
                var key = e.charCode || e.keyCode || 0;
                if (key == 13) {
                    e.preventDefault();
                    return;
                }
            }

            var cleave = new Cleave('.money_gasto', {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand'
            });
            valid_correlative();
            $('#id_serie').change(function(){
                valid_correlative();
            });

            $.fn.select2.defaults.set("width", "100%");
            var dateNow = new Date();
            $("#paid_at ").datetimepicker({
                sideBySide: true,
                locale: 'es',
                format: 'DD/MM/YYYY',
                defaultDate: dateNow
            }).parent().css("position :relative ");
            $("#date_tx").datetimepicker({
                sideBySide: true,
                locale: 'es',
                format: 'DD/MM/YYYY',
                defaultDate: dateNow
            }).parent().css("position :relative");
            $("#paid_at").datetimepicker({
                sideBySide: true,
                locale: 'es',
                format: 'DD/MM/YYYY',
                defaultDate: dateNow
            }).parent().css("position :relative");
            $("#date_payments").datetimepicker({
                sideBySide: true,
                locale: 'es',
                format: 'DD/MM/YYYY'
            }).parent().css("position :relative");
            $('select').select2({
                allowClear: true,
                theme: "bootstrap",
                placeholder: "Buscar"
            });
            autocomplete();
            $("#codigo").keydown(function(event){
                if( event.which == 13)
                {
                    event.preventDefault();
                    var codigo=  $("#codigo").val().trim();
                    if((typeof $("#id_bodega").val() === 'undefined') || ($("#id_bodega").val() === null))
                    {
                        toastr.error("Seleccione una bodega.");
                    }
                    else {
                        var bodega=  $("#id_bodega").val().trim();
                        if((codigo !== "") ) {
                            buscar_codigo(codigo);
                        }
                    }
                };
            });
        });

        /*
        * MODAL PARA MOSTRAR IMAGENES DE PRODUCTOS
        * */
        function showImage(avatar, nombre){
            $('#lblTitulo').text(nombre);
            $('#image').attr("src", avatar);
            $('#modal-image').modal("show");
        }
        /*--------------------------------------*/

        function cambiopago(pago_id) {
            if (pago_id) {
                $.get(APP_URL + '/banks/get-account-type/' + [pago_id] + '/0', function (data) {
                    $('#account_id').select2({
                        allowClear: true,
                        theme: "bootstrap",
                        placeholder: "Buscar"
                    });
                    $('#account_id').empty();
                    $('#account_id').append('<option value="">Seleccione cuenta</option>');
                    $.each(data, function (index, accounts) {
                        $('#account_id').append('<option value="' + accounts.id + '">' + accounts.name + ' - ' + accounts.pct_interes + '</option>');
                    });
                });
            } else {
                $('select[name="account_id"]').empty();
            }
            ;
            $('#id_pago').val(pago_id);
            // console.log('Adm id: '+$('#account_id').val());
        }

        function autocomplete() {
            var options = {
                url: APP_URL+"/api/receivings/autocomplete",
                getValue: "item_name",
                template: {
                    type: "custom",
                    method: function (value, item) {
                        return '<strong>' + item.upc_ean_isbn + " | " + value + '</strong> | Q.' + item.cost_price;
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
                    return data;
                },

                requestDelay: 400
            };

            $("#autocom").easyAutocomplete(options);
        }

        function buscar_codigo(codigo)
        {
            $.get( "{{ url('api/receivings/search_code?id=') }}"+codigo, function( data ) {
                if (data!=""){
                    agregar(data);/*Llamamos la funcion para agregar a la tabla de productos*/
                }
            });
        };
    </script>
    @stop
