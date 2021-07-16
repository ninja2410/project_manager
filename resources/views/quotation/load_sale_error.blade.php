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
                        
                        <header class="clearfix_c cheader">
                            <div class="container_c">
                                <figure>
                                    <img class="logo custlogo"
                                         src="{{ asset('images/system/logo.png') }}"
                                         alt="">
                                </figure>
                                <div class="company-info col-lg-5">
                                    <h2 class="title">&emsp;</h2>
                                    <br>
                                    <span>{{$parameters->address}}</span>
                                    <span class="line"></span>
                                    <a class="phone" href="tel:{{$parameters->phone}}">{{$parameters->phone}}</a>
                                    <span class="line"></span>
                                    <a class="email" href="mailto:{{$parameters->email}}">{{$parameters->email}}</a>
                                </div>
                                <div class="col-lg-6">
                                    <br>
                                    <span>{{$parameters->description}}</span>
                                </div>
                            </div>
                        </header>

                        <section>
                            <div class="details clearfix_c">
                                <div class="client left">
                                    <p>{{trans('customer.customer')}}:</p>
                                    <p class="name">{{$customer->name}}</p>
                                    <p>
                                        <strong>{{trans('customer.nit')}}</strong>: {{$customer->nit_customer}}
                                    </p>
                                    <p>
                                        <strong>{{trans('customer.address')}}</strong>: {{$customer->address}}
                                    </p>
                                </div>
                                <div class="data right">
                                    <div class="title">{{$serie->document->name.' '.$serie->name}}
                                        -{{$quotation->correlative}}</div>
                                    <div class="date">
                                        {{trans('quotation.date')}}: {{date('d/m/Y', strtotime($quotation->date))}}<br>
                                        @if (isset($quotation->days))
                                            {{trans('quotation.days')}}: {{$quotation->days}}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="container" style="width: 100%">
                                <div class="table-wrapper">
                                    <table>
                                        <tbody class="head">
                                        <tr>
                                            <th class="no"></th>
                                            <th >
                                                <div>{{trans('quotation.code')}}</div>
                                            </th>
                                            <th class="desc">
                                                <div>{{trans('quotation.description')}}</div>
                                            </th>
                                            <th class="qty">
                                                <div>{{trans('quotation.quantity')}}</div>
                                            </th>
                                            <th class="unit">
                                                <div>{{trans('quotation.cellar')}}</div>
                                            </th>
                                        </tr>
                                        </tbody>
                                        <tbody class="body">
                                        @foreach ($newdetail as $key => $detail)
                                            <tr>
                                                <td class="no">{{$key+1}}</td>
                                                <td>{{$detail['code']}}</td>
                                                <td class="desc">{{$detail['description']}}</td>
                                                <td class="unit">
                                                    <input style="text-align: center" type="text" max_value="{{$detail['cellar']}}" min="0" detail_id="{{$detail['detail_id']}}" class="form-control new_quantity" value="{{$detail['quantity']}}">
                                                </td>
                                                <td
                                                    @if ($detail['quantity']>$detail['cellar'])
                                                        class="total" style="border-bottom: darkred 1px solid"
                                                    @else
                                                        class="unit"
                                                    @endif
                                                >{{$detail['cellar']}}</td>
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
                                            <td class="desc" style="color: grey; text-align: left;"></td>
                                            <td class="unit" style="font-size: 18px">Bodega seleccionada:</td>
                                            <td class="total" colspan="2" style="font-size: 16px">{{$cellar->name}}</td>
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
                                    <div>{{trans('quotation.comment')}}:</div>
                                    <div>{{$quotation->comment}}</div>
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
            <div class="col-md-2">&nbsp;</div>
            {!! Form::open(array('url' => 'quotation/header', 'id'=>'frmDetails')) !!}
                <input type="hidden" name="newDetails" id="newDetails">
                <input type="hidden" name="cellar_id" value="{{$cellar->id}}">
                <input type="hidden" name="quotation_id" value="{{$quotation->id}}">
            {!! Form::close() !!}
            <div class="col-md-4">
                <button type="button" onclick="newQuantity();"
                        class="btn btn-info pull-right hidden-print">{{trans('quotation.force')}}</button>
            </div>
            <div class="col-md-4">
                <a href="{{ url("/quotation/header") }}" type="button"
                   class="btn btn-danger hidden-print">{{trans('Cancelar')}}</a>
            </div>
            <div class="col-md-2">&nbsp;</div>
        </div>
        <br>
        <hr>
    </section>
@endsection
@section('footer_scripts')
    {{-- FORMATO DE MONEDAS --}}
    <script src="{{asset('assets/js/vuejs/cleave.min.js')}} "></script>
    <script src="{{asset('assets/js/vuejs/vue-cleave.min.js')}} "></script>
    <script>
        //new_quantity
        $(document).ready(function () {
            $('.new_quantity').toArray().forEach(function (field) {
                new Cleave(field, {
                    numeral: true,
                    numeralPositiveOnly: true,
                    numeralThousandsGroupStyle: 'thousand'
                });
            });
        });
        function newQuantity(){
            showLoading("Actualizando datos de cotización, por favor espere...");
            var flag=true;
            var inputs = $('.new_quantity');
            for (var i =0; i<inputs.length; i++){
                if (cleanNumber(inputs[i].value) > cleanNumber($(inputs[i]).attr('max_value'))){
                    toastr.error("No hay existencias suficientes");
                    $(inputs[i]).focus();
                    flag=false;
                    break;
                }
            }
            if (flag){
                var data = [];
                var json = {};
                for (var i =0; i<inputs.length; i++){
                    data.push({
                        "detail_id": $(inputs[i]).attr('detail_id'),
                        "quantity": cleanNumber(inputs[i].value)
                    });
                }
                json = data;
                $('#newDetails').val(JSON.stringify(json));

                $.ajax({
                    type:"post",
                    url:APP_URL+'/quotation/update_details',
                    data:$('#frmDetails').serialize(),
                    success:function(data_){
                        var json = JSON.parse(data_);
                        if (json.flag!=1) {
                            toastr.error(json.mensaje);
                            console.log("Mensaje dev: "+json.mensaje);
                            hideLoading();
                        }
                        else{
                            toastr.success("Modificación realizada con exito, redirigiendo a interfaz de ventas...");
                            location.href = APP_URL+'/'+json.url;
                        }

                    },
                    error:function(error){
                        console.log(error);
                    }
                });
            }
            else{
                hideLoading();
            }
        }
        function printReceipt() {
            var name_document = $('#name_document').text();
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



