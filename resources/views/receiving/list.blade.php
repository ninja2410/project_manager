@extends('layouts/default')

@section('title',trans('receiving.list_cancel_bill_receivings'))
@section('page_parent',trans('receiving.receivings'))

@section('header_styles')
    {{-- date time picker --}}
    <link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet"
          type="text/css"/>

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/pages/tables.css') }}"/>
@stop
@section('content')
    <section class="content">
        <div class="row">
            <div class="panel panel-primary">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"><i class="livicon" data-name="list-ul" data-size="16"
                                                         data-loop="true" data-c="#fff" data-hc="white"></i>
                        {{trans('receiving.list_cancel_bill_receivings')}}
                    </h4>
                    <div class="pull-right">
                        <a href="{{ URL::to('receivings/create') }}" class="btn btn-sm btn-default"><span
                                    class="glyphicon glyphicon-plus"></span> {{trans('receiving.item_receiving')}} </a>
                    </div>
                </div>

                <div class="panel-body table-responsive">
                    
                    {!! Form::open(array('url'=>'receivings','method'=>'get')) !!}
                    @include('partials.receivings_filter')
                    {!! Form::close() !!}

                    <table class="table table-striped table-bordered compact" id="table1">
                        <thead>
                        <tr>
                            <th></th>
                            <th data-priority="2" style="width: 14%;">{{trans('receiving.number_doc')}}</th>
                            <th data-priority="3" style="width: 20%;">{{trans('receiving.supplier')}}</th>
                            <th data-priority="4">{{trans('receiving.date')}}</th>
                            <th data-priority="4">{{trans('receiving.payment_type')}}</th>
                            <th data-priority="4">{{trans('receiving.status')}}</th>
                            <th data-priority="5">{{trans('receiving.amount')}}</th>
                            <th data-priority="6">{{trans('receiving.balance')}}</th>
                            <th style="width: 8%;">{{trans('receiving.actions')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($receiving as $i=> $value)
                            <tr>
                                <td></td>
                                <td><a href="{{route('completereceivings',$value->id)}}" data-toggle="tooltip"
                                       data-original-title="Ir a compra">{{$value->document_and_correlative}}</a></td>
                                <td><a href="{{URL::to('suppliers/'.$value->supplier_id)}}" data-toggle="tooltip"
                                       data-original-title="Ir a proveedor">{{ strtoupper($value->supplier->company_name) }}</a>
                                </td>
                                <td>{{ $value->date }}</td>
                                <td>
                                    @if($value->id_pago==6)
                                        <a href="{{ URL::to('credit_suppliers/statement/invoice/' . $value->id ) }}"
                                           data-toggle="tooltip"
                                           data-original-title="Ver estado de cuenta">{{$value->pago}}</a>
                                    @else
                                        <a href="{{ URL::to('banks/expenses_accounts/' . $value->expenses->id ) }}" data-toggle="tooltip" data-original-title="Ir a transacciȯn">{{$value->pago}}</a>
                                    @endif
                                </td>
                                <td>
                                    @if( $value->cancel_bill==1)
                                        <span class="label label-sm label-danger">{!! 'Anulada' !!}</span>
                                    @else
                                        <span class="label label-sm label-success">{!! 'Activa' !!}</span>
                                    @endif
                                </td>
                                <td style="text-align:right">@money($value->total_cost)</td>
                                <td style="text-align:right">@money($value->total_cost-$value->total_paid)</td>
                                <td>
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-default dropdown-toggle"
                                                data-toggle="dropdown" data-toggle-position="left"
                                                aria-expanded="false">
                                            <i class="fa fa-ellipsis-h"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li>
                                                <a
                                                   href="{{route('completereceivings',$value->id)}}"
                                                   data-toggle="tooltip" data-original-title="Detalles">Detalles {{$value->document_and_correlative}}
                                                </a>
                                                @if ($value->cancel_bill ==0)
                                                    <a onclick="clickBtn(this);" id="{{$value->id}}"
                                                       data-toggle="modal" data-href="#ajax-modal" href="#ajax-modal"> Anular {{$value->document_and_correlative}}
                                                    </a>
                                                @endif
                                            </li>
                                        </ul>
                                    </div>
                                    {{-- <div class="input-group-btn">
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" data-toggle-position="left" aria-expanded="false">
                                            <i class="fa fa-ellipsis-h"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li>
                                                <a href="#">{{trans('sale.void').' '.$value->document_and_correlative}}</a>
                                            </li>
                                        </ul>
                                    </div> --}}
                                </td>

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
                            <th style="text-align:right">Total:</th>
                            <th style="text-align:right"></th>
                            <th style="text-align: right;"></th>
                            <th></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        {{--Begin modal anulación--}}
        <div class="modal fade in" id="ajax-modal" tabindex="-1" role="dialog" aria-hidden="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h4 class="modal-title">Anulación</h4>
                    </div>
                    <div class="modal-body">
                        <div class="text-center">
                            <h3>¿Seguro que desea anular la factura de compra?</h3>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <!-- <input type="button"  id="btnPrint" value="Si" class="btn btn-danger" /> -->
                        {!! Form::open(array('url' => 'cancel_bill_receivings/anular','method' => 'get', 'class' => 'form-horizontal','id'=>'id_form_bodega')) !!}
                        <input type="hidden" name="id_elemento" value="0" id="elemento_a_borrar">
                        <input type="button" data-dismiss="modal" value ="No" class="btn btn-danger">
                        <!-- <a href="" id="anular_factura"class="btn btn-danger">Si</a> -->
                        <input type="submit" name="" value="Si" id="anular_factura" class="btn btn-success">
                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
        {{-- Fin modal anulación --}}
        </div>
    </section>
@endsection
@section('footer_scripts')
    <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"
            type="text/javascript"></script>


    <script type="text/javascript">
       
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
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
            // var fecha= new Date();
            // document.getElementById('date_tx').value=get_date_today();

            setDataTable("table1", [6,7], "{{asset('assets/json/Spanish.json')}}");
        });
        function clickBtn(button)
        {
            var elemento_a_borrar=document.getElementById('elemento_a_borrar');
            elemento_a_borrar.value=button.id;
        }
        $('#anular_factura').click(function () {
            $('#ajax-modal').modal('hide');
            showLoading("Procesando anulación... por favor espere");
        });
    </script>
@stop
