@extends('layouts/default')
@section('title',trans('Traslados de bodega'))
@section('page_parent',trans('Bodegas'))
@section('content')
  <section class="content">
    <div>
      <div class="row">
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="panel-title">
              <i class="livicon" data-name="shopping-cart-in" data-size="18" data-c="#fff" data-hc="#fff"
                 data-loop="true"></i>
              Recibir {{$header->serie->document->name.' '.$header->serie->name.'-'.$header->correlative}}
            </h3>
            <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
          </div>
          <div class="panel-body" id="dvContents">
            <div class="container col-lg-12">
              <div class="row">
                <div class="col-xs-12 col-lg-12 col-md-12">
                  <div class="row">
                    <div class="col-xs-2 col-lg-2 col-md-2">
                      <h5>{{$header->serie->document->name.' '.$header->serie->name}}</h5>
                      <h3>No. {{$header->correlative}}</h3>
                    </div>
                    <div class="col-xs-7 col-lg-7 col-md-7">
                      <img class="logo_invoice" style="max-width: 200px;" src="{{ asset('images/system/logo2.png') }}"
                           alt="">
                    </div>
                    <div class="col-xs-3 col-lg-3 col-md-3">
                      <div class="form-group">
                        {!! Form::label('date_tx', trans('Fecha')) !!}
                        <div class="input-group">
                          <div class="input-group-addon">
                            <i class="livicon" data-name="calendar" data-size="16"
                               data-c="#555555" data-hc="#555555" data-loop="true"></i>
                          </div>
                          <input type="text" class="form-control" readonly name="fecha"
                                 value="{{$header->date}}">
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-4 col-xs-4 col-md-4">
                      <div class="form-group">
                        {!! Form::label('bdOrigen', 'Bodega de origen') !!}
                        <div class="input-group">
                          <div class="input-group-addon">
                            <i class="livicon" data-name="truck" data-size="16"
                               data-c="#555555" data-hc="#555555" data-loop="true"></i>
                          </div>
                          <input type="text" class="form-control" readonly name="fecha"
                                 value="{{$header->Almacen_origin->name}}">
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-4 col-xs-4 col-md-4">
                      <div class="form-group">
                        {!! Form::label('bdDestino', 'Bodega destino') !!}
                        <div class="input-group">
                          <div class="input-group-addon">
                            <i class="livicon" data-name="truck" data-size="16"
                               data-c="#555555" data-hc="#555555" data-loop="true"></i>
                          </div>
                          <input type="text" class="form-control" readonly name="fecha"
                                 value="{{$header->Almacen_destination->name}}">
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-4 col-xs-4 col-md-4">
                      <div class="form-group">
                        {!! Form::label('lblStatus', 'Estado de traslado') !!}
                        <div class="input-group">
                          <div class="input-group-addon">
                            <i class="livicon" data-name="settings" data-size="16"
                               data-c="#555555" data-hc="#555555" data-loop="true"></i>
                          </div>
                          <input type="text" class="form-control" readonly name="cost"
                                 value="{{$header->status->name}}">
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-4 col-xs-4 col-md-4">
                      <div class="form-group">
                        {!! Form::label('ucreador', 'Usuario envía') !!}
                        <div class="input-group">
                          <div class="input-group-addon">
                            <i class="livicon" data-name="user" data-size="16"
                               data-c="#555555" data-hc="#555555" data-loop="true"></i>
                          </div>
                          <input type="text" class="form-control" readonly name="fecha"
                                 value="{{$header->Created_by->name.' '.$header->Created_by->lastname}}">
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-4 col-xs-4 col-md-4">
                      <div class="form-group">
                        {!! Form::label('uReceptor', 'Usuario recibe') !!}
                        <div class="input-group">
                          <div class="input-group-addon">
                            <i class="livicon" data-name="user" data-size="16"
                               data-c="#555555" data-hc="#555555" data-loop="true"></i>
                          </div>
                          <input type="text" class="form-control" readonly name="fecha"
                                 @if(isset($header->updated_by))
                                 value="{{$header->Updated_by->name.' '.$header->Updated_by->lastname}}"
                                  @endif>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                      <div class="form-group">
                        {!! Form::label('lblcosto', 'Costo total de traslado') !!}
                        <div class="input-group">
                          <div class="input-group-addon">
                            <i class="livicon" data-name="money" data-size="16"
                               data-c="#555555" data-hc="#555555" data-loop="true"></i>
                          </div>
                          <input type="text" class="form-control" readonly name="cost"
                                 value="{{$header->amount}}">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                @if(isset($header->account_origin->id) || isset($header->account_destination->id))
                  <div class="col-lg-12 col-md-12 col-xs-12">
                    <div class="col-lg-4">
                      {!! Form::label('uReceptor', 'Cuenta origen') !!}
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="livicon" data-name="user" data-size="16"
                             data-c="#555555" data-hc="#555555" data-loop="true"></i>
                        </div>
                        <input type="text" class="form-control" readonly name="fecha"
                               @if(isset($header->account_origin->id))
                               value="{{$header->account_origin->account_name}}"
                                @endif>
                      </div>
                    </div>
                    <div class="col-lg-4">
                      {!! Form::label('uReceptor', 'Cuenta destino') !!}
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="livicon" data-name="user" data-size="16"
                             data-c="#555555" data-hc="#555555" data-loop="true"></i>
                        </div>
                        <input type="text" class="form-control" readonly name="fecha"
                               @if(isset($header->account_destination->id))
                               value="{{$header->account_destination->account_name}}"
                                @endif>
                      </div>
                    </div>
                    <div class="col-lg-4">
                      {!! Form::label('uReceptor', trans('sale.price')) !!}
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="livicon" data-name="label" data-size="16"
                             data-c="#555555" data-hc="#555555" data-loop="true"></i>
                        </div>
                        <input type="text" class="form-control" readonly
                               value="{{$header->price->name}}"
                      </div>
                    </div>
                  </div>
                @endif
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <label for="comment">{{trans('sale.comments')}}</label>
                  <textarea class="form-control" name="comment" id="comment" readonly
                            rows="2">{{$header->comment}}</textarea>
                </div>
              </div>
              <br>
              <div class="row">
                <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                  <li class="active">
                    <a href="#home" data-toggle="tab">{{trans('product_transfer.config')}}</a>
                  </li>
                  @if (isset($header->account_credit_id))
                    <input type="hidden" value="true" id="set_accounts">
                    <li>
                      <a href="#profile" id="tabPayment" data-toggle="tab">{{trans('product_transfer.payment')}}</a>
                    </li>
                  @else
                    <input type="hidden" value="false" id="set_accounts">
                  @endif
                </ul>
                <div id="myTabContent" class="tab-content">
                  <div class="tab-pane fade active in" id="home">
                    <div class="row">
                      <hr>
                      <div class="col-lg-2"></div>
                      <div class="col-lg-2">
                        <label for="">Ingrese código de producto</label>
                      </div>
                      <div class="col-lg-3">
                        <div class="form-group">
                          <div class="input-group">
                            <div class="input-group-addon">
                              <i class="livicon" data-name="barcode" data-size="16" data-c="#555555"
                                 data-hc="#555555"
                                 data-loop="true"></i>
                            </div>
                            <input type="text" autocomplete="off" id="codigo" class="form-control"
                                   placeholder="Ingrese código">
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-3">
                        <button type="button" class="btn btn-success" id="btnSettAll">Recibir todo</button>
                        <button type="button" class="btn btn-danger" id="btnUnsettAll">No recibir todo</button>
                      </div>
                      <div class="col-lg-2"></div>
                    </div>
                    <div class="row">
                      <div class="col-xs-12 col-lg-12 col-md-12">
                        <div class="table-responsive">
                          <table class="table">
                            <thead class="header_details">
                            <tr>
                              <th style="width: 15%" class="text-left">
                                <strong>{{trans('sale.code')}}</strong>
                              </th>
                              <th style="width: 35%" class="text-left">
                                <strong>{{trans('quotation.description')}}</strong>
                              </th>
                              <th>{{trans('transfers.unit_cost')}}</th>
                              <th style="width: 10%"><strong>{{trans('sale.qty')}} enviada</strong>
                              </th>
                              <th>{{trans('transfers.cost')}}</th>
                              <th style="width: 10%"><strong>{{trans('sale.qty')}} recibida</strong>
                              </th>
                              <th style="width: 10%">{{trans('transfers.sut_total')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $total_products = 0;
                            $total_products_recived = 0;
                            $total_cost_products = 0;
                            ?>
                            @foreach ($details as $detail)
                              <tr style="font-size:12px">
                                <td class="text-left">{{$detail->item->upc_ean_isbn}}</td>
                                <td class="text-left">{{$detail->item->item_name}}</td>
                                <td class="text-left">@money($detail->cost)</td>
                                <td class="text-left">{{$detail->quantity}}</td>
                                <td class="text-left">@money($detail->quantity * $detail->cost)</td>
                                <td class="text-left">
                                  <input type="text" class="form-control receivedQuantity" oldValue="0"
                                         max="{{$detail->quantity}}"
                                         id="{{$detail->item->upc_ean_isbn}}"
                                         detail_id="{{$detail->id}}" value="0"
                                         cost_price="{{$detail->cost}}"
                                                 oninput="updateTotal(this)">
                                </td>
                                <td>
                                  <label id="cost_detail_{{$detail->id}}">Q 0.00</label>
                                </td>
                              </tr>
                              <?php
                              $total_products += $detail->quantity;
                              $total_cost_products += ($detail->cost * $detail->quantity);
                              $total_products_recived += $detail->qty_recived;
                              ?>
                            @endforeach
                            <tr>
                              <td class="no-line"></td>
                              <td colspan="2" class="no-line text-right"><strong>TOTAL PRODUCTOS:</strong></td>
                              <td class="no-line">
                                <input type="text" readonly class="form-control"
                                       value="{{number_format($total_products, 2)}}">
                              </td>
                              <td class="no-line">
                                <input type="text" readonly class="form-control"
                                       value="Q {{number_format($total_cost_products, 2)}}">
                              </td>
                              <td class="no-line"><input type="text" id="totalReceived"
                                                         class="form-control manual_money" value="0" readonly></td>
                              <td>
                                <input type="text" id="total_cost_recived" class="form-control" readonly value="0.00">
                              </td>
                            </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane fade" id="profile">
                    <div class="row">
                      <div class="col-xs-12 col-lg-12 col-md-12">
                        <div class="table-responsive">
                          <table class="table">
                            <thead class="header_details">
                            <tr>
                              <th style="width: 60%" class="text-left">
                                <strong>{{trans('product_transfer.account')}}</strong>
                              </th>
                              <th style="width: 20%; text-align: right;">
                                <strong>{{trans('product_transfer.debit_amount')}}</strong>
                              </th>
                              <th style="width: 20%; text-align: right;"><strong>{{trans('product_transfer.confirm_amount')}}</strong>
                              </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $total_payments = 0;
                            $total_payments_confirm = 0;
                            ?>
                            @foreach ($payments as $payment)
                              <tr>
                                <td class="text-left">{{$payment->account->account_name}}</td>
                                <td class="text-right">
                                  @money($payment->amount)
                                </td>
                                <td class="text-right" style="text-align: right">
                                  <div class="input-group">
                                    <div class="input-group-addon">
                                      Q
                                    </div>
                                    <input style="text-align: right" oninput="updateAmount(this)" type="text" max="{{$payment->amount}}" oldValue="{{$payment->amount}}" payment_id="{{$payment->id}}" class="form-control amount_confirm" value="{{$payment->amount}}">
                                  </div>

                                </td>
                              </tr>
                              <?php
                              $total_payments += $payment->amount;
                              $total_payments_confirm += $payment->amount;
                              ?>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                              <td style="text-align: right">{{trans('product_transfer.total')}}</td>
                              <td class="text-right">@money($total_payments)</td>
                              <td class="text-right">
                                <div class="input-group">
                                  <div class="input-group-addon">
                                    Q
                                  </div>
                                  <input readonly style="text-align: right" id="total_amount_confirmed" type="text" class="form-control" value="{{number_format($total_payments_confirm, 2)}}">
                                </div>
                              </td>
                            </tr>
                            <tr>
                              <td style="text-align: right">{{trans('product_transfer.total_received')}}</td>
                              <td class="text-right"></td>
                              <td class="text-right">
                                <div class="input-group">
                                  <div class="input-group-addon">
                                    Q
                                  </div>
                                  <input readonly style="text-align: right" id="ref_received_amount" type="text" class="form-control" value="{{number_format(0, 2)}}">
                                </div>
                              </td>
                            </tr>
                            </tfoot>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
    <div>
      {!! Form::open(['method' => 'put', 'id'=>'frm', 'url' => url('transfer_to_storage/'.$header->id)]) !!}
      <input type="hidden" name="transfer_id" value="{{$header->id}}">
      <input type="hidden" name="details_received" id="details_received">
      <input type="hidden" name="details_payment" id="detail_payments">
      {!! Form::close() !!}
    </div>
    <div class="row">
      <div class="col-md-3">&nbsp;</div>

      <div class="col-md-3">
        <button type="button" onclick="confirm();"
                class="btn btn-info pull-right hidden-print">Guardar
        </button>
      </div>
      <div class="col-md-3">
        <a href="{{ url("/transfer_to_storage") }}" type="button"
           class="btn btn-danger hidden-print">{{trans('Cancelar')}}</a>
      </div>
      <div class="col-md-3">&nbsp;</div>
    </div>
    <br>
    @include('layouts.modal_confirm_generic', array("confirm"=>"Desea marcar el traslado como recibido?"))
  </section>
@endsection
@section('footer_scripts')
  {{-- FORMATO DE MONEDAS --}}
  <script src="{{asset('assets/js/vuejs/cleave.min.js')}} "></script>
  <script src="{{asset('assets/js/vuejs/vue-cleave.min.js')}} "></script>
  <script src="{{ asset('assets/js/transfer_to_storage/transfer_storage.js')}}" type="text/javascript"></script>
  <script>
      $(document).ready(function () {
          $('.receivedQuantity').toArray().forEach(function (field) {
              new Cleave(field, {
                  numeral: true,
                  numeralPositiveOnly: true,
                  numeralThousandsGroupStyle: 'thousand'
              });
          });
          $('.amount_confirm').toArray().forEach(function (field) {
              new Cleave(field, {
                  numeral: true,
                  numeralPositiveOnly: true,
                  numeralThousandsGroupStyle: 'thousand'
              });
          });
      });
  </script>
@endsection
