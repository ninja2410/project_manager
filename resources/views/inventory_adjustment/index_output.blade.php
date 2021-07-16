@extends('layouts/default')

@section('title',trans('inventory_adjustment.inventory_exit'))
@section('page_parent',trans('inventory_adjustment.inventory'))

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
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"><i class="livicon" data-name="list-ul" data-size="16"
                                                         data-loop="true" data-c="#fff" data-hc="white"></i>
                        {{trans('inventory_adjustment.inventory_exit')}}
                    </h4>
                    <div class="pull-right">
                        <a href="{{ URL::to('/inventory_adjustment/output') }}" class="btn btn-sm btn-default"><span
                                    class="glyphicon glyphicon-plus"></span> Nuevo ajuste </a>
                    </div>
                </div>
                <div class="panel-body">
                    {!! Form::open(array('url'=>'/inventory_adjustment/index/output')) !!}
                    <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <center>
                                        <b>
                                            Seleccione documento
                                        </b>
                                    </center>
                                    <select name="documentos" id="documentos" class="form-control">
                                        <option value="0" @if($document==0) selected @endif>Todos</option>
                                        @foreach($listDocuments as $indice =>$value)
                                        <option value="{{$value->id}}" @if($document==$value->id) selected @endif>{{$value->name}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <center>
                                        <b>Fecha inicial</b>
                                    </center>
                                    <input type="text" name="date_1" class="form-control" id="date_1" style="text-align: right;" value="{{date('d/m/Y', strtotime($fecha1))}}">                                    
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <center>
                                        <b>Fecha final</b>
                                    </center>
                                    <input type="text" name="date_2" class="form-control" id="date_2" style="text-align: right;" value="{{date('d/m/Y', strtotime($fecha2))}}">                                    
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
                    {!!Form::close()!!}
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <table class="table table-bordered table-striped" id="table1">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>No.</th>
                                            <th>Serie</th>
                                            <th>Fecha</th>
                                            <th>Usuario</th>
                                            <th># Productos afectados</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($lista as $i => $value)
                                        <tr>
                                            <td></td>
                                            <td style="text-align:center;">{{$i+1}}</td>
                                            <td style="text-align:center;">{{$value->document_and_correlative}}</td>
                                            <td style="text-align:center;">{{date('d/m/Y',strtotime($value->date))}}</td>
                                            <td style="text-align:center;"><span class="badge badge-info">{{$value->name}}</td>
                                            <td style="text-align:center;">{{$cantidad->find($value->id)->cantidad}}</td>
                                            <td>
                                                <a href="{{ URL::to('inventory_adjustment/detail/output/'.$value->id) }}"  class="btn btn-success" title="Re-imprimir">
                                                    <span class="glyphicon glyphicon-eye-open"></span> 
                                                </a>
                                            </td>                                                
                                        </tr>
                                        @endforeach                                    
                                    </tbody>
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

        $("#date_1").datetimepicker({ sideBySide: true, locale:'es',format:'DD/MM/YYYY'}).parent().css("position :relative");
        $("#date_2").datetimepicker({ sideBySide: true, locale:'es',format:'DD/MM/YYYY'}).parent().css("position :relative");

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

{{-- <script type="text/javascript" src="{{ asset('assets/js/inventory_adjustment/reports/add.js') }}" ></script> --}}
@stop
