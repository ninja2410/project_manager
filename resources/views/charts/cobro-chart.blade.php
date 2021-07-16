@extends('layouts/default')
@section('title',trans('Grafica de cobro'))
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
<link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css"

@stop
@section( 'content') <section class="content">
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="row">
                {{-- {!! Form::open(array('method'=>'get','route'=>array('chats.cobrochart'))) !!} --}} {!! Form::open(['route' => 'charts.cobrochart',
                'method' => 'GET', 'class' => 'nav-form navbar-lef', 'role' => 'search','id'=>'cobro_chart']) !!}
                <div class="row">
                    <div class="col-md-2">
                    </div>
                    <div class="col-md-3">
                        <center><label for=""><b>Fecha inicial</b></label></center>
                        <input type="text" name="date1" id='date1' class="form-control" value="{{date('d/m/Y', strtotime($fecha1))}}">
                    </div>
                    <div class="col-md-3">
                        <center><label for=""><b>Fecha final</b></label></center>
                        <input type="text" name="date2" id='date2' class="form-control" value="{{date('d/m/Y', strtotime($fecha2))}}">
                    </div>
                    <div class="col-md-3">
                        <br>
                        <img id="submitLoading" name="submitLoading" src="{{asset('img/200.gif')}}" style="display: none;" width="10%">                        {!! Form::submit(trans('accounts.generate'), array('class' => 'btn btn-primary','id'=>'generar_grafica'))
                        !!}
                    </div>
                </div>

                {!! Form::close() !!}
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
                @endif
                <div id="container" style="width:100%; height:350px;"></div>
                {{-- <canvas id="cobrochart"></canvas> --}}
                <div class="panel-body table-responsive">
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
                    </table>
                </div>
                <!-- data grafica -->
                <div class="row">
                    <?php $i = 0;
                    if (isset($data_graph)) {
                        ?>
                    <input name="graph_qty" id="graph_qty" type="hidden" value="{{ count($data_graph) }}"> @foreach ($data_graph
                    as $data )
                    <?php  $i++; ?>
                    <input name="ruta_{{ $i }}" id="ruta_{{ $i }}" type="hidden" value="{{ $data->name }}">
                    <input name="meta_{{ $i }}" id="meta_{{ $i }}" type="hidden" value="{{ $data->meta }}">
                    <input name="cobrado_{{ $i }}" id="cobrado_{{ $i }}" type="hidden" value="{{ $data->cobrado }}">
                    <input name="mora_{{ $i }}" id="mora_{{ $i }}" type="hidden" value="{{ $data->mora }}"> @endforeach
                    <?php } ?>
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
<script type="text/javascript" src="{{ asset('js/echarts.min.js') }}"></script>
<script type="text/javascript ">
    document.getElementById("generar_grafica").style.display = "none";
    document.getElementById("submitLoading").style.display= "inline";
    var datos = [];

    for (i = 1; i <= document.getElementById('graph_qty').value; i++)
    {
        console.log($("#ruta_"+i).val(),parseFloat($("#meta_"+i).val()),parseFloat($("#cobrado_"+i).val()),parseFloat($("#mora_"+i).val()));
        datos.push([$("#ruta_"+i).val(),parseFloat($("#meta_"+i).val()),parseFloat($("#cobrado_"+i).val()),parseFloat($("#mora_"+i).val())]);
    }

    var dom = document.getElementById("container");

            var myChart = echarts.init(dom);
            var app = {};
            option = null;
            option = {
                legend: {},
                toolbox: {
                     feature: {
                         saveAsImage : {show: true, title:'Exportar'}
                     }
                 },
                dataset: {
                    dimensions:['Ruta', 'Meta', 'Cobrado', 'Mora (+de 3 dias)'],
                    source:  datos
                        // [['Milk Tea', 83.1,  73.4, 55.1],
                        // ['Cheese Cocoa',  86.4, 65.2,82.5]                ]


                },
                xAxis: {type: 'category'},
                yAxis: {},
                // Declare several bar series, each will be mapped
                // to a column of dataset.source by default.
                series: [
                    {type: 'bar'},
                    {type: 'bar'},
                    {type: 'bar'}
                ]
            };

            if (option && typeof option === "object") {
                myChart.setOption(option, true);

                document.getElementById("generar_grafica").style.display = "inline";
                document.getElementById("submitLoading").style.display= "none";
            }

    function update_graph() {
        document.getElementById("generar_grafica").style.display = "none";
        document.getElementById("submitLoading").style.display= "inline";
        $.get('cobrochartajax?date1='+$("#date1").val()+'&date2='+$("#date2").val(), function (data) {

            var datos    = [];

            $.each(data, function (key, val) {
                // console.log(val.name+' - '+val.meta+' - '+val.cobrado+' - '+val.mora);
                    datos.push([val.name,parseFloat(val.meta),parseFloat(val.cobrado),parseFloat(val.mora)]);
            });
            var dom = document.getElementById("container");

            var myChart = echarts.init(dom);
            var app = {};
            option = null;
            option = {
                legend: {},
                tooltip: {},
                dataset: {
                    dimensions:['Ruta', 'Meta', 'Cobrado', 'Mora'],
                    source:  datos
                        // [['Milk Tea', 83.1,  73.4, 55.1],
                        // ['Cheese Cocoa',  86.4, 65.2,82.5]                ]


                },
                xAxis: {type: 'category'},
                yAxis: {},
                // Declare several bar series, each will be mapped
                // to a column of dataset.source by default.
                series: [
                    {type: 'bar'},
                    {type: 'bar'},
                    {type: 'bar'}
                ]
            };

            if (option && typeof option === "object") {
                myChart.setOption(option, true);

                document.getElementById("generar_grafica").style.display = "inline";
                document.getElementById("submitLoading").style.display= "none";
            }

        });
}

$(document).ready(function() {
    // update_graph();

});

    $("#generar_grafica").click( function() {
         update_graph();
    } );
    $("#date1").datetimepicker({ sideBySide: true, locale:'es',format:'DD/MM/YYYY'}).parent().css("position :relative");
    $("#date2").datetimepicker({ sideBySide: true, locale:'es',format:'DD/MM/YYYY'}).parent().css("position :relative");

</script>
































































@stop
