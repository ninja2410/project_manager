@extends('layouts/default')

@section('title',trans('settlement.create'))
@section('page_parent',trans('route.route'))
@section('header_styles')
    <!-- Validaciones -->
    <link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css"/>
    {{-- date time picker --}}
    <link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <style>
        .money {
            text-align: right;
        }

        .summary {
            border-style: hidden;
            text-align: right;
        }
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
                            {{trans('settlement.create')}}
                        </h3>
                        <span class="pull-right clickable">
                            <i class="glyphicon glyphicon-chevron-up"></i>
                          </span>
                    </div>
                    <div class="panel-body">
                        
                        {!! Form::open(array('url'=>url('routes/settlement/'.$route->id.'/create'),'method'=>'get', 'id'=>'frmFilter')) !!}
                        {!! Form::close() !!}

                        {!! Form::open(['url' => url('routes/settlement'), 'method' => 'post', 'id'=>'frm_send']) !!}
                        <input type="hidden" name="route_id" value="{{$route->id}}">
                        <input type="hidden" name="user_asigned" value="{{$user->id}}">
                        <div class="row">
                            <center><h2>Nueva Liquidación de ruta: {{$route->name}}</h2></center>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    {!! Form::label('user', trans('settlement.users')) !!}
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-user"></i></div>
                                        <input type="text" class="form-control" readonly name="users"
                                               value="{{$user->name}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    {!! Form::label('lblTour', trans('settlement.tour')) !!}
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-random"></i></div>
                                        <input type="text" class="form-control" name="tour">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    {!! Form::label('lblWeek', trans('settlement.week')) !!}
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                        <input type="text" class="form-control" name="week">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    {!! Form::label('lblMonth', trans('settlement.month')) !!}
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                        <input type="text" class="form-control" readonly name="month"
                                               value="{{trans('months.'.$month)}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    {!! Form::label('lblYear', trans('settlement.year')) !!}
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                        <input type="text" class="form-control" readonly name="year" value="{{$year}}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <center><label for=""><b>{{trans('report-sale.start_date')}}</b></label></center>
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                        <input type="text" name="date1" id='start_date' class="form-control"
                                               value="{{date('d/m/Y', strtotime($fecha1))}}" form="frmFilter">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <center><label for=""><b>{{trans('report-sale.end_date')}}</b></label></center>
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                        <input type="text" name="date2" id='end_date' class="form-control"
                                               value="{{date('d/m/Y', strtotime($fecha2))}}" form="frmFilter">
                                    </div>
                                </div>
                                <input type="hidden" name="date1" value="{{date('d/m/Y', strtotime($fecha1))}}">
                                <input type="hidden" name="date2" value="{{date('d/m/Y', strtotime($fecha2))}}">
                            </div>
                            <div class="col-md-1">
                                <br>
                                <button type="submit" class="btn btn-info" form="frmFilter">Filtrar</button>
                            </div>
                        </div>
                        <hr>
                        {{--       TABLA DETALLES DE GASTOS         --}}
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="table-scrollable">
                                    <table class="table table-striped table-bordered table-hover">
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
                                                <td>{{$gasto->name}}</td>
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
                                            <td class="money"><b>@money($user->expenses_max)</b></td>
                                        </tr>
                                        <tr>
                                            <td class="summary"><b>{{trans('settlement.diference')}}</b></td>
                                            <td class="money"><b>@money($total_gasto - $user->expenses_max)</b></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    {!! Form::label('comment', trans('settlement.comments')) !!}
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-text"></i></div>
                                        <textarea name="comment_expense" id="" cols="30" rows="10"
                                                  class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--                        ---------------------------------}}

                        {{--       TABLA DETALLES DE VENTAS         --}}
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="table-scrollable">
                                    <table class="table table-striped table-bordered table-hover">
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
                                                <td>{{$venta->Document.' '.$venta->Serie}}</td>
                                                <td>{{$venta->Facturas}}</td>
                                                <td class="money">@money($venta->monto)</td>
                                            </tr>
                                            <?php
                                            $total_ventas += $venta->monto;
                                            ?>
                                        @endforeach
                                        <input type="hidden" name="json_ventas" value="{{json_encode($ventas)}}">
                                        <input type="hidden" name="total_ventas" value="{{$total_ventas}}">
                                        <tr>
                                            <td></td>
                                            <td class="summary"><b>{{trans('settlement.total')}}</b></td>
                                            <td class="money"><b>@money($total_ventas)</b></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td class="summary"><b>{{trans('settlement.goal')}}</b></td>
                                            <td class="money"><b>@money($route->goal_amount)</b></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    {!! Form::label('comment', trans('settlement.comments')) !!}
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-text"></i></div>
                                        <textarea name="comment_sales" id="" cols="30" rows="10"
                                                  class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--                        ---------------------------------}}
                        {{--       TABLA DETALLES DE COBROS         --}}
                        <div class="row">
                            <input type="hidden" name="detail_payments" id="detail_payments">
                            <div class="col-lg-8">
                                <div class="table-scrollable">
                                    <table class="table table-striped table-bordered table-hover">
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
                                                <td>{{$cobro->Document.' '.$cobro->Serie}}</td>
                                                <td>{{$cobro->contador}}</td>
                                                <td class="money">@money($cobro->monto)</td>
                                            </tr>
                                            <?php
                                            $total_cobros += $cobro->monto;
                                            ?>
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td class="summary"><b>{{trans('settlement.total')}}</b></td>
                                            <td class="money"><b>@money($total_cobros)</b></td>
                                            <input type="hidden" name="total_payments" id="total_payments"
                                                   value="{{$total_cobros}}">
                                            <input type="hidden" name="json_payments" value="{{json_encode($cobros)}}">
                                            <input type="hidden" id="total_manual" value="0">
                                        </tr>
                                        @foreach($pagos as $pago)
                                            <tr>
                                                <td></td>
                                                <td class="summary"><b>(-){{$pago->name}}</b></td>
                                                <td class="money"><input type="text" pago_id="{{$pago->id}}" value="0" oldValue="0"
                                                                         class="form-control detail_manual"></td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td class="summary"><b>{{trans('settlement.diference')}}</b></td>
                                            <td class="money"><input id="diference" readonly name="diference"
                                                                     type="text" value="0" class="form-control"></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td class="summary"><b>{{trans('settlement.commission')}}</b></td>
                                            <td class="money"><input id="comission" name="comission" type="text"
                                                                     value="0" class="form-control"></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td class="summary"><b>{{trans('settlement.collection_goal')}}</b></td>
                                            <td class="money"><b>@money($user->collection_goal)</b></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    {!! Form::label('comment', trans('settlement.comments')) !!}
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-text"></i></div>
                                        <textarea name="comment_payments" id="comment_payments" cols="30" rows="10"
                                                  class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--                        ---------------------------------}}
                        <hr>
                        @include('partials.buttons',['cancel_url'=>"/routes/settlement/".$route->id])
                        {!! Form::close() !!}
                        {{--Begin modal--}}
                        <div class="modal fade modal-fade-in-scale-up" tabindex="-1" id="confirmSave" role="dialog"
                             aria-labelledby="modalLabelfade" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-info">
                                        <h4 class="modal-title">Confirmación guardar</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="text-center">
                                            Datos correctos
                                            <br>
                                            ¿Seguro que desea guardar?
                                        </div>
                                    </div>
                                    <div class="modal-footer" style="text-align:center;">
                                        <a class="btn  btn-info" id="btn_save_confirm"
                                           onclick="showLoading('Guardando liquidación...'); document.getElementById('frm_send').submit();">Aceptar</a>
                                        <a class="btn  btn-danger" data-dismiss="modal">Cancelar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--End modal--}}
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
    {{-- FORMATO DE MONEDAS --}}
    <script src="{{asset('assets/js/vuejs/cleave.min.js')}} "></script>
    <script src="{{asset('assets/js/vuejs/vue-cleave.min.js')}} "></script>
    {{--CUSTOM JS --}}
    <script src="{{ asset('assets/js/route/settlement.js') }} " type="text/javascript "></script>
    <script type="text/javascript">
        $(document).ready(function () {
            var cleave = new Cleave('#comission', {
                numeral: true,
                numeralPositiveOnly: true,
                numeralThousandsGroupStyle: 'thousand'
            });
            var cleave = new Cleave('#diference', {
                numeral: true,
                numeralPositiveOnly: true,
                numeralThousandsGroupStyle: 'thousand'
            });
            $('.detail_manual').toArray().forEach(function(field){
                new Cleave(field, {
                    numeral: true,
                    numeralPositiveOnly: true,
                    numeralThousandsGroupStyle: 'thousand'
                });
            });



            var dateNow = new Date();
            $("#start_date").datetimepicker({
                sideBySide: true,
                locale: 'es',
                format: 'DD/MM/YYYY',
                defaultDate: dateNow
            }).parent().css("position :relative");
            $("#end_date").datetimepicker({
                sideBySide: true,
                locale: 'es',
                format: 'DD/MM/YYYY',
                defaultDate: dateNow
            }).parent().css("position :relative");
            $('.select-all').click(function () {
                let $select2 = $(this).parent().siblings('.select2')
                $select2.find('option').prop('selected', 'selected')
                $select2.trigger('change')
            })

        });


        $("#frm_send").submit(function (ev) {
            ev.preventDefault();
        });
        var idVenta = document.getElementById('btn_save');
        idVenta.addEventListener('click', function () {
            $('body').loadingModal('hide');
            this.style.display = 'inline';

            /**
             * CONSTRUIR JSON PARA ENVIAR DETALLE
             */
            var data = [];
            var json = {};
            $(".detail_manual").each(function () {
                var pago_id = $(this).attr('pago_id');
                data.push({
                    "pago_id": pago_id,
                    "value": $(this).val()
                });
            });
            json = data;
            $('#detail_payments').val(JSON.stringify(json));

            $('#confirmSave').modal('show');
            // alert('ok');
        });

        function filter() {
            toastr.warning("Enviando");
            $('#frmFilter').submit();
        }
    </script>
@stop
