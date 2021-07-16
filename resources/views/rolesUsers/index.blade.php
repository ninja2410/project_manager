@extends('layouts/default')

@section('title',trans('general.roles_and_users'))
@section('page_parent',trans('Accesos'))


@section('content')
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                        {{trans('general.roles_and_users')}}
                    </h3>
                    <span class="pull-right clickable">
                        <i class="glyphicon glyphicon-chevron-up"></i>
                    </span>
                </div>
                <div class="panel-body">
                    <hr />
                    
                    
                    <table class="table table-striped table-bordered" id="table1">
                        <thead>
                            <tr>
                                <th></th>
                                <th style="width:5%;">{{trans('employee.person_id')}}</th>
                                <th>{{trans('employee.name')}}</th>
                                <th>{{trans('Rol de usuario')}}</th>
                                <th style="width:15%;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employee as $value)
                            <tr>
                                <td></td>
                                <td>{{ $value->id }}</td>
                                <td>{{ $value->name }}</td>
                                <td>{{ DB::table('user_roles')->join('roles','user_roles.role_id','=','roles.id')->where('user_roles.user_id','=',$value->id)->value('roles.role') }}</td>
                                <td>
                                    <a class="btn btn-small btn-primary" href="{{ URL::to('user_role/' . $value->id . '/edit') }}">{{trans('Agregar Rol o actualizar rol')}}</a>
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
    });
</script>
@endsection
