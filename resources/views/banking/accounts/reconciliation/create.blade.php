@extends('layouts/default')
@section('title',trans('reconciliation.reconciliation'))
@section('page_parent',"Bancos")
@section('header_styles')
    <link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css"/>
    <!-- Toast -->
    <link href="{{ asset('assets/css/toast/toastr.min.css') }}" rel="stylesheet" type="text/css"/>
    <!-- ALERTS -->
    <link href="{{ asset('assets/css/pages/alerts.css') }}" rel="stylesheet" type="text/css"/>
    {{-- select 2 --}}
    <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css"/>
    {{-- date time picker --}}
    <link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet"
          type="text/css"/>
    {{-- autocomplete --}}
    <link href="{{ asset('assets/css/easy-autocomplete.css') }}" rel="stylesheet" type="text/css"/>

    {{-- CHECKBOX DATATABLES --}}
    <link href="{{ asset('assets/datatables-checkboxes/css/dataTables.checkboxes.css') }}" rel="stylesheet"
          type="text/css"/>
@stop
@section('content')
    <section class="content">
        <div class="row" style="padding-top:5px;">
            <div class="col-lg-12 ">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="shopping-cart-in" data-size="18" data-c="#fff" data-hc="#fff"
                               data-loop="true"></i>
                            {{trans('reconciliation.reconciliation_at').' '.$account->account_name}}
                        </h3>
                        <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
                    </div>
                    <div class="panel-body">
                        <input type="hidden" name="conciliation_data" id="conciliation_data" value="{{json_encode($conciliation)}}">
                        <input type="hidden" id="balance_account" value="{{$account->pct_interes}}">
                        <input type="hidden" id="conciliation_id_ref" value="{{$conciliation->id}}">

                        <div class="alert-message alert-message-success">
                            <h4>{{trans('reconciliation.active_month').trans('months.'.(int)$conciliation->month)}}
                                de {{$conciliation->year}}</h4> (SALDO INICIAL: @money($conciliation->start_balance))
                            <span class="pull-right clickable">
                                <button class="btn btn-warning"
                                        id="cerrarMes">{{trans('reconciliation.close')}}</button>
                            </span>
                            <p>
                                Los documentos seleccionados serán conciliados con el mes en operaciones actual.
                            </p>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th colspan="2" style="text-align: center;">{{trans('reconciliation.tx_con')}}</th>
                                    <th colspan="2"
                                        style="text-align: center;">{{trans('reconciliation.tx_no_con')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <th>{{trans('reconciliation.total_rev')}}</th>
                                    <td>@money($conciliation->recon_revenues)</td>
                                    <th>{{trans('reconciliation.transit_revenue')}}</th>
                                    <td>@money($conciliation->transit_revenue)</td>
                                </tr>
                                <tr>
                                    <th>{{trans('reconciliation.total_exp')}}</th>
                                    <td>@money($conciliation->recon_expenses)</td>
                                    <th>{{trans('reconciliation.outsanding_payments')}}</th>
                                    <td>@money($conciliation->outstanding_payments)</td>
                                </tr>
                                <tr>
                                    <th>{{trans('reconciliation.bank_balance')}}</th>
                                    <th>@money($conciliation->recon_revenues-$conciliation->recon_expenses)</th>
                                    <th>{{trans('reconciliation.total_no_con')}}</th>
                                    <th>@money($conciliation->transit_revenue-$conciliation->outstanding_payments)</th>
                                    <input type="hidden" id="notConciliedBalance" value="{{$conciliation->transit_revenue-$conciliation->outstanding_payments}}">
                                </tr>
                                </tbody>
                            </table>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <label for="bank_balance">{{trans('reconciliation.balance_print')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Q</span>
                                            <input type="text" class="form-control money_input" name="bank_balance"
                                                   id="bank_balance">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="countable_balance">{{trans('reconciliation.countable_balance')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Q</span>
                                            <input type="text" class="form-control" id="countable_balance"
                                                   name="countable_balance" value="{{$conciliation->recon_revenues-$conciliation->recon_expenses}}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="bank_balance">{{trans('reconciliation.pending')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Q</span>
                                            <input type="text" class="form-control" name="pending_balance"
                                                   id="pending" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bs-example">
                            <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                                <li class="active">
                                    <a href="#transacciones"
                                       data-toggle="tab">{{trans('reconciliation.transactions')}}</a>
                                </li>
                                <li id="tab_Cerrar">
                                    <a href="#cerrar_mes" data-toggle="tab"
                                       id="link_pago">{{trans('reconciliation.close')}}</a>
                                </li>
                            </ul>
                            <div id="tabsVentas" class="tab-content">
                                <div class="tab-pane fade active in" id="transacciones">
                                    <div class="row">
                                        <br>
                                        {!! Form::open(array('method'=>'get','url'=>url('bank_reconciliation/account/'.$account->id))) !!}
                                        <div class="row">
                                            <div class="col-md-1">
                                            </div>
                                            <div class="col-md-3">
                                                <center><label for=""><b>Tipo</b></label></center>
                                                <select name="type" id="type" class="form-control">
                                                    <option value="all" @if($tipo=='all') selected @endif>Todos</option>
                                                    <option value="Ingreso" @if($tipo=='Ingreso') selected @endif>Ingresos</option>
                                                    <option value="Gasto" @if($tipo=='Gasto') selected @endif>Gastos</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <center><label for=""><b>Estado</b></label></center>
                                                <select name="status" id="status" class="form-control">
                                                    <option value="all" @if($status=='all') selected @endif>Todos</option>
                                                    <option value="1" @if($status=='1') selected @endif>Conciliado</option>
                                                    <option value="0" @if($status=='0') selected @endif>No conciliado</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <center><label for=""><b>Fecha final</b></label></center>
                                                <input type="text" name="date2" id='date2' class="form-control"
                                                       value="{{date('d/m/Y', strtotime($fecha2))}}">
                                            </div>
                                            <div class="col-md-2">
                                                <br> {!! Form::submit(trans('accounts.generate'), array('class' => 'btn btn-primary ')) !!}
                                            </div>
                                        </div>
                                        <hr> {!! Form::close() !!}
                                    </div>
                                    <div class="row">
                                        <table id="example" class="display table table-bordered " cellspacing="0"
                                               width="100%">
                                            <thead>
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Tipo</th>
                                                <th>Usuario</th>
                                                <th>Forma pago</th>
                                                <th>Descripción</th>
                                                <th>Referencia</th>
                                                <th>Ingreso</th>
                                                <th>Gasto</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($datos_filtrados as $value)
                                                <tr id="{{$value->id}}" tipo="{{$value->tipo}}"
                                                    class="{{$value->reconcilied}}"
                                                    reconcilied="{{$value->reconcilied}}">
                                                    <td>{{date('d/m/y',strtotime($value->paid_at))}}</td>
                                                    <td>
                                                        <span @if ($value->tipo=='Ingreso') class="label label-primary"
                                                              @else class="label label-danger" @endif>{{ $value->tipo }}</span>
                                                    </td>
                                                    <td>{{$value->user}}</td>
                                                    <td>{{$value->payment_method}}</td>
                                                    <td>{{$value->description}}</td>
                                                    <td>{{$value->reference}}</td>
                                                    @if ($value->tipo=='Ingreso')
                                                        <td>@money($value->amount)</td>
                                                        <td></td>
                                                    @else
                                                        <td></td>
                                                        <td>@money($value->amount)</td>
                                                    @endif

                                                    <td></td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="6" style="text-align:right">Total:</th>
                                                    <th colspan="1"></th>
                                                    <th colspan="1"></th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="row" style="text-align: center;">
                                        <a href="#" style="font-size: 14px" id="add_customer_btn"
                                           class="btn btn-success" data-toggle="modal"
                                           data-target="#confirm"><i
                                                    class="fa fa-save"></i> {{trans('reconciliation.save_docs')}}
                                        </a>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="cerrar_mes">
                                    {!! Form::open(array('url' => '#', 'files' => true, 'id'=>'frmClose'))!!}
                                    <input type="hidden" name="conciliation_id" id="conciliation_id">
                                    <input type="hidden" name="bank_print_balance" id="bank_print_balance">
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label for="comment">{{trans('reconciliation.comment')}}</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">T</span>
                                                <textarea class="form-control resize_vertical" id="comment"
                                                          name="comment" rows="5"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row" style="text-align: center;">
                                        <a class="btn btn-success" href="#" style="font-size: 14px"
                                           id="confirm_reconciliation_btn"><i
                                                    class="fa fa-save"></i> {{trans('reconciliation.close_form')}}
                                        </a>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                            {!! Form::open(array('id'=>'frmSend', 'url'=>'#')) !!}
                            <input type="hidden" id="egresos" name="egresos">
                            <input type="hidden" id="ingresos" name="ingresos">
                            {!! Form::close() !!}
                            <input type="hidden" name="path" id="path" value="{{ url('/') }}">
                            @include('banking.accounts.reconciliation.confirm-reconciliation')
                            @include('banking.accounts.reconciliation.confirm-reconciliation-save')
                        </div>
                    </div>
                </div>
            </div>
    </section>
@endsection

@section('footer_scripts')
    {{-- FORMATO DE MONEDAS --}}
    <script src="{{asset('assets/js/vuejs/cleave.min.js')}} "></script>
    <script src="{{asset('assets/js/vuejs/vue-cleave.min.js')}} "></script>
    <script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }}" type="text/javascript"></script>
    {{-- Select2 --}}
    <script type="text/javascript" src="{{ asset('assets/vendors/select2/js/select2.js')}} "></script>

    <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"
            type="text/javascript"></script>

    {{-- CHECKBOX DATATABLES --}}
    <script src="{{ asset('assets/datatables-checkboxes/js/dataTables.checkboxes.js') }}"
            type="text/javascript"></script>
    {{-- CUSTOMS JS RECONCILIATION --}}
    <script src="{{ asset('assets/js/reconciliation/reconciliation.js') }}" type="text/javascript"></script>

    <script type="text/javascript">

        var table;
        $(document).ready(function () {
            inicializar();

            $('.money_input').toArray().forEach(function(field){
                new Cleave(field, {
                    numeral: true,
                    numeralPositiveOnly: true,
                    numeralThousandsGroupStyle: 'thousand'
                });
            });
        });

        function inicializar() {
            table = $('#example').on('init.dt', function () {
                setdefaultValue();
            }).DataTable({
                "footerCallback": function (row, data, start, end, display) {
                    var api = this.api(), data;
                    var intVal = function (i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,Q,]/g, '') * 1 :
                            typeof i === 'number' ?
                                i : 0;
                    };

                    total = api
                        .column(6)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    pageTotal = api
                        .column(6, {page: 'current'})
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    $(api.column(6).footer()).html(
                        'Q' + number_format(pageTotal, 2)
                    );

                    total2 = api
                        .column(7)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    pageTotal2 = api
                        .column(7, {page: 'current'})
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    $(api.column(7).footer()).html(
                        'Q' + number_format(pageTotal2, 2)
                    );
                },
                language: {
                    "url": " {{ asset('assets/json/Spanish.json') }}"
                },
                "ordering": false,
                'columnDefs': [
                    {
                        'targets': 8,
                        'checkboxes': {
                            'selectRow': true
                        }
                    }
                ],
                'select': {
                    'style': 'multi'
                }
                // 'order': [[0, 'desc']]
            });
            $("#date1").datetimepicker({
                sideBySide: true,
                locale: 'es',
                format: 'DD/MM/YYYY'
            }).parent().css("position :relative");
            $("#date2").datetimepicker({
                sideBySide: true,
                locale: 'es',
                format: 'DD/MM/YYYY'
            }).parent().css("position :relative");
        }
    </script>
@stop
