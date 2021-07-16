@extends('layouts/default')

@section('title',trans('Impresión '.$series[0]->document. ' - '.$series[0]->name. ' '.$venta->correlative))
@section('page_parent',trans('credit.credits'))

@section('content')
<style>
table td {
  border-top: none !important;
}
</style>
<section class="content">
    <div class="row">
      <div class="col-md-12" style="text-align:center">
        {{trans('dashboard.empresa')}}
      </div>
    </div>
    <div class="row" id="dvContents">

      <div class="col-lg-12">
          <label for="" class="my_class_name">
            <label id="etiqueta_cliente"><strong>Cliente: </strong></label> {{$venta->customer->name}}<br />
          </label>
          <br>
          <label for="" class="my_class_name_city">
            <label id="etiqueta_direccion"><strong>Direccion: </strong></label>{{$venta->customer->address}}<br />
          </label>
          <br>
          <label for="" class="my_class_name_vendedor">
            <label id="etiqueta_vendedor"><strong>Vendedor: </strong></label>{{$dataUser->name}}<br />
          </label>
          <br>
          <label for="" class="my_class_hora">
            <label id="etiqueta_hora"><strong>Hora: </strong></label>{{date_format($venta->created_at, 'H:m:s')}}<br />
          </label>
          <br>
          <label for="" class="my_class_factura">
            <label id="etiqueta_factura"><strong>Documento:&nbsp; </strong></label ><label id="nombreDocumento">{{$series[0]->document. ' - '.$series[0]->name. ' '.$venta->correlative}}  </label><br />
          </label>
          <div class="" style="text-align:right; padding-right:100px;">
            <label for="" class="my_class_date">
              <label id="etiqueta_fecha"><strong>Fecha: </strong></label>{{date_format($venta->created_at, 'd-m-Y')}}
            </label>
          </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="table-responsive">
           <table class="table" border="0" id="table">
              <tr>
              </tr>
              @foreach($detalleVenta as $value)
              <tr>
                <td style="text-align:center">{{$value->quantity}}</td>
                <td >{{$value->item->item_name}}</td>
                <!-- <td style="text-align: right;">Q <?php echo number_format($value->low_price,2);  ?></td> -->
                <td>&nbsp;</td>
                <td style="text-align: right;">Q <?php echo number_format($value->total_selling,2); ?></td>
              </tr>
              @endforeach
              <br>
            </table>
          </div>
        </div>
      </div>
      <div class="" style="text-align:right;padding-right:30px;">
        <label for="" class="my_class_total">
          <?php echo number_format($venta->total_cost,2);  ?><br />

        </label>
      </div>
      <br>
      <div class="col-lg-12">
        <label for="" class="my_class_total_letter">
          <label for="" id="etiqueta_letras"><strong>Total en letras: </strong></label>  {{$precio_letras}}
        </label>
      </div>
      <div class="col-md-12">
        <b>{{trans('sale.payment_type')}}:</b> {{DB::table('pagos')->where('id','=',$venta->id_pago)->value('name') }}
      </div>
    </div><!--  fin del div que se imprime-->


  <div class="row">
    <div class="col-md-12">
    </div>
  </div>
  <hr class="hidden-print"/>
  <div class="row">
    <div class="col-md-6">
      &nbsp;
    </div>
    <div class="col-md-2">
        <!-- <button type="button"  id="btnPrint_sale" class="btn btn-danger pull-right hidden-print">{{trans('Imprimir salida de bodega')}}</button> -->
        <button type="button" onclick="$('#dvContents').print();"  class="btn btn-primary hidden-print">{{trans('Imprimir '.$series[0]->document. ' - '.$series[0]->name. ' '.$venta->correlative)}}</button>
    </div>
    <div class="col-md-2">
      <a class="btn btn-primary pull-right hidden-print" href="{{ URL::to('credit/complete/' . $detalleCredito[0]->id) }}">{{trans('Imprimir crédito')}}</a>

    </div>
    <div class="col-md-2">
      <a href="{{ url("/sales?id=0&cambio=no") }}" type="button" class="btn btn-info pull-right hidden-print">{{trans('sale.new_sale')}}</a>
    </div>
  </div>
  <br>
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

  $("#btnPrint_sale").click(function () {
    var nombreDocumento=$('#nombreDocumento').text();
          // console.log(nombreDocumento);
          var contents = $("#dvContents").html();
          var frame1 = $('<iframe />');
          frame1[0].name = "frame1";
          frame1.css({ "position": "absolute", "top": "-1000000px" });
          $("body").append(frame1);
          var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
          frameDoc.document.open();
          //Create a new HTML document.
          frameDoc.document.write('<html><head><title>'+nombreDocumento+'</title>');
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
