@extends('layouts/default')

@section('title',$title)
@section('page_parent',trans('item.items'))


@section('header_styles')
<link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet"
type="text/css"/>
<link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet"
type="text/css"/>
<link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet"/>

<!-- ALERTS -->
<link href="{{ asset('assets/css/pages/alerts.css') }}" rel="stylesheet" type="text/css"/>
@stop
@section('content')
    <?php $permisos=Session::get('permisions'); $array_p =array_column(json_decode(json_encode($permisos), True),'ruta');  ?>
    <section class="content">
    <!-- <div class="container"> -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="edit" data-size="18" data-c="#fff" data-hc="#fff"
                            data-loop="true"></i>
                            {{$title}}
                        </h3>
                        <span class="pull-right clickable">
                            <i class="glyphicon glyphicon-chevron-up"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        @include('partials.show-all-errors')
                        @if($show_budget_price->active)
                            @if(!$valid_price)
                                <div class="row">
                                    <div class="alert-message alert-message-danger">
                                        <h4>El costo para presupuesto ha vencido!</h4>
                                        <p>
                                            Se ha superado el tiempo v??lido del costo para elaborar presupuestos.
                                        </p>
                                    </div>
                                </div>
                            @endif
                        @endif
                        {!! Form::model($item, array('route' => array('items.update', $item->id), 'method' => 'PUT', 'files' => true, 'id'=>'newItem')) !!}
                        <input type="hidden" name="stock_action" value="+" id="stock_action">
                        <input type="hidden" name="prices_details" id="prices_details">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    {!! Form::label('upc_ean_isbn', trans('item.upc_ean_isbn'),array('class'=>'control-label')) !!}
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="livicon" data-name="code" data-size="16" data-c="#555555"
                                            data-hc="#555555"
                                            data-loop="true"></i>
                                        </div>
                                        {!! Form::text('upc_ean_isbn', null, array('class' => 'form-control')) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    {!! Form::label('item_name', trans('item.item_name'), array('class'=>'control-label')) !!}
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="livicon" data-name="responsive-menu" data-size="16"
                                            data-c="#555555" data-hc="#555555"
                                            data-loop="true"></i>
                                        </div>
                                        {!! Form::text('item_name', null, array('class' => 'form-control')) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    {!! Form::label('size', trans('Categoria'), array('class'=>'control-label')) !!}
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="livicon" data-name="tag" data-size="16" data-c="#555555"
                                            data-hc="#555555"
                                            data-loop="true"></i>
                                        </div>
                                        {!! Form::select('id_categorie', $categorie_product, Input::old('id'), array('class' => 'form-control js-example-basic-single')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    {!! Form::label('size', trans('item.size'), array('class'=>'control-label')) !!}
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="livicon" data-name="resize-big-alt" data-size="16"
                                            data-c="#555555"
                                            data-hc="#555555"
                                            data-loop="true"></i>
                                        </div>
                                        {!! Form::text('size', null, array('class' => 'form-control')) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    {!! Form::label('item_type_lbl', 'Tipo Servicio ', array('class'=>'control-label')) !!}
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="livicon" data-name="text" data-size="16"
                                            data-c="#555555"
                                            data-hc="#555555"
                                            data-loop="true"></i>
                                        </div>
                                        {!! Form::select('type_id', $type, Input::old('type_id'), array('class' => 'form-control js-example-basic-single', 'id'=>'type_id', 'onChange'=>'loadType(this)')) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    {!! Form::label('minimal_existence', trans('Existencia m??nima'), array('class'=>'control-label')) !!}
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="livicon" data-name="plus" data-size="16"
                                            data-c="#555555"
                                            data-hc="#555555"
                                            data-loop="true"></i>
                                        </div>
                                        <input type="text" name="minimal_existence" id="minimal_existence"
                                        value="{{ $item->minimal_existence}}" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    {!! Form::label('description', trans('item.description'), array('class'=>'control-label')) !!}
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="livicon" data-name="align-justify" data-size="16"
                                            data-c="#555555"
                                            data-hc="#555555"
                                            data-loop="true"></i>
                                        </div>
                                        {!! Form::textarea('description', null, array('class' => 'form-control','size' => '50x3')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    {!! Form::label('avatar', trans('item.choose_avatar'), array('class'=>'control-label')) !!}
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="livicon" data-name="image" data-size="16"
                                            data-c="#555555"
                                            data-hc="#555555"
                                            data-loop="true"></i>
                                        </div>
                                        {!! Form::file('avatar', null, array('class' => 'form-control')) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    {!! Form::label('cost_price', trans('item.cost_price').' *', array('class'=>'control-label')) !!}
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="livicon" data-name="money" data-size="16"
                                            data-c="#555555"
                                            data-hc="#555555"
                                            data-loop="true"></i>
                                        </div>
                                        {!! Form::number('cost_price', Input::old('cost_price'), ['class' => 'form-control', 'id'=>'cost_price', 'step'=>'0.01']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                @if($item->status==2)
                                <div class="form-group">
                                    {!! Form::label('status', trans('item.status').' *') !!}
                                    <select class="form-control" title="" name="status" id="status" required>
                                        <option value="1" @if($item->status=='1') selected='selected' @endif>Activo</option>
                                        <option value="2" @if($item->status=='2') selected='selected' @endif>Inactivo</option>
                                    </select>
                                </div>
                                @else
                                <div class="form-group">
                                    <div class="form-group" id="mensaje">
                                        <input type="hidden" id="status" name="status" value="{{$item->status}}">
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- GESTI??N DE LOS PRECIOS Y UNIDADES DE MEDIDA --}}
                        <div class="row">
                          <center> <h3>{!! trans('item.selling_prices') !!}</h3> </center>
                          <br>
                        </div>
                        <div class="row">
                            <input type="hidden" id="detail_id">
                          <div class="col-lg-2">
                            <div class="form-group">
                              {!! Form::label('size', trans('unit_measure.unit_measure'), array('class'=>'control-label')) !!}
                              <div class="input-group">
                                <select class="form-control" name="unit_id" id="unit_id">
                                  <option value=""></option>
                                  @foreach ($units as $key => $value)
                                    <option value="{{$value->id}}">{{$value->name}} | {{$value->abbreviation}}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-2">
                            <div class="form-group">
                              {!! Form::label('size', trans('item.price_type'), array('class'=>'control-label')) !!}
                              <div class="input-group">
                                <select class="form-control" name="price_id" id="price_id">
                                  <option value=""></option>
                                  @foreach ($prices as $key => $value)
                                    <option profit="{{$value->pct}}" value="{{$value->id}}">{{$value->name}}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-1">
                            <div class="form-group">
                              {!! Form::label('size', trans('item.quantity'), array('class'=>'control-label')) !!}
                              <div class="input-group">
                                <input type="text" name="quantity" value="" class="form-control" id="quantity">
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-2">
                            <div class="form-group">
                              {!! Form::label('size', trans('item.profit'), array('class'=>'control-label')) !!}
                              <div class="input-group">
                                <input type="text" name="profit" value="" class="form-control" id="profit">
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-2">
                            <div class="form-group">
                              {!! Form::label('size', trans('item.selling_price'), array('class'=>'control-label')) !!}
                              <div class="input-group">
                                <input type="text" name="selling_price" value="" class="form-control" id="selling_price">
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-1">
                            <div class="form-group">
                              {!! Form::label('size', trans('item.default_price'), array('class'=>'control-label')) !!}
                              <div class="input-group">
                                <input type="checkbox" name="default_price" value=""  id="default_price">
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-2">
                            <br>
                            <div class="form-group">
                              <div class="input-group">
                                <button type="button" class="btn btn-success"  id="btnAddPrice" ><i class="fa fa-plus"></i></button>
                                <button type="button" class="btn btn-info" style="display: none;" id="btnEditPrice" ><i class="fa fa-pencil"></i></button>
                                <button type="button" class="btn btn-danger"  id="btnCleanInputs" ><i class="fa fa-trash"></i></button>
                              </div>
                            </div>
                          </div>
                        </div>
                        <hr>
                        <table class="table" id="tblPrices">
                          <thead>
                            <tr>
                              <th>{!! trans('unit_measure.unit_measure') !!}</th>
                              <th>{!! trans('item.price_type') !!}</th>
                              <th>{!! trans('item.quantity') !!}</th>
                              <th>{!! trans('item.profit') !!}</th>
                              <th>{!! trans('item.selling_price') !!}</th>
                              <th>{!! trans('item.default_price') !!}</th>
                              <th>{!! trans('item.actions') !!}</th>
                            </tr>
                          </thead>
                          <tbody>
                        {{--                  <tr>--}}
                        {{--                    <th>unidad</th>--}}
                        {{--                    <th>precio</th>--}}
                        {{--                    <th>cantidad</th>--}}
                        {{--                    <th>utilidad</th>--}}
                        {{--                    <th>precio venta</th>--}}
                        {{--                    <th>default</th>--}}
                        {{--                    <th>--}}
                        {{--                        <button class="btn btn-success" type="button" onclick="">--}}
                        {{--                            <i class="livicon" data-name="pen" data-loop="true" data-color="#000" data-hovercolor="black" data-size="14"></i>--}}
                        {{--                            Editar--}}
                        {{--                        </button>--}}
                        {{--                        <button class="btn btn-danger" type="button" onclick="">--}}
                        {{--                            <i class="livicon" data-name="trash" data-loop="true" data-color="#000" data-hovercolor="black" data-size="14"></i>--}}
                        {{--                            Eliminar--}}
                        {{--                        </button>--}}
                        {{--                    </th>--}}
                        {{--                  </tr>--}}
                          </tbody>
                        </table>

                        <input type="hidden" id="item_prices" value="{{json_encode($item_prices)}}">

                        {{-- FIN DE GESTI??N DE PRECIOS Y UNIDADES DE MEDIDA --}}

                        <br>
                        <div class="col-lg-12">
                            @if(in_array('??tems/edit',$array_p))
                            @include('partials.buttons',['cancel_url'=>"/items"])
                            @else
                            <label class="btn-gray" style="padding:10px;">No tiene permisos para editar</label> &nbsp;&nbsp;&nbsp;
                            <a class="btn btn-danger" href="{{ URL::previous() }}">
                                {{trans('button.back')}}
                            </a>
                            @endif
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        <!-- </div> -->
    </section>
    @endsection
    @section('footer_scripts')
      <script language="javascript" type="text/javascript"
              src="{{ asset('assets/vendors/select2/js/select2.js') }}"></script>
      <script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
      {{-- FORMATO DE MONEDAS --}}
      <script src="{{asset('assets/js/vuejs/cleave.min.js')}} "></script>
      <script src="{{asset('assets/js/vuejs/vue-cleave.min.js')}} "></script>

      <script type="text/javascript">
          function loadType(select) {
              /* servicio*/
              if (select.value == 2) {
                  $('#stock_action').val('=');
              } else {
                  $('#stock_action').val('+');
              }
              /* servicio ?? mobiliario */
              if (select.value == 2 || select.value == 3) {
                  $('#minimal_existence').attr('disabled', true);
              } else {
                  $('#minimal_existence').attr('disabled', false);
              }
          }

          $(document).ready(function () {
              $('select').select2({
                  allowClear: true,
                  theme: "bootstrap",
                  placeholder: "Buscar"
              });
              $('#newItem').bootstrapValidator({
                  feedbackIcons: {
                      valid: 'glyphicon glyphicon-ok',
                      invalid: 'glyphicon glyphicon-remove',
                      validating: 'glyphicon glyphicon-refresh'
                  },
                  message: 'Valor no valido',
                  fields: {
                      item_name: {
                          validators: {
                              notEmpty: {
                                  message: 'Debe ingresar el nombre'
                              }
                          }
                      },
                      id_categorie: {
                          validators: {
                              notEmpty: {
                                  message: 'Debe seleccionar categor??a.'
                              }
                          }
                      },
                      type_id: {
                          validators: {
                              notEmpty: {
                                  message: 'Debe seleccionar un tipo.'
                              }
                          }
                      },
                      approach_type: {
                          validators: {
                              notEmpty: {
                                  message: 'Debe seleccionar un tipo.'
                              }
                          }
                      },
                      cost_price: {
                          validators: {
                              notEmpty: {
                                  message: 'Debe ingresar un precio de costo.'
                              },
                              regexp: {
                                  regexp: /^\d*\.?\d*$/,
                                  message: 'Ingrese un n??mero v??lido'
                              },
                              callback: {
                                message: 'Debe seleccioanr un precio principal.',
                                callback: function (value, validator, $field) {
                                    let prices = 0;
                                    let priceAcum=0;
                                    let defaults = 0;
                                    pm.details.forEach((item, i) => {
                                      if (priceAcum != item.price_id) {
                                        priceAcum = item.price_id;
                                        prices++;
                                      }
                                      if (Boolean(item.default)) {
                                        defaults++;
                                      }
                                    });

                                    return prices==defaults;
                                }
                              }
                          }
                      },
                      minimal_existence: {
                          validators: {
                              notEmpty: {
                                  message: 'Debe ingresar la existencia m??nima.'
                              },
                              regexp: {
                                  regexp: /^\d*\.?\d*$/,
                                  message: 'Ingrese un n??mero v??lido'
                              }
                          }
                      }
                  }
              });
              // });

              loadType(document.getElementById('type_id'));

          }); /*Fin Document Ready*/

          var costo = document.getElementById('cost_price');
          costo.addEventListener('input', function (e) {
              var cuantos = $('#cuantos_precios').val();
              for (let index = 0; index <= cuantos; index++) {
                  var price_id = $('#price_id_' + index).val();
                  var profit = cleanNumber($('#profit' + price_id).val() / 100);
                  var venta = cleanNumber($('#selling_price' + price_id).val());
                  var low = cleanNumber($('#low_price' + price_id).val());
                  var costo_ = e.target.value;
                  if (profit > 0) {
                      setPrices(price_id, costo_, profit);
                      console.log('#selling_price' + index);
                      $('#newItem').data('bootstrapValidator')
                          .updateStatus($('#selling_price' + price_id), 'NOT_VALIDATED')
                          .validateField($('#selling_price' + price_id));
                      $('#newItem').data('bootstrapValidator')
                          .updateStatus($('#low_price' + price_id), 'NOT_VALIDATED')
                          .validateField($('#low_price' + price_id));
                  }
              }
          });

          function addChange(id) {

              var profit = document.getElementById('profit' + id);
              profit.addEventListener('input', function (e) {
                  var costo = $('#cost_price').val();
                  var profit = e.target.value / 100;
                  if (costo != "") {
                      setPrices(id, costo, profit)
                  }
                  // $('#newItem').data('bootstrapValidator')
                  // .updateStatus($('#selling_price'+id), 'NOT_VALIDATED')
                  // .validateField($('#selling_price'+id));
                  e.preventDefault();
              });

              var price = document.getElementById('selling_price' + id);
              price.addEventListener('input', function (e) {
                  var profit = cleanNumber($('#profit' + id).val() / 100);
                  var costo = cleanNumber($('#cost_price').val());
                  // var venta = cleanNumber($(this).val());
                  var venta = cleanNumber(e.target.value);
                  if ((venta > 0) && (costo > 0)) {
                      var nuevo_profit = ((cleanNumber(venta - costo) / costo) * 100).toFixed(2);
                      /* La utilidad no puede ser negativa */
                      if (nuevo_profit < 0) {
                          nuevo_profit = 0;
                      }
                      $('#profit' + id).val(nuevo_profit);
                      $('#profit' + id).change();
                      $('#low_price' + id).val(venta);
                      $('#low_price' + id).change();
                  }
                  e.preventDefault();
              });
              $('#selling_price' + id).blur(function (e) {
                  var venta = cleanNumber(e.target.value);
                  var costo = cleanNumber($('#cost_price').val());
                  var profit = cleanNumber($('#profit' + id).val() / 100);
                  if (venta < costo) {
                      toastr.error("El precio venta no puede ser menor al precio costo!!");
                      setPrices(id, costo, profit)
                  }
                  ;
              });


              var low_price = document.getElementById('low_price' + id);
              low_price.addEventListener('input', function (e) {
                  var costo = cleanNumber($('#cost_price').val());
                  var venta = cleanNumber($('#selling_price' + id).val());
                  var low = cleanNumber(e.target.value);

                  e.preventDefault();
              });
              $('#low_price' + id).blur(function (e) {
                  var low = cleanNumber(e.target.value);
                  var costo = cleanNumber($('#cost_price').val());
                  var venta = cleanNumber($('#selling_price' + id).val());
                  if (low < costo) {
                      toastr.error("El precio m??nimo de venta no puede ser menor al precio costo!!");
                      e.target.value = venta;
                  }
              });
          }

          function setPrices(id, costo, profit) {
              $('#selling_price' + id).val(parseFloat(parseFloat(costo * profit) + parseFloat(costo)).toFixed(2));
              $('#low_price' + id).val(parseFloat(parseFloat(costo * profit) + parseFloat(costo)).toFixed(2));
          }


      </script>

      <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
      <script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
      <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"
              type="text/javascript"></script>
      <script src="{{ asset('assets/js/item/prices.js') }}" type="text/javascript"></script>
      <script src="{{ asset('assets/js/item/classPrices.js') }}" type="text/javascript"></script>
      <script src="{{ asset('assets/js/item/edit.js') }}" type="text/javascript"></script>
    @stop
