@extends('layouts/default')
@section('title',trans('settlement.list').' '.$route->name)
@section('page_parent',trans('route.route'))
@section('header_styles')
    {{-- date time picker --}}
    <link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />

@stop
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading clearfix">
                        <h4 class="panel-title pull-left"> <i class="livicon" data-name="list-ul" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                            {{trans('settlement.list').' '.$route->name}}
                        </h4>
                        <div class="pull-right">
                            <a href="{{ URL::to('routes/settlement/'.$route->id.'/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> {{trans('settlement.create')}} </a>
                        </div>
                    </div>
                    <div class="panel-body">
                        
                        <hr>
                        <div class="row">
                            <div class="col-md-2"></div>
                            {!! Form::open(array('url'=>$url,'method'=>'get')) !!}
                            <div class="col-md-3">
                                <center><label for=""><b>{{trans('report-sale.start_date')}}</b></label></center>
                                <input type="text" name="date1"  id='start_date' class="form-control" value="{{date('d/m/Y', strtotime($fecha1))}}">
                            </div>
                            <div class="col-md-3">
                                <center><label for=""><b>{{trans('report-sale.end_date')}}</b></label></center>
                                <input type="text" name="date2"  id='end_date' class="form-control" value="{{date('d/m/Y', strtotime($fecha2))}}" >
                            </div>
                            <div class="col-md-2">
                                <br>
                                {!! Form::submit(trans('report-sale.filter'), array('class' => 'btn button-block btn-primary button-large boton-ancho')) !!}
                            </div>
                            <div class="col-md-2">
                                {!! Form::close() !!}
                            </div>
                        </div>
                        <hr>
                        <table class="table table-striped table-bordered" id="table1">
                            <thead>
                            <tr>
                                <th></th>
                                <th data-priority="1001" style="width: 5%;">No</th>
                                <th data-priority="2">{{trans('settlement.users')}}</th>
                                <th data-priority="6">{{trans('report-sale.start_date')}}</th>
                                <th data-priority="6">{{trans('report-sale.end_date')}}</th>
                                <th data-priority="4">{{trans('settlement.total_sales')}}</th>
                                <th data-priority="4">{{trans('settlement.total_expenses')}}</th>
                                <th data-priority="5">{{trans('settlement.total_payments')}}</th>
                                <th data-priority="3">{{trans('settlement.week')}}</th>
                                <th style="width: 12%;">Acciones</th>
                            </tr>
                            </thead>
                            @foreach($detail as $i => $value)
                                <tr>
                                    <td></td>
                                    <td>{{$i+1}}</td>
                                    <td>{{$value->user_assigned->name}}</td>
                                    <td>{{$value->date_begin}}</td>
                                    <td>{{$value->date_end}}</td>
                                    <td>@money($value->amount_sales)</td>
                                    <td>@money($value->amount_expenses)</td>
                                    <td>@money($value->amount_payments)</td>
                                    <td>{{$value->week}}</td>
                                    <td>
                                        <a class="btn btn-info" href="{{ URL::to('routes/settlement/show/' . $value->id ) }}" data-toggle="tooltip" data-original-title="Detalles">
                                            <span class="glyphicon glyphicon-eye-open"></span>
                                        </a>
                                        <button type='button' data-toggle="modal" data-target="#modalDelete{{$value->id}}" class='delete form btn btn-danger'><span class="glyphicon glyphicon-remove-circle"></span></button>
                                    </td>
                                </tr>
                                {{--Begin modal--}}
                                <div class="modal fade modal-fade-in-scale-up in" tabindex="-1" id="modalDelete{{$value->id}}" role="dialog"
                                     aria-labelledby="modalLabelfade" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger">
                                                <h4 class="modal-title">Confirmación Eliminar</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="text-center">
                                                    <p id="name_item"></p>
                                                    <br>
                                                    ¿Desea eliminar liquidación de ruta seleccionado?
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <div class="row">
                                                    <div class="col-lg-6" style="text-align: right;">
                                                        {!! Form::open(array('id' => 'frm_delete', 'url'=>url('routes/settlement/destroy/'.$value->id))) !!}
                                                        {!! Form::hidden('_method', 'DELETE') !!}
                                                        <button type="submit" class="btn btn-info" type="submit">
                                                            Eliminar
                                                        </button>
                                                        {!! Form::close() !!}
                                                    </div>
                                                    <div class="col-lg-6" style="text-align: left;">
                                                        <button class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{--End modal--}}
                            @endforeach
                            <tbody>
                            </tbody>
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
        $(document).ready(function () {
            var dateNow = new Date();
            $("#start_date").datetimepicker({ sideBySide: true, locale:'es',format:'DD/MM/YYYY', defaultDate:dateNow}).parent().css("position :relative");
            $("#end_date").datetimepicker({ sideBySide: true, locale:'es',format:'DD/MM/YYYY', defaultDate:dateNow}).parent().css("position :relative");
        });
        $('#table1').DataTable({
            language: {
                "url":" {{ asset('assets/json/Spanish.json') }}"
            },
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
        }) ;

    </script>
@stop
