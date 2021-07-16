@extends('layouts/default')

@section('title','Proyectos')
@section('page_parent',"Proyectos")
@section('header_styles')
  {{-- date time picker --}}
  <link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet"
        type="text/css"/>
@endsection
@section('content')
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-primary">
          <div class="panel-heading clearfix">
            <h4 class="panel-title pull-left"><i class="livicon" data-name="list-ul" data-size="16" data-loop="true"
                                                 data-c="#fff" data-hc="white"></i>
              Proyectos
            </h4>
            <div class="pull-right">
              <a href="{{ URL::to('project/projects/create') }}" class="btn btn-sm btn-default"><span
                        class="glyphicon glyphicon-plus"></span> Agregar Proyecto </a>
            </div>
          </div>
          <div class="panel-body">

            @include('project.filter_list')
            <hr>
            <div class="row">
              <table class="table table-striped table-bordered" id="table1">
                <thead>
                <tr>
                  <th>No.</th>
                  <th>Código</th>
                  <th>Nombre</th>
                  <th>Cliente</th>
                  <th>Fecha</th>
                  <th>Tipo</th>
                  <th>Estado</th>
                  <th style="width: 30%">Acciones</th>
                </tr>
                </thead>
                @foreach($projects as $key => $value)
                  <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$value->code}}</td>
                    <td>{{$value->name}}</td>
                    <td>{{$value->cliente}}</td>
                    <td>{{date('d/m/Y', strtotime($value->date))}}</td>
                    <td>{{$value->type->name}}</td>
                    <td>
                      @if ($value->status==1)
                        <span class="label label-sm label-success label-mini">Activo</span>
                      @endif
                      @if ($value->status==2)
                        <span class="label label-sm label-danger label-mini">Inactivo</span>
                      @endif
                      @if ($value->status==3)
                        <span class="label label-sm label-danger label-mini">Suspendido</span>
                      @endif
                    </td>
                    <td>
                      <a class="btn btn-warning" href="{{ URL::to('project/stages_project/' . $value->id ) }}"
                         data-toggle="tooltip" data-original-title="Ver etapas e información del proyecto">
                        <span class="glyphicon glyphicon-copy"></span>&nbsp;&nbsp;Ver proyecto
                      </a>
                      @if ($value->status == 1)
                        <a class="btn btn-info" href="{{ URL::to('project/projects/' . $value->id.'/edit' ) }}"
                           data-toggle="tooltip" data-original-title="Editar">
                          <span class="glyphicon glyphicon-edit"></span>&nbsp;&nbsp;Editar
                        </a>
                        <button type="button" class="btn btn-primary btn-danger" data-toggle="modal"
                                data-target="#modal{!! $value->id !!}">
                          <span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp;
                          Eliminar
                        </button>
                        {{--Begin modal--}}
                        <div class="modal fade modal-fade-in-scale-up" tabindex="-1" id="modal{!! $value->id !!}"
                             role="dialog" aria-labelledby="modalLabelfade" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header bg-danger">
                                <h4 class="modal-title">Confirmación Eliminar</h4>
                              </div>
                              <div class="modal-body">
                                <div class="text-center">
                                  {!! $value->name !!}
                                  <br>
                                  ¿Desea eliminar projecto?
                                </div>
                              </div>
                              <div class="modal-footer" style="text-align:center;">
                                {!! Form::open(['url' => url('project/projects/' . $value->id), 'method' => 'DELETE', 'name'=>'frmDelete']) !!}
                                <button type="submit" class="btn  btn-info">Aceptar</button>
                                <button class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                                {!! Form::close() !!}
                              </div>
                            </div>
                          </div>
                        </div>
                        {{--End modal--}}
                      @else
                        @if(Session::get('administrador', false))
                          <button type="button" class="btn btn-primary btn-success" data-toggle="modal"
                                  data-target="#modal_repeat{!! $value->id !!}">
                            <span class="glyphicon glyphicon-repeat"></span>&nbsp;&nbsp;
                            Reactivar
                          </button>
                          {{--Begin modal--}}
                          <div class="modal fade modal-fade-in-scale-up" tabindex="-1"
                               id="modal_repeat{!! $value->id !!}" role="dialog" aria-labelledby="modalLabelfade"
                               aria-hidden="true">
                            <div class="modal-dialog" role="document">
                              <div class="modal-content">
                                <div class="modal-header bg-success">
                                  <h4 class="modal-title">Confirmación reactivación de proyecto</h4>
                                </div>
                                <div class="modal-body">
                                  <div class="text-center">
                                    {!! $value->name !!}
                                    <br>
                                    ¿Desea reactivar el projecto?
                                  </div>
                                </div>
                                <div class="modal-footer" style="text-align:center;">
                                  {!! Form::open(array('url' => 'project/repeat/' . $value->id, 'name'=>'frmDelete')) !!}
                                  <button type="submit" class="btn  btn-info">Aceptar</button>
                                  <button class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                                  {!! Form::close() !!}
                                </div>
                              </div>
                            </div>
                          </div>
                          {{--End modal--}}
                        @endif
                      @endif
                    </td>
                  </tr>
                @endforeach
                <tbody>
                </tbody>
              </table>
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
  <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"
          type="text/javascript"></script>
  <script type="text/javascript">
      $(document).ready(function () {
          setDataTable("table1", [], "{{ asset('assets/json/Spanish.json') }}");
          var dateNow = new Date();
          var dateBack = new Date();
          dateBack.setMonth(dateNow.getMonth() - 3);
          $("#start_date").datetimepicker({
              sideBySide: true,
              locale: 'es',
              format: 'DD/MM/YYYY',
              defaultDate: dateBack
          }).parent().css("position :relative");
          $("#end_date").datetimepicker({
              sideBySide: true,
              locale: 'es',
              format: 'DD/MM/YYYY',
              defaultDate: dateNow
          }).parent().css("position :relative");
      });

  </script>
@endsection
