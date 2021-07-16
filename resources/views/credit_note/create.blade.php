@extends('layouts/default')
@section('title',trans('credit_notes.create'))
@section('page_parent',trans('credit_notes.credit_note'))
@section('header_styles')
  <link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css"/>
  <!-- Toast -->
  <link href="{{ asset('assets/css/toast/toastr.min.css') }}" rel="stylesheet" type="text/css"/>
  {{-- select 2 --}}
  <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
  <link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css"/>
  {{-- date time picker --}}
  <link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet"
        type="text/css"/>
  {{-- autocomplete --}}
  <link href="{{ asset('assets/css/easy-autocomplete.css') }}" rel="stylesheet" type="text/css"/>
  <!-- ALERTS -->
  <link href="{{ asset('assets/css/pages/alerts.css') }}" rel="stylesheet" type="text/css"/>
  <style>
    .hide {
      display: none;
    }
  </style>
@stop
@section('content')
  <section class="content">
    <div class="row" style="padding-top:5px;">
      <div class="col-lg-12 ">
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="panel-title">
              <i class="livicon" data-name="shopping-cart-in" data-size="18" data-c="#fff" data-hc="#fff"
                 data-loop="true"></i>
              {{trans('credit_notes.create')}}
            </h3>
            <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
          </div>
          <div class="panel-body">
            <div class="bs-example">
              <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                <li class="active">
                  <a href="#venta" data-toggle="tab">{{trans('credit_notes.credit_note')}}</a>
                </li>
                <li id="tab_pago" style="display:none;">
                  <a href="#pago" data-toggle="tab" id="link_pago">Pago</a>
                </li>
              </ul>
              <div id="tabsVentas" class="tab-content">
                <div class="tab-pane fade active in" id="venta">
                  <div class="row">
                    {!! Form::open(array('url' => 'credit_note', 'class' => 'form','id'=>'save_credit_note')) !!}
                    <input type="hidden" name="token" value="{{csrf_token()}}" id="token_">
                    <div class="col-lg-3">
                      <div class="form-group">
                        {!! Form::label('date_tx', trans('Fecha')) !!}
                        <div class="input-group">
                          <div class="input-group-addon">
                            <i class="livicon" data-name="calendar" data-size="16"
                               data-c="#555555" data-hc="#555555" data-loop="true"></i>
                          </div>
                          {!! Form::text('date_tx', Input::old('date_tx'), array('id'=>'date_tx','class' => 'form-control')) !!}
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-4">
                        <div class="form-group">
                          <label for="serie_id"
                                 class="control-label">{{trans('sale.document')}}</label>
                          <div class="input-group select2-bootstrap-prepend">
                            <div class="input-group-addon"><i class="fa fa-folder"></i>
                            </div>
                            <select class="form-control" name="serie_id" id="id_serie"
                                    onchange="valid_correlative()">
                              @foreach($series as $value)
                                <option value="{!!$value->id!!}">{!!$value->nombre!!}
                                  -{!!$value->name !!}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-2">
                        <div class="form-group">
                          {!! Form::label('user_relation', trans('sale.number'), ['class' => 'control-label']) !!}
                          <input type="number" readonly name="correlative" class="form-control" min="1"
                                 id="correlative" value="{{$correlative}}">
                        </div>
                      </div>
                      <div class="col-lg-3">
                        <div class="form-group">
                          {!! Form::label('user_relation', trans('receiving.employee'), ['class' => 'control-label']) !!}
                          <div class="input-group select2-bootstrap-prepend">
                            <div class="input-group-addon"><i class="fa fa-user"></i></div>
                            <select class="form-control" name="user_relation"
                                    id="user_relation">
                              @foreach($dataUsers as $id => $name)
                                <option value="{!! $name->id !!}" {{($name->id==$idUserActive ? 'selected' : '')}} >
                                  {{ $name->name.' '.$name->last_name }}
                                </option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                      </div>

                    </div>
                    <div class="row">
                      <div class="col-lg-12">
                        <div class="form-group">
                          {!! Form::label('commnet', trans('credit_notes.comments')) !!}
                          <div class="input-group">
                            <div class="input-group-addon">
                              <i class="livicon" data-name="file" data-size="16"
                                 data-c="#555555" data-hc="#555555" data-loop="true"></i>
                            </div>
                            <textarea name="comment" id="comment" cols="25"
                                      class="form-control" rows="2"></textarea>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="alert-message alert-message-success">
                      <h4>Documento de venta</h4>
                      <div class="row">
                        <div class="col-lg-3">
                          <br>
                          <div class="form-group">
                            <a class="btn btn-info btn-block" href="#" id="add_item_btn"
                               data-toggle="modal" data-target="#modal-products"><span
                                      class="glyphicon glyphicon-list-alt"></span>&nbsp;&nbsp;Seleccionar
                              factura de venta</a>
                          </div>
                        </div>
                        <div class="col-lg-3">
                          <div class="form-group">
                            {!! Form::label('credit_notes_sale', trans('credit_notes.sale')) !!}
                            <div class="input-group">
                              <div class="input-group-addon">
                                <i class="livicon" data-name="file" data-size="16"
                                   data-c="#555555" data-hc="#555555"
                                   data-loop="true"></i>
                              </div>
                              <input type="text" readonly name="credit_note_sale"
                                     class="form-control"
                                     id="credit_note_sale">
                            </div>
                          </div>
                          {{--                                                HIDENS DE VENTA--}}
                          <input type="hidden" id="total_sale" name="total_sale">
                          <input type="hidden" id="sale_id" name="sale_id"
                                 value="{{$sale_id}}">
                        </div>
                        <div class="col-lg-4">
                          <div class="form-group">
                            {!! Form::label('action_nc', trans('credit_notes.action_type'), ['class' => 'control-label']) !!}
                            <div class="input-group select2-bootstrap-prepend">
                              <div class="input-group-addon"><i class="fa fa-file"></i></div>
                              <select class="form-control" name="type_nc"
                                      id="type_nc" onchange="setDetails(this)">
                                <option value="">Seleccione tipo</option>
                                <option value="1">{{trans('credit_notes.type1')}}</option>
                                <option value="4">{{trans('credit_notes.type4')}}</option>
                                <option value="2">{{trans('credit_notes.type2')}}</option>
                                <option value="3">{{trans('credit_notes.type3')}}</option>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-2">
                          <div class="form-group">
                            {!! Form::label('pending_apply', trans('credit_notes.pending_amount')) !!}
                            <div class="input-group">
                              <div class="input-group-addon">
                                Q
                              </div>
                              <input type="text" readonly class="form-control"
                                     name="pending_amount" id="pending_amount">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <table class="table" id="sale_info">
                          <thead>
                          <tr>
                            <th>{{trans('sale.customer')}}</th>
                            <th>{{trans('sale.date')}}</th>
                            <th>{{trans('sale.amount')}}</th>
                            <th>{{trans('sale.nc_applied')}}</th>
                            <th>{{trans('sale.payment_type')}}</th>
                          </tr>
                          </thead>
                          <tbody>

                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group">
                        <div class="col-md-12">
                          <table id="target" class="table table-bordered">
                            <thead style="background: #9CBBD9">
                            <tr>
                              {{-- <th>No.</th> --}}
                              <th>Código</th>
                              <th style="width:30%">Producto</th>
                              <th>Precio</th>
                              <th style="width: 10%">Cantidad</th>
                              <th>Total</th>
                              <th style="width:10%" id="col_devolucion"
                                  class="devuelta detControl devolucion hide">Cant
                                Devuelta
                              </th>
                              <th style="width:10%" id="col_descuento"
                                  class="descuento detControl descuento hide">Descuento
                              </th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6"></div>
                      <div class="col-md-6" style="text-align: right;font-size: 28px;">
                        <div class="form-group">
                          <label for="supplier_id"
                                 class="col-sm-4 control-label">Total:</label>
                          <div class="col-sm-8">
                            <input type="hidden" name="item_quantity" value="0"
                                   id="item_quantity">
                            <input type="hidden" name="max_credit_amount" value="-1"
                                   id="max_credit_amount">
                            <input type="text" name="total_nota_credito"
                                   style="border: none;text-align:center;background-color: white;"
                                   readonly id="total_general" value="0">
                          </div>
                        </div>
                        <div>&nbsp;</div>

                      </div>
                    </div>

                  </div>
                </div>
                <div class="tab-pane fade" id="pago">
                </div>
                <div class="form-group">
                  <div class="col-lg-6">
                  </div>
                  <div class="col-lg-2">

                  </div>
                  <div class="col-lg-4">
                    <button type="button" id="idVenta"
                            class="btn btn-primary btn-block">{{trans('credit_notes.save')}}</button>
                  </div>
                  <input type="hidden" name="descuentos" id="descuentos_json">
                  <input type="hidden" name="devolucion" id="devoluciones_json">
                  {!! Form::close() !!}
                </div>
              </div>

            </div>

            <input type="hidden" name="path" id="path" value="{{ url('/') }}">

          </div>
        </div>
      </div>
    </div>
    {{-- Inicio Modal productos --}}
    @include('credit_note.list_products')
    @include('credit_note.confirm')
    {{-- Fin Modal productos --}}
  </section>
