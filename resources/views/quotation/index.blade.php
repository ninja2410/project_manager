@extends('layouts/default')

@section('title',trans('quotation.quotes'))
@section('page_parent',trans('quotation.quotation'))

@section('header_styles')
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css') }}" /> --}}
    <link href="{{ asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/pages/tables.css') }}"/>
@stop
@section('content')
    <section class="content">
        <div class="row">
            <input type="hidden" id="load_sale_url">
            <div class="panel panel-primary">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"><i class="livicon" data-name="list-ul" data-size="16"
                                                         data-loop="true" data-c="#fff" data-hc="white"></i>
                        {{trans('quotation.quotes')}}
                    </h4>
                    <div class="pull-right">
                        <a href="{{ URL::to('quotation/header/create') }}" class="btn btn-sm btn-default"><span
                                    class="glyphicon glyphicon-plus"></span> {{trans('quotation.create')}} </a>
                    </div>
                </div>

                <div class="panel-body table-responsive">
                    
                    <div class="row">
                        {!! Form::open(array('method'=>'get','url'=>'quotation/header')) !!}
                        <div class="row">
                            <div class="col-md-1">
                            </div>
                            <div class="col-md-3">
                                <center><label for=""><b>{{trans('quotation.status')}}</b></label></center>
                                <select name="status" id="status" class="form-control">
                                    <option value="1" @if($status==1) selected @endif>Activo</option>
                                    <option value="2" @if($status==2) selected @endif>Convertida</option>
                                    <option value="3" @if($status==3) selected @endif>Inactivas</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <center><label for=""><b>Fecha inicial</b></label></center>
                                <input type="text" name="date1" id='date1' class="form-control"
                                       value="{{date('d/m/Y', strtotime($fecha1))}}">
                            </div>
                            <div class="col-md-3">
                                <center><label for=""><b>Fecha final</b></label></center>
                                <input type="text" name="date2" id='date2' class="form-control"
                                       value="{{date('d/m/Y', strtotime($fecha2))}}">
                            </div>
                            <div class="col-md-1">
                                <br> {!! Form::submit(trans('accounts.generate'), array('class' => 'btn btn-primary ')) !!}
                            </div>
                        </div>
                        <hr> {!! Form::close() !!}
                    </div>
                    <div class="table-responsive-lg table-responsive-sm table-responsive-md">
                        <table class="table table-striped table-bordered" id="table1">
                            <thead>
                            <tr>
                                <th style="width: 0%;"></th>
{{--                                <th data-priority="1001" style="width: 5%;">{{trans('No.')}}</th>--}}
                                <th data-priority="4" style="width: 5% !important;">{{trans('quotation.correlative')}}</th>
                                <th data-priority="2" style="width: 25%;">{{trans('quotation.customer')}}</th>
                                <th data-priority="3" style="width: 10%;">{{trans('quotation.date')}}</th>
                                <th data-priority="5" style="width: 5%;">{{trans('quotation.days')}}</th>
                                <th data-priority="1" style="width: 5%;">{{trans('quotation.status')}}</th>
                                <th data-priority="6" style="width: 15%;">{{trans('quotation.amount')}}</th>
                                <th data-priority="7" style="width: 10%;">{{trans('quotation.invoiced')}}</th>
                                <th style="width: 25%;">{{trans('customer.actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($quotations as $i=> $value)
                                <tr>
                                    <td></td>
{{--                                    <td>{{ $i+1 }}</td>--}}
                                    <td>{{$value->correlative}}</td>
                                    <td style="font-size:12px;">{{$value->customer->name}}</td>
                                    <td style="font-size:12px">{{date('d/m/Y', strtotime($value->date))}}</td>
                                    <td>{{$value->days}}</td>
                                    <td>
                                        @if ($value->status==1)
                                            <span class="label label-success">Activo</span>
                                        @elseif ($value->status==2)
                                            <span class="label label-info">Convertido</span>
                                        @else
                                            <span class="label label-danger">Desactivada</span>
                                        @endif
                                    </td>
                                    <td style="text-align:right;">@money($value->amount)</td>
                                    <td>
                                        @if (isset($value->sale_id))
                                            <a data-toggle="tooltip" target="_blank" data-original-title="{{trans('quotation.see_sale')}}" href="{{url('sales/complete/'.$value->sale->id)}}">{{$value->sale->serie->document->name.' '.$value->sale->serie->name.'-'.$value->sale->correlative}}</a>
                                        @else
                                            {{trans('quotation.n/f')}}
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-success" style="width: 40px" data-toggle="tooltip"
                                           data-original-title="Ver detalles"
                                           href="{{URL::to('quotation/header/'.$value->id)}}">
                                            <span class="glyphicon glyphicon-file"></span>
                                        </a>
                                        @if ($value->status==1)
                                            <a class="btn btn-info" style="width: 40px" data-toggle="tooltip"
                                               data-original-title="{{trans('quotation.edit')}}"
                                               href="{{URL::to('quotation/header/'.$value->id.'/edit')}}">
                                                <span class="glyphicon glyphicon-edit"></span>
                                            </a>
                                        @endif

                                        @if ($value->status<3)
                                            <a class="btn btn-default" style="width: 40px" data-toggle="tooltip"
                                               data-original-title="{{trans('quotation.duplicate')}}"
                                               href="{{URL::to('quotation/header/duplicate/'.$value->id.'/true')}}">
                                                <span class="glyphicon glyphicon-copy"></span>
                                            </a>
                                        @endif
                                        @if ($value->status==1)
                                            <a class="btn btn-warning" style="width: 40px"
                                               toaction="{{ URL::to('quotation/load_sale/' . $value->id) }}"
                                               data-toggle="tooltip" data-original-title="{{trans('quotation.tosale')}}" onclick="showModal(this)">
                                                <span class="glyphicon glyphicon-export"></span>
                                            </a>
                                            <button type="button" style="width: 40px" class="btn btn-danger" data-toggle="modal" data-target="#modal{!! $value->id !!}">
                                                <span class="glyphicon glyphicon-remove-circle"></span>
                                            </button>
                                            {{--Begin modal--}}
                                            <div class="modal fade modal-fade-in-scale-up" tabindex="-1" id="modal{!! $value->id !!}" role="dialog" aria-labelledby="modalLabelfade" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-danger">
                                                            <h4 class="modal-title">Confirmación Eliminar</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="text-center">
                                                                {!! $value->name !!}
                                                                <br>
                                                                ¿Desea marcar la cotización como inactiva?
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer" style="text-align:center;">
                                                            {!! Form::open(array('url' => 'quotation/header/' . $value->id, 'class' => 'pull-right')) !!}
                                                            {!! Form::hidden('_method', 'DELETE') !!}
                                                            <button type="submit" class="btn  btn-info">Aceptar</button>
                                                            <button class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                                                            {!! Form::close() !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{--End modal--}}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @include('quotation.load_sale')
        </div>
    </section>
@endsection
@section('footer_scripts')

    {{-- <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/datatables.min.js') }}"></script> --}}
    {{-- Calendario --}}
    <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>

    <script type="text/javascript">
        var load_sale_url;
        $(document).ready(function () {
            setDataTable("table1", [], "{{ asset('assets/json/Spanish.json') }}");
        });

        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
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
        });
        function showModal(control){
            load_sale_url = $(control).attr('toaction');
            $('#id_bodega').val('');
            $('#load_sale_modal').modal('show');
        }
        function setCellar(){
            if ($('#id_bodega').val()==''){
                toastr.error("Debe seleccionar una bodega para continuar");
                $('#id_bodega').focus();
                return;
            }
            showLoading("Configurando cotización, espere un momento.");
            window.location = load_sale_url+'/'+$('#id_bodega').val();
        }
    </script>
@stop
