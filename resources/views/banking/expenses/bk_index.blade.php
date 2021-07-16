@extends('layouts/default')
@if (isset($account))
    @section('title',trans('bank_expenses.expenses').' '.$account->account_name)
@else
    @section('title',trans('bank_expenses.expenses'))
@endif

@section('page_parent',trans('bank_expenses.banks'))

@section('header_styles')
    {{-- date time picker --}}
    <link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />
    <style media="screen">
        th { white-space: nowrap; }
    </style>
@stop
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading clearfix">
                        <h4 class="panel-title pull-left"> <i class="livicon" data-name="list-ul" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                            {{trans('bank_expenses.expenses')}}
                        </h4>
                        <div class="pull-right">
                            @if (!isset($account->id))
                                <a href="{{ URL::to('banks/expenses/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> {{trans('bank_expenses.new_expense')}} </a>
                            @else
                                <a href="{{ URL::to('project/expenses/create/'.$account->id) }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> {{trans('Nuevo gasto de proyecto')}} </a>
                            @endif
                        </div>
                    </div>
                    <div class="panel-body">
                        <hr /> 
                        {!! Form::open(array('url'=>'banks/expenses','method'=>'get')) !!}
                        @include('partials.banktx_filter_full')
                        {!! Form::close() !!}

                        <table class="table table-striped table-bordered" id="table1">
                            <thead>
                            <tr>
                                <th></th>
                                <th style="width: 5%;">No</th>
                                <th>Fecha</th>
                                @if (isset($account))
                                    <th>Etapa</th>
                                @else
                                    <th>Cuenta</th>
                                @endif
                                <th>Proveedor</th>
                                <th>Categoria</th>
                                <th>Forma pago</th>
                                <th>Monto</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($expenses as $i => $value)
                                <tr>
                                    <td></td>
                                    <td>{{$i+1}}</td>
                                    <td>{{$value->paid_at}}</td>

                                    @if (isset($account))
                                        <td>{{$value->etapa}}</td>
                                    @else
                                        <td><a href="{{ URL::to('banks/accounts/statement/' . $value->account_id ) }}" data-toggle="tooltip" data-original-title="Estado de cuenta">{{$value->account->account_name}}</a></td>
                                    @endif

                                    <td>{{!empty($value->supplier->company_name) ? $value->supplier->company_name : trans('general.na')}}</td>
                                    <td>{{$value->category->name}}</td>
                                    <td>{{$value->pago->name}}</td>
                                    <td>@money($value->amount)</td>
                                    <td>
                                        @if ($value->status==4)
                                            <span class="label label-success">{{ trans('bank_expenses.active_status') }}</span> @else
                                            <span class="label label-danger">{{ trans('bank_expenses.inactive_status') }}</span> @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-warning" href="{{ URL::to('banks/expenses/' . $value->id ) }}" data-toggle="tooltip" data-original-title="Detalles">
                                            <span class="glyphicon glyphicon-edit"></span>&nbsp;&nbsp;Detalles
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="7" style="text-align:right">Total:</th>
                                <th colspan="3"></th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footer_scripts')
    <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var dateNow = new Date();
            $("#start_date").datetimepicker({ sideBySide: true, locale:'es',format:'DD/MM/YYYY', defaultDate:dateNow}).parent().css("position :relative");
            $("#end_date").datetimepicker({ sideBySide: true, locale:'es',format:'DD/MM/YYYY', defaultDate:dateNow}).parent().css("position :relative");

            $('#table1').DataTable({
                "footerCallback": function ( row, data, start, end, display ) {
                    var api = this.api(), data;
                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,Q,]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                    };

                    total = api
                        .column( 7 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    pageTotal = api
                        .column( 7, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    $( api.column( 7 ).footer() ).html(
                        'Q'+number_format(pageTotal, 2) +' ( Q '+ number_format(total, 2) +' Total general)'
                    );
                },
                "language":{ "url": "{{ asset('assets/json/Spanish.json') }}" },
                dom: 'Bfrtip',
                responsive: {
                    details: {
                        type: 'column'
                    }
                },
                columnDefs: [ {
                    className: 'control',
                    orderable: false,
                    targets:   0
                } ],
                buttons: [
                    {
                        extend: 'collection',
                        text: 'Exportar/Imprimir',
                        buttons: [
                            {
                                extend:'copy',
                                text: 'Copiar',
                                title: document.title,
                                exportOptions:{
                                    columns: 'th:not(:last-child)'
                                }
                            },
                            {
                                extend:'excel',
                                title: document.title,
                                exportOptions:{
                                    columns: 'th:not(:last-child)'
                                }
                            },
                            {
                                extend:'pdf',
                                title: document.title,
                                exportOptions:{
                                    columns: 'th:not(:last-child)'
                                }
                            },
                            {
                                extend:'print',
                                text: 'Imprimir',
                                title: document.title,
                                exportOptions:{
                                    columns: 'th:not(:last-child)'
                                },
                            }
                        ]
                    },
                ],
            });
        });

    </script>
@stop
