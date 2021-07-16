@extends('layouts/default')

@section('title',trans('credit.print_credit_detail'))
@section('page_parent',trans('credit.credits'))

@section('content')
{!! Html::script('js/angular.min.js', array('type' => 'text/javascript')) !!}
{!! Html::script('js/app.js', array('type' => 'text/javascript')) !!}
<style>
  table td {
    border-top: none !important;
  }
</style>
<section class="content">
  <div class="panel panel-primary">
    <div class="panel-heading">
      <h3 class="panel-title">
        <i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
        {{trans('credit.credit_detail')}}
      </h3>
      <span class="pull-right clickable">
        <i class="glyphicon glyphicon-chevron-up"></i>
      </span>
    </div>  
    <div id="print_credit_detail">
      <br>
        <div class="row">
          <div class="col-md-12" style="text-align:center">
            {{trans('dashboard.empresa')}}
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <strong>{{trans('Nombre del cliente')}}: </strong>   {{ $cliente->name}}<br />
            <strong>Documento: </strong>{{$documento}} <br/>
            <strong>Fecha: </strong> {{date('d/m/Y H:i',strtotime($detalleVenta->created_at))}}
          </div>
        </div>
        <div class="row">
          <div class="col-md-4"></div>
          <div class="col-md-4" style="text-align:center">
            <h4>Detalle de cuenta por cobrar</h4>
          </div>
          <div class="col-md-4"></div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12">
            <b>{{trans('Monto de cuenta por cobrar')}}:</b> Q {{ number_format($detalleCredito->credit_total,2)}}<br />
            <b>{{trans('Total de Cuotas')}}:</b> {{$detalleCredito->number_payments}}<br />
          </div>
        </div>
        <br><br>
        <div class="row ">
          <div class="col-md-12 " >
            <div class="table-responsive">
              <?php $totalPagado=0; ?>
              <table class="table" border="0">
                <tr>
                  <td><strong>{{trans('No.')}}</strong></td>
                  <td style="text-align: center;"><strong>{{trans('Fecha de pago')}}</strong></td>
                  <td  style="text-align: right;"><strong>{{trans('Total a pagar pago')}}</strong></td>
                  <td  style="text-align: right;"><strong>{{trans('Recargo')}}</strong></td>
                  <td>&nbsp;</td>
                  <td  style="text-align: right;"><strong>{{trans('Total pagado')}}</strong></td>
                  <td style="text-align: center;"><strong>{{trans('Fecha pago')}}</strong></td>
                </tr>
                @foreach($detallePagosCredito as $i=> $value2)
                <tr>
                  <td>{{$i+1}}</td>
                  <td style="text-align: center;">{{ date('d/m/Y', strtotime($value2->date_payments)) }}</td>
                  <td style="text-align: right;">Q <?php echo number_format($value2->total_payments,2);  ?></td>
                  <td style="text-align: right;">Q <?php echo number_format($value2->surcharge,2);  ?></td>
                  <td>&nbsp;</td>
                  <td  style="text-align: right;">Q <?php echo number_format(($value2->real_total_payment+$value2->surcharge),2);  ?></td>
                  @if($value2->payment_real_date ==0000-00-00)
                  <td style="text-align: center; color: red;text-align: center;"  ><b>Pendiente</b></td>
                  <!-- <td  style="text-align: right;">Q 00.00</td> -->
                  @else
                  <td style="text-align: center;" >{{ date('d/m/Y', strtotime($value2->date_payments)) }}</td>
                  <?php $totalPagado=$totalPagado+$value2->real_total_payment;?>
                  @endif
                </tr>
                @endforeach
                <tr>
                  <td colspan="7"></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td><strong>{{trans('Total Pagado')}}</strong></td>
                  <td style="text-align: center;"><strong>Q {{ number_format($totalPagado,2) }}</strong></td>
                  <td>&nbsp;</td>
                  <td  style="text-align: right;"><strong>{{trans('Por pagar')}}</strong></td>
                  <td style="text-align: center;"><strong>Q {{number_format($detalleVenta->total_cost-$totalPagado,2)}}</strong></td>
                  <td>&nbsp;</td>
                </tr>
              </table>
            </div>
          </div>
        </div>
        <br></br>
      </div>
      <div class="row">
        <hr class="hidden-print"/>
        
        <div class="row">
          <div class="col-md-4"></div>
          <div class="col-md-2">
            <a href="{{ URL::previous() }}" type="button" class="btn btn-danger pull-right hidden-print">{{trans('Átras ')}}</a>
          </div>
          <div class="col-md-2">
            <button type="button" onclick="$('#print_credit_detail').print();"  class="btn btn-info hidden-print">{{trans('Imprimir')}}</button>
          </div>
          <div class="col-md-4"></div>
        </div>
        <br>
      </div>
    </section>
    @endsection
    @section('footer_scripts')
    <script type="text/javascript" src="{{ asset('/assets/vendors/print/js/jquery-1.12.4.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/metisMenu.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/jquery-slimscroll/jquery.slimscroll.js') }}"></script>
    <script>
      function printInvoice() {
        window.print();
      }
      
      // jramirez
      $.fn.extend({
        print: function() {
          var contents = $("#print_credit_detail").html();
          var frame1 = $('<iframe />');
          frame1[0].name = "frame1";
          frame1.css({ "position": "absolute", "top": "-1000000px" });
          $("body").append(frame1);
          var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
          frameDoc.document.open();
          //Create a new HTML document.
          frameDoc.document.write('<html><head><title>Estado de cuenta</title>');
            frameDoc.document.write('</head><body>');
              //Append the external CSS file.
              
              frameDoc.document.write('<link href="{{ asset('assets/css/pages/print_page2.css')}}" rel="stylesheet" type="text/css" />');
              //Append the DIV contents.
              frameDoc.document.write(contents);
              frameDoc.document.write('</body></html>');
              frameDoc.document.close();
              setTimeout(function () {
                window.frames["frame1"].focus();
                window.frames["frame1"].print();
                frame1.remove();
              }, 500);
            }
          });
        </script>
        @endsection
        