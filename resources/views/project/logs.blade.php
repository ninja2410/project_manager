@extends('layouts/default')

@section('title',trans('project.logs'))
@section('page_parent',trans('project.project'))

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            {{trans('project.logs')}}
                        </h3>
                        <span class="pull-right clickable">
            <i class="glyphicon glyphicon-chevron-up"></i>
          </span>
                    </div>
                    <div class="panel-body">
                        <h3>Historial de cambios</h3>
                        <hr>
                        <table class="table table-striped table-bordered" id="table1">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Fecha</th>
                                <th>Acción</th>
                                <th>Usuario</th>
                                <th>Valor anterior</th>
                                <th>Nuevo valor</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($logs as $value)
                                <tr>
                                    <td></td>
                                    <td>{{date('d/m/Y', strtotime($value->created_at))}}</td>
                                    <td>{{$value->action}}</td>
                                    <td>{{$value->user->name}}</td>
                                    <td>{{$value->oldValue}}</td>
                                    <td>{{$value->newValue}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="row">
                            <center>
                                <a href="{{url("project/stages_project/".$project_id)}}">
                                    <button type="button" class="btn btn-danger">
                                        Cancelar
                                    </button>
                                </a>
                            </center>
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
            setDataTable("table1", [], "{{asset('assets/json/Spanish.json')}}");
        });

    </script>
@stop
