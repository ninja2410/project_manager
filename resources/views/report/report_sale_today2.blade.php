@extends('app')
@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/colReorder.bootstrap.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/rowReorder.bootstrap.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/scroller.bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/pages/tables.css') }}" />
    <link href="{{ asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />
@stop

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">{{trans('report-sale.report_day')}}</div>
        {!! Form::open(array('url'=>'reports/reporte_venta')) !!}
				<div class="panel-body">
          <div class="col-md-12">
            <div class="btn-group btn-group-justified">
              <div class="col-md-4">
              <input type="text" name="date1"  id='admited_at'class="form-control" value="{{date('d/m/Y', strtotime($fecha1))}}">
              <center><label for=""><b>Fecha inicial</b></label></center>
            </div>
              <div class="col-md-4">
              <input type="text" name="date2"  id='admited_at2'class="form-control" value="{{date('d/m/Y', strtotime($fecha2))}}" >
              <center><label for=""><b>Fecha final</b></label></center>
            </div>
              <div class="col-md-4">
              {!! Form::submit(trans('Generar'), array('class' => 'btn btn-primary ')) !!}
            </div>
            </div>
          </div>
        </div>
          <div class="panel-body">
            {!! Form::close() !!}
          <!-- <div class="panel-body table-responsive">
            <table class="table table-striped table-bordered display" id="table1">
              <thead>
                  <tr>
                      <td>Reporte de ventas</td>
                  </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                  </td>
                </tr>
              </tbody>
            </table> -->
            @foreach($dataPagos as $valor)
            <h4><b>Tipo de pago: </b>{{$valor->name}}</h4>
            <table class="table">
              <thead>
                <td><b>Documento</b></td>
                <td><b>Cantidad</b></td>
                <td style="text-align: right;"><b>Total factura</b></td>
              </thead>
              @foreach(ReportSalesDetailed::tipo_de_pago_reporte_venta($valor->id,$fecha1,$fecha2) as $saleDetail)
              <tbody>
              <tr>
                <td>{{$saleDetail->document}} {{$saleDetail->serie}}-{{$saleDetail->correlative}}</td>
                <td>{{DB::table('sale_items')->where('sale_id','=',$saleDetail->id)->sum('quantity')}}</td>
                <td style="text-align: right;">Q <?php echo number_format($saleDetail->total_cost,2); ?></td>
              </tr>
              @endforeach
              <?php $totalTipoCredito=DB::table('sales')->where('id_pago','=',$valor->id)->sum('total_cost'); ?>
              <td></td>
              <td></td>
              <?php $totalTipoPago=DB::table('sales')->where('id_pago','=',$valor->id)->whereBetween('created_at',[$fecha1,$fecha2])->sum('total_cost'); ?>
              <td style="text-align: right;color:red;"><b>Total {{$valor->name}}: </b>Q <?php echo number_format($totalTipoPago,2); ?></td>
            </tbody>
            </table>
            @endforeach
            <?php $totalFinalTipoPagos=DB::table('sales')->whereBetween('created_at',[$fecha1,$fecha2])->sum('total_cost'); ?>
            <h2>Total: Q <?php echo number_format($totalFinalTipoPagos,2); ?></h2>
          </div>
          <br>
          <div style="text-align: right;">
              <button type="button" onclick="printInvoice()" class="btn btn-info pull-right hidden-print">{{trans('sale.print')}}</button>
          </div>
          <!-- <div class="panel-body table-responsive">
              <table class="table table-striped table-bordered display" id="table1">
                  <thead>
                      <tr>
                          <td>{{trans('item.item_id')}}</td>
                      </tr>
                  </thead>
                  <tbody>
                  </tbody>
              </table>
          </div> -->

				</div>
			</div>
		</div>
	</div>
</div>
<script>
function printInvoice() {
    window.print();
}
</script>
@endsection
@section('footer_scripts')
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>

    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.buttons.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.responsive.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/buttons.colVis.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/buttons.html5.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/buttons.print.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/buttons.bootstrap.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/buttons.print.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/pdfmake.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/vfs_fonts.js') }}" ></script>

<script type="text/javascript">
    $(document).ready(function(){
    $('#table1').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
    }) ;
});
</script>
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
