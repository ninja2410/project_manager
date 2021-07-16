@extends('layouts/default')

@section('title',trans('sale.register_transfer_to_storage_name'))
@section('page_parent',trans('Bodegas'))

@section('header_styles')
    <link href="{{ asset('assets/css/toast/toastr.min.css') }}" rel="stylesheet" type="text/css"/>

    {{-- select 2 --}}
    <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css"/>
    {{-- autocomplete --}}
    <link href="{{ asset('assets/css/easy-autocomplete.css') }}" rel="stylesheet" type="text/css"/>
@stop
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff"
                               data-loop="true"></i>
                            {{trans('sale.register_transfer_to_storage_name')}}
                        </h3>
                        <span class="pull-right clickable">
            <i class="glyphicon glyphicon-chevron-up"></i>
          </span>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div id="message_div" style="display: none">
                                <p id="error_message" class="alert alert-danger"></p>
                                <!-- <p id="notification_message" class="alert alert-info"></p>                 -->
                            </div>
                            <input type="hidden" id="parameter_price" value="{{$param}}">
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="id_document">Documento</label>
                                <select class="form-control" name="id_document" id="id_document" form="addCustomerPayment">
                                    @foreach($data_documents as $value)
                                        <option value="{{$value->id}}">{{$value->document}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label for="price_id">Precio a aplicar:</label>
                                <select class="form-control" name="price_id" id="price_id" form="id_storage_origins_form" required>
                                    <option value="0" selected>Precio costo</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            {!! Form::open(array('url'=>'transfer_to_storage/create','id'=>'id_storage_origins_form', 'method'=>'GET')) !!}
                            <input type="hidden" name="last_items" id="last_items" value="{{json_encode($last_items)}}">
                            <input type="hidden" name="type_change" id="type_change" value="{{$type_change}}">
                            <div class="col-lg-6">
                                <label for="bodega_origen">Bodega origen:</label>
                                <select class="form-control" name="id_storage_origins" id="id_storage_origins"
                                        onchange="refrescarForm(0)" required>
                                    <option value="">Seleccione una bodega</option>
                                    @foreach($data_storage as $value)
                                        <option value="{{$value->id}}" {{ ($selected_storage == $value->id) ?  'selected="selected"' : '' }}>
                                            {{$value->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            {!! Form::close() !!}
                            <div class="col-lg-6">
                                <label for="bodega_origen">Bodega Destino:</label>
                                <select class="form-control" name="id_storage_destination" id="id_storage_destination">
                                    <option value="">Seleccione una bodega</option>
                                    @foreach($data_storage as $value)
                                        @if($selected_storage!=$value->id)
                                            <option value="{{$value->id}}">{{$value->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
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
                        @if(isset($data_items[0]))
                            @include('partials.list-products-transfers')
                        @else
                            <input type="hidden" id="not_items" value="true">
                        @endif
                        <div class="row">
                            <div class="col-md-12">
                                <label>Productos a enviar</label>
                                {!! Form::open(array('url'=>'transfer_to_storage','id'=>'addCustomerPayment')) !!}
                                <input type="hidden" name="totalCost" id="totalCost">
                                <table id="target" class="table table-bordered" >
                                    <thead style="background: #9CBBD9">
                                    <tr>
                                        {{-- <th>No.</th> --}}
                                        <th>Código</th>
                                        <th style="width:30%">Producto</th>
                                        <th style="width: 15%;">Cantidad</th>
                                        <th>Costo</th>
                                        <th>Total</th>
                                        <th style="width:0%">Acciones</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        {{-- <th>No.</th> --}}
                                        <th></th>
                                        <th style="text-align: right;">TOTALES</th>
                                        <th style="width: 15%;"><strong id="total_items_lbl"></strong></th>
                                        <th></th>
                                        <th style="text-align: center;"><strong id="total_cost_lbl"></strong></th>
                                        <th style="width:0%"></th>
                                        <th>&nbsp;</th>
                                    </tr>
                                    </tfoot>
                                </table>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="comment">{{trans('sale.comments')}}</label>
                                        <textarea class="form-control" name="comment" id="comment" rows="2"></textarea>
                                    </div>
                                    {{--                                    <div class="col-lg-6" style="text-align: right;">--}}
                                    {{--                                        <h3>Costo total: <strong id="total_cost_lbl"></strong></h3>--}}
                                    {{--                                    </div>--}}
                                </div>

                                <!--  Elementos para guardar-->
                                <input type="hidden" name="bodega_destino" value="0" id="bodega_destino">
                                <input type="hidden" name="bodega_origen" value="{{$selected_storage}}">
                                <input type="hidden" name="documento" value="0" id="documento">
                                <input type="hidden" name="item_quantity" value="0" id="item_quantity">
                                <input type="hidden" name="tsComment" id="tsComment">
                                {!! Form::close() !!}
                                <div class="col-md-12" style="text-align: center;">
                                    <div class="row">
                                        <br>
                                        <input type="button" name="" value="Guardar" id="btn_save_transfer"
                                               class="btn btn-primary">
                                        <a class="btn btn-danger" href="{{ url('/transfer_to_storage') }}">
                                            Cancelar
                                        </a>
                                    </div>
                                <!-- @include('partials.buttons',['cancel_url'=>"/transfer_to_storage"]) -->

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--Begin modal--}}
        <div class="modal fade modal-fade-in-scale-up" tabindex="-1" id="confirmSave" role="dialog"
             aria-labelledby="modalLabelfade" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h4 class="modal-title">Confirmación guardar</h4>
                    </div>
                    <div class="modal-body">
                        <div class="text-center">
                            Datos correctos
                            <br>
                            ¿Seguro que desea guardar?
                        </div>
                    </div>
                    <div class="modal-footer" style="text-align:center;">
                        <a class="btn  btn-info" id="btn_save_confirm" onclick="sendForm()">Aceptar</a>
                        <a class="btn  btn-danger" data-dismiss="modal">Cancelar</a>
                    </div>
                </div>
            </div>
        </div>
        {{--End modal--}}
    </section>
@stop
@section('footer_scripts')
    {{-- FORMATO DE MONEDAS --}}
    <script src="{{asset('assets/js/vuejs/cleave.min.js')}} "></script>
    <script src="{{asset('assets/js/vuejs/vue-cleave.min.js')}} "></script>
    <script type="text/javascript " src="{{ asset('assets/js/toast/toastr.min.js')}} "></script>
    {{-- Select2 --}}
    <script type="text/javascript" src="{{ asset('assets/vendors/select2/js/select2.js')}} "></script>
    {{-- autocomplete --}}
    <script src="{{asset('assets/js/jquery.easy-autocomplete.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/transfer_alter.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            /* Inicialización de dropdowns */
            $('select').select2({
                allowClear: true,
                theme: "bootstrap",
                placeholder: "Buscar"
            });

            $("#price_id").prop("disabled", true);

            $('#table_advanced').DataTable({
                "bLengthChange": false,
                // "bFilter": true,
                "bInfo": false,
                "bAutoWidth": false,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Buscar...",
                    "sProcessing": "Procesando...",
                    "sLengthMenu": "Mostrar _MENU_ registros",
                    "sZeroRecords": "No se encontraron resultados",
                    "sEmptyTable": "Ningún dato disponible en esta tabla",
                    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix": "",
                    "sUrl": "",
                    "sInfoThousands": ",",
                    "sLoadingRecords": "Cargando...",
                    "oPaginate": {
                        "sFirst": "Primero",
                        "sLast": "Último",
                        "sNext": "Next",
                        "sPrevious": "Previous"
                    },
                    "oAria": {
                        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }
                }
            });

            autocomplete();
            update_cart_items();

            $("#codigo").keydown(function (event) {
                if (event.which == 13) {
                    event.preventDefault();
                    var codigo = $("#codigo").val().trim();
                    if ((typeof $("#id_storage_origins").val() == '') || ($("#id_storage_origins").val() === "")) {
                        toastr.error("Seleccione una bodega.");
                    }
                    else {
                        var bodega = $("#id_storage_origins").val().trim();
                        var id_price = $("#price_id").val().trim();
                        if ((codigo !== "")) {
                            buscar_codigo(codigo, bodega, id_price);
                        }
                    }
                };
            });
            updateCost();
            document.getElementById("addCustomerPayment").onkeypress = function (e) {
                var key = e.charCode || e.keyCode || 0;
                if (key == 13) {
                    e.preventDefault();
                }
            }
        });

        function autocomplete() {
            var options = {
                url: APP_URL+"/api/transfers/autocomplete",
                getValue: "item_name",
                template: {
                    type: "custom",
                    method: function (value, item) {
                        return '<strong>' + item.code + " | " + value + '</strong> | Q.' + item.cost_price+' |  Ext:'+item.quantity;
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
                        agregar(value);
                        $("#autocom").val('');
                        $("#autocom").focus();
                        //add(value); /*Llamamos la funcion para agregar a la tabla de productos*/
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
                    data.bodega = $('#id_storage_origins').val();
                    data.price = $('#price_id').val();
                    return data;
                },

                requestDelay: 400
            };

            $("#autocom").easyAutocomplete(options);
        }


        function refrescarForm(price) {
            get_cart_items();
            $('#type_change').val(price);
            let bodega = document.getElementById('id_storage_origins').value;
            let pp = document.getElementById('parameter_price').value;
            if(bodega == ''){
                toastr.error("Debe seleccionar bodega de origen.");
                document.getElementById('id_storage_origins').focus();
                return;
            }
            if(pp>0){
                let price = document.getElementById('price_id').value;
                if(price == ''){
                    toastr.error("Debe seleccionar tipo de precio.");
                    document.getElementById('price_id').focus();
                    return;
                }
            }

            $('#id_storage_origins_form').submit();
        }

        $('#add_item_btn').click(function(){
            let val = $('#not_items').val();
            if(val != null && $('#id_storage_origins').val() != ''){
                toastr.error("La bodega seleccionada no tiene existencia de ningún producto");
            }
        });

        function buscar_codigo(codigo, bodega, price_id)
        {
            showLoading("Buscando producto...");
            $.get( "{{ url('api/transfers/search_code?code=')}}"+codigo+'&price='+price_id+'&bodega='+bodega, function( data ) {
                if (data!=""){
                    agregar(data);
                    $("#codigo").val('');
                    $("#codigo").focus();
                    //agregar(data);/*Llamamos la funcion para agregar a la tabla de productos*/
                }
                hideLoading();
            });
        };

    </script>


@stop
