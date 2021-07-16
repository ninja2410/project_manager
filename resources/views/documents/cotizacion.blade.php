@extends('layouts/default')
@section('title',trans('quotation.show'))
@section('page_parent',trans('quotation.quotation'))
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
                            {{trans('quotation.show')}} {{$serie->document->name.' '.$serie->name.'-'.$quotation->correlative}}
                        </h3>
                        <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
                    </div>
                    <div class="panel-body" id="dvContents">
                        <div>
                            {{-- <div class="container cardbody"> --}}
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="row">
                                        <div class="col-xs-2 text-center logo_proforma">
                                            <img class="logo_invoice" style="max-width: 250px" src="{{ asset('images/system/logo2.png') }}"
                                                 alt="">

                                        </div>
                                        <div class="col-xs-7 text-center color_primary">
                                            <center>
                                                <h1 style="color: var(--color_primary)">{{$serie->document->name.' '.$serie->name}}-{{$quotation->correlative}}</h1>
                                            </center>
                                        </div>
                                        <div class="col-xs-3">
                                            <h3>
                                                {{trans('quotation.date')}}
                                                : {{date('d/m/Y', strtotime($quotation->date))}}<br>
                                                @if (isset($quotation->days))
                                                    {{trans('quotation.days')}}: {{$quotation->days}}
                                                @endif
                                            </h3>

                                        </div>
                                    </div>

                                    <input type="hidden" id="name_document" name="name_document"
                                           value="{{$serie->document->name.' '.$serie->nameNo.'  #'.$quotation->correlative}}">
                                    <br>
                                    <div class="row">
                                        <div class="col-xs-8">
                                            <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                                            <strong>{{trans('parameter.address')}}
                                                :</strong> {{$parameters->address}}
                                        </div>
                                        <div class="col-xs-4 text-right">
                                            <address>
                                                <span class="glyphicon glyphicon-earphone" aria-hidden="true"></span>
                                                <strong>{{trans('parameter.phone')}}:</strong> {{$parameters->phone}}
                                            </address>
                                        </div>
                                    </div>
                                    @if($imprimir_propietario==1)
                                        <div class="row">
                                                <div class="col-xs-4 text-center back_primary ceo"
                                                     style="height: 65px;">
                                                    {{$parameters->ceo}}
                                                </div>
                                                <div class="col-xs-8 text-center back_second description"
                                                     style="height: 65px;">
                                                    {{$parameters->description}}
                                                </div>
                                        </div>
                                    @endif
                                    <br>
                                    <div class="row">
                                        <div class="col-xs-8">
                                            <div class="customer-info">
                                                <strong>{{trans('customer.name')}}
                                                    :</strong>@if($imprimir_codigo_cliente==1) {{ $customer->customer_code.' - '}} @endif  {{$customer->name}}
                                            </div>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="customer-info">
                                                <strong>{{trans('customer.nit')}}</strong> {{$customer->nit_customer}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                            <div class="col-xs-8">
                                                <div class="customer-info">
                                                    <strong>{{trans('customer.address')}}</strong> {{$customer->address}}
                                                </div>
                                            </div>
                                            <div class="col-xs-4">
                                                <div class="customer-info">
                                                    <strong>{{trans('quotation.user')}}
                                                        :</strong> {{$quotation->user->name}}
                                                </div>
                                            </div>
                                    </div>
                                    <br>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-condensed table_details" style="width:100%">
                                            <thead class="header_details">
                                            <tr>
                                                <td style="width: 10%"><strong>{{trans('sale.qty')}}</strong>
                                                </td>
                                                <td style="width: 15%" class="text-left">
                                                    <strong>{{trans('sale.code')}}</strong>
                                                </td>
                                                <td style="width: 45%" class="text-left">
                                                    <strong>{{trans('quotation.description')}}</strong></td>
                                                <td style="width: 15%" class="text-right">
                                                    <strong>{{trans('sale.unit')}}</strong>
                                                </td>
                                                <td style="width: 15%" class="text-right">
                                                    <strong>{{trans('sale.total')}}</strong>
                                                </td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($details as $detail)
                                                <tr style="font-size:12px">
                                                    <td class="text-center">{{$detail->quantity}}</td>
                                                    <td class="text-left">{{$detail->item->upc_ean_isbn}}</td>
                                                    <td class="text-left">{{$detail->item->item_name}}</td>
                                                    <td class="text-right">
                                                        @money($detail->price)
                                                    </td>
                                                    <td class="text-right">@money($detail->price*$detail->quantity)</td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td class="no-line" colspan="3">
                                                    &nbsp;<strong>{{trans('quotation.leters')}}
                                                        :</strong> {{$precio_letras}}</td>
                                                {{-- <td class="no-line"></td> --}}
                                                {{-- <td class="no-line"></td> --}}
                                                <td class="text-right table_footer">
                                                    <strong>{{trans('sale.total')}}</strong></td>
                                                <td class="text-right table_footer">
                                                    <strong>@money($quotation->amount)</strong></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        {{-- <div class="col-xs-12">
                                            <strong>{{trans('quotation.leters')}}:</strong> {{$precio_letras}}
                                        </div> --}}
                                    </div>
                                    <footer>
                                        <div class="container">
                                            {{--                                <div class="thanks">Gracias por su compra!</div>--}}
                                            <div class="notice">
                                                <div class="col-lg-6 col-xs-6">
                                                    <div>{{trans('quotation.comment')}}:</div>
                                                    <div>{{$quotation->comment}}</div>
                                                </div>
                                                <div class="col-lg-6 col-xs-6">
                                                    @if (isset($quotation->sale_id))
                                                        <strong>{{trans('quotation.sale_details')}}: </strong> <a
                                                                data-toggle="tooltip" target="_blank"
                                                                data-original-title="{{trans('quotation.see_sale')}}"
                                                                href="{{url('sales/complete/'.$quotation->sale->id)}}">{{$quotation->sale->serie->document->name.' '.$quotation->sale->serie->name.'-'.$quotation->sale->correlative}}</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div style="text-align: center;" class="end">
                                            Cacao GT
                                        </div>
                                    </footer>
                                </div>

                            </div>
                        </div>
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
                <button type="button" onclick="showModal(this);"
                        toaction="{{ URL::to('quotation/load_sale/' . $quotation->id) }}"
                        class="btn btn-success pull-right hidden-print">{{trans('quotation.tosale')}}</button>
            </div>
            <div class="col-md-2">
                <a href="{{ url("/quotation/header") }}" type="button"
                   class="btn btn-danger hidden-print">{{trans('Cancelar')}}</a>
            </div>
            <div class="col-md-3">&nbsp;</div>
        </div>
        @include('quotation.load_sale')
        <br>
    </section>
@endsection
@section('footer_scripts')
    <script>
        function showModal(control) {
            load_sale_url = $(control).attr('toaction');
            $('#id_bodega').val('');
            $('#load_sale_modal').modal('show');
        }

        function setCellar() {
            if ($('#id_bodega').val() == '') {
                toastr.error("Debe seleccionar una bodega para continuar");
                $('#id_bodega').focus();
                return;
            }
            showLoading("Configurando cotización, espere un momento.");
            window.location = load_sale_url + '/' + $('#id_bodega').val();
        }

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
            {{--            frameDoc.document.write('<link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />');--}}
            frameDoc.document.write('<link href="{{ asset('assets/css/bootstrap/grid.css') }}" rel="stylesheet" type="text/css" />');
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



