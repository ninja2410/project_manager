@extends('layouts/default')
@section('title',trans('accounts.statement').': '.$account->account_name.' - '.$account->account_number)
@section('page_parent',trans('accounts.banks'))

@section('header_styles')

  <link href="{{ asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet"
        type="text/css"/>
  <link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet"
        type="text/css"/>
@stop
@section('content')
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="panel-title">
              <i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff"
                 data-loop="true"></i>
              {{trans('accounts.statement')}}
            </h3>
            <span class="pull-right clickable">
                        <i class="glyphicon glyphicon-chevron-up"></i>
                    </span>
          </div>
          <div class="row">
            <br>
            {!! Form::open(array('method'=>'get','route'=>array($url,$account->id), 'id'=>'frmFilter')) !!}
            <input type="hidden" name="all" value="false" id="all">
            <div class="row">
              <div class="col-md-2">
              </div>
              <div class="col-md-3">
                <center><label for=""><b>Fecha inicial</b></label></center>
                <input type="text" name="date1" id='date1' class="form-control"
                       value="{{date('d/m/Y', strtotime($fecha1))}}">
              </div>
              <div class="col-md-3">
                <center><label for=""><b>Fecha final</b></label></center>
                <input type="text" name="date2" id='date2' class="form-control"
                       value="{{date('d/m/Y', strtotime($fecha2))}}">
              </div>
              <div class="col-md-1">
                <br> {!! Form::submit(trans('accounts.generate'), array('class' => 'btn btn-primary ')) !!}
              </div>
              <div class="col-md-1">
                <br>
                <button type="button" class="btn btn-primary btn-info" onclick="getAll()">
                  <span class="glyphicon glyphicon-eye"></span>
                  Ver todo
                </button>
              </div>

            </div>
            <hr> {!! Form::close() !!}
          </div>

          <div class="panel-body table-responsive">
            <table class="table table-bordered " id="table_advanced">
              <thead>
              <th></th>
              <th>Fecha</th>
              {{-- <th>Tipo</th> --}}
              <th>Forma pago</th>
              <th>Descripción</th>
              <th>Referencia</th>
              <th>Estado</th>
              <th style="width: 10%">{{trans('credit.debit')}}</th>
              <th style="width: 10%">{{trans('credit.credit')}}</th>
              <th style="width: 10%">{{trans('credit.balance')}}</th>
              <th style="width: 5%"></th>
              </thead>
              <tbody>
              <?php $saldo = 0; $credito = 0;$debito = 0;?>
              @foreach($accounts as $i => $value)
                <tr>
                  <td></td>
                  <td style="font-size:12px;">{{date('d/m/y',strtotime($value->paid_at))}}</td>
                  <td style="font-size:12px;">
                    <span @if ($value->tipo=='Ingreso') class="label label-primary"
                          @else class="label label-danger" @endif>
                          {{$value->payment_method}}
                    </span>
                  </td>
                  <td style="font-size:12px;">{{$value->description}}</td>
                  <td style="font-size:12px;">{{$value->reference}}</td>
                  <td style="font-size:11px;">
                    @if($value->status == 'Inactivo')
                      <span class="label label-danger">{{$value->status}}</span>
                    @endif
                    @if($value->status == 'No Conciliado')
                      <span class="label label-info">{{$value->status}}</span>
                    @endif
                    @if($value->status == 'Conciliado')
                      <span class="label label-success">{{$value->status}}</span>
                    @endif
                  </td>
                  <td style="font-size:12px;text-align:right;">
                    @if ($value->tipo!='Ingreso')
                      @if ($value->status!='Inactivo')
                              <?php $debito = $value->amount; ?>
                        @money($value->amount)
                      @else
                        @money(0)
                      @endif
                    @endif
                  </td>
                  <td style="font-size:12px;text-align:right;">
                    @if ($value->tipo=='Ingreso')
                      @if ($value->status!='Inactivo')
                              <?php $credito = $value->amount; ?>
                        @money($value->amount)
                      @else
                        @money(0)
                      @endif
                    @endif
                  </td>
                    <?php $saldo += $credito - $debito; $credito = 0;$debito = 0;?>
                  <td style="font-size:12px;text-align:right;">@money($saldo)</td>
                  <td>
                    <div class="input-group-btn">
                      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                              data-toggle-position="left" aria-expanded="false">
                        <i class="fa fa-ellipsis-h"></i>
                      </button>
                      <ul class="dropdown-menu dropdown-menu-right">
                        <li>
                          <a class="btn btn-warning" href="{{ URL::to($value->route.'/' . $value->id ) }}"
                             data-toggle="tooltip" data-original-title="Detalles">
                            <span class="glyphicon glyphicon-eye-open"></span>&nbsp;Detalles
                          </a>
                        </li>
                        <li>
                          @if ($value->tipo == 'Ingreso' && isset($value->serie_id))
                            <a class="btn btn-info" href="{{ URL::to('banks/revenues/print_voucher/' . $value->id.'/false' ) }}"
                               data-toggle="tooltip" data-original-title="Imprimir comprobante">
                              <span class="glyphicon glyphicon-print"></span>&nbsp;Imprimiir comprobante
                            </a>
                          @endif
                        </li>
                        <li>
                            <?php
                                $anul = false;
                            if ($value->tipo=='Ingreso'){
                                $anul = $anl_rev;
                            }
                            else{
                                $anul = $anl_exp;
                            }
                            ?>
                            @if ($value->status !='Inactivo' && $anul)
                                {{-- <button type="button" class="btn btn-danger" onclick="showModalConf('{{$value->route.'/'.$value->id}}', '{{$value->description}}')">
                                    <span class="glyphicon glyphicon-remove-circle"> Anular</span>
                                </button> --}}
                                <a type="button" class="btn btn-danger"
                                   onclick="showModalConf('{{$value->route.'/'.$value->id}}', '{{$value->description}}')"
                                   data-toggle="tooltip" data-original-title="Anular">
                                    <span class="glyphicon glyphicon-remove-circle"> Anular</span>
                                </a>
                            @endif
                        </li>
                      </ul>
                    </div>
                  </td>
                </tr>
              @endforeach
              </tbody>
              <tfoot>
              <tr>
                <th></th>
                <th></th>
                {{-- <th ></th> --}}
                <th></th>
                <th></th>
                <th></th>
                <th colspan="1" style="text-align:right">TOTALES</th>
                <th colspan="1" style="text-align:right"></th>
                <th colspan="1" style="text-align:right"></th>
                <th colspan="1" style="text-align:right">@money($saldo)</th>
                <th></th>
              </tr>
              </tfoot>
            </table>
            <div>
              <a href="{{ URL::previous() }}" class="btn btn-danger btn-large button-block">
                                <span class="livicon" data-name="undo" data-size="14" data-loop="true" data-c="#fff"
                                      data-hc="white">&nbsp;&nbsp;</span>
                Regresar
              </a>
            </div>
          </div>
          {{--Begin modal--}}
          <span class="table-remove">
                  <div class="modal fade modal-fade-in-scale-up" tabindex="-1" id="modal" role="dialog"
                       aria-labelledby="modalLabelfade"
                       aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header bg-danger">
                          <h4 class="modal-title">Confirmación anulación de transacción</h4>
                        </div>
                        <div class="modal-body">
                          <div class="text-center">
                            <p id="description"></p>
                            <br> ¿Desea anular la transacción?
                              <hr>
                              <strong>Nota:</strong> Si la transacción a anular está asociada a un documento de compra o venta, el mismo quedará registrado al crédito.
                          </div>
                        </div>
                        <div class="modal-footer" style="text-align:center;">
                          {!! Form::open(array('class' => 'pull-right', 'id' => 'confirmFrm')) !!}
                          {!! Form::hidden('_method','DELETE') !!} {{-- test --}}
                                  <a href="#">
                                    <button type="submit" class="btn  btn-info">Aceptar</button>
                                  </a>
                                <button class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                          {!! Form::close() !!}
                        </div>
                      </div>
                    </div>
                  </div>
          {{--End modal--}}
        </div>
      </div>
    </div>
  </section>
