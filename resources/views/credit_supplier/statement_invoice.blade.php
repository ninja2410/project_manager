@extends('layouts/default')

@section('title',trans('credit.statement_full').' : '.strtoupper($name_sale))
@section('page_parent',trans('credit.credits'))

@section('header_styles')
    {{-- date time picker --}}
    <link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/pages/tables.css') }}" />
@stop
@section('content')
    <section class="content">
        <div class="row">
            <div class="panel panel-primary">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="list-ul" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        {{trans('credit.statement_full').' : '.strtoupper($name_sale)}}
                    </h4>
                    <span class="pull-right clickable">
                    <i class="glyphicon glyphicon-chevron-up"></i>
                </span>
                </div>

                <div class="panel-body table-responsive">
                    
                    <table style="width:100%">
                        <tr>
                            <td style="text-align:center;"><h4>{{trans('credit.statement_full').' : '.strtoupper($name_sale)}}</h4></td>
                            {{-- <td style="width:10%"></td>
                            <td style="text-align:left"><h4>{{trans('credit.balance').' : '}}@money($customer->balance)</h4></td> --}}
                        </tr>
                    </table>
                    <br>
                    <table class="table table-striped table-bordered compact" id="table1">
                        <thead>
                        <tr>
                            <th></th>
                            <th data-priority="2" style="width: 10%;">{{trans('credit.date')}}</th>
                            <th data-priority="3" style="width: 15%;">{{trans('credit.document')}}</th>
                            <th data-priority="4">{{trans('credit.payment_type')}}</th>
                            <th data-priority="4">{{trans('credit.reference')}}</th>
                            <th data-priority="4">{{trans('credit.due_date')}}</th>
                            {{-- <th data-priority="4">{{trans('credit.status')}}</th> --}}
                            <th style="width: 9%;" data-priority="5">{{trans('credit.debit')}}</th>
                            <th style="width: 9%;">{{trans('credit.credit')}}</th>
                            <th style="width: 9%;">{{trans('credit.balance')}}</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php $saldo = 0; ?>
                        <tr>
                            <?php $saldo = $credit->invoice->total_cost; ?>
                            <td></td>
                            <td>{{$credit->invoice->date}}</td>
                            <td>{{$name_sale}}</td>
                            <td>{{$credit->invoice->pago->name}}</td>
                            <td></td>
                            <td>{{date('d/m/Y', strtotime($credit->date_payments) )}}</td>
                            <td style="text-align:right">@money($credit->invoice->total_cost)</td>
                            <td style="text-align:right">@money(0)</td>
                            <td style="text-align:right">@money($saldo)</td>
                        </tr>
                        @foreach($details as $i=> $value)
                            <tr>
                                <td></td>
                                <td>{{date('d/m/Y',strtotime($value->paid_date))}}</td>
                                <td>
                                    {{$value->expense->pago->name}}
                                </td>
                                <td>{{$value->expense->pago->name}}</td>
                                <td>{{ $value->expense->description }}</td>
                                <td></td>
                                <td style="text-align:right">@money(0)</td>
                                <td style="text-align:right">@money($value->amount)</td>
                                <?php $saldo -= $value->amount;?>
                                <td style="text-align:right">@money($saldo)</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th ></th>
                            <th ></th>
                            <th ></th>
                            <th ></th>
                            <th ></th>
                            <th colspan="1" style="text-align:right">TOTALES =></th>
                            <th colspan="1" style="text-align:right"></th>
                            <th colspan="1" style="text-align:right"></th>
                            <th colspan="1" style="text-align:right">@money($saldo)</th>
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
            $('[data-toggle="tooltip"]').tooltip();
            var dateNow = new Date();
            $("#start_date").datetimepicker({ sideBySide: true, locale:'es',format:'DD/MM/YYYY', defaultDate:dateNow}).parent().css("position :relative");
            $("#end_date").datetimepicker({ sideBySide: true, locale:'es',format:'DD/MM/YYYY', defaultDate:dateNow}).parent().css("position :relative");

            $('#table1').DataTable({
                language: {
                    "url":" {{ asset('assets/json/Spanish.json') }}"
                },
                responsive: {
                    details: {
                        type: 'column'
                    }
                },
                columnDefs: [ {
                    className: 'control',
                    orderable: false,
                    targets:   0
                } ,
                    { orderable: false, targets: '_all' }],
                dom: 'Bfrtip',
                "footerCallback": function ( row, data, start, end, display ) {
                    var api = this.api(), data;

                    // Remove the formatting to get integer data for summation
                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,Q,]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                    };

                    // Total monto
                    total_debito = api
                        .column( 6 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    total_credito = api
                        .column( 7 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );
                    // Update footer
                    $( api.column(6 ).footer() ).html(
                        'Q  '+number_format(total_debito,2)
                    );
                    $( api.column(7 ).footer() ).html(
                        'Q  '+number_format(total_credito,2)
                    );

                },
                buttons: [
                    {
                        extend: 'collection',
                        text: 'Exportar/Imprimir',
                        buttons: [
                            {
                                extend: 'copy',
                                text: 'Copiar',
                                footer: true,
                                title: document.title,
                                exportOptions: {
                                    columns: [1,2,3,4,5,6,7,8]
                                }
                            },
                            {
                                extend: 'excel',
                                footer: true,
                                title: document.title,
                                exportOptions: {
                                    columns: [1,2,3,4,5,6,7,8]
                                }
                            },
                            {
                                extend: 'pdf',
                                footer: true,
                                title: document.title,
                                exportOptions: {
                                    columns: [1,2,3,4,5,6,7,8]
                                }
                            },
                            {
                                extend: 'print',
                                footer: true,
                                text: 'Imprimir',
                                title: document.title,
                                exportOptions: {
                                    columns: [1,2,3,4,5,6,7,8]
                                },
                            }
                        ]
                    },
                ],

            });
        });


    </script>
@stop
