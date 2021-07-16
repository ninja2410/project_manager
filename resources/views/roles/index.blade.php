@extends('layouts/default')

@section('title',trans('users.roles_list'))
@section('page_parent',trans('Accesos'))

@section('header_styles')
 
@stop
@section('content')
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-primary">
        <div class="panel-heading clearfix">
          <h4 class="panel-title pull-left"> <i class="livicon" data-name="list-ul" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
            Roles
          </h4>
          <div class="pull-right">
            <a href="{{ URL::to('roles/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> Agregar rol </a>
          </div>
        </div>				
        <div class="panel-body">        
          <hr />
          {{--  --}}
          <table class="table table-striped table-bordered" id="table1">
            {{-- <table class=" table table-bordered table-striped table-hover datatable"> --}}
            <thead>
              <tr>
                <th></th>
                <td data-priority="1001">Id</td>
                <td data-priority="2">Nombre</td>
                <td data-priority="3">Permisos</td>
                <td data-priority="4">Admin?</td>
                <td data-priority="1000" style="width:10%;">Acciones</td>
              </tr>
            </thead>
            <tbody>
              @foreach($roles as $value)
              <tr>
                <td></td>
                <td>{{$value->id}}</td>
                <td>{{$value->role }}</td>
                <td>
                  @foreach($value->permissions as $key => $item)
                  <span class="badge badge-info">{{ $item->descripcion }}</span>
                  @endforeach
                </td>
                <td>@if($value->admin==1) Si @else No @endif</td>
                <td>
                  <a class="btn btn-info" style="width:40px"  href="{{ URL::to('roles/' . $value->id . '/edit') }}" data-toggle="tooltip" data-original-title="Editar">
                    <span class="glyphicon glyphicon-edit"></span>
                  </a>                  
                  {!! Form::open(array('url' => 'roles/' . $value->id, 'class' => 'pull-right')) !!}
                  {!! Form::hidden('_method', 'DELETE') !!}
                  <button type="submit" style="width: 40px" class="btn btn-primary btn-danger" type="submit"  title="Borrar" data-toggle="tooltip" data-original-title="trans('customer.delete')">
                    <span class="glyphicon glyphicon-remove-circle"></span>
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
  
  
  
  <script type="text/javascript">
    $(document).ready(function(){
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
            title: '{{ trans('users.roles_list') }}',
            exportOptions:{
               columns: 'th:not(:last-child)'
            }
          },        
          {
            extend:'excel',
            text: 'Excel',
            title: '{{ trans('users.roles_list') }}',
            exportOptions:{
               columns: 'th:not(:last-child)'
            }
          },
          {
            extend:'pdf',
            text: 'Pdf',
            title: '{{ trans('users.roles_list') }}',
            exportOptions:{
               columns: 'th:not(:last-child)'
            }
          },
          {
            extend:'print',
            text: 'Imprimir',
            title: '{{ trans('users.roles_list') }}',
            exportOptions:{
               columns: 'th:not(:last-child)'
            }
          }
          ], 
        }
        ],
      }) ;
    });
    
    
  </script>
  @stop
  