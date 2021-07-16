@extends('layouts/default')

@section('title',$title)
@section('page_parent',trans('item.items'))

<!--  Para agregar el calendario-->
@section('header_styles')
  <link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css"/>
  <link href="{{ asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet"
        type="text/css"/>
  <link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet"
        type="text/css"/>
  <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet"/>
  <link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet"/>
@stop
@section('content')
  <section class="content">
    <!-- <div class="container"> -->
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="panel-title">
              <i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff"
                 data-loop="true"></i>
              {{$title}}
            </h3>
            <span class="pull-right clickable">
                            <i class="glyphicon glyphicon-chevron-up"></i>
                        </span>
          </div>
          <div class="panel-body">

            {!! Form::open(array('url' => 'items','id'=>'newItem', 'files' => true)) !!}
            <input type="hidden" name="stock_action" value="+" id="stock_action">
            <div class="row">
              <div class="col-lg-4">
                <div class="form-group">
                  {!! Form::label('upc_ean_isbn', trans('item.upc_ean_isbn'), array('class'=>'control-label')) !!}
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="livicon" data-name="code" data-size="16" data-c="#555555"
                         data-hc="#555555"
                         data-loop="true"></i>
                    </div>
                    {!! Form::text('upc_ean_isbn', Input::old('upc_ean_isbn'), array('class' => 'form-control')) !!}
                  </div>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="form-group">
                  {!! Form::label('item_name', trans('item.item_name').' *', array('class'=>'control-label'))!!}
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="livicon" data-name="responsive-menu" data-size="16"
                         data-c="#555555" data-hc="#555555"
                         data-loop="true"></i>
                    </div>
                    {!! Form::text('item_name', Input::old('item_name'), array('class' => 'form-control')) !!}
                  </div>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="form-group">
                  {!! Form::label('size', trans('item.category'), array('class'=>'control-label')) !!}
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="livicon" data-name="tag" data-size="16" data-c="#555555"
                         data-hc="#555555"
                         data-loop="true"></i>
                    </div>
                    {!! Form::select('id_categorie', $categorie_product, Input::old('id'), array('class' => 'form-control')) !!}
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
                    {!! Form::text('size', Input::old('size'), array('class' => 'form-control')) !!}
                  </div>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="form-group">
                  {!! Form::label('item_type_id_lbl', trans('item.item_type'), array('class'=>'control-label')) !!}
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="livicon" data-name="text" data-size="16"
                         data-c="#555555"
                         data-hc="#555555"
                         data-loop="true"></i>
                    </div>
                    {!! Form::select('type_id', $type, Input::old('type_id'), array('class' => 'form-control', 'id'=>'type_id', 'onChange'=>'loadType(this)')) !!}
                  </div>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="form-group">
                  {!! Form::label('minimal_existence', trans('item.minimal_existence_long'), array('class'=>'control-label')) !!}
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="livicon" data-name="plus" data-size="16"
                         data-c="#555555"
                         data-hc="#555555"
                         data-loop="true"></i>
                    </div>
                    <input type="text" name="minimal_existence" id="minimal_existence" value="0"
                           class="form-control">
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
                    {!! Form::textarea('description', Input::old('description'), array('class' => 'form-control','size' => '50x3')) !!}
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
                    {!! Form::file('avatar', Input::old('avatar'), array('class' => 'form-control')) !!}
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
                <div class="form-group">
                  <div class="form-group" id="mensaje">
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" id="show_budget_price" value="{{$show_budget_price->active}}">
            @if($show_budget_price->active)
              <div class="row">
                <div class="col-lg-4">
                  <div class="form-group">
                    {!! Form::label('cost_price', trans('item.budget_cost'), array('class'=>'control-label')) !!}
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="livicon" data-name="money" data-size="16"
                           data-c="#555555"
                           data-hc="#555555"
                           data-loop="true"></i>
                      </div>
                      {!! Form::number('budget_cost', Input::old('budget_cost'), ['class' => 'form-control cmoney', 'id'=>'budget_cost', 'step'=>'0.01']) !!}
                    </div>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="form-group">
                    {!! Form::label('days_valid', trans('item.days_valid'), array('class'=>'control-label')) !!}
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="livicon" data-name="calendar" data-size="16"
                           data-c="#555555"
                           data-hc="#555555"
                           data-loop="true"></i>
                      </div>
                      {!! Form::number('days_valid', Input::old('days_valid'), ['class' => 'form-control cnumber', 'id'=>'days_valid']) !!}
                    </div>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="form-group">
                    {!! Form::label('months_valid', trans('item.monts_valid'), array('class'=>'control-label')) !!}
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="livicon" data-name="calendar" data-size="16"
                           data-c="#555555"
                           data-hc="#555555"
                           data-loop="true"></i>
                      </div>
                      {!! Form::number('months_valid', Input::old('months_valid'), ['class' => 'form-control cnumber', 'id'=>'months_valid']) !!}
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-4">
                  <div class="form-group">
                      {!! Form::label('wildcard', trans('item.wildcard'), array('class'=>'control-label')) !!}
                      <div class="input-group">
                          <div class="input-group-addon">
                              <i class="livicon" data-name="table" data-size="16"
                                 data-c="#555555"
                                 data-hc="#555555"
                                 data-loop="true"></i>
                          </div>
                          {!! Form::checkbox('wildcard', false, Input::old('wildcard'), ['class' => 'form-control', 'id'=>'wildcard']) !!}
                      </div>
                  </div>
                </div>
                  <div class="col-lg-4">
                      <div class="form-group">
                          {!! Form::label('approach_type', trans('item.approach_type'), array('class'=>'control-label')) !!}
                          <div class="input-group">
                              <div class="input-group-addon">
                                  <i class="livicon" data-name="sort" data-size="16"
                                     data-c="#555555"
                                     data-hc="#555555"
                                     data-loop="true"></i>
                              </div>
                              {!! Form::select('approach_type', array(null=>'Seleccione una opcion','1'=>trans('item.toInteger'), '2'=>trans('item.toDecimal')), ['class' => 'form-control', 'id'=>'approach_type']) !!}
                          </div>
                      </div>
                  </div>
              </div>
            @endif
            <br>
            {{--  --}}
            @foreach($prices as $indice=> $key)
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                    {!! Form::label('profit', trans('item.price_pagos_asoc'), array('class'=>'control-label')) !!}
                    <div class="input-group">
                      <div class="input-group-addon"><strong>{{$key->name}}</strong></div>
                        <?php $tipos_pago = ""; $cuantos = count($key->pagos);
                        foreach ($key->pagos as $i => $pago) {
                            if ($cuantos > ($i + 1)) {
                                $tipos_pago = $tipos_pago . $pago->name . ',  ';
                            } else {
                                $tipos_pago = $tipos_pago . $pago->name;
                            }

                        } ?>

                      {!! Form::text('customer_balance', $tipos_pago , array('id'=>'price_name','class' => 'form-control','readonly'=>'readonly','style'=>'text-align: left;') ) !!}
                      <input type="hidden" id="{{'price_id_'.$indice}}" name="{{'price_id_'.$indice}}"
                             value={{$key->id}}>
                    </div>
                  </div>
                </div>
                <div class="col-lg-2">
                  <div class="form-group">
                    {!! Form::label('profit', trans('item.profit').' *', array('class'=>'control-label')) !!}
                    <div class="input-group">
                      <div class="input-group-addon">
                        %
                      </div>
                      {!! Form::number('profit'.$key->id, Input::old('profit',$key->pct), array('id'=>'profit'.$key->id,'class' => 'form-control paddingright15', 'step'=>'0.01','required'=>'required')) !!}
                    </div>
                  </div>
                </div>
                <div class="col-lg-2">
                  <div class="form-group">
                    {!! Form::label('selling_price', trans('item.selling_price').' *', array('class'=>'control-label')) !!}
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="livicon" data-name="money" data-size="16"
                           data-c="#555555"
                           data-hc="#555555"
                           data-loop="true"></i>
                      </div>
                      {!! Form::number('selling_price'.$key->id, Input::old('selling_price'), array('id'=>'selling_price'.$key->id,'class' => 'form-control paddingright15', 'step'=>'0.01','required'=>'required')) !!}
                    </div>
                  </div>
                </div>
                <div class="col-lg-2">
                  <div class="form-group">
                    {!! Form::label('low_price', trans('item.minimal_sale_price'), array('class'=>'control-label')) !!}
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="livicon" data-name="money" data-size="16"
                           data-c="#555555"
                           data-hc="#555555"
                           data-loop="true"></i>
                      </div>
                      {!! Form::number('low_price'.$key->id, Input::old('low_price'), array('id'=>'low_price'.$key->id,'class' => 'form-control paddingright15', 'step'=>'0.01','required'=>'required')) !!}
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
            <input type="hidden" id="cuantos_precios" name="cuantos_precios" value={{$indice}}>

            <br>
            <div class="row">
              <div class="col-lg-12">
                @include('partials.buttons',['cancel_url'=>"/items"])
              </div>
            </div>
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
    <!-- </div> -->
  </section>
