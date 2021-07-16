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
              Ver {{$header->serie->document->name.' '.$header->serie->name.'-'.$header->correlative}}
            </h3>
            <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
          </div>
          <div class="panel-body" id="dvContents">
            <div class="col-lg-12">
              <div class="container col-lg-12">
                <div class="row">
                  <div class="col-xs-12 col-md-12 col-lg-12">
                    <div class="row">
                      <div class="col-lg-2 col-xs-2 col-md-2">
                        <h3>No. {{$header->correlative}}</h3>
                      </div>
                      <div class="col-lg-7 col-xs-7 col-md-7">
                        <center>
                          <img class="logo_invoice" style="max-width: 200px;"
                               src="{{ asset('images/system/logo2.png') }}"
                               alt="">
                        </center>
                      </div>
                      <div class="col-lg-3 col-xs-3 col-md-3">
                        <div class="form-group">
                          {!! Form::label('date_tx', trans('transfers.date_created')) !!}
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
                      @if (($header->date_received)!='30/11/-0001')
                        <div class="col-lg-3 col-xs-3 col-md-3">
                          <div class="form-group">
                            {!! Form::label('date_tx', trans('transfers.date_received')) !!}
                            <div class="input-group">
                              <div class="input-group-addon">
                                <i class="livicon" data-name="calendar" data-size="16"
                                   data-c="#555555" data-hc="#555555" data-loop="true"></i>
                              </div>
                              <input type="text" class="form-control" readonly name="fecha"
                                     value="{{$header->date_received}}">
                            </div>
                          </div>
                        </div>
                      @endif
                    </div>
                    <div class="row" style="border-bottom: solid 1px; margin-bottom: 8px;">
                      <center><h4>{{$header->serie->document->name.' '.$header->serie->name}}</h4></center>
                    </div>
                    <div class="row">
                      <div class="col-lg-6 col-xs-6">
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
                      <div class="col-lg-6 col-xs-6">
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
                    </div>
                    <div class="row">
                      <div class="col-lg-6 col-xs-6">
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
                      <div class="col-lg-6 col-xs-6">
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
                    </div>
                    <div class="row">
                      <div class="col-lg-6 col-xs-6">
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
                      <div class="col-lg-6">
                        <div class="form-group">
                          {!! Form::label('lblcosto', 'Costo total de traslado') !!}
                          <div class="input-group">
                            <div class="input-group-addon">
                              Q
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
                  @if(isset($header->account_credit_id))
                    <div class="col-lg-12 col-md-12 col-xs-12">
                      <div class="col-lg-6">
                        {!! Form::label('uReceptor', trans('product_transfer.account_credit')) !!}
                        <div class="input-group">
                          <div class="input-group-addon">
                            <i class="livicon" data-name="money" data-size="16"
                               data-c="#555555" data-hc="#555555" data-loop="true"></i>
                          </div>
                          <input type="text" class="form-control" readonly name="fecha"
                                 @if(isset($header->account_credit_id))
                                 value="{{$header->account_credit->account_name}}"
                                  @endif>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        {!! Form::label('uReceptor', trans('sale.price')) !!}
                        <div class="input-group">
                          <div class="input-group-addon">
                            <i class="livicon" data-name="label" data-size="16"
                               data-c="#555555" data-hc="#555555" data-loop="true"></i>
                          </div>
                          <input type="text" class="form-control" readonly
                                 value="{{isset($header->price_id) ? $header->price->name: "Precio Costo"}}">
                        </div>
                      </div>
                    </div>
                  @endif
                </div>
                <div class="row">
                  <div class="col-xs-12 col-md-12 col-lg-12">
                    <div class="table-responsive">
                      <table class="table">
                        <thead class="header_details">
                        <tr>
                          <th style="width: 15%" class="text-left">
                            <strong>{{trans('sale.code')}}</strong>
                          </th>
                          <th style="width: 45%" class="text-left">
                            <strong>{{trans('quotation.description')}}</strong>
                          </th>
                          <th style="text-align: right; width: 15%;">
                            <strong>{{trans('sale.unit')}}</strong>
                          </th>
                          <th style="width: 10%; text-align: center;"><strong>{{trans('sale.qty')}} enviada</strong>
                          </th>
                          <th style="width: 15%; text-align: right">
                            <strong>{{trans('sale.stotal')}}</strong>
                          </th>
                          <th style="width: 10%; text-align: center;"><strong>{{trans('sale.qty')}} recibida</strong>
                          </th>
                          <th>
                            <strong>{{trans('sale.stotal')}}</strong>
                          </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $total_products = 0;
                        $total_products_recived = 0;
                        $tcost = 0;
                        $tcostRecived = 0;
                        ?>
                        @foreach ($details as $detail)
                          <tr style="font-size:12px">
                            <td class="text-left">{{$detail->item->upc_ean_isbn}}</td>
                            <td class="text-left">{{$detail->item->item_name}}</td>
                            <td class="text-left" style="text-align: right">@money($detail->cost)</td>
                            <td class="text-left" style="text-align: center;">{{$detail->quantity+0}}</td>
                            <td class="text-left" style="text-align: right">@money(($detail->cost*$detail->quantity))
                            </td>
                            <td class="text-left" style="text-align: center;">{{$detail->quantity_received+0}}</td>
                            <td class="text-left" style="text-align: right">@money($detail->cost *
                              $detail->quantity_received)
                            </td>
                          </tr>
                          <?php
                          $total_products += $detail->quantity;
                          $total_products_recived += $detail->quantity_received;
                          $tcost += ($detail->quantity * $detail->cost);
                          $tcostRecived += $detail->cost * $detail->quantity_received;
                          ?>
                        @endforeach
                        <tr>
                          <td class="no-line"></td>
                          <td class="no-line"></td>
                          <td class="no-line text-right"><strong>TOTAL:</strong></td>
                          <td class="no-line" style="text-align: center;"><strong>{{$total_products}}</strong></td>
                          <td class="no-line" style="text-align: right"><strong>Q{{number_format($tcost, 2)}}</strong>
                          </td>
                          <td class="no-line" style="text-align: center;"><strong>{{$total_products_recived}}</strong>
                          </td>
                          <td class="no-line" style="text-align: right">
                            <strong>Q{{number_format($tcostRecived, 2)}}</strong></td>
                        </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                @if (isset($header->account_credit_id))
                  <div class="row">
                    <div class="col-xs-12 col-md-12 col-lg-12">
                      <h4>{{trans('product_transfer.account_debit')}}</h4>
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
                                <a href="{{URL::to('banks/expenses_accounts/'.$payment->transaction_id)}}" data-toggle="tooltip" data-original-title="Ver transacción">@money($payment->amount)</a>
                              </td>
                              <td class="text-right" style="text-align: right">@money($payment->confirm_amount)</td>
                            </tr>
                            <?php
                            $total_payments += $payment->amount;
                            $total_payments_confirm += $payment->confirm_amount;
                            ?>
                          @endforeach
                          </tbody>
                          <tfoot>
                          <tr>
                            <td style="text-align: right">{{trans('product_transfer.total')}}</td>
                            <td class="text-right">@money($total_payments)</td>
                            <td class="text-right">@money($total_payments_confirm)</td>
                          </tr>
                          </tfoot>
                        </table>
                      </div>
                    </div>
                  </div>
                @endif
                <div class="row">
                  <div class="col-lg-12">
                    <label for="comment">{{trans('sale.comments')}}</label>
                    <textarea class="form-control" name="comment" id="comment" readonly
                              rows="2">{{$header->comment}}</textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">&nbsp;</div>
      <div class="col-md-4">
        <button type="button" onclick="printReceipt();"
                class="btn btn-info hidden-print">{{trans('quotation.print')}}</button>
        @if($header->status_transfer_id == 8 && $header->user_id != $current_user)
          <a href="{{ url("/transfer_to_storage/".$header->id.'/edit') }}" type="button"
             class="btn btn-warning hidden-left">Recibir</a>
        @endif
        <a href="{{ url("/transfer_to_storage") }}" type="button"
           class="btn btn-danger hidden-print">{{trans('Cancelar')}}</a>
      </div>
      <div class="col-md-4">
      </div>
    </div>
    <br>
  </section>
@endsection
@section('footer_scripts')
  <script>
      function printReceipt() {
          var name_document = $('#name_document').val();
          var contents = $("#dvContents").html();
          var frame1 = $('<iframe />');
          frame1[0].name = "frame1";
          frame1.css({"position": "absolute", "top": "-1000000px"});
          $("body").append(frame1);
          var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
          frameDoc.document.open();
          //Create a new HTML document.
          frameDoc.document.write('<html><head><title>' + name_document + '</title>');
          frameDoc.document.write('</head><body>');
          //Append the external CSS file.

          frameDoc.document.write('<link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />');
          //Append the DIV contents.
          frameDoc.document.write(contents);
          frameDoc.document.write('</body></html>');
          frameDoc.document.close();
          setTimeout(function () {
              window.frames["frame1"].focus();
              window.frames["frame1"].print();
              frame1.remove();
          }, 500);
      }
  </script>
@endsection
