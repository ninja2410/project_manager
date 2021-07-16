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
  <div id="dvContents">
     <div class="row">
          <div class="col-md-12" style="text-align:center">
              {{--MasterPOS - MastertechGT Point of Sale--}}
              {{trans('dashboard.empresa')}}
          </div>
      </div>
      <div class="row">
          <div class="col-md-12">
          <strong>{{trans('Nombre del cliente')}}: &nbsp;</strong>   {{ $cliente->name}}<br />
          <b>Documento: &nbsp;</b> <label id="name_document">{{$series[0]->document. ' - '.$series[0]->name. ' '.$detalleVenta->correlative}}  </label><br />
          <b>{{trans('sale.grand_total')}}&nbsp;</b> Q <?php echo number_format($detalleVenta->total_cost,2); ?> <br />
          <b>Fecha:&nbsp; </b> {{date('d/m/Y',strtotime($detalleVenta->created_at))}} <br />

          </div>
      </div>
      <div class="row">
          <div class="col-md-12">
              <div class="table-responsive">
             <table class="table" >
               <td>
               <h4><b>Detalle de crédito</b></h4>
               <td><strong>Monto del credito: </strong>Q <?php echo number_format($detalleCredito->credit_total,2); ?></td>
               <td><strong>Total de Cuotas: </strong>{!!$detalleCredito->number_payments!!}</td>
             </td>
             <tr>
                 <td><strong>{{trans('Cuota No.')}}</strong></td>
                 <td style="text-align:center"><strong>{{trans('Fecha de pago')}}</strong></td>
                 <td style="text-align:right"><strong>{{trans('Monto del pago')}}</strong></td>
                 <td>&nbsp;</td>
             </tr>
             @foreach($detallePagosCredito as $i=> $value2)
             <tr>
                 <td>{{$i+1}}</td>
                 <td style="text-align:center">{{ date('d/m/Y', strtotime($value2->date_payments)) }}</td>
                 <td style="text-align:right">Q <?php echo number_format($value2->total_payments,2); ?></td>
                 <td>&nbsp;</td>
             </tr>
             @endforeach
              </table>
          </div>
          </div>
      </div>
    </div>
    <div class="row">
        <div class="col-md-12">
        </div>
    </div>
    <hr class="hidden-print"/>
    <div class="row">
        <div class="col-md-5">
            &nbsp;
        </div>
        <div class="col-md-2">
          <form>
            <input type="button" class="btn btn-danger  hidden-print " value="Átras (imprimir factura/salida)" name="volver atrás2" onclick="history.back()" />
          </form>
        </div>
        <div class="col-md-1">
            &nbsp;
        </div>
        <div class="col-md-2">
            <!-- <input type="button" id="btnPrint" class="btn btn-info pull-right hidden-print" value="{{trans('Imprimir')}}"> -->
            <button type="button" onclick="$('#dvContents').print();"  class="btn btn-info hidden-print">{{trans('Imprimir')}}</button>
        </div>
        <div class="col-md-2">
          <a href="{{ url("/sales?id=0") }}" type="button" class="btn btn-info hidden-print">{{trans('sale.new_sale')}}</a>
        </div>

    </div>
    <br>
</section>
@endsection
@section('footer_scripts')
<script>

  $.fn.extend({
    print: function() {
        var frameName = 'CagaoGT_Print';
        var doc = window.frames[frameName];
        if (!doc) {
            $('<iframe>').hide().attr('name', frameName).appendTo(document.body);
            doc = window.frames[frameName];
        }
        doc.document.body.innerHTML = this.html();
        doc.window.print();
        return this;
    }
});

  $("#btnPrint").click(function () {
    var name_document=$('#name_document').text();
          var contents = $("#dvContents").html();
          var frame1 = $('<iframe />');
          frame1[0].name = "frame1";
          frame1.css({ "position": "absolute", "top": "-1000000px" });
          $("body").append(frame1);
          var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
          frameDoc.document.open();
          //Create a new HTML document.
          frameDoc.document.write('<html><head><title>'+name_document+'</title>');
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

    });
</script>
@endsection