@endsection

@section('footer_scripts')
  {{-- FORMATO DE MONEDAS --}}
  <script src="{{asset('assets/js/vuejs/cleave.min.js')}} "></script>
  <script src="{{asset('assets/js/vuejs/vue-cleave.min.js')}} "></script>
  {{-- Select2 --}}
  <script type="text/javascript" src="{{ asset('assets/vendors/select2/js/select2.js')}} "></script>
  <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"
          type="text/javascript"></script>
  {{-- autocomplete --}}
  <script src="{{asset('assets/js/jquery.easy-autocomplete.min.js')}}"></script>
  {{--    <script type="text/javascript" src="{{ asset('assets/js/sales/customer_add.js')}} "></script>--}}

  {{--CREDIT NOTE CUSTOM JS--}}
  <script type="text/javascript" src="{{ asset('assets/js/credit_notes/sales-table.js')}} "></script>
  <script type="text/javascript" src="{{ asset('assets/js/credit_notes/detail.js')}} "></script>
  <script type="text/javascript" src="{{ asset('assets/js/credit_notes/header_sale.js')}} "></script>
  <script type="text/javascript" src="{{ asset('assets/js/credit_notes/initial.js')}} "></script>

  <script type="text/javascript">
      $(document).ready(function () {

          $.fn.select2.defaults.set("width", "100%");

          $("#type_nc").prop("disabled", true);

          var dateNow = new Date();
          $("#date_tx").datetimepicker({
              sideBySide: true,
              locale: 'es',
              format: 'DD/MM/YYYY',
              defaultDate: dateNow
          }).parent().css("position :relative");
          $('select').select2({
              allowClear: true,
              theme: "bootstrap",
              placeholder: "Buscar"
          });
          $('#customer_id').select2({
              allowClear: true,
              theme: "bootstrap",
              disabled: true
          });
          let sale_id = +document.getElementById('sale_id').value;
          if (sale_id > 0) {
              loadSaleDefault(sale_id);
          }
          $('.number').toArray().forEach(function (field) {
              new Cleave(field, {
                  numeral: true,
                  numeralPositiveOnly: true,
                  numeralThousandsGroupStyle: 'thousand'
              });
          });
      });

      /*
      * MODAL PARA MOSTRAR IMAGENES DE PRODUCTOS
      * */
      function showImage(avatar, nombre) {
          $('#lblTitulo').text(nombre);
          $('#image').attr("src", avatar);
          $('#modal-image').modal("show");
      }

      /*--------------------------------------*/

  </script>
@stop
