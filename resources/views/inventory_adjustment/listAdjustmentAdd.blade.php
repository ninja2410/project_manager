@extends('layouts/default')

@section('title',trans('report-sale.adjustment_add_report'))
@section('page_parent',trans('Bodegas'))

<!--  calendario -->

@section('header_styles')
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
                        {{trans('report-sale.adjustment_add_report')}}
                    </h3>
                    <span class="pull-right clickable">
                        <i class="glyphicon glyphicon-chevron-up"></i>
                    </span>
                </div>
                <div class="panel-body">
                    <!-- {!! Form::open(array('url'=>'/listAdjustmentAdd')) !!} -->
                    <form action="" id="formData">
                        <div class="row">
{{--                            <div class="col-md-3">--}}
{{--                                <div class="form-group">--}}
{{--                                    <center>--}}
{{--                                        <b>--}}
{{--                                            Seleccione documento--}}
{{--                                        </b>--}}
{{--                                    </center>--}}
{{--                                    <select name="documentos" id="documentos" class="form-control">--}}
{{--                                        <option value="0">Todos</option>--}}
{{--                                        @foreach($listDocuments as $indice =>$value)--}}
{{--                                        <option value="{{$value->id}}">{{$value->name}}</option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-md-3">--}}
{{--                                <div class="form-group">--}}
{{--                                    <center>--}}
{{--                                        <b>Seleccione serie</b>--}}
{{--                                    </center>--}}
{{--                                    <select name="id_serie" id="id_serie" class="form-control">--}}
{{--                                        <option value="">Todos</option>--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div class="col-lg-2"></div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <center>
                                        <b>Fecha inicial</b>
                                    </center>
                                    <input type="text" name="date_1" class="form-control" id="date_1" value="{{date('d/m/Y', strtotime($fecha1))}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <center>
                                        <b>Fecha final</b>
                                    </center>
                                    <input type="text" name="date_2" class="form-control" id="date_2" value="{{date('d/m/Y', strtotime($fecha2))}}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <center>&nbsp;</center>
                                    <div class="pull-right">
                                        <button class="btn btn-primary" id="generate">Generar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <table class="table table-bordered table-striped" id="table1">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;">No.</th>
                                            <th>Documento</th>
                                            <th>Fecha</th>
                                            <th>Usuario</th>
                                            <th>Bodega</th>
                                            <th>Costo</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($receivingsReport as $i=> $value)
                                        <tr @if($i==0) class="class_detalle_factura" @endif>
                                            <td style="text-align:center;">{{$i+1}}</td>
                                            <td style="text-align:center">{{$value->serie->document->name.' '.$value->serie->name.'-'.$value->correlative}}</td>
                                            <td style="text-align:center">{{date('d/m/Y',strtotime($value->inventory_adjustament_date))}}</td>
                                            <td style="text-align:center">{{$value->user->name}}</td>
                                            <td style="text-align:center">{{$value->almacen->name}}</td>
                                            <td style="text-align:left">@money($value->total)</td>
                                            <td>

                                                <a href="{{ URL::to('inventory_adjustment/detail/input/' . $value->id ) }}"  class="btn btn-info" title="Re-imprimir">
                                                    <span class="glyphicon glyphicon-print"></span>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th colspan="4" style="text-align: right">Total</th>
                                        <th colspan="3"></th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
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
<script>
    $(function(){
        var dateNow = new Date();
        var dateBack = new Date();
        dateBack.setMonth(dateNow.getMonth() - 3);
        $("#date_1").datetimepicker({
            sideBySide: true,
            locale: 'es',
            format: 'DD/MM/YYYY',
            defaultDate: dateBack
        }).parent().css("position :relative");
        $("#date_2").datetimepicker({
            sideBySide: true,
            locale: 'es',
            format: 'DD/MM/YYYY',
            defaultDate: dateNow
        }).parent().css("position :relative");
        setDataTable("table1", [5], "{{ asset('assets/json/Spanish.json') }}");
    });
</script>
@stop
