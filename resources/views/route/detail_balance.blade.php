<?php
$tMora=0;
$tAmountPayments=0;
$tAmountOutlays=0;
$tAmountTransfer=0;
$tAmountNewPagares=0;
$tAmountCashes=0;
?>
@extends('layouts/default')

@section('title','Detalle de pagos arqueo de ruta #'.$balance->id)
@section('page_parent',"Detalle de pagos")
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
          {{-- Tabla detalle de pagos --}}
          <div class="row" style="padding:15px;" id="detallePagos">
            <div class="col-md-12 col-xs-12">
              <center><h3>Detalle de pagos</h3></center>
              <div class="table-responsive">
                <table class="table table1" id="table">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>No. Tarjeta</th>
                      <th>Cliente</th>
                      <th>Cuota</th>
                      <th>Mora</th>
                      <th>Total Pago</th>
                      @if ($permiso[0]->role_id==1)
                        <th>Acciones</th>
                      @endif
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($details as $key => $value)
                      <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$value->card_number}}</td>
                        <td>{{$value->name}}</td>
                        <td>Q {{number_format($value->amount, 2)}}</td>
                        <td>Q {{number_format($value->mora, 2)}}</td>
                        <td>Q {{number_format(($value->amount+$value->mora), 2)}}</td>
                        @if ($permiso[0]->role_id==1)
                          <td>
                            <a class="btn btn-success" href="{{ URL::to('edit_payment/' . $value->id ) }}" data-toggle="tooltip" data-original-title="Editar">
                              <span class="glyphicon glyphicon-edit"></span>&nbsp;&nbsp;Editar pago
                            </a>
                          </td>
                        @endif
                      </tr>
                      <?php
                      $tMora+=$value->mora;
                      $tAmountPayments+=$value->amount;
                      ?>
                    @endforeach
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td><b>Total Mora</b></td>
                      <td>Q {{number_format($tMora, 2)}}</td>
                      @if ($permiso[0]->role_id==1)
                        <td>
                        </td>
                      @endif
                    </tr>
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td><b>Total Cuotas</b></td>
                      <td>Q {{number_format($tAmountPayments, 2)}}</td>
                      @if ($permiso[0]->role_id==1)
                        <td></td>
                      @endif
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="row" style="text-align:center">
            <a href="{!! url('route-balances-show/'.$balance->id) !!}">
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
    $('.table1').DataTable({
      dom: 'Bfrtip',
      buttons: [{
          extend: 'copy',
          title: 'Listado de pagos balance:{{$balance->id}} de fecha {{date("d/m/Y", strtotime($balance->date))}}',
          exportOptions: {
            columns: [0, 1, 2, 3, 4]
          }
        },
        {
          extend: 'csv',
          title: 'Listado de pagos balance:{{$balance->id}} de fecha {{date("d/m/Y", strtotime($balance->date))}}',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5]
          }
        },
        {
          extend: 'excel',
          title: 'Listado de pagos balance:{{$balance->id}} de fecha {{date("d/m/Y", strtotime($balance->date))}}',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5]
          }
        },
        {
          extend: 'pdf',
          title: 'Listado de pagos balance:{{$balance->id}} de fecha {{date("d/m/Y", strtotime($balance->date))}}',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5]
          }
        },
        {
          extend: 'print',
          title: 'Listado de pagos balance:{{$balance->id}} de fecha {{date("d/m/Y", strtotime($balance->date))}}',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5]
          }
        }
      ],
      "order": [[1,"desc"]]
    });
  });
  </script>
@endsection
