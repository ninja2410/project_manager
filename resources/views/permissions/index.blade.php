@extends('layouts/default')

@section('title',trans('general.permissions_list'))
@section('page_parent',trans('Accesos'))

@section('header_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css') }}" />
@stop

@section('content')
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-primary">
        <div class="panel-heading clearfix">
          <h4 class="panel-title pull-left"> <i class="livicon" data-name="list-ul" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
            {{trans('general.permissions_list')}}
          </h4>
          <div class="pull-right">
            <a href="{{ URL::to('permissions/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> {{trans('general.permission_add')}}</a>
          </div>
        </div>
        <div class="panel-body">
          
          <!-- <table class="table table-striped table-bordered"> -->
            <table class="table table-striped table-bordered" id="table1">
              <thead>
                <tr>
                  <td>Id</td>
                  <td>Descripcion</td>
                  <td>Ruta</td>
                  <td>Acciones</td>
                </tr>
              </thead>
              <tbody>
                @foreach($listPermission as $value)
                <tr>
                  <td>{{$value->id}}</td>
                  <td>{{$value->descripcion}}</td>
                  <td>{{$value->ruta}}</td>
                  <td>
                    <a class="btn btn-info"  href="{{ URL::to('permissions/' . $value->id . '/edit') }}" data-toggle="tooltip" data-original-title="Editar">
                      <span class="glyphicon glyphicon-edit"></span> {{trans('Editar')}}
                    </a>
                    {!! Form::open(array('url' => 'permissions/' . $value->id, 'class' => 'pull-right')) !!}
                    {!! Form::hidden('_method', 'DELETE') !!}
                    <button type="submit" class="btn btn-primary btn-danger" type="submit"  title="Borrar" data-toggle="tooltip" data-original-title="trans('customer.delete')">
                      <span class="glyphicon glyphicon-remove-circle"></span> Eliminar
                    </button>
                    {!! Form::close() !!}
                    
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
  <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/datatables.min.js') }}"></script>
  
  <script type="text/javascript">
    $(document).ready(function(){
      $('#table1').DataTable({
        language: {
          "url":" {{ asset('assets/json/Spanish.json') }}"
        },
        dom: 'Bfrtip',
        buttons: [
        {
          extend: 'collection',
          text: 'Exportar/Imprimir',
          buttons: [
          {
            extend: 'copy',
            text: 'Copiar',
            title: '{{ trans('general.permissions_list') }}',
            exportOptions:{
              columns:[0,1,2]
            }
          },          
          {
            extend:'excel',
            text: 'Excel',
            title: '{{ trans('general.permissions_list') }}',
            exportOptions:{
              columns:[0,1,2]
            }
          },
          {
            extend:'pdf',
            text: 'Pdf',
            title: '{{ trans('general.permissions_list') }}',
            exportOptions:{
              columns:[0,1,2]
            }
          },
          {
            extend:'print',            
            text: 'Imprimir',
            title: '{{ trans('general.permissions_list') }}',
            exportOptions:{
              columns:[0,1,2]
            }
          }
          ],
        }],
      }) ;
    });
  </script>
  @endsection
  