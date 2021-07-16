<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>{{$serie->document->name.' '.$serie->name.'-'.$sale->correlative}}</title>
        <meta http-equiv="cache-control" content="max-age=0" />
        <meta http-equiv="cache-control" content="no-cache" />
        <meta http-equiv="expires" content="0" />
        <meta http-equiv="pragma" content="no-cache" />
        {{-- <link href="{{ asset('assets/css/pages/receipt.css') }}" rel="stylesheet" type="text/css"/> --}}
        <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        
        <style type="text/css" media="all">
            body { color: #000; }
            #invoicex-POS { max-width: 480px; margin: 0 auto; padding: 1px; }
            .btn { border-radius: 0; margin-bottom: 10px; }
            .bootbox .modal-footer { border-top: 0; text-align: center; }
            h3 { margin: 5px 0; }
            .order_barcodes img { float: none !important; margin-top: 5px; }
            @media print {
                .no-print { display: none; }
                #invoicex-POS { max-width: 480px; width: 100%; min-width: 250px; margin: 0 auto; }
                .no-border { border: none !important; }
                .border-bottom { border-bottom: 1px solid #ddd !important; }
                table tfoot { display: table-row-group; }
            }
            #top .logo-ticket{
                float: left;
                height: 75px;
                width: 60px;
                /* background: url({{ asset('images/system/logo2.png') }}) no-repeat; */
                /* background-color:gray; */
                /* background-size: 60px 60px; */
            }
            .info-receipt{
                display: block;
                /* float:right; */
                margin-left: 0;
                /* font-size:10px; */
                
            }
            .cust-data {
                /* font-size:10px; */
            }
            .titulos {
                font-size: 1.1em;
                font-weight: 300;
                background: #e0e0e0;
            }
            .tableitem {
                /* font-size: 0.8em; */
            }
            .service{
                border-bottom: 1px solid #EEE;
                border-top: 0.4em solid #fff;
            }
            .total {
                
            }
            #legalcopy{
                margin-top: 5mm;
                text-align: center;
            }
            .contenedor {
                /* border: 3px solid blue; */
                padding: 10px 30px 10px;
                max-width: 640px;
            }
            .contenedor__image {
                    display: inline;
                    /* vertical-align: top; */
                    /* width: 46%; */
                    /* margin: 20px 30px 0 0; */
                }
            .contenedor__text {
                    display: inline-block;
                    /* width: 46%; */
                    @media (max-width: 620px) {
                        width: 100%;
                    }
                }
            @media print {
                .social_media_image {
                    
                    max-width: 30px;
                }
            }
            @media screen {
                .social_media_image {
                    
                    max-width: 30px;
                }
            }
}
        </style>
    </head>
    <body>
{{-- <section class="content">
    <div class="contenedor"> --}}
        {{-- <div id="invoice-POS" style="width: 70mm;"> --}}
    <div id="invoicex-POS" >
            
            {{-- <center id="top"> --}}
            <div class="text-center">
                <div class="logo-ticket">
                    <img class="logo_invoice" src="{{ asset('images/system/logo2.png') }}" alt="" style="width:40%">
                </div>
                <div class="info-receipt"> 
                    {{-- <h2 class="ache2"><strong>{{ $parameters->name_company }}</strong></h2> --}}
                    <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                    <strong>{{trans('parameter.address')}}:</strong> {{$parameters->address}}<br>
                    <span class="glyphicon glyphicon-earphone" aria-hidden="true"></span>
                    <strong>{{trans('parameter.phone')}}:</strong> {{$parameters->phone}}
                </div><!--End Info-->
            </div>
            {{-- </center><!--End InvoiceTop--> --}}
            <input type="hidden" id="name_document" name="name_document" value="{{$serie->document->name.' '.$serie->nameNo.'  #'.$sale->correlative}}">
            <br>
            <div id="mid">
                <div class="info-receipt">
                    <table style="width:100%">
                        <tr>
                            <td style="text-align:center">
                                <h6 >{{$serie->document->name.' '.$serie->name.' '}}<strong><br># {{$sale->correlative}}</strong></h6>
                            </td>
                            <td style="text-align:center">
                                <h6 >{{'Fecha: '}}<strong>{{$sale->sale_date}}</strong></h6>
                            </td>
                        </tr>
                    </table>                                        
                    {{-- <h2 class="ache2">Cliente</h2> --}}                    
                </div>
                <div class="info-receipt" >
                    <div class="cust-data">Nit : <strong>{{ $customer->nit_customer}}</strong></div>
                    <div class="cust-data">Cliente : <strong>{{ $customer->name}}</strong></div>
                    <div class="cust-data">Dirección   : <strong>{{ $customer->address}}</strong></div>                
                </div>
            </div><!--End Invoice Mid-->            
            <hr>
            <div id="bot">                
                <div id="table">
                    <table style="width:100%">
                        <thead>
                            <tr class="tabletitle">
                                <td style="width: 62%;"><strong>Producto</strong></td>
                                <td style="width: 13%;text-align:center;"><strong>Cant</strong></td>
                                <td style="width: 25%;text-align:right;"><strong>Sub Total</strong></td>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($details as $detail)
                        <tr class="service">
                            <td class="tableitem" >{{$detail->item->item_name}}</td>
                            <td class="tableitem" style="text-align:center;">{{$detail->quantity}}</td>
                            <td class="tableitem" style="text-align:right;padding-right:5px;">{{number_format($detail->total_selling,2)}}</td>
                        </tr>
                        @endforeach                                                                                                
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th><hr></th>
                                <th><hr></th>
                            </tr>
                            <tr class="titulos">
                                <th style="text-align:right"><strong>Total</strong></th>
                                <th colspan="2"  style="width: 25%;text-align:right;"><strong>@money($sale->total_cost)</strong></td>
                            </tr>
                            <tr>
                                <th colspan="3"><hr></th>
                            </tr>
                            <tr class="tabletitle" style="border-top:5px solid color:white">
                                <th colspan="3" class="tableitem">
                                    <strong style="font-size:11px">{{trans('quotation.leters')}}</strong> {{$precio_letras}}                                
                                </th>
                            </tr>
                            @if($sale->discount_amount>0)
                            <tr class="tabletitle" style="border-top:5px solid color:white">
                                <th colspan="3" class="tableitem" style="text-align:center">
                                    <strong style="font-size:12px">{{trans('quotation.savings').': '}}</strong> @money($sale->discount_amount)
                                </th>
                            </tr>
                            @endif
                            <tr>
                                <th colspan="3"></th>
                            </tr>
                        </tfoot>
                        
                    </table>
                </div><!--End Table-->
                <hr>
                <div>
                    <table style="width:100%">
                        <tr>
                            <td style="text-align:center">
                                <font style="text-decoration: underline">Detalle de pago</font>                                
                            </td>                              
                        </tr>
                        <tr>
                            <td style="text-align:center">
                                {{trim($documento[0]->forma_pago)}} <font >@if (isset($documento[0]->date_payments)) &nbsp;&nbsp;|&nbsp;&nbsp; Fecha Pago:&nbsp;{{date('d/m/Y', strtotime(substr($documento[0]->date_payments,0,10)))}} @endif
                            </td>            
                            <td style="text-align:center">
                                @if ($documento[0]->type==1)
                                Pagado : @money($documento[0]->paid)<br>
                                Vuelto: @money($documento[0]->change)
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align:center">                                
                                <br>Le atendio: {{$usuario->name.' '.$usuario->last_name}}
                                <hr>
                            </td>                              
                        </tr>
                    </table>
                </div>            
                
                <div id="legalcopy">
                    <p class="legal"><strong>Gracias por su compra!</strong><br></p>
                    @if($parameters->footer_text)
                        <p>{{$parameters->footer_text}}</p>
                    @endif
                    @if($parameters->website)
                        <font>{{$parameters->website}}</font><br>
                    @endif
                    @if($parameters->facebook)
                    <div class="contenedor">
                        <div class="contenedor__image">
                            <img src="{{ asset('images/system/facebook.png') }}" alt="" style="width:30px" >
                        </div>
                        <div class="contenedor__text">
                            <font >{{'/'.$parameters->facebook}}</font>
                        </div>
                    </div>
                    @endif
                    @if($parameters->instagram)
                    <div class="contenedor">
                        <div class="contenedor__image">
                            <img src="{{ asset('images/system/instagram.png') }}" style="width:30px" >
                        </div>
                        <div class="contenedor__text">
                            <font >{{'/'.$parameters->instagram}}</font>
                        </div>
                    </div>
                    @endif
                    @if($parameters->twitter)
                    <div class="contenedor">
                        <div class="contenedor__image">
                            <img src="{{ asset('images/system/twitter.png') }}" style="width:30px" >
                        </div>
                        <div class="contenedor__text">
                            <font >{{'/'.$parameters->twitter}}</font>
                        </div>
                    </div>
                    @endif
                    @if($parameters->whatsapp)
                    <div class="contenedor">
                        <div class="contenedor__image">
                            <img class="social_media_image" src="{{ asset('images/system/whatsapp.png') }}" style="width:30px">
                        </div>
                        <div class="contenedor__text">
                            <font >{{''.$parameters->whatsapp}}</font>
                        </div>
                    </div><br>
                    @endif
                </div>
                
            </div><!--End InvoiceBot-->
            {{--  --}}
            <div id="buttons" style="padding-top:10px; text-transform:uppercase;" class="no-print">
                <hr>
                <span class="pull-right col-xs-12">
                    <button onclick="window.print(); window.onafterprint = window.close();" class="btn btn-block btn-primary">Imprimir</button> </span>
                <div style="clear:both;"></div>
                <div class="col-xs-12" style="background:#F5F5F5; padding:10px;">
                    <p style="font-weight:bold;">
                        Por favor no olvide deshabilitar encabezado y pie de de página en las configuraciones de impresión del navegador.
                    </p>
                </div>
                <div style="clear:both;"></div>
            </div>
        </div><!--End Invoice-->
     
</body>
</html>