@extends('layouts/default')

@section('title','Factura '.$serie->name.'-'.$invoice->number)
@section('page_parent','Facturación de Créditos')

@section('header_styles')
<!--  Tablas -->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/colReorder.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/rowReorder.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/scroller.bootstrap.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/invoice.css') }}">
<!--  Tablas -->
<link href="{{ asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />
<!-- Validaciones -->
<link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css" /> 
<!-- Toast -->
<link href="{{ asset('assets/css/toast/toastr.min.css') }}" rel="stylesheet" type="text/css" />

@stop
@section('content')

<?php
$total_amount=0;
$total_interes=0;
$total_admin=0;
$total_mora=0;
$total_invoice=0;
$tmp_admin=100;
$tmp_inter=100;
if ($credit->ptc_interes>$invoice->percent_admin) {
  $tmp_admin=$credit->ptc_interes - $invoice->percent_admin;
  $tmp_inter=$invoice->percent_admin;
}
else{
  $tmp_admin=0;
  $tmp_inter=$credit->ptc_interes;
}
?>
  <section class="content">
    <div class="row">
      <div id="complete_receiving">
        <div class="row">
          <div class="col-md-12" style="text-align:center">

          </div>
        </div>
      <div class="row">
          <div class="col-md-12">

          </div>
      </div>
      <div class="row">
          <div class="col-md-12">
            <div id="dvContents">
              <label for="" class="my_class_name">
                <label id="etiqueta_cliente"><strong>Cliente: </strong></label> {{ $customer->name}}<br />
              </label>
              <br>
              <label for="" class="my_class_name_city">
                  <label id="etiqueta_direccion"><strong>Direccion: </strong></label>{{$customer->address}}<br />
              </label>
                <br>
                <label for="" class="my_class_name_vendedor">
                  <label id="etiqueta_vendedor"> <strong>Vendedor: </strong></label>{{$dataUsers->name}}<br />
              </label>
                <br>
              <label for="" class="my_class_hora">
                  <label id="etiqueta_hora"><strong>Hora: </strong></label>{{date_format($invoice->created_at, 'h:m:s')}}<br />
              </label>
              <br>
              <label for="" class="my_class_factura">
                  <label id="etiqueta_factura"><strong>Factura: </strong></label> <span id="name_document">{{$serie->name.'-'.$invoice->number}}</span> <br />
              </label>
            <div class="" style="text-align:right; padding-right:100px;">
              <label for="" class="my_class_date">
                  <label id="etiqueta_fecha"><strong>Fecha: </strong></label>{{date_format(date_create($invoice->date), 'd      m      Y')}}
              </label>
            </div>
            <br>
            <div class="" style="text-align:right; padding-right:100px;">
              <label for="" class="my_class_nit">
                {{$customer->nit_customer}}
              </label>
            </div>
              <div class="table-responsive">
              <table class="table" id="table">
                  <tr>
                    <!-- <td style="text-align:center"><b>{{trans('sale.qty')}}</b></td>
                      <td><b>{{trans('sale.item')}}</b></td>
                      <td style="text-align:right"><b>{{trans('sale.price')}}</b></td>
                      <td>&nbsp;</td>
                      <td style="text-align:right"><b>{{trans('sale.total')}}</b></td> -->
                  </tr>
                  @foreach($details as $value)
                  <tr>
                    <td style="text-align:center" class="my_class_quantity">1</td>
                      <td class="my_class_name_product">{{$value->user_description}}</td>
                      <!-- <td style="text-align: right;">Q <?php echo number_format($value->low_price,2); ?></td>
                      <td>&nbsp;</td> -->
                      <td  class="my_class_total_product" style="text-align: right;">Q <?php echo number_format($value->amount,2); ?></td>
                  </tr>
                  @endforeach
              </table>
              <div class="" style="text-align:right;padding-right:30px;">
                <label for="" class="my_class_total">
                  <?php echo number_format($invoice->amount,2);  ?><br />

                </label>
              </div>
              <br>
              <label for="" class="my_class_total_letter">
                <label for="" id="etiqueta_letras"><strong>Total en letras: </strong></label>  {{$precio_letras}}
              </label>
              </div>
            </div>
          </div>
      </div>
      <div class="row">

      </div>
      <hr class="hidden-print"/>
      <div class="row">
          <div class="col-md-4">
          </div>
          <div class="col-md-2">

          </div>
      </div>
      </div>
      <div class="row">
        <div class="col-lg-4"></div>
        <div class="col-lg-4">
          <button class="btn btn-primary" onclick="printInvoice()">
            Imprimir
        </button>
          <a href="{{route('pagares.show',$credit->id)}}"><button type="button" class="btn btn-danger" name="button">Regresar</button></a>
        </a>
        </div>
        <div class="col-lg-4"></div>
      </div>
    </div>
  </section>
  @endsection
  @section('footer_scripts')
  <script src="{{asset('assets/js/vuejs/vue.min.js')}} "></script>
  <script src="{{asset('assets/js/vuejs/cleave.min.js')}} "></script>
  <script src="{{asset('assets/js/vuejs/vue-cleave.min.js')}} "></script>
  <script src="{{asset('assets/js/vuejs/numeral.min.js')}} "></script>
  <!--  Axios -->
  <script src="{{asset('assets/js/vuejs/axios.min.js')}} "></script>
  <!--  Axios -->
  <!--  Tablas -->
  <script type="text/javascript " src="{{ asset('assets/js/datatables/jquery.dataTables.min.js')}} "></script>
  <script type="text/javascript " src="{{ asset('assets/js/datatables/dataTables.bootstrap.min.js')}} "></script>
  <!--  Tablas -->
  <!--  Calendario -->
  <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }} " type="text/javascript "></script>
  <script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }} " type="text/javascript "></script>
  <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }} " type="text/javascript "></script>
  <!--  Calendario -->
  <!-- Valiadaciones -->
  <script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
  <!-- Toast -->
  <script type="text/javascript " src="{{ asset('assets/js/toast/toastr.min.js')}} "></script>
  <script type="text/javascript " src="{{ asset('assets/js/pagare/app.js') }} "></script>
  <script>
  function printInvoice() {
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

          frameDoc.document.write('<link href="{{ asset('assets/css/pages/print_invoiceCredit.css')}}" rel="stylesheet" type="text/css" />');
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
  </script>
  @stop
