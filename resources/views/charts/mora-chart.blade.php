@extends('layouts/default')
@section('title',trans('Saldos'))
@section('page_parent',trans('Graficas'))
@section('header_styles')

<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/colReorder.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/rowReorder.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/scroller.bootstrap.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/pages/tables.css') }}" />
<link href="{{ asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css">

<link rel="stylesheet" href="{{ asset('css/pages/tab.css') }}" />
<style>
    .tab-content>.tab-pane {
        display: block;
        height: 0;
        overflow: hidden;
    }

    .tab-content>.tab-pane.active {
        height: 100%;
    }
</style>





@stop
@section( 'content')
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="row">

                </div>
                <!-- <div class="panel-heading">Listado de series y documentos</div> -->
                <div class="panel-body">
                    @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif {{-- --}}
                    <div class="panel panel-primary">
                        <div class="panel-body">
                            <!--tab starts-->
                            <!-- Nav tabs category -->
                            <ul class="nav nav-tabs faq-cat-tabs">
                                <li class="active">
                                    <a href="#faq-cat-0" data-toggle="tab">General</a>
                                </li>
                                @foreach ($all as $data)
                                <li>
                                    <a href="#faq-cat-{{$data->id}}" data-toggle="tab" id="tab2" onclick="grafica2({{$data->id}})">{{$data->name}}</a>
                                </li>
                                @endforeach
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content faq-cat-content">
                                <div class="tab-pane active in fade" id="faq-cat-0">
                                    <div class="panel panel-default panel-faq">
                                        <div class="panel-group" id="accordion-cat-1">
                                            <div id="container" style="width:100%; height:450px;"></div>
                                        </div>
                                    </div>
                                </div>
                                @foreach ($all as $data)
                                <div class="tab-pane fade" id="faq-cat-{{$data->id}}">
                                    <div class="panel panel-default panel-faq">
                                        <div class="panel-group" id="accordion-cat-2">
                                            <div id="container{{$data->id}}" style="height: 450px"></div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <!--tab ends-->
                        </div>
                    </div>
                    {{-- --}}

                    <hr>
                    <br> {{--
                    <div id="containerdet" style="width:100%; height:450px;"></div> --}} {{-- <canvas id="cobrochart"></canvas> --}}
                    <div class="panel-body table-responsive">
                        {{--
                        <table class="table table-bordered " id="table1">
                            <thead>
                                <tr class="filters" style="font-size: 13px;">
                                    <th style="text-align: center;">No.</th>
                                    <th style="text-align: center; width:250px">Ruta</th>
                                    <th style="text-align: center;">Meta</th>
                                    <th style="text-align: center;">Cobrado</th>
                                    <th style="text-align: center;">%</th>
                                    <th style="text-align: center;">Mora</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_graph as $i => $data)
                                <tr style="font-size: 12px">
                                    <td style="text-align: center;">{!! $i+1 !!}</td>
                                    <td>{!! $data->name !!}</td>
                                    <td style="text-align: right; ">@money( $data->meta)</td>
                                    <td style="text-align: right; ">@money($data->cobrado )</td>
                                    <td style="text-align: right; ">@pct( ( $data->cobrado/$data->meta)*100 )</td>
                                    <td style="text-align: right; ">@money($data->mora )</td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table> --}}
                    </div>
                    <!-- data grafica -->
                    <div class="row">
                        <?php $i = 0;
                    $monto_total = 0;
                    $monto_total_mora = 0;
                    if (isset($all)) {
                        ?>
                        <input name="graph_qty" id="graph_qty" type="hidden" value="{{ count($all) }}"> @foreach ($all as
                        $data )
                        <?php  $i++;
                    $monto_total = $monto_total+ $data->amount; ?>
                        <input name="ruta_{{ $i }}" id="ruta_{{ $i }}" type="hidden" value="{{ $data->name }}">
                        <input name="amount_{{ $i }}" id="amount_{{ $i }}" type="hidden" value="{{ $data->amount }}"> @endforeach
                        <input name="total_amount" id="total_amount" type="hidden" value="{{ $monto_total }}">
                        <?php }
                    $i = 0;
                    if (isset($morosos)) {
                        ?>
                        <input name="morosos_qty" id="morosos_qty" type="hidden" value="{{ count($morosos) }}"> @foreach($morosos
                        as $data )
                        <?php  $i++;
                    $monto_total_mora = $monto_total_mora+ $data->amount; ?>
                        <input name="ruta_mora_{{ $i }}" id="ruta_mora_{{ $i }}" type="hidden" value="{{ $data->name }}">
                        <input name="amount_mora_{{ $i }}" id="amount_mora_{{ $i }}" type="hidden" value="{{ $data->amount }}">                        @endforeach
                        <?php }
                    ?>
                        <input name="total_amount_mora" id="total_amount_mora" type="hidden" value="{{ $monto_total_mora }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('footer_scripts')
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>

