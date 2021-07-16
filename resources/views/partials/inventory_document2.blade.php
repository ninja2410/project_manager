@extends('layouts/default')

@section('title','Impresion')
@section('page_parent',isset($module_name)?$module_name:'Impresion')

@section('header_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('/css/bootstrap.min.css') }}" id="bootstrap-css" />
<style type="text/css">
    .invoice-title h2, .invoice-title h3 {
    display: inline-block;
}

.table > tbody > tr > .no-line {
    border-top: none;
}

.table > thead > tr > .no-line {
    border-bottom: none;
}

.table > tbody > tr > .thick-line {
    border-top: 2px solid;
}
</style>
@stop
@section('content')
<section class="content">
<div class="container" id="document_to_print">
    <div class="row">
        <div class="col-xs-12">
            <div class="invoice-title">
                <h2 id="nombreDocumento">{{$document_name}}  # {{$document_number}}</h2>
                <input type="hidden" name="nombreEmpresa" id="nombreEmpresa" value="{{trans('dashboard.empresa')}}">
            </div>
            <hr>
            <div class="row">
                <div class="col-xs-6">
                    <address>
                    <strong>{{$persona}}:</strong><br>
                            {{$data_persona}}<br>
                    </address>
                </div>
                <div class="col-xs-6 text-right">
                    <address>
                    <strong>Usuario:</strong><br>
                        {{$docuser}}<br>
                    </address>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <address>
                        <strong>Metodo de Pago:</strong><br>
                        {{isset($forma_pago)?$forma_pago:''}}<br>
                    
                    </address>
                </div>
                <div class="col-xs-6 text-right">
                    <address>
                        <strong>Fecha:</strong><br>
                        {{date_format($docheader->created_at, 'd  /   m  /   Y')}}<br><br>
                    </address>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong>Detalle</strong></h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <td style="width: 10%" class="text-center"><strong><label id="etiqueta_hora">Cantidad</label></strong></td>
                                    <td style="width: 60%"><strong><label id="etiqueta_hora">Descripción</label></strong></td>
                                    <td  class="text-center"><strong><label id="etiqueta_hora">Precio</label></strong></td>                                 
                                    <td class="text-right"><strong><label id="etiqueta_hora">Total</label></strong></td>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- foreach ($order->lineItems as $line) or some such thing here -->
                                @foreach($docdetail as $i=> $value)
                                    <tr @if($i==0) class="class_detalle_factura" @endif>
                                      <td style="text-align:center" class="my_class_quantity">{{$value->quantity}}</td>
                                        <td class="my_class_name_product">{{$value->item->item_name}}</td>                                        
                                        <td  class="my_class_total_product" style="text-align: right;" id="etiqueta_hora">Q <?php echo number_format($value->cost_price,2); ?></td>
                                        <td  class="my_class_price_product" style="text-align: right;">Q <?php echo number_format($value->total_cost,2); ?></td>
                                    </tr>
                                @endforeach                                     
                                <tr>
                                    <td class="thick-line"></td>
                                    <td class="thick-line"></td>
                                    <td class="thick-line text-center"><strong>TOTAL</strong></td>
                                    <td class="thick-line text-right"><strong>Q{{number_format($docheader->total_cost,2)}}</strong></td>
                                </tr>
                                                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="row">
        <hr class="hidden-print"/>    
        <div class="row">
            <div class="col-md-4">
                &nbsp;
            </div>
            <div class="col-md-2">
                <button type="button"  id="btnPrint_doc" class="btn btn-primary pull-right hidden-print">{{trans('receiving.print')}}</button>
                
            </div>
            <div class="col-md-2">
                    <button class="btn btn-danger" onclick="window.history.back()">Regresar</button>         
                    <!-- <a href="{{ $cancel_url }}" type="button" class="btn btn-danger pull-right hidden-print">{{trans('button.cancel')}}</a> -->
            </div>
            <div class="col-md-4">
                &nbsp;
            </div>
        </div>
        </br></br>
    </div>

</section>
@endsection
@section('footer_scripts')
<!-- <script src="//code.jquery.com/jquery-1.11.1.min.js"></script> -->
<script type="text/javascript" src="{{ asset('/src/js/jquery.min.js') }}" ></script>
<script type="text/javascript" src="{{ asset('/js/bootstrap.min.js') }}" ></script>
<script type="text/javascript" src="{{ asset('/js/metisMenu.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/jquery-slimscroll/jquery.slimscroll.js') }}"></script>
<script type="text/javascript">
    

    $("#btnPrint_doc").click(function () {
    var nombreDocumento=$('#nombreEmpresa').val();
          // console.log(nombreDocumento);
          var contents = $("#document_to_print").html();
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

          frameDoc.document.write('<link href="{{ asset('/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />');
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