@extends('layouts/default')

@section('title',trans('item.item_search'))
@section('page_parent',trans('item.items'))

@section('header_styles')
<style>
    .texCenter {
        text-align: center;
    }

    .texRight {
        text-align: right;
    }
</style>
{{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css') }}" /> --}}
{{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/tables/table_responsive.css') }}" /> --}}
{{-- <link href="{{ asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css"/> --}}
<!-- Toast -->
<link href="{{ asset('assets/css/toast/toastr.min.css') }}" rel="stylesheet" type="text/css" />
{{-- autocomplete --}}
<link href="{{ asset('assets/css/easy-autocomplete.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/iCheck/css/all.css') }}" rel="stylesheet">
@stop
@section('content')
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                        {{trans('item.item_search')}}
                    </h3>
                    <span class="pull-right clickable">
                        <i class="glyphicon glyphicon-chevron-up"></i>
                    </span>
                </div>
                <!-- <div class="panel-heading">Listado de créditos</div> -->
                <div class="panel-body">

                    
                    <div class="row">
                        <h4>{{trans('item.write_to_search')}}</h4>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <input type="text" autocomplete="off" id="codigo" class="form-control" placeholder="{{trans('item.enter_code')}}">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <input type="text" autocomplete="off" id="item_name" class="form-control" placeholder="{{trans('item.enter_item_name')}}">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <input type="text" autocomplete="off" id="other_field" class="form-control" placeholder="{{trans('item.enter_other_text')}}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4"></div>
                        <div class="col-lg-4">
                            <h6 style="text-align:right">{{trans('item.disclaimer_search')}}.</h6>
                        </div>
                        <div class="col-lg-4"></div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-2"><label for="">Contenido a mostrar: </label></div>

                        <div class="col-md-2" style="text-align:center">
                            <h4>Precios</h4>
                        </div>
                        <div class="col-md-2" style="text-align:center">
                            <input name="tipo_" checked id="tipo_"  type="radio" class="square" value="precio">

                        </div>
                        <div class="col-md-2" style="text-align:center">
                            <h4>Existencias</h4>
                        </div>
                        <div class="col-md-2" style="text-align:center">
                            <input name="tipo_" id="tipo_2"  type="radio"  class="square" value="existencia">

                        </div>
                        <div class="col-md-2">
                            <input type="hidden" id="type_search" value="precio">
                        </div>
                    </div>
                    <!-- search box container ends  -->
                    {{-- <div class="panel-body table-responsive"> --}}
                        <div class="panel-body">
                            <div id="txtHint" class="title-color" style="padding-top:50px; text-align:center;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endsection

        @section('footer_scripts')
        <div class="modal fade" id="parameter_inspector" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color:  #d9534f;color: white;">
                        <h4 class="modal-title" id="exampleModalLabel">Falta configurar parámetros.</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>No se puede crear créditos sin antes configurar los parámetros generales de su empresa, por favor dirigase
                            al menú Parametros o de clic <a href="{{url('parameters')}}">Aquí</a> para ser redirigido.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                $(function () {$('body').on('hidden.bs.modal', '.modal', function () {$(this).removeData('bs.modal');});});

            </script>

            {{-- <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/datatables.min.js') }}"></script> --}}
            <!-- Valiadaciones -->
            {{-- <script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script> --}}

            {{-- <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }} " type="text/javascript "></script>
            <script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }} " type="text/javascript "></script>
            <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }} " type="text/javascript "></script> --}}

            <!-- Toast -->
            <script type="text/javascript " src="{{ asset('assets/js/toast/toastr.min.js')}} "></script>
            {{-- autocomplete --}}
            <script src="{{asset('assets/js/jquery.easy-autocomplete.min.js')}}"></script>
            <script src="{{ asset('assets/vendors/iCheck/js/icheck.js') }}"></script>

            <script type="text/javascript">
                function clearInputs(){
                    $("#item_name").val('');
                    $("#other_field").val('');
                    $("#code").val('');
                }
                $(document).ready(function(){
                    /*Se inicializan los checkboxes*/
                    $('input[type="checkbox"].square, input[type="radio"].square').iCheck({
                        checkboxClass: 'icheckbox_square-green',
                        radioClass: 'iradio_square-green',
                        increaseArea: '20%'
                    });

                    $('#tipo_').on('ifChecked', function(event){
                        document.getElementById('type_search').value = 'precio';
                        $( "#txtHint" ).html('<table class="table table-striped table-bordered responsive" id="tablePagares"><thead><tr><th data-priority="1" style="text-align: center: width:5%"># codigo</th><th data-priority="6">Ruta</th><th data-priority="4" style="text-align: center;font-size:12px">Fecha</th><th data-priority="5" >Nombre</th><th data-priority="8">Tipo</th><th data-priority="7" style="text-align: center;">Monto del crédito</th><th data-priority="3" style="text-align: center;">Saldo pendiente</th><th data-priority="2" style="text-align: center;width:5%">Pagar</th></tr></thead></table>');
                        clearInputs();
                    });
                    $('#tipo_2').on('ifChecked', function(event){
                        document.getElementById('type_search').value = 'existencia';
                        $( "#txtHint" ).html('<table class="table table-striped table-bordered responsive" id="tablePagares"><thead><tr><th data-priority="1" style="text-align: center: width:5%"># codigo</th><th data-priority="6">Ruta</th><th data-priority="4" style="text-align: center;font-size:12px">Fecha</th><th data-priority="5" >Nombre</th><th data-priority="8">Tipo</th><th data-priority="7" style="text-align: center;">Monto del crédito</th><th data-priority="3" style="text-align: center;">Saldo pendiente</th><th data-priority="2" style="text-align: center;width:5%">Pagar</th></tr></thead></table>');
                        clearInputs();
                    });


                    var table= $('#table1').DataTable({
                        dom: 'rtip',
                        "language":{
                            "url":"{{ asset('assets/js/datatables/Spanish.js')}}"
                        }, "dom": 'Bfrtip',
                        responsive: {
                            details: {
                                type: 'column'
                            }
                        },
                        columnDefs: [ {
                            className: 'control',
                            orderable: false,
                            searchable: false,
                            targets:   0
                        } ]
                    });
                    document.getElementById("codigo").onkeypress = function(e) {
                        var key = e.charCode || e.keyCode || 0;
                        if (key == 13) {
                            // e.preventDefault();
                            valida_codigo();
                        }
                    }

                    document.getElementById("item_name").onkeypress = function(e) {
                        var key = e.charCode || e.keyCode || 0;
                        if (key == 13) {
                            e.preventDefault();
                        }
                    };

                    document.getElementById("other_field").onkeypress = function(e) {
                        var key = e.charCode || e.keyCode || 0;
                        if (key == 13) {
                            e.preventDefault();
                        }
                    };
                    $("#codigo").keyup(function(){
                        valida_codigo();
                    });
                    function valida_codigo(){
                        var str=  $("#codigo").val().trim();
                        var largo = $("#codigo").val().length;
                        if((str == "")  || (largo==0))
                        {
                            $( "#txtHint" ).html('<table class="table table-striped table-bordered responsive" id="tablePagares"><thead><tr><th data-priority="1" style="text-align: center: width:5%"># codigo</th><th data-priority="6">Ruta</th><th data-priority="4" style="text-align: center;font-size:12px">Fecha</th><th data-priority="5" >Nombre</th><th data-priority="8">Tipo</th><th data-priority="7" style="text-align: center;">Monto del crédito</th><th data-priority="3" style="text-align: center;">Saldo pendiente</th><th data-priority="2" style="text-align: center;width:5%">Pagar</th></tr></thead></table>');
                        }else {
                            $("#item_name").val('');
                            $("#other_field").val('');
                            consultar(str);
                        }
                    }
                    $("#codigo").focus(function(){
                        $("#item_name").val('');
                        $("#other_field").val('');
                        $( "#txtHint" ).html('<table class="table table-striped table-bordered responsive" id="tablePagares"><thead><tr><th data-priority="1" style="text-align: center: width:5%"># codigo</th><th data-priority="6">Ruta</th><th data-priority="4" style="text-align: center;font-size:12px">Fecha</th><th data-priority="5" >Nombre</th><th data-priority="8">Tipo</th><th data-priority="7" style="text-align: center;">Monto del crédito</th><th data-priority="3" style="text-align: center;">Saldo pendiente</th><th data-priority="2" style="text-align: center;width:5%">Pagar</th></tr></thead></table>');
                    });
                    $("#item_name").focus(function(){
                        $("#codigo").val('');
                        $("#other_field").val('');
                        $( "#txtHint" ).html('<table class="table table-striped table-bordered responsive" id="tablePagares"><thead><tr><th data-priority="1" style="text-align: center: width:5%"># codigo</th><th data-priority="6">Ruta</th><th data-priority="4" style="text-align: center;font-size:12px">Fecha</th><th data-priority="5" >Nombre</th><th data-priority="8">Tipo</th><th data-priority="7" style="text-align: center;">Monto del crédito</th><th data-priority="3" style="text-align: center;">Saldo pendiente</th><th data-priority="2" style="text-align: center;width:5%">Pagar</th></tr></thead></table>');
                    });
                    $("#other_field").focus(function(){
                        $("#codigo").val('');
                        $("#item_name").val('');
                        $( "#txtHint" ).html('<table class="table table-striped table-bordered responsive" id="tablePagares"><thead><tr><th data-priority="1" style="text-align: center: width:5%"># codigo</th><th data-priority="6">Ruta</th><th data-priority="4" style="text-align: center;font-size:12px">Fecha</th><th data-priority="5" >Nombre</th><th data-priority="8">Tipo</th><th data-priority="7" style="text-align: center;">Monto del crédito</th><th data-priority="3" style="text-align: center;">Saldo pendiente</th><th data-priority="2" style="text-align: center;width:5%">Pagar</th></tr></thead></table>');
                    });
                    $("#item_name").keyup(function(){
                        var str=  $("#item_name").val().trim();
                        var largo = Number($("#item_name").val().length);
                        console.log('item_name '+str+' largo '+largo);
                        if((str == "")  || (largo<3))
                        {
                            $( "#txtHint" ).html('<table class="table table-striped table-bordered responsive" id="tablePagares"><thead><tr><th data-priority="1" style="text-align: center: width:5%"># codigo</th><th data-priority="6">Ruta</th><th data-priority="4" style="text-align: center;font-size:12px">Fecha</th><th data-priority="5" >Cliente</th><th data-priority="8">Tipo</th><th data-priority="7" style="text-align: center;">Monto del crédito</th><th data-priority="3" style="text-align: center;">Saldo pendiente</th><th data-priority="2" style="text-align: center;width:5%">Pagar</th></tr></thead></table>');
                        }else {
                            $("#codigo").val('');
                            $("#other_field").val('');
                            consulta_cliente(str);
                        }
                    });

                    $("#other_field").keyup(function(){
                        var str=  $("#other_field").val().trim();
                        var largo = Number($("#other_field").val().length);
                        console.log('other_field '+str+' largo '+largo);
                        if((str == "")  || (largo<3))
                        {
                            $( "#txtHint" ).html('<table class="table table-striped table-bordered responsive" id="tablePagares"><thead><tr><th data-priority="1" style="text-align: center: width:5%"># codigo</th><th data-priority="6">Ruta</th><th data-priority="4" style="text-align: center;font-size:12px">Fecha</th><th data-priority="5" >Cliente</th><th data-priority="8">Tipo</th><th data-priority="7" style="text-align: center;">Monto del crédito</th><th data-priority="3" style="text-align: center;">Saldo pendiente</th><th data-priority="2" style="text-align: center;width:5%">Pagar</th></tr></thead></table>');
                        }else {
                            $("#codigo").val('');
                            $("#item_name").val('');
                            consulta_otros(str);
                        }
                    });
                });


                function consultar(codigo)
                {
                    let type = $("#type_search").val();
                    let url = 'api/items/search_by_code?type_search='+type+'&codigo='+codigo;
                    $.get( APP_URL + "/"+ url, function( data ) {
                        $( "#txtHint" ).html( data );
                    });
                };

                function consulta_cliente(item_name)
                {
                    let type = $("#type_search").val();
                    let url = 'api/items/search_by_name?type_search='+type+'&item_name='+item_name;
                    $.get( APP_URL + "/"+ url, function( data ) {
                        $( "#txtHint" ).html( data );
                    });
                };
                function consulta_otros(other_field)
                {
                    let type = $("#type_search").val();
                    let url = 'api/items/search_by_other?type_search='+type+'&other='+other_field;
                    $.get( APP_URL + "/"+ url, function( data ) {
                        $( "#txtHint" ).html( data );
                    });
                };
            function convertTable(){
                $('#table1').DataTable({
                    "language": {"url": "{{ asset('assets/json/Spanish.json') }}"},
                    dom: 'Bfrtip',
                    responsive: {
                        details: {
                            type: 'column'
                        }
                    },
                    columnDefs: [{
                        className: 'control',
                        orderable: false,
                        targets: 0
                    }],
                    buttons: [
                        {
                            extend: 'collection',
                            text: 'Exportar/Imprimir',
                            buttons: [
                                {
                                    extend: 'copy',
                                    text: 'Copiar',
                                    title: document.title,
                                    exportOptions: {
                                        columns: 'th:not(:last-child)'
                                    }
                                },
                                {
                                    extend: 'excel',
                                    title: document.title,
                                    exportOptions: {
                                        columns: 'th:not(:last-child)'
                                    }
                                },
                                {
                                    extend: 'pdf',
                                    title: document.title,
                                    exportOptions: {
                                        columns: 'th:not(:last-child)'
                                    }
                                },
                                {
                                    extend: 'print',
                                    text: 'Imprimir',
                                    title: document.title,
                                    exportOptions: {
                                        columns: 'th:not(:last-child)'
                                    },
                                }
                            ]
                        },
                    ],
                });
            }

            </script>
            @stop