@endsection
<!--  para agregar el calendario de bootstrap-->
@section('footer_scripts')
  <script language="javascript" type="text/javascript"
          src="{{ asset('assets/vendors/select2/js/select2.js') }}"></script>
  <script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
  <script type="text/javascript">
      function loadType(select) {
          /* servicio*/
          if (select.value == 2) {
              $('#stock_action').val('=');
          } else {
              $('#stock_action').val('+');
          }
          /* servicio ó mobiliario */
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
                              message: 'Debe seleccionar categoría.'
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
                              message: 'Ingrese un número válido'
                          }
                      },
                      required: true
                  },
                  low_price: {
                      validators: {
                          notEmpty: {
                              message: 'Debe ingresar un precio de ventas mínimo.'
                          },
                          regexp: {
                              regexp: /^\d*\.?\d*$/,
                              message: 'Ingrese un número válido'
                          }
                      },
                      required: true
                  },
                  selling_price: {
                      validators: {
                          notEmpty: {
                              message: 'Debe ingresar un precio de venta.'
                          },
                          regexp: {
                              regexp: /^\d*\.?\d*$/,
                              message: 'Ingrese un número válido'
                          }
                      }
                  },
                  profit: {
                      validators: {
                          regexp: {
                              regexp: /^\d*\.?\d*$/,
                              message: 'Ingrese un número válido'
                          }
                      }
                  },
                  minimal_existence: {
                      validators: {
                          notEmpty: {
                              message: 'Debe ingresar la existencia mínima.'
                          },
                          regexp: {
                              regexp: /^\d*\.?\d*$/,
                              message: 'Ingrese un número válido'
                          }
                      }
                  },
                  budget_cost: {
                      validators: {
                          notEmpty: {
                              message: 'Debe ingresar un costo para presupuestos.'
                          },
                          regexp: {
                              regexp: /^\d*\.?\d*$/,
                              message: 'Ingrese un número válido'
                          },
                          callback: {
                              message: "El costo para presupuesto debe ser mayor a 0",
                              callback: function (value) {
                                  if (cleanNumber(value) <= 0) {
                                      return false;
                                  } else {
                                      return true;
                                  }
                              }
                          }
                      }
                  },
                  days_valid: {
                      validators: {
                          notEmpty: {
                              message: 'Debe ingresar numero de dias válidos para el precio.'
                          },
                          regexp: {
                              regexp: /^\d*\.?\d*$/,
                              message: 'Ingrese un número válido'
                          }
                      }
                  },
                  months_valid: {
                      validators: {
                          notEmpty: {
                              message: 'Debe ingresar numero de meses válidos para el precio.'
                          },
                          regexp: {
                              regexp: /^\d*\.?\d*$/,
                              message: 'Ingrese un número válido'
                          }
                      }
                  },
                  years_valid: {
                      validators: {
                          notEmpty: {
                              message: 'Debe ingresar numero de años válidos para el precio.'
                          },
                          regexp: {
                              regexp: /^\d*\.?\d*$/,
                              message: 'Ingrese un número válido'
                          }
                      }
                  }
              }
          });
          // });

          loadType(document.getElementById('type_id'));

          /*Obtenemos la cantidad de precios*/
          var cuantos = $('#cuantos_precios').val();
          /* En base a la cantidad de precios recorremos
          los campos y agregamos funciones de validación
          en el evento change
           */
          for (let index = 0; index <= cuantos; index++) {
              var price_id = $('#price_id_' + index).val();
              addChange(price_id);

          }

          /**
           * Verificar si el costo para presupuestos esta activado y aplicar validaciones de bootstrap
           */

          $('#newItem').bootstrapValidator('enableFieldValidators', 'budget_cost', false, null);
          $('#newItem').bootstrapValidator('enableFieldValidators', 'days_valid', false, null);
          $('#newItem').bootstrapValidator('enableFieldValidators', 'months_valid', false, null);
          $('#newItem').bootstrapValidator('enableFieldValidators', 'years_valid', false, null);
          $('#newItem').bootstrapValidator('enableFieldValidators', 'approach_type', false, null);

          let valid_budget = $('#show_budget_price').val();
          if (valid_budget) {
              $('#newItem').bootstrapValidator('enableFieldValidators', 'budget_cost', true, null);
              $('#newItem').bootstrapValidator('enableFieldValidators', 'days_valid', true, null);
              $('#newItem').bootstrapValidator('enableFieldValidators', 'months_valid', true, null);
              $('#newItem').bootstrapValidator('enableFieldValidators', 'years_valid', true, null);
              $('#newItem').bootstrapValidator('enableFieldValidators', 'approach_type', true, null);
          }

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
                  toastr.error("El precio mínimo de venta no puede ser menor al precio costo!!");
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
@stop
