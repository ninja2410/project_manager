@extends('layouts/default')

@section('title',trans('general.add_permission_to_rol'))
@section('page_parent',trans('Accesos'))


@section('header_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/colReorder.bootstrap.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/rowReorder.bootstrap.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/scroller.bootstrap.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/pages/tables.css') }}" />
@stop
@section('content')
<section class="content">
  <div class="row" id="permisos_div">
    <div class="col-md-12">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">
            <i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
            {{trans('general.add_permission_to_rol')}}
          </h3>
          <span class="pull-right clickable">
            <i class="glyphicon glyphicon-chevron-up"></i>
          </span>
        </div>  
        <div class="panel-body">
          {!! Html::ul($errors->all()) !!}
          {!! Form::model($dataPermisos, array('route' => array('role_permission.update', $dataRol->id), 'method' => 'PUT', 'files' => true)) !!}
          <input type="hidden" name="id_Rol" value="{{$dataRol->id}}">
          <div class="form-group">
            <h3>Rol: {{$dataRol->role}}</h3>
          </div>
          <div class="form-group">
            {!! Form::label('size', trans('Elija los permisos para este rol')) !!}
            <br>
            {{-- <table class="table table-striped table-bordered" > --}}
              <table class="table table-striped table-bordered" id="table1">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Nombre del permiso </th>					        
                  <th>Estado</th>
                </tr>
              </thead>
              <tbody>
                @foreach($dataPermisos as $i=> $value)
                <tr>
                  <td>{{$i+1}}</td>
                  <td>
                    <label for="">{{$value->descripcion}}</label>
                  </td>									
                  <td>
                    <input type="checkbox" name="permisos[]" class="form-control" value="{{$value->IdPermiso}}" {{ ($value->Valor == 1) ?  'checked="checked"' : '' }}">
                    <input type="hidden" name="permisos_" class="form-control" value="{{$value->IdPermiso}}" >
                  </td>
                  
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          @include('partials.buttons',['cancel_url'=>"/roles"])
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
@section('footer_scripts')
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>

<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}" ></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.buttons.js') }}" ></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.responsive.js') }}" ></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/buttons.colVis.js') }}" ></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/buttons.html5.js') }}" ></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/buttons.print.js') }}" ></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/buttons.bootstrap.js') }}" ></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/buttons.print.js') }}" ></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/pdfmake.js') }}" ></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/vfs_fonts.js') }}" ></script>

<script type="text/javascript">
  $(document).ready(function(){
    $('#table1').DataTable({
      dom: 'Bfrtip',
      buttons: [
      {
        extend:'copy',
        title:'Permisos de un rol'
      },
      {
        extend:'csv',
        title:'Permisos de un rol'
      },
      {
        extend:'excel',
        title:'Permisos de un rol'
      },
      {
        extend:'pdf',
        title:'Permisos de un rol'
      },
      {
        extend:'print',
        title:'Permisos de un rol'
      }
      ],
    }) ;
  });
  
</script>
@endsection
