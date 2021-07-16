@extends('layouts/default')

@section('title',trans('report-sale.product_and_cellars'))
@section('page_parent',trans('report-sale.reports'))

@section('content')
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="linechart" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            {{trans('report-sale.product_and_cellars')}}
                        </h3>
                        <span class="pull-right clickable">
                            <i class="glyphicon glyphicon-chevron-up"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        <hr />
                        @if (Session::has('message'))
                        <div class="alert alert-info">{{ Session::get('message') }}</div>
                        @endif
                        <div class="panel-body table-responsive">
                            <div class="row">
                                {!! Form::open(['url' => '/reports/product_and_cellars_notPrice','files'=>true,'id'=>'documentForms']) !!}
                                <div class="col-lg-4">

                                </div>
                                <div class="col-lg-4">
                                    <label for="Storage"><strong>Seleccione bodega:</strong></label>
                                    <select name="idStorage" id="idStorage" class="form-control">
                                        @foreach($dataStorage as $key => $value)
                                        @if($key==0)
                                        <option value="0" {{ ($idStorage == 0) ?  'selected="selected"' : '' }}>Seleccione bodega</option>
                                        @endif
                                        <option value="{{$value->id}}" {{ ($idStorage == $value->id) ?  'selected="selected"' : '' }}>{{$value->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <br>
                                    <button class="btn btn-primary">Generar</button>
                                </div>
                                {!! Form::close() !!}
                            </div>
                            <table class="table table-striped table-bordered display" id="table1">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">{{trans('No.')}}</th>
                                        <!-- <th>{{trans('item.upc_ean_isbn')}}</th> -->
                                        <th>{{trans('item.item_name')}}</th>
                                        <!-- <th>{{trans('item.size')}}</th> -->
                                        <th>{{trans('Categoria')}}</th>
                                        <th>{{trans('item.item_cellar')}}</th>
                                        <th style="width: 12%;">Existencia</th>
                                        <!-- <th>{{trans('item.cost_price')}}</th> -->
                                        <!-- <th>{{trans('item.total_cost')}}</th> -->
                                        <!-- <th>{{trans('item.avatar')}}</th> -->

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($datosObtenidos as $i=> $value)
                                    <tr>
                                        <td>{{$i+1}}</td>
                                        <td>{{$value->item_name}}</td>
                                        <td>{{$value->categoria}}</td>
                                        <td>{{$value->name}}</td>
                                        <td style="text-align:center">{{$value->quantity}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" style="text-align:right">Total:</th>
                                        <th ></th>
                                    </tr>
                                </tfoot>
                            </table>
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

                    // Total over all pages
                    total = api
                    .column( 4 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                    // Total over this page
                    pageTotal = api
                    .column( 4, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                    // Update footer
                    $( api.column( 4 ).footer() ).html(
                    number_format(total,2) +' Unidades'
                    );
                },
                buttons: [
                    {
                        extend: 'collection',
                        text: 'Exportar/Imprimir',
                        buttons: [
                            {
                                extend: 'copy',
                                footer: true,
                                text: 'Copiar',
                                title: document.title,
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            {
                                extend: 'excel',
                                footer: true,
                                title: document.title,
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            {
                                extend: 'pdf',
                                footer: true,
                                title: document.title,
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            {
                                extend: 'print',
                                footer: true,
                                text: 'Imprimir',
                                title: document.title,
                                exportOptions: {
                                    columns: ':visible'
                                }
                            }

                        ]
                    }
                ],
                order:[],
            }) ;
        });
    </script>
    @stop
