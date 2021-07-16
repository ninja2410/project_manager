@extends('layouts/default')

@section('title',trans('sale.print'))
@section('page_parent',"Ventas")
@section('header_styles')
<style>
  table td {
    border-top: none !important;
  }
</style>
@stop
@section('content')
<section class="content">
  <!-- <div class="container-fluid"> -->
    <div class="row">
      <div class="col-md-12" style="text-align:center">
        <b>
          {{trans('dashboard.empresa')}}
        </b>
      </div>
    </div>
    <input type="hidden" name="" value="{{$bandera}}"id="bandera">
    <div class="row">
      <div class="col-md-12">
        <!-- <b>{{trans('sale.customer')}}:</b> {{ $sales->customer->name}}<br /> -->
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div id="dvContents">
          <label for="" class="my_class_name" style="font-size:12px">
            <label id="etiqueta_cliente"><strong>Cliente: </strong></label>@if($imprimir_codigo_cliente==1) {{ $sales->customer->customer_code.' - '}} @endif {{ $sales->customer->name}}<br />
          </label>
          <br>
          <label for="" class="my_class_name_city" style="font-size:12px">
            <label id="etiqueta_direccion"><strong>Direccion: </strong></label>{{$sales->customer->address}}<br />
          </label>
          <br>
          <label for="" class="my_class_name_vendedor">
            <label id="etiqueta_vendedor"> <strong>Vendedor: </strong></label>{{$dataUsers->name}}<br />
          </label>
          <br>
          <label for="" class="my_class_hora">
            <label id="etiqueta_hora"><strong>Hora: </strong></label>{{date_format($sales->created_at, 'H:i:s')}}<br />
          </label>
          <br>
          <label for="" class="my_class_factura">
            <label id="etiqueta_factura"><strong>Factura No: </strong></label> <span id="name_document">@if($bandera==1)N/A @else {!!$documento[0]->documento!!} @endif</span> <br />
          </label>
          <!-- <b>{{trans('sale.employee')}}:</b> {{$sales->user->name}}<br /> -->
          <div class="" style="text-align:right; padding-right:100px;">
            <label for="" class="my_class_date" style="font-size:12px">
              <label id="etiqueta_fecha"><strong>Fecha: </strong></label> {{$sales->sale_date}}
            </label>
          </div>
          <br>
          <div class="" style="text-align:right; padding-right:100px;">
            <label for="" class="my_class_nit" style="font-size:12px">
              {{$sales->customer->nit_customer}}
            </label>
          </div>
          <div class="table-responsive">
            <table class="table table-condensed" id="table" style="font-size: 9px;">
                @foreach($saleItems as $value)
                <tr>
                  <td style="text-align:center; width:5%" class="my_class_quantity">{{$value->quantity}}</td>
                  <td style="text-align:left; width:5%; font-size:10px" class="my_class_codigo">{{$value->item->codigo}}</td>
                  <td class="my_class_name_product">{{$value->item->item_name}}</td>
                  <td  class="my_class_product" style="text-align: right;padding-right: 10px;">{{$value->selling_price}}</td>
                  <td  class="my_class_total_product" style="text-align: right;width:12%">{{$value->total_selling}}</td>
                  </tr>
                  @endforeach
                </table>
                <div class="" style="text-align:right;padding-right:30px;">
                  <label for="" class="my_class_total">
                    @money($sales->total_cost)<br />
                    
                  </label>
                </div>
                <br>
                <label for="" class="my_class_total_letter">
                  <label for="" id="etiqueta_letras"><strong>Total en letras: </strong></label>  {{$precio_letras}}
                </label>            
                @if($sales->transport!="")
                <br>
                <div class="row ocultar">
                  <div class="col-md-12">
                    <label for="" id="etiqueta_letras">{{trans('sale.transport')}}:</label>{{$sales->transport}}
                  </div>
                </div>
                @endif
                @if($sales->order!="")
                <br>
                <div class="sale_order">
                  <div class="col-md-12">
                    <label for="" id="">{{trans('sale.order')}}:</label>{{$sales->order}}
                  </div>
                </div>
                @endif
                @if($sales->printable_comment!="")
                <br>
                <div class="printable_comment">
                  <div class="col-md-12">
                    <label for="" id="etiqueta_letras">{{trans('sale.comment_printable')}}:</label>{{$sales->printable_comment}}
                  </div>
                </div>
                @endif
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="" class="my_class_payment">
                  <b id="etiqueta_letras">{{trans('sale.payment_type')}}:</b> {{DB::table('pagos')->where('id','=',$sales->id_pago)->value('name') }}
                  </label>
                </div>
              </div>
            </div>  {{-- Div imprmible --}}
          </div>
        </div>
        
        
        <hr class="hidden-print"/>
        <div class="row">
          <div class="col-md-2">
          </div>
          <div class="col-md-2" style="text-align:center">
            <input type="button"   id="btnPrint" value="Imprimir" class="btn btn-primary" />
          </div>
          <div class="col-md-2" style="text-align:center">
            @if((isset($imprir_ticket)) && ($imprir_ticket==1))
            <button type="button" onclick="print_ticket({{$sales->id}});" class="btn btn-success hidden-print">&nbsp;&nbsp;&nbsp;Ticket&nbsp;&nbsp;&nbsp;</button>
            @endif
          </div>
          
          <div class="col-md-2" style="text-align:center">
            @if(isset($_GET['return']))
            <a class="btn btn-danger" href="{{ URL::previous() }}">
              {{trans('button.back')}}
            </a>
            @else
            <a href="{{ url("/sales/create") }}" type="button" class="btn btn-info pull-right hidden-print">{{trans('sale.new_sale')}}</a>
            @endif
          </div>
          <div class="col-md-2" style="text-align:center">
            @if(isset($_GET['return']))
                <a class="btn btn-danger" href="{{ URL::previous() }}">
                {{trans('button.back')}}
                </a>
            @else
                <a href="{{ url("/sales") }}" type="button" class="btn btn-danger hidden-print">{{trans('Cancelar')}}</a>
            @endif
        </div>
          <div class="col-md-2">
          </div>
        </div>
        <!-- </div> -->
      </section>
      
      @endsection
      @section('footer_scripts')
      <script>
        $("#btnPrint").click(function () {
          var name_document=$('#name_document').text();
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
                function print_ticket(sale_id){
                  let newWindow = open(APP_URL+"/sales/ticket/"+sale_id, 'Ticket', 'width=450,height=550')
                  newWindow.focus();
                }            
              </script>
              @stop
              