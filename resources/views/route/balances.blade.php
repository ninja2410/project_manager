@extends('layouts/default')

@section('title','Arqueos de ruta')
@section('page_parent',"Arqueos")
@section('header_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/colReorder.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/rowReorder.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/scroller.bootstrap.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/pages/tables.css') }}" />
@stop
@section('content')
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <!-- <div class="panel-heading">Listado de series y documentos</div> -->
        <div class="panel-body">
          {{-- <a class="btn btn-small btn-success" href="{{ URL::to('series/create') }}">Agregar serie a documento</a> --}}
          @if (Session::has('message'))
          <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
          @endif
          <hr>
          <table class="table table-striped table-bordered" id="table1">
            <thead>
              <tr>
                <td>Ruta</td>
                <td>Fecha</td>
                <td>Encargardo de Revisión</td>
                <td>Total de Cobros</td>
                <td>Efectivo</td>
                <td>Descripción</td>
                <td style="width: 150px">Acciones</td>
              </tr>
            </thead>
            @foreach($bal as $value)
            <tr>
              <td>{{$value->Ruta}}</td>
              <td>{{date('d/m/Y', strtotime($value->date))}}</td>
              <td>{{$value->Revisado}}</td>
              <td>Q {{number_format($value->PAGOS,2)}}</td>
              <td>Q {{number_format($value->cash,2)}}</td>
              <td>{{$value->description}}</td>
              <td>
                <a class="btn btn-info" href="{{ URL::to('route-balances-show/' . $value->id ) }}" data-toggle="tooltip" data-original-title="Editar">
                  <span class="glyphicon glyphicon-list-alt"></span>&nbsp;&nbsp;Ver resumen
                </a>
              </td>
            </tr>
            @endforeach
            <tbody>
            </tbody>
          </table>
          <div class="row" style="text-align:center">
            <a href="{!! url('routes') !!}">
                <button class="btn  btn-danger">Cancelar</button>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
@section('footer_scripts')
  <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.buttons.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.responsive.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/buttons.colVis.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/buttons.html5.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/buttons.print.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/buttons.bootstrap.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/buttons.print.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/pdfmake.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/vfs_fonts.js') }}"></script>
  <script type="text/javascript">
  $(document).ready(function() {
    $('#table1').DataTable({
      dom: 'Bfrtip',
      buttons: [{
          extend: 'copy',
          title: 'Listado de balances de ruta',
          exportOptions: {
            columns: [0, 1, 2, 3]
          }
        },
        {
          extend: 'csv',
          title: 'Listado de balances de ruta',
          exportOptions: {
            columns: [0, 1, 2, 3]
          }
        },
        {
          extend: 'excel',
          title: 'Listado de balances de ruta',
          exportOptions: {
            columns: [0, 1, 2, 3]
          }
        },
        {
          extend: 'pdf',
          title: 'Listado de balances de ruta',
          exportOptions: {
            columns: [0, 1, 2, 3]
          }
        },
        {
          extend: 'print',
          title: 'Listado de balances de ruta',
          exportOptions: {
            columns: [0, 1, 2, 3]
          }
        }
      ],
    });
  });
  </script>
@endsection
