@extends('layouts/default')

@section('title',trans('item.inventory_cost'))
@section('page_parent',trans('item.items'))


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
                            {{trans('item.inventory_cost')}}
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
                                {!! Form::open(['url' => '/items/product_and_cellars','files'=>true,'id'=>'documentForms']) !!}
                                <div class="col-lg-4">
                                    <label for="Storage"><strong>Seleccione bodega:</strong></label>
                                </div>
                                <div class="col-lg-4">
                                    <select name="idStorage" id="idStorage" class="form-control">
                                        @foreach($dataStorage as $key => $value)
                                        @if($key==0)
                                        <option value="-1" {{ ($idStorage == -1) ?  'selected="selected"' : '' }}>Seleccione bodega</option>
                                        <option value="0" {{ ($idStorage == 0) ?  'selected="selected"' : '' }}>Todas</option>
                                        @endif
                                        <option value="{{$value->id}}" {{ ($idStorage == $value->id) ?  'selected="selected"' : '' }}>{{$value->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <button class="btn btn-primary">Generar</button>
                                </div>
                                {!! Form::close() !!}
                            </div>
                            <table class="table table-striped table-bordered display" id="table1">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>{{trans('No.')}}</th>
                                        <th class="all">{{trans('item.item_name')}}</th>
                                        <th>{{trans('item.category')}}</th>
                                        <th>{{trans('item.item_cellar')}}</th>
                                        <th>{{trans('item.quantity')}}</th>
                                        <th>{{trans('item.cost_price')}}</th>
                                        <th class="all" style="width:12%">{{trans('item.total_cost')}}</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($datosObtenidos as $i=> $value)
                                    <tr>
                                        <td></td>
                                        <td>{{$i+1}}</td>
                                        <td>{{$value->item_name}}</td>
                                        <td>{{$value->categoria}}</td>
                                        <td>{{$value->name}}</td>
                                        <td style="text-align:center">{{$value->quantity}}</td>
                                        <td style="text-align:right">@money($value->cost_price)</td>
                                        <td style="text-align:right">@money($value->subtotal)</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th style="text-align:right">Total:</th>
                                        <th style="text-align:right"></th>
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
            setDataTable("table1", [7], "{{asset('assets/json/Spanish.json')}}", null, 10, true);
        });
    </script>
    @stop
