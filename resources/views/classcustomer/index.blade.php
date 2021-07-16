@extends('layouts/default')

@section('title','Clasificación de clientes')
@section('page_parent',"Clasificación de clientes")

@section('content')
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <!-- <div class="panel-heading">Listado de series y documentos</div> -->
        <div class="panel-body">
          <a class="btn btn-small btn-success" href="{{ URL::to('class/create') }}">Agregar Clasificación</a>
          <hr />
          @if (Session::has('message'))
          <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
          @endif
          <table class="table table-striped table-bordered">
            <thead>
              <tr>
                <td>Id</td>
                <td>Nombre</td>
                <td>Descripción</td>
                <td>Color</td>
                <td style="width: 250px">Acciones</td>
              </tr>
            </thead>
            @foreach($classes as $value)
            <tr>
              <td>{{$value->id}}</td>
              <td>{{$value->name}}</td>
              <td>{{$value->description}}</td>
              <td style="background-color:{{$value->color}}">{{$value->color}}</td>
              <td>
                <a class="btn btn-info" href="{{ URL::to('class/edit/' . $value->id ) }}" data-toggle="tooltip" data-original-title="Editar">
                  <span class="glyphicon glyphicon-edit"></span>&nbsp;&nbsp;Editar
                </a>
                <span class="table-remove">
                        <button type="button" class="btn btn-primary btn-danger" data-toggle="modal" data-target="#modal{!! $value->id !!}">
                          <span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp;
                          Eliminar
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
                                    ¿Desea eliminar clase?
                                </div>
                            </div>
                            <div class="modal-footer" style="text-align:center;">
                                <a href="{!! url('class/delete/'.$value->id) !!}">
                                    <button class="btn  btn-info">Aceptar</button>
                                </a>
                                <button class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                            </div>
                        </div>
                    </div>
                </div>
                    {{--End modal--}}
                </span>
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
</section>
@endsection
