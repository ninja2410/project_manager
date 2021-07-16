@extends('layouts/default')
@section('title','Ganancia por ruta')
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
                {{-- {!! Form::open(array('method'=>'get','route'=>array('chats.cobrochart'))) !!} --}}
                {!! Form::open(['url' => url('charts/earnings'),
                'method' => 'GET', 'class' => 'nav-form navbar-lef', 'role' => 'search','id'=>'cobro_chart']) !!}
                <div class="row">
                    <div class="col-md-3">
                    </div>
                    <div class="col-md-3">
                        <center><label for=""><b>Fecha inicial</b></label></center>
                        <input type="text" name="date1" id='date1' class="form-control" value="{{$fecha}}">
                    </div>
                    <div class="col-md-3">
                        <center><label for=""><b>Ruta</b></label></center>
                        <select class="form-control" name="route" id="route">
                          <option value="0">General</option>
                          @foreach ($rutas as $key => $value)
                            <option value="{{$value->id}}"
                              @if ($value->id==$ruta)
                                selected
                              @endif>
                              {{$value->name}}
                            </option>
                          @endforeach
                        </select>
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
                <canvas id="cobrochart"></canvas>
                @if ($ruta==0)
                  <div class="panel-body table-responsive">
                      <table class="table table-bordered " id="table1">
                          <thead>
                              <tr class="filters" style="font-size: 13px;">
                                  <th style="text-align: center;">No.</th>
                                  <th style="text-align: center;">Ruta</th>
                                  <th style="text-align: center;">Interes</th>
                                  <th style="text-align: center;">Mora</th>
                                  <th style="text-align: center;">Total</th>
                              </tr>
                          </thead>
                          <tbody>
                              @foreach ($data_graph as $i => $data)
                              <tr style="font-size: 12px">
                                  <td>{{$i+1}}</td>
                                  <td>{!! $data->Route !!}</td>
                                  <td style="text-align: right; ">@money( $data->Interes)</td>
                                  <td style="text-align: right; ">@money( $data->Mora)</td>
                                  <td style="text-align: right; "><b>@money( $data->Mora+$data->Interes)</b></td>
                              </tr>
                              @endforeach
                              <tr>
                                <td></td>
                                <td> <b>Total</b> </td>
                                <td style="text-align: right; "> <b>@money($totalInteres)</b> </td>
                                <td style="text-align: right; "> <b>@money($totalMora)</b> </td>
                                <td style="text-align: right; "> <b>@money($totalInteres+$totalMora)</b> </td>
                              </tr>
                          </tbody>
                      </table>
                  </div>
                @else
                  <div class="panel-body table-responsive">
                      <table class="table table-bordered " id="table1">
                          <thead>
                              <tr class="filters" style="font-size: 13px;">
                                  <th style="text-align: center;">Tarjeta #.</th>
                                  <th style="text-align: center;">Clinete</th>
                                  <th style="text-align: center;">Interes</th>
                                  <th style="text-align: center;">Mora</th>
                              </tr>
                          </thead>
                          <tbody>
                              @foreach ($data_graph as $i => $data)
                              <tr style="font-size: 12px">
                                  <td>{!! $data->number_card !!}</td>
                                  <td>{!! $data->name !!}</td>
                                  <td style="text-align: right; ">@money( $data->Interes)</td>
                                  <td style="text-align: right; ">@money( $data->Mora)</td>
                              </tr>
                              @endforeach
                              <tr>
                                <td></td>
                                <td> <b>Total</b> </td>
                                <td style="text-align: right; "> <b>@money($totalInteres)</b> </td>
                                <td style="text-align: right; "> <b>@money($totalMora)</b> </td>
                              </tr>
                          </tbody>
                      </table>
                  </div>
                @endif

                <!-- data grafica -->
                <div class="row">
                    <?php $i = 0;
                    if (isset($data_graph)) {
                        ?>
                    <input name="graph_qty" id="graph_qty" type="hidden" value="{{ count($data_graph) }}">
                    <input type="hidden" name="totalMora" id="totalMora" value="{{$totalMora}}">
                    <input type="hidden" name="totalMora" id="totalGanancia" value="{{$totalGanancia}}">
                    <input type="hidden" name="totalInteres" id="totalInteres" value="{{$totalInteres}}">
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
    // document.getElementById("generar_grafica").style.display = "none";
    // document.getElementById("submitLoading").style.display= "inline";
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
              toolbox: {
                   feature: {
                       saveAsImage : {show: true, title:'Exportar'}
                   }
               },
                title : {
                  @if ($ruta!=0)
                    text: 'Gráfica ganancias: {{$rutas->find($ruta)->name}}, total: @money($totalGanancia)',
                  @else
                    text: 'Gráfica ganancias: General, total: @money($totalGanancia)',
                  @endif
                    x:'center'
                },
                tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b}: {c} ({d}%)"
                },
                legend: {
                    orient: 'vertical',
                    x: 'left',
                    data:['Interés','Mora']
                },
                series: [
                    {
                        name:'Ganancia: @money($totalGanancia)',
                        type:'pie',
                        radius: ['50%', '70%'],
                        avoidLabelOverlap: false,
                        label: {
                            normal: {
                                show: false,
                                position: 'center'
                            },
                            emphasis: {
                                show: true,
                                textStyle: {
                                    fontSize: '30',
                                    fontWeight: 'bold'
                                }
                            }
                        },
                        labelLine: {
                            normal: {
                                show: false
                            }
                        },
                        data:[
                            {value:parseFloat($('#totalInteres').val()), name:'Interés'},
                            {value:parseFloat($('#totalMora').val()), name:'Mora'}
                        ]
                    }
                ]
            };

            if (option && typeof option === "object") {
                myChart.setOption(option, true);

                document.getElementById("generar_grafica").style.display = "inline";
                document.getElementById("submitLoading").style.display= "none";
            }

$(document).ready(function() {
  var table = $('#table1').DataTable({
    "language":{ "url": "{{ asset('assets/json/Spanish.json') }}" },
    dom: 'Bfrtip',
    lengthChange: false,
    "order": [],
    // buttons: ['copy', 'excel', 'pdf', 'colvis','print']
    buttons: [
      {
        extend:'copy',
        text:'COPIAR',
        @if ($ruta==0)
          title:'Renovaciones en General'
        @else
          title:'Renovaciones en: {{$rutas->find($ruta)->name}}'
        @endif

      },
      {
        extend:'csv',
        text:'CSV',
        @if ($ruta==0)
          title:'Renovaciones en General'
        @else
          title:'Renovaciones en: {{$rutas->find($ruta)->name}}'
        @endif
      },
      {
        extend:'excel',
        text:'EXCEL',
        @if ($ruta==0)
          title:'Renovaciones en General'
        @else
          title:'Renovaciones en: {{$rutas->find($ruta)->name}}'
        @endif
      },
      {
        extend:'pdf',
        text:'PDF',
        @if ($ruta==0)
          title:'Renovaciones en General'
        @else
          title:'Renovaciones en: {{$rutas->find($ruta)->name}}'
        @endif

      },
      {
        extend:'print',
        text:'IMPRIMIR',
        @if ($ruta==0)
          title:'Renovaciones en General'
        @else
          title:'Renovaciones en: {{$rutas->find($ruta)->name}}'
        @endif
      }
    ]
  });
  table.buttons().container()
    .appendTo('#example_wrapper .col-sm-6:eq(0)');
});
    $("#generar_grafica").click( function() {
         update_graph();
    } );
    $("#date1").datetimepicker({
       sideBySide: true, locale:'es',format:'MM/YYYY'
     }).parent().css("position :relative");

</script>
@stop
