@extends('layouts/default')
@section('title',trans('sale.show'))
@section('page_parent',trans('sale.sales'))
@section('header_styles')
    <style>
        :root {
            --color_primary: {{$parameters->primary}};
            --color_secundary: {{$parameters->second}};
        }
    </style>
    {{-- PRINTABLE --}}
    <link href="{{ asset('assets/css/pages/proforma.css') }}" rel="stylesheet" type="text/css"/>
@stop
@section('content')
    <section class="content">
        <div>
            <div class="row">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="shopping-cart-in" data-size="18" data-c="#fff" data-hc="#fff"
                               data-loop="true"></i>
                            {{trans('sale.show')}} {{$serie->document->name.' '.$serie->name.'-'.$sale->correlative}}
                        </h3>
                        <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
                    </div>
                    <div class="panel-body" id="dvContents" style="padding-top: 5px;">
                        <div >
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="row">
                                        <div class="col-xs-7 text-center logo_proforma">
                                            <table style="width:100%; font-size:13px;">
                                                <tr>
                                                <th style="text-align:left">
                                                    <img class="logo_invoice" src="{{ asset('images/system/logo2.png') }}" alt="" style="max-height:40px">
                                                </th>
                                                <th style="text-align:left">
                                                    <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                                                    <strong>{{trans('parameter.address')}}:</strong> {{$parameters->address}}<br>
                                                    <span class="glyphicon glyphicon-earphone" aria-hidden="true"></span>
                                                        <strong>{{trans('parameter.phone')}}:</strong> {{$parameters->phone}}
                                                </th>
                                                </tr>
                                            </table>

                                        </div>
                                        <div class="col-xs-1 text-center color_primary">
                                        </div>

                                        <div class="col-xs-4">
                                            <h6>{{$serie->document->name.' '.$serie->name.' '}}<strong>No. {{$sale->correlative}}</strong></h6>
                                            <h6>{{'Fecha: '}}<strong>{{$sale->sale_date}}</strong></h6>
                                        </div>
                                    </div>
                                    <input type="hidden" id="name_document" name="name_document"
                                           value="{{$serie->document->name.' '.$serie->nameNo.'  #'.$sale->correlative}}">

                                    {{-- <br> --}}
                                    <div class="row">
                                        <div class="col-xs-7">
                                            <div class="customer-info">
                                                <strong>{{trans('sale.customer')}}:</strong>@if($imprimir_codigo_cliente==1) {{ $customer->customer_code.' - '}} @endif  {{$customer->name}}
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="customer-info">
                                                <strong>{{trans('customer.nit')}}:</strong> {{$customer->nit_customer}}
                                            </div>
                                        </div>
                                        <div class="col-xs-2">
                                            <div class="customer-info" style="font-size:11px">
                                                Att: {{$dataUsers->name.' '.$dataUsers->last_name}}
                                            </div>
                                        </div>
                                        <br>
                                        <div>
                                            <div class="col-xs-7">
                                                <div class="customer-info-lower">
                                                    <strong>{{trans('customer.address')}}:</strong> {{$customer->address}}
                                                </div>
                                            </div>
                                            <div class="col-xs-5">
                                                <div class="customer-info-lower">
                                                    <strong>{{trans('sale.payment_type')}}:</strong> {{$documento[0]->forma_pago}} <font style="font-size:12px">@if (isset($documento[0]->date_payments)) <strong>&nbsp;&nbsp;| Fecha Pago:&nbsp;</strong>{{date('d/m/Y', strtotime(substr($documento[0]->date_payments,0,10)))}} @endif</font>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <br> --}}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-condensed table_details">
                                            <thead class="header_details">
                                            <tr>
                                                <td style="width: 10%"><strong>{{trans('sale.qty')}}</strong>
                                                </td>
                                                <td style="width: 10%" class="text-center"><strong>{{trans('unit_measure.unit')}}</strong>
                                                </td>
                                                <td style="width: 15%" class="text-left">
                                                    <strong>{{trans('sale.code')}}</strong>
                                                </td>
                                                <td style="width: 40%" class="text-left">
                                                    <strong>{{trans('quotation.description')}}</strong></td>
                                                <td style="width: 10%" class="text-right">
                                                    <strong>{{trans('sale.unit')}}</strong>
                                                </td>
                                                <td style="width: 15%" class="text-right">
                                                    <strong>{{trans('sale.total')}}</strong>
                                                </td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($details as $detail)
                                                <tr style="font-size:11px">
                                                    <td class="text-center">{{$detail->quantity}}</td>
                                                    <td class="text-center">{{$detail->unit->abbreviation}}</td>
                                                    <td class="text-left">{{$detail->item->upc_ean_isbn}}</td>
                                                    <td class="text-left">{{$detail->item->item_name}}</td>
                                                    <td class="text-right">
                                                        @money(($detail->total_selling/$detail->quantity))
                                                    </td>
                                                    <td class="text-right">@money(($detail->total_selling))</td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                {{-- <td class="no-line total-letras" colspan="3">&nbsp;<strong >{{trans('quotation.leters')}}:</strong> {{$precio_letras}}</td> --}}
                                                <td class="no-line" colspan="4"></td>
                                                {{-- <td class="no-line"></td> --}}
                                                <td class="text-right table_footer">
                                                    <strong>{{trans('sale.total')}}</strong></td>
                                                <td class="text-right table_footer">
                                                    <strong>@money($sale->total_cost)</strong></td>
                                            </tr>

                                                <tr class="tabletitle" style="border-top:5px solid color:white;font-size:11px">
                                                    <td class="no-line total-letras" colspan="3">&nbsp;<strong >{{trans('quotation.leters')}}:</strong> {{$precio_letras}}</td>
                                                    <th colspan="2" class="tableitem" style="text-align:center">@if($sale->discount_amount>0)
                                                        <strong style="font-size:12px">{{trans('quotation.savings').': '}}</strong> @money($sale->discount_amount)@endif
                                                    </th>
                                                </tr>

                                            </tbody>
                                            <tfoot>

                                            </tfoot>
                                        </table>
                                        {{-- <div class="col-xs-12">
                                            <strong>{{trans('quotation.leters')}}:</strong> {{$precio_letras}}
                                        </div> --}}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            {{-- <div class="col-md-1 col-6">&nbsp;</div> --}}
            {{-- <div class="col-md-2 col-6"> --}}
            <div class="col-md-2 col-xs-6">
                <button type="button" onclick="printReceipt();"
                        class="btn btn-info pull-right hidden-print">{{trans('quotation.print')}}</button>
            </div>
            <div class="col-md-2 col-xs-6">
                @if((isset($imprir_ticket)) && ($imprir_ticket==1))
                <button type="button" onclick="print_ticket({{$sale->id}});" class="btn btn-success pull-right hidden-print">Ticket</button>
                @endif
            </div>
            <div class="col-md-2 col-xs-6">
                <button type="button" onclick="print_invoice({{$sale->id}});" class="btn btn-primary pull-right hidden-print">Formato Factura</button>
            </div>
            <div class="col-md-2 col-xs-6">
                <a href="{{ url("/sales/create") }}" type="button" class="btn btn-info pull-right hidden-print">{{trans('sale.new_sale')}}</a>
            </div>

            <div class="col-md-2 col-xs-6">
                @if(isset($_GET['return']))
                    <a class="btn btn-danger" href="{{ URL::previous() }}">
                    {{trans('button.back')}}
                    </a>
                @else
                    <a href="{{ url("/sales") }}" type="button" class="btn btn-danger hidden-print">{{trans('Cancelar')}}</a>
                @endif
            </div>
            {{-- <div class="col-md-1 col-6">&nbsp;</div> --}}
        </div>
        <br>
    </section>
@endsection
@section('footer_scripts')
    <script>
        function printReceipt() {
            var name_document = $('#name_document').val();
            var contents = $("#dvContents").html();
            var frame1 = $('<iframe />');
            frame1[0].name = "frame1";
            frame1.css({"position": "absolute", "top": "-1000000px"});
            $("body").append(frame1);
            var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
            frameDoc.document.open();
            //Create a new HTML document.
            frameDoc.document.write('<html><head><title>' + name_document + '</title>');
            frameDoc.document.write('</head><body>');
            //Append the external CSS file.

            frameDoc.document.write('<style>:root { --color_primary: {{$parameters->primary}}; --color_secundary: {{$parameters->second}}; }</style>');
            frameDoc.document.write('<link href="{{ asset('assets/css/pages/proforma.css')}}" rel="stylesheet" type="text/css" />');
            frameDoc.document.write('<link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />');
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
        function print_ticket(sale_id){
            let newWindow = open(APP_URL+"/sales/ticket/"+sale_id, 'Ticket', 'width=450,height=550')
            newWindow.focus();
        }
        function print_invoice(sale_id){
            window.location.href =APP_URL+"/sales/invoice/"+sale_id;
        }
    </script>
@endsection
