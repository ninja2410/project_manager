@extends('layouts/default')

@section('title',trans('report-sale.items_quantity_sales'))
@section('page_parent',trans('report-sale.reports'))


@section('header_styles')
{{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/print/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/print/css/buttons.bootstrap.min.css')}}"> --}}
<!-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/print/css/bootstrap.min.css')}}"> -->
<!--  calendario -->
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
            {{trans('report-sale.items_quantity_sales')}}
          </h3>
          <span class="pull-right clickable">
            <i class="glyphicon glyphicon-chevron-up"></i>
          </span>
        </div>
        <!-- <div class="panel-heading">{{trans('report-sale.items_quantity_sales')}}</div> -->
        {!! Form::open(array('url'=>'reports/items_quantity_sales')) !!}
        <div class="panel-body">
          <div class="col-md-14">
            <div class="btn-group btn-group-justified">
              <div class="col-md-3">
                <center><label for=""><b>Fecha inicial</b></label></center>
                <input type="text" name="date1"  id='admited_at'class="form-control" value="{{date('d/m/Y', strtotime($fecha1))}}">
              </div>
              <div class="col-md-3">
                <center><label for=""><b>Fecha final</b></label></center>
                <input type="text" name="date2"  id='admited_at2'class="form-control" value="{{date('d/m/Y', strtotime($fecha2))}}" >
              </div>
              <div class="col-md-2">
                <center><label for=""><b>Resultados</b></label></center>
                <input type="text" name="cantidadLimite" id="cantidadLimite"  onkeypress="return valida(event)" class="form-control"  style="text-align:center" value="{{$cantidadLimite}}" >
              </div>
              <div class="col-md-4">
                <br>
                {!! Form::submit(trans('Generar'), array('class' => 'btn btn-primary ')) !!}
              </div>
            </div>
          </div>
          <div class="panel-body">
            <div class="panel-body table-responsive">
              <table id="example" class="table table-striped table-bordered " cellspacing="0" width="100%">
                <thead>
                  <th></th>
                  <th style="width:5%;">No.</th>
                  <th style="width:30%;">Producto</th>
                  <th style="width:10%;"># Ventas/Salidas</th>
                  <th style="width:10%;">Cantidad</th>
                  <th style="width:15%;">Precio U.</th>
                  <th style="width:15%;">Total</th>
                </thead>
                <tbody>
                  @foreach($datosObtenidos as $i=>$value)
                  <tr>
                    <td></td>
                    <td>{{$i+1}}</td>
                    <td>{{$value->item_name}}</td>
                    <td style="text-align:center;">{{$value->tx}}</td>
                    <td style="text-align:center;">{{$value->cantidad}}</td>
                    <td style="text-align:right;">Q{{$value->low_price}}</td>
                    <td style="text-align:right;">Q{{$value->total}}</td>
                  </tr>
                  @endforeach
                </tbody>
                <tfoot>
                  <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="text-align:right">Total:</th>
                    <th ></th>
                  </tr>
                </tfoot>
              </table>
            </div>
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
<script >
  function valida(e){
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla==8){
      return true;
    }
    patron =/[0-9]/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
  }
</script>
@endsection
@section('footer_scripts')

{{-- <script type="text/javascript" src="{{ asset('/assets/vendors/print/js/jquery-1.12.4.js') }}"></script>
<script type="text/javascript" src="{{ asset('/assets/vendors/print/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/assets/vendors/print/js/dataTables.bootstrap.min.js') }}"></script> --}}
<script type="text/javascript" src="{{ asset('/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/metisMenu.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/jquery-slimscroll/jquery.slimscroll.js') }}"></script>

<script>
  $(document).ready(function() {
    var table = $('#example').DataTable({
      "footerCallback": function ( row, data, start, end, display ) {
        var api = this.api(), data;

        // Remove the formatting to get integer data for summation
        var intVal = function ( i ) {
          return typeof i === 'string' ?
          i.replace(/[\$,Q,]/g, '')*1 :
          typeof i === 'number' ?
          i : 0;
        };

        // Total over all pages
        total = api
        .column( 6 )
        .data()
        .reduce( function (a, b) {
          return intVal(a) + intVal(b);
        }, 0 );

        // Total over this page
        pageTotal = api
        .column( 6, { page: 'current'} )
        .data()
        .reduce( function (a, b) {
          return intVal(a) + intVal(b);
        }, 0 );

        // Update footer
        $( api.column( 6 ).footer() ).html(
        'Q  '+number_format(total,2)
        );
      },
      language: {
        "url":" {{ asset('assets/json/Spanish.json') }}"
      },
      "pageLength": 20,
      dom: 'Bfrtip',
      responsive: {
        details: {
          type: 'column'
        }
      },
      columnDefs: [ {
        className: 'control',
        orderable: false,
        targets:   0
      } ],
      buttons: [
      {
        extend: 'collection',
        text: 'Exportar/Imprimir',
        buttons: [
        {
          extend:'copy',
          text: 'Copiar',
          footer: true,
          title: document.title,
          exportOptions:{
            columns: ':visible'
          }
        },
        {
          extend:'excel',
          footer: true,
          title: document.title,
          exportOptions:{
            columns: ':visible'
          }
        },
        {
          extend:'pdf',
          footer: true,
          title: document.title,
          exportOptions:{
            columns: ':visible'
          }
        },
        {
          extend:'print',
          text: 'Imprimir',
          footer: true,
          title: document.title,
          exportOptions:{
            columns: ':visible'
          },
        }
        ]
      },
      ],
    });
    // table.buttons().container()
    // .appendTo('#example_wrapper .col-sm-6:eq(0)');
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
