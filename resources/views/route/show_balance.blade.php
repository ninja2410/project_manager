<?php
$tMora=0;
$tAmountPayments=0;
$tAmountOutlays=0;
$tAmountTransfer=0;
$tAmountNewPagares=0;
$tAmountCashes=0;
$tAmountDisbursement=0;
?>
@extends('layouts/default')

@section('title','Arqueo de ruta')
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
<!-- Main content -->
<section class="content paddingleft_right15">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-success" id="content">
        <div class="panel-heading">
          <h3 class="panel-title"><i class="livicon" data-name="money" data-size="14" data-loop="true" data-c="#fff" data-hc="#fff"></i>Detalle de arqueo <b>{{$balance->created_at->format('d/m/Y')}}</b></h3>
        </div>
        <div class="panel-body" style="border:1px solid #ccc;padding:0;margin:0;">
          <div class="row" style="padding: 15px;margin-top:5px;">
            <div class="col-md-6">
              <h2>Arqueo de ruta {{$parameter->name_company}}</h2>
            </div>
            <div class="col-md-6">
              <div class="pull-right">

              </div>
            </div>
          </div>
          <div class="row" style="padding: 15px;">
            <div class="col-md-9 col-xs-6" style="margin-top:5px;">
              <strong>Ruta:</strong>
              <br> <strong>{{$route->name}}</strong>
              <br> Encargado:
              <br> {{$user->name}}
              <br>{{$user->email}}
              <br>{{$user->phone}}
            </div>
            <div class="col-md-3 col-xs-6" style="padding-right:0">
              <div id="invoice" style="background-color: #eee;text-align:right;padding: 15px;padding-bottom:10px;border-bottom-left-radius: 6px;border-top-left-radius: 6px;">
                <h4><strong>Arqueo: {{$balance->id}}</strong></h4>
                <h4><strong>{{date('d/m/Y', strtotime($balance->date))}}</strong></h4>
                <br> Revisado por:
                <br>{{$userB->name}}
              </div>
            </div>
          </div>
          {{-- Tabla detalle de desembolsos --}}
          <div class="row" style="padding:15px;">
            <div class="col-md-12 col-xs-12">
              <h3>Detalle de desembolsos</h3>
              <div class="table-responsive">
                <table class="table">
                  <thead>
                    <tr>
                      <th>Tarjeta</th>
                      <th>Cliente</th>
                      <th>Monto</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($disbursement as $key => $value)
                      <tr>
                        <td>{{$value->number_card}}</td>
                        <td>{{$value->name}}</td>
                        <td>Q {{number_format($value->amount, 2)}}</td>
                      <tr>
                      <?php
                      $tAmountDisbursement+=$value->amount;
                      ?>
                    @endforeach
                    <tr>
                      <td></td>
                      <td><b>Total Desembolsos</b></td>
                      <td>Q {{number_format($tAmountDisbursement, 2)}}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          {{-- ---------------------------- --}}
          {{-- Tabla detalle de gastos --}}
          <div class="row" style="padding:15px;">
            <div class="col-md-12 col-xs-12">
              <h3>Detalle de gastos</h3>
              <div class="table-responsive">
                <table class="table">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Descripcion</th>
                      <th>Referencia</th>
                      <th>Monto</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($outlays as $key => $value)
                      <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$value->description}}</td>
                        <td>{{$value->reference}}</td>
                        <td>Q {{number_format($value->amount, 2)}}</td>
                      <tr>
                      <?php
                      $tAmountOutlays+=$value->amount;
                      ?>
                    @endforeach
                    <tr>
                      <td></td>
                      <td></td>
                      <td><b>Total Gastos</b></td>
                      <td>Q {{number_format($tAmountOutlays, 2)}}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          {{-- ------------------------ --}}
          {{-- Tabla detalle de transferencias --}}
          @if (isset($transfer[0]->id))
            <div class="row" style="padding:15px;">
              <div class="col-md-12 col-xs-12">
                <h3>Detalle de transferencias</h3>
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Cuenta</th>
                        <th>Responsable</th>
                        <th>Monto</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($transfer as $key => $value)
                        <tr>
                          <td>{{$key+1}}</td>
                          <td>{{$value->account_name}}</td>
                          <td>{{$value->responsible}}</td>
                          <td>Q {{number_format($value->amount, 2)}}</td>
                        <tr>
                        <?php
                        $tAmountTransfer+=$value->amount;
                        ?>
                      @endforeach
                      <tr>
                        <td></td>
                        <td></td>
                        <td><b>Total Transferencias</b></td>
                        <td>Q {{number_format($tAmountTransfer, 2)}}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          @endif
          {{-- ------------------------ --}}
          {{-- Tabla detalle de nuevos créditos --}}
          {{-- <div class="row" style="padding:15px;">
            <div class="col-md-12 col-xs-12">
              <h3>Detalle de nuevos créditos</h3>
              <div class="table-responsive">
                <table class="table">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Tarjeta</th>
                      <th>Cliente</th>
                      <th>Monto</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($bNewPagares as $key => $value)
                      <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$value->number_card}}</td>
                        <td>{{$value->Cliente}}</td>
                        <td>Q {{number_format($value->amount, 2)}}</td>
                      <tr>
                    @endforeach
                    <tr>
                      <td></td>
                      <td></td>
                      <td><b>Total nuevos créditos</b></td>
                      <td>Q {{number_format($tAmountNewPagares, 2)}}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div> --}}
          {{-- ------------------------ --}}
          {{-- Tabla detalle de efectivo --}}
          <div class="row" style="padding:15px;">
            <div class="col-md-12 col-xs-12">
              <h3>Detalle de efectivo</h3>
              <div class="table-responsive">
                <table class="table">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Denominación</th>
                      <th>Cantidad</th>
                      <th>Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($detailCash as $key => $value)
                      <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$value->name}}</td>
                        <td>{{$value->quantity}}</td>
                        <td>Q {{number_format($value->quantity*$value->value, 2)}}</td>
                      <tr>
                      <?php
                      $tAmountCashes+=$value->quantity*$value->value;
                      ?>
                    @endforeach
                    <tr>
                      <td></td>
                      <td></td>
                      <td><b>Total efectivo </b></td>
                      <td><b>Q {{number_format($tAmountCashes, 2)}}</b></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          {{-- ------------------------ --}}
          {{-- CALCULAR TOTALES DE PAGO --}}
          @foreach ($details as $key => $value)
            <?php
            $tMora+=$value->mora;
            $tAmountPayments+=$value->amount;
            ?>
          @endforeach
          {{-- ------------------------ --}}
          {{-- Tabla detalle de transferencias --}}
          @if ($balance->surplus==1)
            <div class="row" style="padding:15px;">
              <div class="col-md-12 col-xs-12">
                <h3>Acción con sobrante</h3>
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Cuenta</th>
                        <th>Responsable</th>
                        <th>Monto</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($transferSobrante as $key => $value)
                        <tr>
                          <td>{{$key+1}}</td>
                          <td>{{$value->account_name}}</td>
                          <td>{{$value->responsible}}</td>
                          <td>Q {{number_format($value->amount, 2)}}</td>
                        <tr>
                      @endforeach
                      <tr>
                        <td></td>
                        <td></td>
                        <td><b>Total Transferencia Sobrante</b></td>
                        <td>Q {{number_format($value->amount, 2)}}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          @endif
          {{-- ------------------------ --}}
          {{-- --------RESUMEN BALANCE --}}
          <div class="row" style="padding:15px;">
            <div class="col-md-12 col-xs-12">
              <h2>Resumen Arqueo</h2>
              <div class="table-responsive">
                <table class="table">
                  <thead>
                    <tr>
                      <th>Descripcion</th>
                      <th>Ingreso</th>
                      <th>Egreso</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Total Efectivo Anterior</td>
                      <td>Q {{number_format($balance->lastCash, 2)}}</td>
                      <td></td>
                    </tr>
                    <tr>
                      <td>Total Pagos</td>
                      <td>Q {{number_format($tAmountPayments, 2)}}</td>
                      <td></td>
                    </tr>
                    <tr>
                      <td>Total Mora</td>
                      <td>Q {{number_format($tMora, 2)}}</td>
                      <td></td>
                    </tr>
                    @if ($balance->diference>0)
                      <tr>
                        <td style="color:red;">Sobrante</td>
                        <td style="color:red;">Q {{number_format($balance->diference, 2)}}</td>
                        <td></td>
                      </tr>
                    @endif
                    <tr>
                      <td>Total Efectivo</td>
                      <td></td>
                      <td>Q {{number_format($balance->cash, 2)}}</td>
                    </tr>
                    <tr>
                      <td>Total Transferencias</td>
                      <td></td>
                      <td>Q {{number_format($tAmountTransfer, 2)}}</td>
                    </tr>
                    <tr>
                      <td>Total desembolsos</td>
                      <td></td>
                      <td>Q {{number_format($tAmountDisbursement, 2)}}</td>
                    </tr>
                    <tr>
                      <td>Total Gastos</td>
                      <td></td>
                      <td>Q {{number_format($tAmountOutlays, 2)}}</td>
                    </tr>
                    {{-- <tr>
                      <td>Total Nuevos Créditos</td>
                      <td></td>
                      <td>Q {{number_format($tAmountNewPagares, 2)}}</td>
                    </tr> --}}
                    <tr>
                      <th><b>SUMATORIA</b></th>
                      @if ($balance->diference>0)
                        <th> <b>Q {{number_format($balance->initial_cash+$balance->lastCash+$tAmountPayments+$tMora+$balance->diference,2)}}</b> </th>
                      @else
                        <th> <b>Q {{number_format($balance->initial_cash+$balance->lastCash+$tAmountPayments+$tMora,2)}}</b> </th>
                      @endif
                      <th> <b>Q {{number_format($tAmountDisbursement+$balance->cash+$tAmountTransfer+$tAmountOutlays+$tAmountNewPagares,2)}}</b> </th>
                    </tr>
                  </tbody>
                </table>
                <p> <strong> Comisión por cobros:</strong> Q {{number_format($tAmountPayments*($parameter->percent_commission/100),2)}}</p>
              </div>
            </div>
          </div>
          {{-- -------------------------- --}}
          <div style="background-color: #eee;padding:15px;" id="footer-bg">
            <div class="row">
              <div class="col-md-12">
                <strong>Comentarios</strong>
                <br>
                {{$balance->description}}
                <br>
              </div>
            </div>
            <hr>
            <p class="text-center"><strong></strong></p>
          </div>
          <div class="row">
            <center><h3>Firmas:</h3></center>
            <br>
            {{-- SI EXISTEN TRANSFERENCIAS --}}
            @foreach ($transfer as $key => $value)
              <div class="col-lg-6 col-xs-6">
                <center>
                  <p>f.__________________________________________</p>
                  <p><strong>{{$value->responsible}}<strong> <br>
                    {{$value->account_name}}</p>
                </center>
                <br>
              </div>
            @endforeach
            @foreach ($transferSobrante as $key => $value)
              <div class="col-lg-6 col-xs-6">
                <center>
                  <p>f.__________________________________________</p>
                  <p><strong>{{$value->responsible}}<strong> <br>
                    {{$value->account_name}}</p>
                </center>
              </div>
            @endforeach
          </div>
          <div class="row">
            <div class="col-lg-6 col-xs-6">
              <center>
                <p>f.__________________________________________</p>
                <p><strong>{{$user->name.' '.$user->last_name}}<strong> <br>
                  ENCARGADO: {{$route->name}}</p>
              </center>
            </div>
            <div class="col-lg-6 col-xs-6">
              <center>
                <p>f.__________________________________________</p>
                <p><strong>{{$userB->name.' '.$userB->last_name}}<strong> <br>
                  ADMINISTRADOR</p>
              </center>
            </div>
          </div>
        </div>
      </div>
      <div style="margin:10px 20px;text-align:center;" class="btn-section">
        <button type="button" class="btn btn-responsive button-alignment btn-info" data-toggle="button">
          <span style="color:#fff;" onclick="printDiv('content')">
            <i class="livicon" data-name="printer" data-size="16" data-loop="true" data-c="#fff" data-hc="white" style="position:relative;top:3px;"></i>
            Imprimir
          </span>
        </button>
        <a href="{{url("detail_balance/".$balance->id)}}">
          <button type="button" class="btn btn-success">
              Ver detalle de cobros
            </span>
          </button>
        </a>
        <a href="{{url("route-balances/".$route->id)}}">
          <button type="button" class="btn btn-danger">
              Cancelar
            </span>
          </button>
        </a>
      </div>
    </div>
  </div>
</section>
<!-- content -->
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
function printDiv(nombreDiv) {
   var contenido= document.getElementById(nombreDiv).innerHTML;
   var contenidoOriginal= document.body.innerHTML;
   document.body.innerHTML = contenido;
   window.print();
   document.body.innerHTML = contenidoOriginal;
}
</script>
@endsection
