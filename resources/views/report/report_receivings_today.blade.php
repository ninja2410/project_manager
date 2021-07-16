@extends('layouts/default')

@section('title',trans('report-receiving.report-day'))
@section('page_parent',trans('report-receiving.reports'))

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
            {{trans('report-receiving.report-day')}}
          </h3>
          <span class="pull-right clickable">
            <i class="glyphicon glyphicon-chevron-up"></i>
          </span>
        </div>
        {!! Form::open(array('url'=>'reports/reporte_compra')) !!}
        <div class="panel-body">
          <div class="col-md-14">
            <div class="btn-group btn-group-justified">
              <div class="col-md-4"><center><label for=""><b>Fecha inicial</b></label></center>
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
          </div>
        </div>
        <div class="panel-body">
          {!! Form::close() !!}
          <div class="panel-body table-responsive">
            <table id="example" class="table table-striped table-bordered " cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Documento</th>
                  <th>Fecha</th>
                  <th>Tipo pago</th>
                  <th>Monto</th>
                </tr>
              </thead>
              <tbody>
                @foreach($dataPagos as $i=>$valor)
                <tr>
                  <td>{{$i+1}}</td>
                  <td>{{$valor->document}} {{$valor->serie}}-{{$valor->correlative}}</td>
                  <td>{{$valor->created_at}}</td>
                  <td>{{$valor->name}}</td>
                  <td style="text-align:right">Q{{ number_format($valor->total_cost,2) }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
            <div class="panel-body">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script>
  function printInvoice() {
    window.print();
  }
</script>
@endsection
@section('footer_scripts')


<script type="text/javascript" src="{{ asset('/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/metisMenu.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/jquery-slimscroll/jquery.slimscroll.js') }}"></script>



<script>
  $(document).ready(function() {
    var table = $('#example').DataTable({
      language: {
        "url":" {{ asset('assets/json/Spanish.json') }}"
      },
      dom: 'Bfrtip',
      lengthChange: false,
      "ordering": false,
      rowGroup: {
        startRender: null,
        endRender: function ( rows, group ) {
          var Cuantos = rows.count();


          var MontoTotal = rows
          .data()
          .pluck(4)
          .reduce( function (a, b) {
            return a + b.replace(/[\$,Q,]/g, '')*1;
          }, 0) ;
          MontoTotal = $.fn.dataTable.render.number(',', '.', 2, 'Q').display( MontoTotal );

          return $('<tr/>')
          .append( '<td colspan="2">No. de operaciones '+group+': '+Cuantos.toFixed(0)+'</td>' )
          // .append( '<td>'+Cuantos.toFixed(0)+'</td>' )
          .append( '<td colspan="2" style="text-align:right;"> Monto total '+group+':</td>' )
          .append( '<td style="text-align:right;">'+MontoTotal+'</td>' );
        },
        dataSrc: 3
      },
      buttons: [
          {
            extend: 'collection',
            text: 'Exportar/Imprimir',
            buttons: [
            {
              extend:'copy',
              text: 'Copiar',
              title: document.title+' | Del '+$('#admited_at').val()+' al: '+$('#admited_at2').val(),
              exportOptions:{
                columns: ':visible'
              }
            },
            {
              extend:'excel',
              title: document.title+' | Del '+$('#admited_at').val()+' al: '+$('#admited_at2').val(),
              exportOptions:{
                columns: ':visible'
              }
            },
            {
              extend:'pdf',
              title: document.title+' | Del '+$('#admited_at').val()+' al: '+$('#admited_at2').val(),
              exportOptions:{
                columns: ':visible'
              }
            },
            {
              extend:'print',
              text: 'Imprimir',
              title: document.title+' | Del '+$('#admited_at').val()+' al: '+$('#admited_at2').val(),
              exportOptions:{
                columns: ':visible'
              },
            }
            ]
          },
          ],
    });
    table.buttons().container()
    .appendTo('#example_wrapper .col-sm-6:eq(0)');
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
