@extends('layouts/default')

@section('title',trans('inventory_closing.show').' '.$header->almacen->name.' '."De ".trans('months.'.$header->l_month).'/'.$header->l_year .' a '. trans('months.'.$header->month).'/'.$header->year)
@section('page_parent',trans('inventory_closing.inventory_closing'))


@section('header_styles')
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/colReorder.bootstrap.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/rowReorder.bootstrap.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/scroller.bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/pages/tables.css') }}" /> --}}
@stop

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="livicon" data-name="linechart" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                                {{trans('inventory_closing.show').' '.$header->almacen->name.' '."De ".trans('months.'.$header->l_month).'/'.$header->l_year}} a {{trans('months.'.$header->month).'/'.$header->year}}
                            </h3>
                            <span class="pull-right clickable">
                            <i class="glyphicon glyphicon-chevron-up"></i>
                        </span>
                        </div>
                        <div class="panel-body">
                            @if (Session::has('message'))
                                <div class="alert alert-info">{{ Session::get('message') }}</div>
                            @endif
                            <div class="row">
                                <center>
                                <h3>{{trans('inventory_closing.show').' '.$header->almacen->name.' '."De ".trans('months.'.$header->l_month).'/'.$header->l_year}} a {{trans('months.'.$header->month).'/'.$header->year}}</h3>
                                </center>
                            </div>
                                <hr>
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        {!! Form::label('upc_ean_isbn', trans('inventory_closing.date')) !!}
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="livicon" data-name="calendar" data-size="16" data-c="#555555"
                                                   data-hc="#555555"
                                                   data-loop="true"></i>
                                            </div>
                                            <input type="text" name="date" value="{{date('d/m/Y', strtotime($header->date))}}" readonly id="date"
                                                   class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        {!! Form::label('upc_ean_isbn', trans('inventory_closing.correlative')) !!}
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="livicon" data-name="circle" data-size="16" data-c="#555555"
                                                   data-hc="#555555"
                                                   data-loop="true"></i>
                                            </div>
                                            <input type="text" name="date" value="{{$header->correlative}}" readonly id="date"
                                                   class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        {!! Form::label('upc_ean_isbn', trans('inventory_closing.cellar')) !!}
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="livicon" data-name="truck" data-size="16" data-c="#555555"
                                                   data-hc="#555555"
                                                   data-loop="true"></i>
                                            </div>
                                            <input type="text" value="{{$header->almacen->name}}" readonly
                                                   class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        {!! Form::label('upc_ean_isbn', trans('inventory_closing.user')) !!}
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="livicon" data-name="user" data-size="16" data-c="#555555"
                                                   data-hc="#555555"
                                                   data-loop="true"></i>
                                            </div>
                                            <input type="text" value="{{$header->user->name.' '.$header->user->last_name}}" readonly
                                                   class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        {!! Form::label('upc_ean_isbn', trans('inventory_closing.month')) !!}
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="livicon" data-name="calendar" data-size="16" data-c="#555555"
                                                   data-hc="#555555"
                                                   data-loop="true"></i>
                                            </div>
                                            <input type="text" value="De {{trans('months.'.$header->l_month).'/'.$header->l_year}} a {{trans('months.'.$header->month).'/'.$header->year}}" readonly
                                                   class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        {!! Form::label('upc_ean_isbn', trans('inventory_closing.amount'))!!}
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="livicon" data-name="money" data-size="16" data-c="#555555"
                                                   data-hc="#555555"
                                                   data-loop="true"></i>
                                            </div>
                                            <input type="text" value="Q {{number_format($header->amount, 2)}}" readonly
                                                   class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        {!! Form::label('upc_ean_isbn', trans('inventory_closing.total_quantity'))!!}
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="livicon" data-name="wide-screen" data-size="16" data-c="#555555"
                                                   data-hc="#555555"
                                                   data-loop="true"></i>
                                            </div>
                                            <input type="text" value="{{$header->total_quantity}}" readonly
                                                   class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        {!! Form::label('upc_ean_isbn', trans('inventory_closing.created_at'))!!}
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="livicon" data-name="calendar" data-size="16" data-c="#555555"
                                                   data-hc="#555555"
                                                   data-loop="true"></i>
                                            </div>
                                            <input type="text" value="{{date('d/m/Y', strtotime($header->created_at))}}" readonly
                                                   class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        {!! Form::label('upc_ean_isbn', trans('inventory_closing.comment'))!!}
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="livicon" data-name="list" data-size="16" data-c="#555555"
                                                   data-hc="#555555"
                                                   data-loop="true"></i>
                                            </div>
                                            <input type="text" value="{{$header->comment}}" readonly
                                                   class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body table-responsive">
                                <table class="table table-striped table-bordered display" id="table1">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>{{trans('No.')}}</th>
                                        <th class="all">{{trans('item.item_name')}}</th>
                                        <th>{{trans('item.category')}}</th>
                                        <th>{{trans('item.quantity')}}</th>
                                        <th>{{trans('item.cost_price')}}</th>
                                        <th class="all">{{trans('item.total_cost')}}</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($details as $i=> $value)
                                        <tr>
                                            <td></td>
                                            <td>{{$i+1}}</td>
                                            <td>{{$value->item->item_name}}</td>
                                            <td>{{$value->item->itemCategory->name}}</td>
                                            <td style="text-align:center">{{$value->quantity}}</td>
                                            <td style="text-align:right">Q <?php echo number_format($value->cost,2); ?></td>
                                            <td style="text-align:right">Q <?php echo number_format(($value->quantity*$value->cost),2); ?></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th></th>
                                        <th colspan="3" style="text-align:right">Total:</th>
                                        <th></th>
                                        <th style="text-align: right;">Total</th>
                                        <th></th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="row" style="text-align: center">
                                <a class="btn btn-danger" href="{{ URL::to('inventory_closing') }}" data-toggle="tooltip" data-original-title="Regresar">
                                    <span class="glyphicon glyphicon-cancel"></span>&nbsp;&nbsp;Regresar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
