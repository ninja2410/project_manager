@extends('layouts/default')

@section('title',trans('report-sale.sales_r_payment_type'))
@section('page_parent',trans('report-sale.reports'))


@section('header_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css') }}" />
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
            {{trans('report-sale.sales_r_payment_type')}}
          </h3>
          <span class="pull-right clickable">
            <i class="glyphicon glyphicon-chevron-up"></i>
          </span>
        </div>
        {!! Form::open(array('url'=>'`')) !!}
        <div class="panel-body">
          <div class="col-md-14">
            <div class="btn-group btn-group-justified">
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
          </div>
        </div>
        <div class="panel-body">
          {!! Form::close() !!}
          <div class="panel-body table-responsive">
            <table id="example" class="table table-striped table-bordered " cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th>Documento</th>
                  <th>Cantidad</th>
                  <th>Total Factura</th>
                </tr>
              </thead>
              <tbody>
                @foreach($dataPagos as $valor)
                <tr>
                  <td style="background-color: #085202;color:#ffffff;">Tipo de Pago: {{$valor->name}}</td>
                  <td></td>
                  <td></td>
                </tr>
                @foreach(ReportSalesDetailed::tipo_de_pago_reporte_venta($valor->id,$fecha1,$fecha2) as $saleDetail)
                <tr>
                  <td>{{$saleDetail->document}} {{$saleDetail->serie}}-{{$saleDetail->correlative}} / {{date('m/d/Y',strtotime($saleDetail->created_at))}}</td>
                  <td>{{DB::table('sale_items')
                    ->join('sales','sale_items.sale_id','=','sales.id')
                    ->join('series','sales.id_serie', '=', 'series.id')
                    ->join('documents','series.id_document','=','documents.id')
                    ->where('documents.sign','=','-')
                    ->where('sale_items.sale_id','=',$saleDetail->id)->sum('sale_items.quantity')}}</td>
                    <td style="text-align: right;">Q <?php echo number_format($saleDetail->total_cost,2); ?></td>
                  </tr>
                  @endforeach
                  <?php $totalTipoPago=DB::table('sales')
                  ->join('series','sales.id_serie','=','series.id' )
                  ->join('documents','series.id_document','=','documents.id')
                  ->where('documents.sign','=','-')
                  ->where('sales.id_pago','=',$valor->id)->whereBetween('sales.created_at',[$fecha1,$fecha2])
                  ->where('sales.cancel_bill','=',0)->sum('sales.total_cost'); ?>
                  <tr>
                    <td></td>
                    <td></td>
                    <td style="text-align: right;background-color: #9b94ed;"><b>Total {{$valor->name}}: </b>Q <?php echo number_format($totalTipoPago,2); ?></td>
                  </tr>
                  <tr>
                    <td style="background-color: #3c3639"></td>
                    <td style="background-color: #3c3639" ></td>
                    <td style="background-color: #3c3639"></td>
                  </tr>
                  @endforeach
                  <?php $totalFinalTipoPagos=DB::table('sales')
                  ->join('series','sales.id_serie','=','series.id' )
                  ->join('documents','series.id_document','=','documents.id')
                  ->where('documents.sign','=','-')
                  ->whereBetween('sales.created_at',[$fecha1,$fecha2])
                  ->where('sales.cancel_bill','=',0)->sum('sales.total_cost'); ?>
                  <tr>
                    <td ></td>
                    <td ></td>
                    <td style="background-color: #073870;color:#ffffff;text-align: right;"><h4>{{ 'TOTAL Q'.number_format($totalFinalTipoPagos,2) }}</h4></td>
                  </tr>
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
  
  {{-- <script type="text/javascript" src="{{ asset('/assets/vendors/print/js/jquery-1.12.4.js') }}"></script>
  <script type="text/javascript" src="{{ asset('/assets/vendors/print/js/jquery.dataTables.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('/assets/vendors/print/js/dataTables.bootstrap.min.js') }}"></script> --}}
  <script type="text/javascript" src="{{ asset('/js/bootstrap.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('/js/metisMenu.js') }}"></script>
  <script type="text/javascript" src="{{ asset('/js/jquery-slimscroll/jquery.slimscroll.js') }}"></script>
  
  <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/datatables.min.js') }}"></script>

  {{-- <script src="{{ asset('assets/vendors/print/js/jquery.dataTables.min.js')}}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendors/print/js/dataTables.bootstrap.min.js')}}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendors/print/js/dataTables.buttons.min.js')}}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendors/print/js/buttons.bootstrap.min.js')}}" type="text/javascript"></script> --}}
  {{-- <script src="{{ asset('assets/vendors/print/js/jszip.min.js')}}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendors/print/js/pdfmake.min.js')}}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendors/print/js/vfs_fonts.js')}}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendors/print/js/buttons.html5.min.js')}}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendors/print/js/buttons.print.min.js')}}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendors/print/js/buttons.colVis.min.js')}}" type="text/javascript"></script>
   --}}
  
  <script>
    $(document).ready(function() {
      var table = $('#example').DataTable({
        lengthChange: false,
        "ordering": false,
        // buttons: ['copy', 'excel', 'pdf', 'colvis','print']
        buttons: [
        {
          extend:'copy',
          text:'COPIAR',
          title:'{{ trans('dashboard.empresa') }} - Ventas por tipo de pago '+$('#documentsName option:selected').text() +' Del '+$('#admited_at').val()+' al: '+$('#admited_at2').val(),
        },      
        {
          extend:'excel',
          text:'EXCEL',
          title:'{{ trans('dashboard.empresa') }} - Ventas por tipo de pago '+$('#documentsName option:selected').text() +' Del '+$('#admited_at').val()+' al: '+$('#admited_at2').val(),
        },
        {
          extend:'pdf',
          text:'PDF',
          title:'{{ trans('dashboard.empresa') }} - Ventas por tipo de pago '+$('#documentsName option:selected').text() +' Del '+$('#admited_at').val()+' al: '+$('#admited_at2').val(),          
        },
        {
          extend:'print',
          text:'IMPRIMIR',
          title:'<center><h1 style="color:#1200D0">{{ trans('dashboard.empresa') }}</h1><h2>Reporte de tipos de pagos</h2><h3> Del '+$('#admited_at').val() +' al: '+$('#admited_at2').val()+' </h3></center>',          
        }
            ]
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
      