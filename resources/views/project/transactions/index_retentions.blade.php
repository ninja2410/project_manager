@extends('layouts/default')
@section('title',trans('project.retentions'))
@section('page_parent',trans('project.project'))

@section('header_styles')
    {{-- date time picker --}}
    <link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css"/>
@stop
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading clearfix">
                        <h4 class="panel-title pull-left"><i class="livicon" data-name="list-ul" data-size="16"
                                                             data-loop="true" data-c="#fff" data-hc="white"></i>
                            {{trans('project.retentions')}} | {{$project->name}}
                        </h4>
                        <div class="pull-right">
                            <a href="{{ URL::to('project/stages_project/'.$project->id) }}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-arrow-left"></span> {{trans('project.return')}} </a>
{{--                            <a href="{{ URL::to('project/expenses/create/'.$account->id) }}"--}}
{{--                               class="btn btn-sm btn-default"><span--}}
{{--                                        class="glyphicon glyphicon-plus"></span> {{trans('Nuevo gasto de proyecto')}}--}}
{{--                            </a>--}}
                        </div>
                    </div>
                    <div class="panel-body">
                        <hr/> 
                        {!! Form::open(array('url'=>'project/retentions/'.$project->id,'method'=>'get')) !!}
                        @include('partials.filter_retentions')
                        {!! Form::close() !!}

                        <table class="table table-striped table-bordered" id="table1">
                            <thead>
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th>Fecha</th>
                                <th>Ingreso origen</th>
                                <th>Retención</th>
                                <th>Monto Calculado</th>
                                <th>Monto Ingresado</th>
                                <th>Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($retentions as $i => $value)
                                <tr>
                                    <td>{{$i+1}}</td>
                                    <td>{{$value->date}}</td>
                                    <td>
                                        @if(isset($value->revenue_origin_id))
                                            {{$value->revenue_origin->description}} <a href="{{URL::to('banks/revenues/' . $value->revenue_origin->id )}}" target="_blank"><span class="label label-sm label-success label-mini">Ver Detalles</span></a>
                                        @endif
                                    </td>
                                    <td>{{$value->retention->name}}</td>
                                    <td>@money($value->calculated_value)</td>
                                    <td>@money($value->real_value)</td>
                                    <td>
                                        <div class="input-group-btn">
                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" data-toggle-position="left" aria-expanded="false">
                                                {{-- <span class="caret"></span> --}}
                                                <i class="fa fa-ellipsis-h"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                <li>
                                                    <a class="btn btn-warning" href="{{ URL::to('banks/revenues/' . $value->revenue_id ) }}" data-toggle="tooltip" data-original-title="Detalles">
                                                        <span class="glyphicon glyphicon-edit"></span>&nbsp;&nbsp;Detalles
                                                    </a>
                                                </li>
                                                {{--                                                <li>--}}
                                                {{--                                                    <a class="btn btn-info" href="#" data-toggle="tooltip" data-original-title="Conciliar">--}}
                                                {{--                                                        <span class="glyphicon glyphicon-edit"></span>&nbsp;&nbsp;{{trans('revenues.conciliate').' '.$value->id}}--}}
                                                {{--                                                    </a>--}}
                                                {{--                                                </li>--}}
                                                {{-- <li class="divider"></li> --}}
                                                {{-- <li>
                                                  <a href="#">
                                                    Separated link
                                                  </a>
                                                </li> --}}
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th style="text-align:right">Total:</th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                            </tfoot>
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
    <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"
            type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function () {
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

            setDataTable("table1", [4,5], "{{ asset('assets/json/Spanish.json') }}");
        });
    </script>
@stop