@endsection
@section('footer_scripts')

    <script type="text/javascript">
        $(document).ready(function(){
            $('#table1').DataTable({
                "footerCallback": function ( row, data, start, end, display ) {
                    var api = this.api(), data;

                    // Remove the formatting to get integer data for summation
                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,Q,]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                    };

                    // Total over all pages
                    total = api
                        .column( 6 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    // Total over all pages
                    totalQty = api
                        .column( 4 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    // Total over this page
                    pageTotal = api
                        .column( 6, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );
                    pageTotalQty = api
                        .column( 4, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    // Update footer
                    $( api.column( 6 ).footer() ).html(
                        'Q  '+number_format(total,2) +'/(Q '+ number_format(pageTotal,2)+')'
                    );
                    $( api.column( 4 ).footer() ).html(
                        number_format(pageTotalQty,2) +'/('+ number_format(totalQty,2)+')'
                    );
                },
                language: {
                    "url":" {{ asset('assets/json/Spanish.json') }}"
                },
                "pageLength": 20,
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
                                footer:true,
                                title: document.title,
                                exportOptions:{
                                    columns: ':visible'
                                }
                            },
                            {
                                extend:'excel',
                                footer:true,
                                title: document.title,
                                exportOptions:{
                                    columns: ':visible'
                                }
                            },
                            {
                                extend:'pdf',
                                footer:true,
                                title: document.title,
                                exportOptions:{
                                    columns: ':visible'
                                }
                            },
                            {
                                extend:'print',
                                footer:true,
                                text: 'Imprimir',
                                title: document.title,
                                exportOptions:{
                                    columns: ':visible'
                                },
                            }
                        ]
                    },
                ],
            }) ;
        });
    </script>
@stop
