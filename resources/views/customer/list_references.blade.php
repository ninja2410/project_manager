@extends('layouts/default')

@section('title','Listado de referencias')
@section('page_parent','Listado de referencias')

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
	<div class="row">
      <div class="col-md-12">
			<div class="panel panel-default">

				<div class="panel-body">
                    @if (Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                    @endif

                    <center><h2>Historial Referencias</h2></center>
                    <table class="table table-striped table-bordered" id="table1">
                        <thead>
                          <th>Fecha</th>
                          <th>Tipo de Referencia</th>
                          <th>Nombre</th>
                          <th>Dirección</th>
                          <th>Teléfono</th>
                      </thead>
                      <tbody>
                        @foreach($references as $i=> $reference)
                        <tr>
                            <td>{{date('d/m/Y', strtotime($reference->created_at))}}</td>
                            <td>Lugar de trabajo</td>
                            <td>{{$reference->work_name}}</td>
                            <td>{{$reference->work_address}}</td>
                            <td>{{$reference->work_number}}</td>
                        </tr>
                        <tr>
                          <td>{{date('d/m/Y', strtotime($reference->created_at))}}</td>
                          <td>Referencia Laboral</td>
                          <td>{{$reference->work_ref1_name}}</td>
                          <td>{{$reference->work_ref1_address}}</td>
                          <td>{{$reference->work_ref1_number}}</td>
                        </tr>
                        <tr>
                          <td>{{date('d/m/Y', strtotime($reference->created_at))}}</td>
                          <td>Referencia Personal</td>
                          <td>{{$reference->refer1_name}}</td>
                          <td>{{$reference->refer1_address}}</td>
                          <td>{{$reference->refer1_phone}}</td>
                        </tr>
                         <tr>
                           <td>{{date('d/m/Y', strtotime($reference->created_at))}}</td>
                           <td>Referencia Laboral</td>
                           <td>{{$reference->work_ref2_name}}</td>
                           <td>{{$reference->work_ref2_address}}</td>
                           <td>{{$reference->work_ref2_number}}</td>
                         <tr>
                           <td>{{date('d/m/Y', strtotime($reference->created_at))}}</td>
                           <td>Referencia Peronal</td>
                           <td>{{$reference->refer2_name}}</td>
                           <td>{{$reference->refer2_address}}</td>
                           <td>{{$reference->refer2_phone}}</td>
                         </tr>
                      @endforeach
                  </tbody>
              </table>
              <div class="col-lg-12" style="text-align:center;">
                <a class="btn btn-danger" href="{{ url('customers') }}">
                    {{trans('button.cancel')}}
                </a>
              </div>

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
        "language":{
            "url":"{{ asset('assets/js/datatables/Spanish.js')}}"
        },
        dom: 'Bfrtip',
        buttons: [
          {
            extend:'copy',
            title:'Listado de clientes',
            exportOptions:{
              columns:[0,1,2,3,4]
            }
          },
          {
            extend:'csv',
            title:'Listado de clientes',
            exportOptions:{
              columns:[0,1,2,3,4]
            }
          },
          {
            extend:'excel',
            title:'Listado de clientes',
            exportOptions:{
              columns:[0,1,2,3,4]
            }
          },
          {
            extend:'pdf',
            title:'Listado de clientes',
            exportOptions:{
              columns:[0,1,2,3,4]
            }
          },
          {
            extend:'print',
            title:'Listado de clientes',
            exportOptions:{
              columns:[0,1,2,3,4]
            }
          }
        ],
        "order":[[0,"desc"]],
    }) ;
});
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
@stop
