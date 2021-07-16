@extends('layouts/default')
@section('title',trans('credit_notes.show'))
@section('page_parent',trans('credit_notes.credit_note'))
@section('header_styles')
    <style>
        :root {
            --color_primary: {{$parameters->primary}};
            --color_secundary: {{$parameters->second}};
        }
    </style>
    {{-- PRINTABLE --}}
    <link href="{{ asset('assets/css/pages/quotation.css') }}" rel="stylesheet" type="text/css"/>
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
                            {{trans('credit_notes.show')}}
                        </h3>
                        <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
                    </div>
                    <div class="panel-body body_c" id="dvContents">
                        <header class="clearfix_c cheader">
                            <div class="container_c">
                                <div class="col-xs-3 text-center">
                                    <img class="logo custlogo" src="{{ asset('images/system/logo2.png') }}" style="max-width:250px; height:70px;">
                                </div>
                                <div class="company-info col-xs-5">
                                    {{-- <h2 class="title">{{$parameters->name_company}}</h2> --}}
                                    <span class="line"></span><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                                    <span><strong>{{trans('parameter.address')}}:</strong> {{$parameters->address}}</span><br>
                                    {{-- <span>{{$parameters->address}}</span> --}}
                                    <span class="line"></span><span class="glyphicon glyphicon-earphone" aria-hidden="true"></span>
                                    <span><strong>{{trans('parameter.phone')}}:</strong>{{$parameters->phone}}</span><br>
                                    <span class="line"></span><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
                                    <span><strong>{{$parameters->email}}:</strong>{{$parameters->email}}</span>
                                </div>
                                <div class="col-xs-4" style="font-size: 12px;">

                                </div>
                            </div>

                        </header>

                        <section>
                            <div class="details clearfix_c">
                                <div class="client left">
                                    <p>{{trans('customer.customer')}}:</p>
                                    <p class="name">{{$credit_note->customer->name}}</p>
                                    <p>
                                        <strong>{{trans('customer.nit')}}</strong>: {{$credit_note->customer->nit_customer}}
                                    </p>
                                    <p>
                                        <strong>{{trans('customer.address')}}</strong>: {{$credit_note->customer->address}}
                                    </p>
                                </div>
                                <div class="data right">
                                    <div class="title">{{$credit_note->serie->document->name.' '.$credit_note->serie->name}}
                                        -{{$credit_note->correlative}}</div>
                                    <div class="name">
                                        <p style="font-size: 19px">{{trans('credit_notes.type'.$credit_note->type)}}</p>
                                    </div>
                                    <div class="date">
                                        {{trans('quotation.date')}}: {{date('d/m/Y', strtotime($credit_note->date))}}<br>
                                    </div>
                                </div>
                                <input type="hidden" id="name_document" name="name_document" value="{{$credit_note->serie->document->name.' '.$credit_note->serie->name.'  #'.$credit_note->correlative}}">
                            </div>
                            <div class="row">
                                <div class="table-responsive">
                                {{-- <div class="table-wrapper"> --}}
                                    <table>
                                        <tbody class="head">
                                        <tr>
                                            <th class="no"></th>
                                            <th class="desc">
                                                <div>{{trans('quotation.description')}}</div>
                                            </th>
                                            <th class="qty">
                                                <div>{{trans('quotation.quantity')}}</div>
                                            </th>
                                            <th class="unit">
                                                <div>{{trans('quotation.unit_price')}}</div>
                                            </th>
                                            <th class="total">
                                                <div>{{trans('quotation.stotal')}}</div>
                                            </th>
                                        </tr>
                                        </tbody>
                                        <tbody class="body">
                                        @foreach ($details as $key => $detail)
                                            <tr>
                                                <td class="no">{{$key+1}}</td>
                                                @if(isset($detail->item_id))
                                                    <td class="desc">{{$detail->item->item_name}}</td>
                                                @else
                                                    <td class="desc">{{$detail->manual_detail}}</td>
                                                @endif
                                                <td class="qty">{{$detail->quantity}}</td>
                                                <td class="unit">@money($detail->price)</td>
                                                <td class="total" style="text-align:right;padding-right: 2em;">@money($detail->price*$detail->quantity)</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="no-break">
                                    <table class="grand-total">
                                        <tbody>
                                        <tr>
                                            <td class="no"></td>
                                            <td class="desc" style="color: grey; text-align: left;"><strong>{{trans('quotation.leters')}} </strong>{{$precio_letras}}</td>
                                            <td class="unit" style="font-size: 18px">{{trans('quotation.total')}}:</td>
                                            <td class="total" colspan="2" style="font-size: 16px">@money($credit_note->amount)</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </section>

                        <footer>
                            <div class="container">
                                {{--                                <div class="thanks">Gracias por su compra!</div>--}}
                                <div class="notice">
                                    <div class="row">
                                        <div class="col-lg-7">{{trans('quotation.comment')}}:{{$credit_note->comment}}</div>
                                        @if (isset($credit_note->sale_id))
                                            {{-- <div class="col-lg-7"></div> --}}
                                            <div class="col-lg-5">
                                                <strong>{{trans('quotation.sale_details')}}: </strong> <a style="text-decoration:underline !important" data-toggle="tooltip" target="_blank" data-original-title="{{trans('quotation.see_sale')}}" href="{{url('sales/complete/'.$credit_note->sale->id)}}">{{$credit_note->sale->serie->document->name.' '.$credit_note->sale->serie->name.'-'.$credit_note->sale->correlative}}</a><br>
                                                <strong>{{trans('sale.invoice_date')}}: </strong> {{$credit_note->sale->sale_date}}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="container">
                                    @if ($credit_note->api_uuid!='')
                                        <div class="col-md-4">
                                            <b>{{trans('credit_notes.no_aut')}}:</b> <br>{{$credit_note->api_uuid}}
                                        </div>
                                        <div class="col-md-4">
                                            <b>{{trans('credit_notes.fecha_cert')}}:</b> <br>{{$credit_note->api_fecha}}
                                        </div>
                                        <div class="col-md-4">
                                            <b>{{trans('credit_notes.document')}}:</b> <br>{{$credit_note->api_serie.' '.$credit_note->api_numero}}
                                        </div>

                                    @endif
                                </div>
                                <div class="end">Cacao GT
                                </div>
                            </div>

                        </footer>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">&nbsp;</div>

            <div class="col-md-2">
                <button type="button" onclick="printReceipt();"
                        class="btn btn-info pull-right hidden-print">{{trans('quotation.print')}}</button>
            </div>
            <div class="col-md-2">
                <a href="{{ url("/credit_note") }}" type="button"
                   class="btn btn-danger hidden-print">{{trans('Cancelar')}}</a>
            </div>
            <div class="col-md-3">&nbsp;</div>
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

            frameDoc.document.write('<style>:root { --color_primary: {{$parameters->primary}}; --color_secundary: {{$parameters->seccond}}; }</style>');
            frameDoc.document.write('<link href="{{ asset('assets/css/pages/quotation.css')}}" rel="stylesheet" type="text/css" />');
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
@endsection