@endsection

@section('footer_scripts')
  {{-- Calendario --}}
  <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"
          type="text/javascript"></script>
  <script type="text/javascript">
      function getAll() {
          document.getElementById('all').value = true;
          document.getElementById('frmFilter').submit();
      }

      $(document).ready(function () {

          $('#table_advanced').DataTable({
              language: {
                  "url": " {{ asset('assets/json/Spanish.json') }}"
              },
              dom: 'Bfrtip',
              responsive: {
                  details: {
                      type: 'column'
                  }
              },
              columnDefs: [{
                  className: 'control',
                  orderable: false,
                  targets: 0
              }],
              buttons: [
                  {
                      extend: 'collection',
                      text: 'Exportar/Imprimir',
                      buttons: [
                          {
                              extend: 'copy',
                              text: 'Copiar',
                              footer: true,
                              title: document.title,
                              exportOptions: {
                                  columns: 'th:not(:last-child)'
                              }
                          },
                          {
                              extend: 'excel',
                              footer: true,
                              title: document.title,
                              exportOptions: {
                                  columns: 'th:not(:last-child)'
                              }
                          },
                          {
                              extend: 'pdf',
                              footer: true,
                              title: document.title,
                              exportOptions: {
                                  columns: 'th:not(:last-child)'
                              }
                          },
                          {
                              extend: 'print',
                              text: 'Imprimir',
                              footer: true,
                              title: document.title,
                              exportOptions: {
                                  columns: 'th:not(:last-child)'
                              },
                          }
                      ]
                  },
              ],
              "footerCallback": function (row, data, start, end, display) {
                  var api = this.api(), data;

                  // Remove the formatting to get integer data for summation
                  var intVal = function (i) {
                      return typeof i === 'string' ?
                          i.replace(/[\$,Q,]/g, '') * 1 :
                          typeof i === 'number' ?
                              i : 0;
                  };

                  // Total monto
                  total_debito = api
                      .column(6)
                      .data()
                      .reduce(function (a, b) {
                          return intVal(a) + intVal(b);
                      }, 0);

                  total_credito = api
                      .column(7)
                      .data()
                      .reduce(function (a, b) {
                          return intVal(a) + intVal(b);
                      }, 0);
                  // Update footer
                  $(api.column(6).footer()).html(
                      'Q  ' + number_format(total_debito, 2)
                  );
                  $(api.column(7).footer()).html(
                      'Q  ' + number_format(total_credito, 2)
                  );

              },
          });
      });

      $("#date1").datetimepicker({
          sideBySide: true,
          locale: 'es',
          format: 'DD/MM/YYYY'
      }).parent().css("position :relative");
      $("#date2").datetimepicker({
          sideBySide: true,
          locale: 'es',
          format: 'DD/MM/YYYY'
      }).parent().css("position :relative");

      function showModalConf(url, description) {
          $('#description').append('');
          $('#description').append(description);
          $('#confirmFrm').attr('action', APP_URL + '/' + url);
          $('#modal').modal('show');
      }

  </script>
@stop
