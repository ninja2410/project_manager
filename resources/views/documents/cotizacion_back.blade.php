@extends('layouts/default')
@section('title',trans('quotation.show'))
@section('page_parent',trans('quotation.quotation'))
@section('header_styles')
    <link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css"/>
    <!-- Toast -->
    <link href="{{ asset('assets/css/toast/toastr.min.css') }}" rel="stylesheet" type="text/css"/>
    {{-- select 2 --}}
    <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css"/>
    {{-- date time picker --}}
    <link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet"
          type="text/css"/>
    {{-- autocomplete --}}
    <link href="{{ asset('assets/css/easy-autocomplete.css') }}" rel="stylesheet" type="text/css"/>

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
                            {{trans('quotation.show')}}
                        </h3>
                        <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
                    </div>
                    <div class="panel-body body_c" id="dvContents">
                        <div class="row">
                            <header class="clearfix_c cheader">
                                <div class="container_c">
                                    {{--                                    <figure>--}}
                                    {{--                                        <img class="logo custlogo"--}}
                                    {{--                                             src="{{ asset('images/system/logo2.png') }}"--}}
                                    {{--                                             alt="">--}}
                                    {{--                                    </figure>--}}
                                    <div class="col-lg-3 col-sm-4 col-md-3 col-xs-4 div_logo">
                                        <img class="logo custlogo"
                                             src="{{ asset('images/system/logo2.png') }}"
                                             alt="">
                                    </div>
                                    <div class="company-info col-lg-4 col-sm-8 col-md-4 col-xs-8">
                                        <h2 class="title">{{$parameters->name_company}}</h2>

                                        <span>{{$parameters->address}}</span>
                                        <span class="line"></span>
                                        <a class="phone" href="tel:{{$parameters->phone}}">{{$parameters->phone}}</a>
                                        <span class="line"></span>
                                        <a class="email" href="mailto:{{$parameters->email}}">{{$parameters->email}}</a>
                                    </div>
                                    <div class="col-lg-5 col-sm-12 col-md-5 col-xs-12">
                                        <br>
                                        <span>{{$parameters->description}}</span>
                                    </div>

                                </div>

                            </header>
                        </div>

                        <hr>
                        <section>
                            <div class="row">
                                <div class="details clearfix_c">
                                    <div class="client left col-md-5">
                                        <p>{{trans('customer.customer')}}: <strong>{{$customer->name}}</strong></p>
                                        <p>
                                            <strong>{{trans('customer.nit')}}</strong>: {{$customer->nit_customer}}
                                        </p>
                                        <p>
                                            <strong>{{trans('customer.address')}}</strong>: {{$customer->address}}
                                        </p>
                                    </div>
                                    <div class="data right col-md-7">
                                        <div class="title">{{$serie->document->name.' '.$serie->name}}-{{$quotation->correlative}}</div>
                                        <div class="date">
                                            {{trans('quotation.date')}}: {{date('d/m/Y', strtotime($quotation->date))}}<br>
                                            @if (isset($quotation->days))
                                                {{trans('quotation.days')}}: {{$quotation->days}}
                                            @endif
                                        </div>
                                    </div>
                                    <input type="hidden" id="name_document" name="name_document" value="{{$serie->document->name.' '.$serie->name.'  #'.$quotation->correlative}}">
                                </div>
                                <div class="container" style="padding-left:0px !important">
                                    <div class="table-wrapper">
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
                                                    <td class="desc">{{$detail->item->item_name}}</td>
                                                    <td class="qty">{{$detail->quantity}}</td>
                                                    <td class="unit">@money($detail->price)</td>
                                                    <td class="total">@money($detail->price*$detail->quantity)</td>
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
                                                <td class="total" colspan="2" style="font-size: 16px">@money($quotation->amount)</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </section>

                        <footer>
                            <div class="container">
{{--                                <div class="thanks">Gracias por su compra!</div>--}}
                                <div class="notice">
                                    <div>{{trans('quotation.comment')}}:</div>
                                    <div>{{$quotation->comment}}</div>
                                    @if (isset($quotation->sale_id))
                                        <div class="col-lg-7"></div>
                                        <div class="col-lg-5">
                                            <strong>{{trans('quotation.sale_details')}}: </strong> <a data-toggle="tooltip" target="_blank" data-original-title="{{trans('quotation.see_sale')}}" href="{{url('sales/complete/'.$quotation->sale->id)}}">{{$quotation->sale->serie->document->name.' '.$quotation->sale->serie->name.'-'.$quotation->sale->correlative}}</a>
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
        <br>
        @include('quotation.load_sale')
        <hr>
    </section>
@endsection
@section('footer_scripts')
    <script>
        function showModal(control){
            load_sale_url = $(control).attr('toaction');
            $('#id_bodega').val('');
            $('#load_sale_modal').modal('show');
        }
        function setCellar(){
            if ($('#id_bodega').val()==''){
                toastr.error("Debe seleccionar una bodega para continuar");
                $('#id_bodega').focus();
                return;
            }
            showLoading("Configurando cotización, espere un momento.");
            window.location = load_sale_url+'/'+$('#id_bodega').val();
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

            frameDoc.document.write('<style>:root { --color_primary: {{$parameters->primary}}; --color_secundary: {{$parameters->seccond}}; }</style>');
            frameDoc.document.write('<link href="{{ asset('assets/css/pages/quotation.css')}}" rel="stylesheet" type="text/css" />');
            //frameDoc.document.write('<link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />');
            {{--frameDoc.document.write('<link href="{{ asset('assets/css/bootstrap/grid.min.css') }}" rel="stylesheet" type="text/css" />');--}}
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