<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.buttons.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.responsive.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/buttons.colVis.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/buttons.html5.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/buttons.print.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/buttons.bootstrap.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/buttons.print.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/pdfmake.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/vfs_fonts.js') }}"></script>
{{-- Calendario --}}
<script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>

<script src="{{ asset('js/pages/tabs_accordions.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('js/echarts.min.js') }}"></script>
<script type="text/javascript ">
    var datos = [];

    /*Grafica general */
    datos = [
        {value: parseFloat($("#total_amount_mora").val()),name: "Mora" },
        {value:parseFloat($("#total_amount").val()),name: "Saldo" }
        ];

    var dom = document.getElementById("container");

            var myChart = echarts.init(dom);
            var app = {};
            option = null;
            option = {
                toolbox: {
                    feature: {
                        saveAsImage : {show: true, title:'Exportar'}
                    }
                },
                 title: {
                    text: "Saldo no morosos vs morosos : General",
                    subtext: "Todas las rutas",
                    x: "center"
                    },
                tooltip: {
                    trigger: "item",
                    formatter: "{b} : Q {c} ({d}%)"
                },
                 legend: {
                    orient: "vertical",
                    left: "left",
                    data: ["Mora", "Saldo"]
                },
                series: [
                {
                    name: "Saldo vs Mora",
                    type: "pie",
                    radius: "55%",
                    center: ["50%", "60%"],
                    data: datos,
                    itemStyle: {
                        emphasis: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: "rgba(0, 0, 0, 0.5)"
                        }
                    }
                }
                ]
            };

            if (option && typeof option === "object") {
                myChart.setOption(option, true);


            }



    function grafica2(ruta)
    {/*Grafica por ruta  */
        var datos = [];


            datos = [
                {value: parseFloat($("#amount_mora_"+ruta).val()),name: "Mora" },
                {value:parseFloat($("#amount_"+ruta).val()),name: "Saldo" }
                ];

            var dom = document.getElementById("container"+ruta);

            var myChart = echarts.init(dom);
            var app = {};
            option = null;
            option = {
                toolbox: {
                    feature: {
                        saveAsImage : {show: true}
                    }
                },
                 title: {
                    text: "Saldo no morosos vs morosos : "+$("#ruta_"+ruta).val(),
                    // subtext: $("#ruta_"+ruta).val(),
                    x: "center"
                    },
                tooltip: {
                    trigger: "item",
                    formatter: "{b} : Q {c} ({d}%)"
                },
                 legend: {
                    orient: "vertical",
                    left: "left",
                    data: ["Mora", "Saldo"]
                },
                series: [
                {
                    name: "Saldo vs Mora",
                    type: "pie",
                    radius: "55%",
                    center: ["50%", "60%"],
                    data: datos,
                    itemStyle: {
                        emphasis: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: "rgba(255,255,0,0.3)"
                        }
                    }
                }
                ]
            };

            if (option && typeof option === "object") {
                myChart.setOption(option, true);
            }
    }
$(document).ready(function() {
    // update_graph();

});

</script>
@stop
