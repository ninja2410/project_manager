@extends('layouts/default')

@section('title',trans('settlement.show'))
@section('page_parent',trans('route.route'))
@section('header_styles')
    <!-- Validaciones -->
    <link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css"/>
    {{-- date time picker --}}
    <link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <style>
        .money {text-align: right;}

        .summary {border-style: hidden;text-align: right;}

        .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {padding: 0px !important;}
    </style>
@stop
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff"
                               data-loop="true"></i>
                            {{trans('settlement.show')}}
                        </h3>
                        <span class="pull-right clickable">
                            <i class="glyphicon glyphicon-chevron-up"></i>
                          </span>
                    </div>
                    <div class="panel-body">
                        
                        <div id="dvContents">
                            <div class="row">
                                <center><h2>Ver Liquidación de ruta: {{$header->route->name}}</h2></center>
                            </div>
                            <div class="row">
                                <table class="table">
                                    <tr>
                                        <td>{!! Form::label('user', trans('settlement.users')). ':  '.$header->user_assigned->name !!}</td>
                                        <td>{!! Form::label('lblTour', trans('settlement.tour')).':  '.$header->tour  !!}</td>
                                        <td>{!! Form::label('lblWeek', trans('settlement.week')).':  '.$header->week  !!}</td>
                                    </tr>
                                    <tr>
                                        <td>{!! Form::label('lblMonth', trans('settlement.month').'/'.trans('settlement.year')) !!} : {{trans('months.'.$month)}}/{{$year}}</td>
                                        <td><label for=""><b>{{trans('report-sale.start_date')}}</b></label>: {{$header->date_begin}}</td>
                                        <td><label for=""><b>{{trans('report-sale.end_date')}}</b></label> : {{$header->date_end}}</td>
                                    </tr>
                                </table>
                            </div>
                            {{--       TABLA DETALLES DE GASTOS         --}}
                            <div class="row">
                                <div class="col-xs-8 col-sm-8 col-md-8">
                                    <div class="table">
                                        <table class="table">
                                            <thead>
                                            <tr style="background-color: #939393">
                                                <th colspan="2">
                                                    <center>{{trans('settlement.expenses')}}</center>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th scope="col">{{trans('settlement.description')}}</th>
                                                <th scope="col">{{trans('settlement.amount')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $total_gasto = 0;
                                            ?>
                                            @foreach ($gastos as $gasto)
                                                <tr>
                                                    <td>{{$gasto->category->name}}</td>
                                                    <td class="money">@money($gasto->amount)</td>
                                                </tr>
                                                <?php
                                                $total_gasto += $gasto->amount;
                                                ?>
                                            @endforeach
                                            <input type="hidden" name="json_gastos" value="{{json_encode($gastos)}}">
                                            <input type="hidden" name="total_gastos" value="{{$total_gasto}}">
                                            <tr>
                                                <td class="summary"><b>{{trans('settlement.total')}}</b></td>
                                                <td class="money"><b>@money($total_gasto)</b></td>
                                            </tr>
                                            <tr>
                                                <td class="summary"><b>(-){{trans('settlement.assigned')}}</b></td>
                                                <td class="money"><b>@money($header->user_assigned->expenses_max)</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="summary"><b>{{trans('settlement.diference')}}</b></td>
                                                <td class="money"><b>@money($total_gasto -
                                                        $header->user_assigned->expenses_max)</b></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-xs-4 col-sm-4 col-md-4">
                                    <label for="">{{trans('settlement.comments')}}: {{$header->comment_expenses}}</label>
                                </div>
                            </div>
                            {{--                        ---------------------------------}}

                            {{--       TABLA DETALLES DE VENTAS         --}}
                            <div class="row">
                                <div class="col-xs-8 col-sm-8 col-md-8">
                                    <div class="table">
                                        <table class="table">
                                            <thead>
                                            <tr style="background-color: #939393">
                                                <th colspan="3">
                                                    <center>{{trans('settlement.sales')}}</center>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th scope="col">{{trans('settlement.serie')}}</th>
                                                <th scope="col">{{trans('settlement.no_documents')}}</th>
                                                <th scope="col">{{trans('settlement.amount')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $total_ventas = 0;
                                            ?>
                                            @foreach ($ventas as $venta)
                                                <tr>
                                                    <td>{{$venta->serie->Document->name.' '.$venta->serie->name}}</td>
                                                    <td>{{$venta->quantity}}</td>
                                                    <td class="money">@money($venta->amount)</td>
                                                </tr>
                                                <?php
                                                $total_ventas += $venta->amount;
                                                ?>
                                            @endforeach
                                            <input type="hidden" name="json_ventas" value="{{json_encode($ventas)}}">
                                            <input type="hidden" name="total_ventas" value="{{$total_ventas}}">
                                            <tr>
                                                <td class="summary"></td>
                                                <td class="summary"><b>{{trans('settlement.total')}}</b></td>
                                                <td class="money"><b>@money($total_ventas)</b></td>
                                            </tr>
                                            <tr>
                                                <td class="summary"></td>
                                                <td class="summary"><b>{{trans('settlement.goal')}}</b></td>
                                                <td class="money"><b>@money($header->route->goal_amount)</b></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-xs-4 col-sm-4 col-md-4">
                                    <label for="">{{trans('settlement.comments')}}: {{$header->comment_sales}}</label>
                                </div>
                            </div>
                            {{--                        ---------------------------------}}
                            {{--       TABLA DETALLES DE COBROS         --}}
                            <div class="row">
                                <div class="col-xs-8 col-sm-8 col-md-8">
                                    <div class="table">
                                        <table class="table">
                                            <thead>
                                            <tr style="background-color: #939393">
                                                <th colspan="3">
                                                    <center>{{trans('settlement.payments')}}</center>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th scope="col">{{trans('settlement.serie')}}</th>
                                                <th scope="col">{{trans('settlement.no_documents')}}</th>
                                                <th scope="col">{{trans('settlement.amount')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $total_cobros = 0;
                                            ?>
                                            @foreach ($cobros as $cobro)
                                                <tr>
                                                    <td>{{$cobro->serie->Document->name.' '.$cobro->serie->name}}</td>
                                                    <td>{{$cobro->quantity}}</td>
                                                    <td class="money">@money($cobro->amount)</td>
                                                </tr>
                                                <?php
                                                $total_cobros += $cobro->amount;
                                                ?>
                                            @endforeach
                                            <tr>
                                                <td class="summary"></td>
                                                <td class="summary"><b>{{trans('settlement.total')}}</b></td>
                                                <td class="money"><b>@money($total_cobros)</b></td>
                                            </tr>
                                            @foreach($detalle_manual as $pago)
                                                <tr>
                                                    <td class="summary"></td>
                                                    <td class="summary"><b>(-){{$pago->pago->name}}</b></td>
                                                    <td class="money">@money($pago->amount)</td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td class="summary"></td>
                                                <td class="summary"><b>{{trans('settlement.diference')}}</b></td>
                                                <td class="money">@money($header->diference)
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="summary"></td>
                                                <td class="summary"><b>{{trans('settlement.commission')}}</b></td>
                                                <td class="money">@money($header->comission)</td>
                                            </tr>
                                            <tr>
                                                <td class="summary"></td>
                                                <td class="summary"><b>{{trans('settlement.collection_goal')}}</b></td>
                                                <td class="money"><b>@money($header->user_assigned->collection_goal)</b>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-xs-4 col-sm-4 col-md-4">
                                    <label for="">{{trans('settlement.comments')}}: {{$header->comment_payments}}</label>
                                </div>
                            </div>
                            {{--                        ---------------------------------}}
                            <hr>
                        </div>
                        {{--                        BOTONES PARA IMPMRIMIR Y CANELAR --}}
                        <div class="row">
                            <div class="col-md-3">&nbsp;</div>

                            <div class="col-md-3">
                                <button type="button" onclick="printReceipt();"
                                        class="btn btn-info pull-right hidden-print">{{trans('quotation.print')}}</button>
                            </div>
                            {{--            <div class="col-md-2">--}}
                            {{--                <button type="button" onclick="showModal(this);"--}}
                            {{--                        toaction="{{ URL::to('quotation/load_sale/' . $quotation->id) }}"--}}
                            {{--                        class="btn btn-success pull-right hidden-print">{{trans('quotation.tosale')}}</button>--}}
                            {{--            </div>--}}
                            <div class="col-md-3">
                                <a href="{{ url("/routes/settlement/".$header->route->id) }}" type="button"
                                   class="btn btn-danger hidden-print">{{trans('Cancelar')}}</a>
                            </div>
                            <div class="col-md-3">&nbsp;</div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
@endsection
@section('footer_scripts')
    <!-- Valiadaciones -->
    <script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
    <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"
            type="text/javascript"></script>
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

            frameDoc.document.write('<style>.money {text-align: right;} .summary {border-style: hidden;text-align: right;} .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {padding: 0px !important;}</style>');
            frameDoc.document.write('<link href="{{ asset('assets/css/pages/print_receipt.css')}}" rel="stylesheet" type="text/css" />');
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

@stop
