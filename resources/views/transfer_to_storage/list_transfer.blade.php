@extends('layouts/default')
@section('title',trans('Traslados de bodega'))
@section('page_parent',trans('Bodegas'))
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
                        <h4 class="panel-title pull-left"><i class="livicon" data-name="list-ul" data-size="16"
                                                             data-loop="true" data-c="#fff" data-hc="white"></i>
                            Traslados de bodega
                        </h4>
                        <div class="pull-right">
                            <a href="{{ URL::to('transfer_to_storage/create') }}" class="btn btn-sm btn-default"><span
                                        class="glyphicon glyphicon-plus"></span> Nuevo traslado </a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <input type="hidden" name="path" id="path" value="{{ url('/') }}">
                        
                        <div class="row">
                            <div class="col-md-1"></div>
                            {!! Form::open(array('url'=>'transfer_to_storage','method'=>'get')) !!}
                            <div class="col-md-3">
                                <center><label for=""><b>{{trans('report-sale.start_date')}}</b></label></center>
                                <input type="text" name="date1"  id='start_date'class="form-control" value="{{date('d/m/Y', strtotime($fecha1))}}">
                            </div>
                            <div class="col-md-3">
                                <center><label for=""><b>{{trans('report-sale.end_date')}}</b></label></center>
                                <input type="text" name="date2"  id='end_date'class="form-control" value="{{date('d/m/Y', strtotime($fecha2))}}" >
                            </div>
                            <div class="col-md-3">
                                <center><label for=""><b>Estado</b></label></center>
                                <select name="status" id="status" class="form-control">
                                    <option value="" @if(count($status)==2) selected @endif>Todos</option>
                                    @foreach($all_status as $value)
                                        <option value="{{$value->id}}"
                                        @if(count($status)==1 && $value->id == $status[0])
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
                        <hr>
                        {{-- <div class="row">
                          <div class="col-md-6"></div>
                          <div class="col-md-3"><br><label for=""><b>Nota: </b>Ordenado por fecha</label></div>
                          <div class="col-md-3">
                            <br>
                            {!! Form::submit(trans('report-sale.filter'), array('class' => 'btn btn-primary ')) !!}
                          </div>
                        </div> --}}
                        <table class="table table-striped table-bordered" id="table1">
                            <thead>
                            <th></th>
                            <th>No.</th>
                            <th>Documento</th>
                            <th>Fecha</th>
                            <th># Productos</th>
                            <th>Enviado por</th>
                            <th>Bodega origen</th>
                            <th>Bodega Destino</th>
                            <th>Estado</th>
                            <th>Costo</th>
                            <th>Acciones</th>
                            </thead>
                            <tbody>
                            @foreach($transfers as $i=> $value)
                                <tr>
                                    <td></td>
                                    <td>{{ $i+1}}</td>
                                    <td>{{$value->serie->name.'-'.$value->correlative}}</td>
                                    <td>{{$value->date}}</td>
                                    <td>{{$value->quantity_items}}</td>
                                    <td>{{$value->Created_by->name}}</td>
                                    <td>{{$value->Almacen_origin->name}}</td>
                                    <td>{{$value->Almacen_destination->name}}</td>
                                    <td>
                                        @if($value->status_id == 8)
                                            <span class="label label-sm label-warning">{{$value->status->name}}</span>
                                        @elseif($value->status_id == 9)
                                            <span class="label label-sm label-success">{{$value->status->name}}</span>
                                        @endif
                                    </td>
                                    <td>@money($value->amount)</td>
                                    <td>
                                        <a class="btn btn-small btn-warning"
                                           href="{{URL::to('/transfer_to_storage/'.$value->id)}}">{{trans('report-receiving.detail')}}</a>
                                        @if($value->status_id == 8 && $current_user != $value->created_by)
                                            <a class="btn btn-small btn-info"
                                               href="{{URL::to('/transfer_to_storage/'.$value->id.'/edit')}}">Recibir</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
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
            $('#table1').DataTable({
                language: {
                    "url": " {{ asset('assets/json/Spanish.json') }}"
                },
                dom: 'Bfrtip',
                responsive: {
                    details: {
                        type: 'column'
                    }
                },
                columnDefs: [{
                    className: 'control',
                    orderable: false,
                    targets: 0
                }],
                buttons: [
                    {
                        extend: 'collection',
                        text: 'Exportar/Imprimir',
                        buttons: [
                            {
                                extend: 'copy',
                                text: 'Copiar',
                                title: document.title,
                                exportOptions: {
                                    columns: 'th:not(:last-child)'
                                }
                            },
                            {
                                extend: 'excel',
                                title: document.title,
                                exportOptions: {
                                    columns: 'th:not(:last-child)'
                                }
                            },
                            {
                                extend: 'pdf',
                                title: document.title,
                                exportOptions: {
                                    columns: 'th:not(:last-child)'
                                }
                            },
                            {
                                extend: 'print',
                                text: 'Imprimir',
                                title: document.title,
                                exportOptions: {
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
