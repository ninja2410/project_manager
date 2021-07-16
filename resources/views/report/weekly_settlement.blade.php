@extends('layouts/default')

@section('title',trans('weekly_settlement.ws'))
@section('page_parent',trans('report-sale.reports'))


@section('header_styles')
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/print/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/print/css/buttons.bootstrap.min.css')}}"> --}}
    <!-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/print/css/bootstrap.min.css')}}"> -->
    <!--  calendario -->
    <link href="{{ asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />

@stop

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12 ">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="linechart" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            {{trans('weekly_settlement.ws')}}
                        </h3>
                        <span class="pull-right clickable">
            <i class="glyphicon glyphicon-chevron-up"></i>
          </span>
                    </div>
                <!-- <div class="panel-heading">{{trans('report-sale.items_quantity_sales')}}</div> -->
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-1"></div>
                            {!! Form::open(array('url'=>url('reports/inventory_week'),'method'=>'get')) !!}
                            <div class="col-md-3">
                                <center><label for=""><b>{{trans('report-sale.start_date')}}</b></label></center>
                                <input type="text" name="date1"  id='start_date' class="form-control" value="{{date('d/m/Y', strtotime($fecha1))}}">
                            </div>
                            <div class="col-md-3">
                                <center><label for=""><b>{{trans('report-sale.end_date')}}</b></label></center>
                                <input type="text" name="date2"  id='end_date' class="form-control" value="{{date('d/m/Y', strtotime($fecha2))}}" >
                            </div>
                            <div class="col-md-3">
                                <center><label for=""><b>Bodega</b></label></center>
                                <select name="bodega" id="bodega" class="form-control">
                                    @foreach($bodegas as $value)
                                        <option value="{{$value->id}}"
                                                @if($value->id == $bodega)
                                                selected
                                                @endif
                                        >{{$value->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <br>
                                {!! Form::submit(trans('report-sale.filter'), array('class' => 'btn button-block btn-primary button-large boton-ancho')) !!}
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="panel-body table-responsive">
                                <table id="example" class="table table-striped table-bordered " cellspacing="0" width="100%">
                                    <thead>
                                    <th style="width:5%;">No.</th>
                                    <th style="width:35%;">Producto</th>
                                    <th style="width:10%;">Existencia</th>
                                    <th style="width:10%;"># Ventas/Salidas</th>
                                    <th style="width:10%;">Saldo</th>
{{--                                    <th style="width:15%;">Precio U.</th>--}}
{{--                                    <th style="width:15%;">Total</th>--}}
                                    </thead>
                                    <tbody>
                                    @foreach($items as $i=>$value)
                                        <tr>
                                            <td>{{$i+1}}</td>
                                            <td>{{$value->NOMBRE}}</td>
                                            <td style="text-align:center;">{{$value->EXISTENCIAS}}</td>
                                            <td style="text-align:center;">{{$value->VENTAS}}</td>
                                            <td style="text-align:center;">{{$value->EXISTENCIAS-$value->VENTAS}}</td>
{{--                                            <td style="text-align:right;">Q{{$value->low_price}}</td>--}}
{{--                                            <td style="text-align:right;">Q{{$value->total}}</td>--}}
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
{{--                                    <tr>--}}
{{--                                        <th colspan="4" style="text-align:right">Total:</th>--}}
{{--                                        <th colspan="3"></th>--}}
{{--                                    </tr>--}}
                                    </tfoot>
                                </table>
                            </div>
                            <div class="panel-body">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('footer_scripts')

    <script>
        $(document).ready(function() {
            var table = $('#example').DataTable({
                // "footerCallback": function ( row, data, start, end, display ) {
                //     var api = this.api(), data;
                //
                //     // Remove the formatting to get integer data for summation
                //     var intVal = function ( i ) {
                //         return typeof i === 'string' ?
                //             i.replace(/[\$,Q,]/g, '')*1 :
                //             typeof i === 'number' ?
                //                 i : 0;
                //     };
                //
                //     // Total over all pages
                //     total = api
                //         .column( 6 )
                //         .data()
                //         .reduce( function (a, b) {
                //             return intVal(a) + intVal(b);
                //         }, 0 );
                //
                //     // Total over this page
                //     pageTotal = api
                //         .column( 6, { page: 'current'} )
                //         .data()
                //         .reduce( function (a, b) {
                //             return intVal(a) + intVal(b);
                //         }, 0 );
                //
                //     // Update footer
                //     $( api.column( 5 ).footer() ).html(
                //         'Q  '+number_format(total,2) +'     ( Q '+ number_format(pageTotal,2) +' página)'
                //     );
                // },
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
                                title: document.title,
                                exportOptions:{
                                    columns: ':visible'
                                }
                            },
                            {
                                extend:'excel',
                                title: document.title,
                                exportOptions:{
                                    columns: ':visible'
                                }
                            },
                            {
                                extend:'pdf',
                                title: document.title,
                                exportOptions:{
                                    columns: ':visible'
                                }
                            },
                            {
                                extend:'print',
                                text: 'Imprimir',
                                title: document.title,
                                exportOptions:{
                                    columns: ':visible'
                                },
                            }
                        ]
                    },
                ],
            });
            // table.buttons().container()
            // .appendTo('#example_wrapper .col-sm-6:eq(0)');
        });
    </script>
    <!--Canlendario  -->
    <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
    <script>
        $("#start_date").datetimepicker({ sideBySide: true, locale:'es',format:'DD/MM/YYYY'}).parent().css("position :relative");
    </script>
    <script>
        $("#end_date").datetimepicker({ sideBySide: true, locale:'es',format:'DD/MM/YYYY'}).parent().css("position :relative");
    </script>
@stop
