@extends('layouts/default')

@section('title',trans('employee.list_employees'))
@section('page_parent',trans('user.access'))


@section('content')
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-primary">
        <div class="panel-heading clearfix">
          <h4 class="panel-title pull-left"> <i class="livicon" data-name="list-ul" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
            {{trans('employee.list_employees')}}
          </h4>
          <div class="pull-right">
            <a href="{{ URL::to('employees/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> {{trans('employee.new_employee')}}</a>
          </div>
        </div>
        <div class="panel-body">
          <hr />
          {{--  --}}
          
          <table class="table table-striped table-bordered" id="table1">
            <thead>
              <tr>
                <th></th>
                <th style="text-align: center;width: 5%">No.</th>
                <th style="width: 25%">{{trans('employee.name')}}</th>
                <th>{{trans('employee.mobile')}}</th>
                <th>{{trans('employee.email')}}</th>
                <th>{{trans('Estado')}}</th>
                <th>{{trans('Roles')}}</th>
                <th style="width: 14%">Acciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach($employee as $index => $value)
              <tr>
                <td></td>
                <td style="text-align: center;">{{ $index+1 }}</td>
                <td>{{ $value->name.' '.$value->last_name }}</td>
                <td>{{ $value->mobile }}</td>
                <td>{{ $value->email }}</td>
                <td>{{ DB::table('state_cellars')->where('id', $value->user_state)->value('state_cellars.name') }}</td>
                <td>
                  @foreach($value->roles as $key => $item)
									<span class="badge badge-info">{{ $item->role }}</span>
									@endforeach
                </td>
                <td>
                  <a class="btn btn-success" style="width: 40px" data-toggle="tooltip" data-original-title="Ver perfil" href="{{ URL::to('employees/'.$value->id) }}">
                    <span class="glyphicon glyphicon-user"></span>
                  </a>               
                  <a class="btn btn-info" style="width: 40px"  href="{{ URL::to('employees/' . $value->id . '/edit') }}" data-toggle="tooltip" data-original-title="Editar">
                    <span class="glyphicon glyphicon-edit"></span>
                  </a>
                  {!! Form::open(array('url' => 'employees/' . $value->id, 'class' => 'pull-right')) !!}
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
