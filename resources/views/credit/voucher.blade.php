@extends('layouts/default')

@section('title',trans('Impresión '.$series[0]->document. ' - '.$series[0]->name))
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
   <div class="row">
        <div class="col-md-12" style="text-align:center">
            <b>
              {{trans('dashboard.empresa')}}
            </b>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
              <!-- <b>{{trans('sale.customer')}}:</b> {{ $sales->customer->name}}<br /> -->
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
          <div id="dvContents">
            <label for="" class="my_class_name">
              <label id="etiqueta_cliente"><strong>Cliente: </strong></label> {{ $sales->customer->name}}<br />
            </label>
            <br>
            <label for="" class="my_class_name_city">
                <label id="etiqueta_direccion"><strong>Direccion: </strong></label>{{$sales->customer->address}}<br />
            </label>
            <br>
            <label for="" class="my_class_name_vendedor">
                <label id="etiqueta_vendedor"><strong>Vendedor: </strong></label>{{$sales->user->name}}<br />
            </label>
            <br>
            <label for="" class="my_class_name_documento">
                <label id="etiqueta_documento"><strong>Documento: </strong>
                  
                  <label id="nombre_documento">
                    @if($_GET['id_credito'])
                      {{$series[0]->document.' - '.$series[0]->name.' '.$dataSalesCurrent->correlative}}
                    @else 
                      {{$series[0]->document.' - '.$series[0]->name.' '.$sales->correlative}}
                    @endif
                  </label>
                </label><br />
            </label>
            <br>
            <label for="" class="my_class_hora">
                <label id="etiqueta_hora"><strong>Hora: </strong></label>{{date_format($sales->created_at, 'H:m:s')}}<br />
            </label>
            <br>
            <label for="" class="my_class_factura">
            </label>
          <!-- <b>{{trans('sale.employee')}}:</b> {{$sales->user->name}}<br /> -->
          <div class="" style="text-align:right; padding-right:100px;">
            <label for="" class="my_class_date">
                <label id="etiqueta_fecha"><strong>Fecha: </strong></label>
                @if($_GET['id_credito'])
                  {{date('d      m      Y',strtotime($dateCurrent))}}
                @else 
                  {{date_format($sales->created_at, 'd      m      Y')}}
                @endif

            </label>
          </div>
          <br>
          <div class="" style="text-align:right; padding-right:100px;">
            <label for="" class="my_class_nit">
              {{$sales->customer->nit_customer}}
            </label>
          </div>
            <div class="table-responsive">
            <table class="table" id="table">
                <tr>
                  <td style="text-align:center"><b>{{trans('sale.qty')}}</b></td>
                    <td><b>{{trans('sale.item')}}</b></td>
                    <td style="text-align:right"><b>{{trans('sale.price')}}</b></td>
                    <td>&nbsp;</td>
                    <td style="text-align:right"><b>{{trans('sale.total')}}</b></td>
                </tr>
                @foreach($saleItems as $value)
                <tr>
                  <td style="text-align:center" class="my_class_quantity">{{$value->quantity}}</td>
                    <td class="my_class_name_product">{{$value->item->item_name}}</td>
                    <td style="text-align: right;">Q <?php echo number_format($value->low_price,2); ?></td>
                    <td>&nbsp;</td>
                    <td style="text-align: right;">Q <?php echo number_format($value->total_selling,2); ?></td>
                </tr>
                @endforeach
            </table>
            <div class="" style="text-align:right;padding-right:30px;">
              <label for="" class="my_class_total">
                <?php echo number_format($sale_credit_anterior->total_cost,2);  ?><br />
              </label>
            </div>
            <label for="" class="my_class_total_letter">
              <label for="" id="etiqueta_letras"><strong>Total en letras: </strong></label>  {{$precio_letras}}
            </label>
            </div>
          </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
          <b>{{trans('sale.payment_type')}}:</b> {{DB::table('pagos')->where('id','=',$sales->id_pago)->value('name') }}
        </div>
    </div>
    <hr class="hidden-print"/>
    <div class="row">
        <div class="col-md-4">
            &nbsp;
        </div>
        <div class="col-md-2">
        </div>
        <div class="col-md-2">
            <!-- <button type="button" onclick="printInvoice()" class="btn btn-info pull-right hidden-print">{{trans('sale.print')}}</button> -->
            <input type="button"   id="btnPrint" value="Imprimir" class="btn btn-danger" />
        </div>
        <div class="col-md-2">
            <a href="{{ url("/sales") }}" type="button" class="btn btn-info pull-right hidden-print">{{trans('sale.new_sale')}}</a>
        </div>
    </div>
    <br>
    <br>
</section>
@endsection
@section('footer_scripts')

<script src="{{ asset('assets/vendors/print/js/jquery-1.12.4.js')}}" type="text/javascript"></script>
<script>
$("#btnPrint").click(function () {
  var nombre_documento=$('#nombre_documento').text();
      if($("#bandera").val()==1)
      {
        var contents = $("#dvContents").html();
        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({ "position": "absolute", "top": "-1000000px" });
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html><head><title>'+nombre_documento+'</title>');
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
      else
      {
        var contents = $("#dvContents").html();
        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({ "position": "absolute", "top": "-1000000px" });
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html><head><title>'+nombre_documento+'</title>');
        frameDoc.document.write('</head><body>');
        //Append the external CSS file.

        frameDoc.document.write('<link href="{{ asset('assets/css/pages/print_page.css')}}" rel="stylesheet" type="text/css" />');
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
  $("#btnPrint_sale").click(function () {
          var contents = $("#dvContents").html();
          var frame1 = $('<iframe />');
          frame1[0].name = "frame1";
          frame1.css({ "position": "absolute", "top": "-1000000px" });
          $("body").append(frame1);
          var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
          frameDoc.document.open();
          //Create a new HTML document.
          frameDoc.document.write('<html><head><title>'+nombre_documento+'</title>');
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
@stop
