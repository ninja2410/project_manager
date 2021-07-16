@extends('layouts/default')

@section('title',trans('sale.print'))
@section('page_parent',"Ventas")

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
<div class="container">
    <div id="dvContents">
    <div class="row">
        <div class="col-xs-12">
    		<div class="invoice-title">
    			<label for="" class="my_class_factura"><h2>{!!$docheader[0]->documento!!}  # {!!$docheader[0]->correlativo!!}</h2></label>
    		</div>
    		<hr>
    		<div class="row">
    			<div class="col-xs-6">
    				<address>
    				<label id="etiqueta_cliente" ><strong>CLIENTE: </strong></label>
    					<label for="" class="my_class_name">{{$docheader[0]->customer_name}}</label><br>
                        <label id="etiqueta_cliente" ><strong>NIT : </strong></label><label for="" class="my_class_nit">{{$docheader[0]->customer_nit}}</label>
                        <br>
                        <br>
    				</address>
    			</div>
                <div class="col-xs-3">
                    <address>
                    <label id="etiqueta_cliente" ><strong>DIRECCION: </strong></label>
                    <label for="" class="my_class_name_city">{{$docheader[0]->customer_address}}</label>
                        <br>                        
                    </address>
                </div>
    			<div class="col-xs-3">
    				<address>
        			     <label id="etiqueta_vendedor"><strong>USUARIO:</strong></label>
    					<label for="" class="my_class_name_vendedor">{{$docuser->name}}</label><br>    					
                        <label id="etiqueta_hora"><strong>FECHA:</strong></label>
                        <label for="" class="my_class_date">{{date_format($docheader[0]->created_at, 'd      m      Y')}}</label>
                        <br>
                        <br>
    				</address>
    			</div>
                
    		</div>
    	</div>
    </div>

    
    <div class="row">
    	<div class="col-md-12">
    		<div class="panel panel-default">
    			<div class="panel-heading" id="etiqueta_hora">
    				<label ><h3 class="panel-title"><strong>Detalle</strong></h3></label>
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
    						<tbody >
                                 @foreach($docdetail as $i=> $value)
                                    <tr @if($i==0) class="class_detalle_factura" @endif>
                                      <td style="text-align:center" class="my_class_quantity">{{$value->quantity}}</td>
                                        <td class="my_class_name_product">{{$value->item->item_name}}</td>                                        
                                        <td  class="my_class_total_product" style="text-align: right;" id="etiqueta_hora">Q <?php echo number_format($value->low_price,2); ?></td>
                                        <td  class="my_class_price_product" style="text-align: right;">Q <?php echo number_format($value->total_selling,2); ?></td>
                                    </tr>
                                @endforeach    							
    							<tr>
    								<td class="thick-line"></td>
    								<td class="thick-line"></td>
    								<td class="thick-line text-center" id="etiqueta_hora"><strong>Total</strong></td>
    								<td class="thick-line text-right"><label for="" class="my_class_total">Q{{$docheader[0]->total_cost}}</label></td>
    							</tr>
    							<tr>
    								<td class="no-line"></td>
    								<td class="no-line"></td>
    								<td class="no-line text-center"></td>
    								<td class="no-line text-right"></td>
    							</tr>
    							<tr>
    								<td class="no-line" colspan="4"><label id="etiqueta_hora"><strong>Total en letras: </strong></label><label for="" class="my_class_total_letter">  {{$precio_letras}}</label></td>
    							</tr>
                                <tr>
                                    <td class="no-line" colspan="4"><label id="etiqueta_hora"><strong>METODO DE PAGO: </strong>  {{$docheader[0]->pago}}</label></td>
                                </tr>
    						</tbody>
    					</table>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>    
    </div>
    <hr class="hidden-print"/>
    <div class="row">
        <div class="col-md-4">
        </div>
        <div class="col-md-2">
        <!-- <button type="button" onclick="printInvoice()" class="btn btn-info pull-right hidden-print">{{trans('sale.print')}}</button> -->
            <input type="button"   id="btnPrint" value="Imprimir factura" class="btn btn-primary" />
        </div>
        @if($bandera==0)
            <div class="col-md-2">
                <input type="button"   id="btnPrint_sale" value="Imprimir salida de bodega" class="btn btn-primary" />
            </div>
        @endif
        <div class="col-md-2">
           @if(isset($_GET['return']))
                &nbsp;<input type="button" class="btn btn-danger" value="Regresar" onclick="window.history.back();">
            @else
                <a href="{{ url("/sales") }}" type="button" class="btn btn-info pull-right hidden-print">{{trans('sale.new_sale')}}</a>
            @endif
        </div>
        <br>
        <br>
    </div>
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
    var name_document=$('#name_document').text();
    $("#btnPrint").click(function () {
    
    // console.log(name_document);
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
        frameDoc.document.write('<html><head><title>'+name_document+'</title>');
        frameDoc.document.write('</head><body>');
        //Append the external CSS file.

        frameDoc.document.write('<link href="{{ asset('assets/css/pages/print_page_new2.css')}}" rel="stylesheet" type="text/css" />');
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
          //esta parte es ddonde se imprime la factura que se va a llevar el cliente
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

        frameDoc.document.write('<link href="{{ asset('assets/css/pages/print_page_new.css')}}" rel="stylesheet" type="text/css" />');
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