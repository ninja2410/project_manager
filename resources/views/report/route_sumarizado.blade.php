@extends('layouts/default')

@section('title',trans('report-route.route_report_sale'))
@section('page_parent',trans('report-sale.reports'))


@section('header_styles')

<link href="{{ asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />
@stop

@section('content')
<section class="content">
  <div class="row">
    <div class="col-md-12 ">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">
            <i class="livicon" data-name="linechart" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
            {{trans('report-route.route_report_sale')}}
          </h3>
          <span class="pull-right clickable">
            <i class="glyphicon glyphicon-chevron-up"></i>
          </span>
        </div>
        <div class="panel-body">
          {!! Form::open(array('url'=>'/reports/route/profit')) !!}
          <div class="row">
            <div class="col-md-4">
              <center><label for=""><b>Fecha inicial</b></label></center>
              <input type="text" name="date1"  id='admited_at'class="form-control" value="{{date('d/m/Y', strtotime($fecha1))}}">
            </div>
            <div class="col-md-4">
              <center><label for=""><b>Fecha final</b></label></center>
              <input type="text" name="date2"  id='admited_at2'class="form-control" value="{{date('d/m/Y', strtotime($fecha2))}}" >
            </div>
            <div class="col-md-4">
              <br>
              {!! Form::submit(trans('Generar'), array('class' => 'btn btn-primary ')) !!}
            </div>
          </div>
          {!! Form::close() !!}
          <hr>
          <div class="row">
            <div class="col-md-12">
              <table class="table table-striped table-bordered" id="table1">
                <thead>
                  <th style="width: 5%;">No.</th>
                  <th style="text-align: center">Ruta</th>
                  <th style="text-align: center">Encargados</th>
                  <th style="text-align: center">Ventas</th>
                  <th style="text-align: center">Gastos</th>
                  <th style="text-align: center">Costos</th>
                  <th style="text-align: center">Utilidad</th>
                </thead>
                <tbody>
                  @foreach ($ruta as $key=>$value)
                  <tr>
                    <td style="text-align: center;">{{$key+1}}</td>
                    <td>{{$value->name}}</td>
                    <td>
                      @foreach ($value->users as $user)
                        <span class="badge badge-info">{{$user->name}}</span>
                      @endforeach
                    </td>
                    @if($data->contains($value->id))
                      <td class="text-right">@money($data->find($value->id)['monto'])</td>
                    @else
                      <td class="text-right">@money(0)</td>
                    @endif
                    @if($dataGasto->contains($value->id))
                      <td class="text-right">@money($dataGasto->find($value->id)['gasto'])</td>
                    @else
                      <td class="text-right">@money(0)</td>
                    @endif
                    @if($data->contains($value->id))
                      <td class="text-right">@money($data->find($value->id)['costo'])</td>
                    @else
                      <td class="text-right">@money(0)</td>
                    @endif
                    <td class="text-right"> @money($data->find($value->id)['monto']-$dataGasto->find($value->id)['gasto']-$data->find($value->id)['costo'])</td>
                  </tr>
                  @endforeach
                </tbody>
                <tfoot>
                <th></th>
                <th></th>
                  <th class="text-right">Totales:</th>
                  <th class="text-right">@money($totalMonto)</th>
                  <th class="text-right">@money($totalGasto)</th>
                  <th class="text-right">@money($totalCosto)</th>
                  <th class="text-right">@money($totalUtilidad)</th>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
@section('footer_scripts')
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/datatables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/general-function/currency_format.js') }}" ></script>

<script type="text/javascript">
  $(document).ready(function(){
    $('#table1').DataTable({
      "language": {
        "url": "{{ asset('assets/json/Spanish.json') }}"
      },
      xscrollable:true,
      dom: 'Bfrtip',
      buttons: [
          {
            extend: 'collection',
            text: 'Exportar/Imprimir',
            buttons: [
            {
              extend:'copy',
              text: 'Copiar',
              footer: true,
              title: document.title+' Del '+$('#admited_at').val()+' al: '+$('#admited_at2').val(),
              exportOptions:{
                columns: 'th:not(:last-child)'
              }
            },
            {
              extend:'excel',
              footer: true,
              title: document.title+' Del '+$('#admited_at').val()+' al: '+$('#admited_at2').val(),
              exportOptions:{
                columns: 'th:not(:last-child)'
              }
            },
            {
              extend:'pdf',
              footer: true,
              title: document.title+' Del '+$('#admited_at').val()+' al: '+$('#admited_at2').val(),
              exportOptions:{
                columns: 'th:not(:last-child)'
              }
            },
            {
              extend:'print',
              text: 'Imprimir',
              footer: true,
              title: document.title+' Del '+$('#admited_at').val()+' al: '+$('#admited_at2').val(),
              exportOptions:{
                columns: 'th:not(:last-child)'
              },
            }
            ]
          },
          ],
      // order:[],
    }) ;
  });


  </script>
  <!--Canlendario  -->
  <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
  <script>
    $("#admited_at").datetimepicker({ sideBySide: true, locale:'es',format:'DD/MM/YYYY'}).parent().css("position :relative");
  </script>
  <script>
    $("#admited_at2").datetimepicker({ sideBySide: true, locale:'es',format:'DD/MM/YYYY'}).parent().css("position :relative");
  </script>
  @stop
