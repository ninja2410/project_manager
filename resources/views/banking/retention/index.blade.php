@extends('layouts/default')
@section('title','Retenciones')
@section('page_parent','Retenciones')

@section('header_styles')

@stop
@section('content')
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-primary">
          <!-- <div class="panel-heading">Listado de series y documentos</div> -->
          <div class="panel-heading clearfix">
            <h4 class="panel-title pull-left"><i class="livicon" data-name="list-ul" data-size="16" data-loop="true"
                                                 data-c="#fff" data-hc="white"></i>
              {{trans('project.retention')}}
            </h4>
            <div class="pull-right">
              <a href="{{ URL::to('banks/retention/create') }}" class="btn btn-sm btn-default"><span
                        class="glyphicon glyphicon-plus"></span> Nueva retención </a>
            </div>
          </div>

          <div class="panel-body">
            <hr/> @if (Session::has('message'))
              <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
            @endif
            <div class="row">
              <div class="col-md-1"></div>
              {!! Form::open(array('url'=>$url,'method'=>'get')) !!}
              <div class="col-md-3"></div>
              <div class="col-md-3">
                <center><label for=""><b>Estado</b></label></center>
                <select name="status" id="status" class="form-control">
                  <option value="0" @if(count($status)>1) selected @endif>Todos</option>
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

            <div class="panel-body table-responsive">
              <table class="table table-bordered" id="table1">
                <thead>
                <tr>
                  <th>No</th>
                  <th>Nombre</th>
                  <th>Porcentaje</th>
                  <th>Descripción</th>
                  <th>Estado</th>
                  <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                @foreach($retentions as $i => $value)
                  <tr>
                    <td>{{$i+1}}</td>
                    <td>{{$value->name}}</td>
                    <td>{{number_format($value->percent, 2)}}%</td>
                    <td>{{$value->description}}</td>
                    <td>
                      @if ($value->status==1)
                        <span class="label label-success">{{$value->estado->name}}</span> @else
                        <span class="label label-danger">{{$value->estado->name}}</span> @endif
                    </td>
                    <td>
                      @if ($value->status == 1)
                        <a class="btn btn-info" href="{{ URL::to('banks/retention/' . $value->id.'/edit' ) }}"
                           data-toggle="tooltip" data-original-title="Editar">
                          <span class="glyphicon glyphicon-edit"></span>
                        </a>
                        <span class="table-remove">
                      <button type="button" class="btn btn-primary btn-danger" data-toggle="modal"
                              data-target="#modal{!! $value->id !!}">
                        <span class="glyphicon glyphicon-remove"></span>
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
                                      ¿Desea eliminar retencion?
                                  </div>
                              </div>
                              <div class="modal-footer" style="text-align:center;">
                                {{-- test --}}
                                {!! Form::open(array('url' => 'banks/retention/' . $value->id, 'class' => 'pull-right')) !!} {!! Form::hidden('_method',
                                'DELETE') !!}
                                {{-- test --}}
                                <button type="submit" class="btn  btn-info">Aceptar</button>
                                <button class="btn  btn-danger" data-dismiss="modal">Cancelar</button> {!! Form::close() !!}
                              </div>
                          </div>
                      </div>
                  </div>
                      {{--End modal--}}
                  </span>
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
    </div>
  </section>
@endsection

@section('footer_scripts')
  <script type="text/javascript">
      $(document).ready(function () {
          setDataTable("table1", [], "{{ asset('assets/json/Spanish.json') }}");
      });

  </script>
@stop
